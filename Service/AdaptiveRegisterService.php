<?php

declare(strict_types=1);

namespace Tabsl\Turnstile\Service;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;
use Tabsl\Turnstile\Core\Setup;

class AdaptiveRegisterService
{
    private const TABLE = 'tabslturnstile_regattempt';
    private const PROTECT_UNTIL_PARAM = 'tabslturnstile_register_protect_until';

    public function recordAttempt($email): void
    {
        $turnstileService = new TurnstileService();

        if (!$turnstileService->isEnabledForRegister() || !$turnstileService->isRegisterAdaptiveEnabled()) {
            return;
        }

        $normalized = self::normalizeEmail($email);
        if ($normalized === '') {
            return;
        }

        if (!self::isEmailCountable($normalized, self::parseDomainList($turnstileService->getRegisterDomains()))) {
            return;
        }

        $now = time();
        $shopId = $this->getShopId();
        $windowSeconds = $turnstileService->getRegisterWindowMinutes() * 60;

        try {
            $this->upsertAttempt($shopId, $normalized, $now);
            $this->cleanup($shopId, $now - (2 * $windowSeconds));
            $this->refreshProtection($turnstileService, $shopId, $now, $windowSeconds);
        } catch (\Exception $exception) {
            Setup::createDatabaseTables();
            try {
                $this->upsertAttempt($shopId, $normalized, $now);
                $this->cleanup($shopId, $now - (2 * $windowSeconds));
                $this->refreshProtection($turnstileService, $shopId, $now, $windowSeconds);
            } catch (\Exception $retryException) {
                return;
            }
        }
    }

    public function isChallengeRequired(): bool
    {
        $turnstileService = new TurnstileService();

        if (!$turnstileService->isEnabledForRegister()) {
            return false;
        }

        if (!$turnstileService->isRegisterAdaptiveEnabled()) {
            return true;
        }

        return $this->isWaveActive($turnstileService);
    }

    public static function normalizeEmail($email): string
    {
        $email = strtolower(trim((string) $email));
        if ($email === '' || strpos($email, '@') === false) {
            return '';
        }

        if (strlen($email) > 255) {
            $email = substr($email, 0, 255);
        }

        return $email;
    }

    public static function extractDomain($email): string
    {
        $normalized = self::normalizeEmail($email);
        if ($normalized === '') {
            return '';
        }

        $parts = explode('@', $normalized);
        $domain = strtolower(trim((string) end($parts)));
        $domain = ltrim($domain, '@');

        return $domain;
    }

    public static function parseDomainList($raw): array
    {
        if ($raw === null || $raw === '' || $raw === false) {
            return [];
        }

        if (is_string($raw)) {
            $raw = preg_split('/\r\n|\r|\n/', $raw);
        }

        if (!is_array($raw)) {
            return [];
        }

        $domains = [];
        foreach ($raw as $entry) {
            $domain = strtolower(trim((string) $entry));
            $domain = ltrim($domain, '@');
            if ($domain !== '') {
                $domains[] = $domain;
            }
        }

        return array_values(array_unique($domains));
    }

    public static function isEmailCountable($email, array $domains): bool
    {
        $normalized = self::normalizeEmail($email);
        if ($normalized === '') {
            return false;
        }

        if ($domains === []) {
            return true;
        }

        $domain = self::extractDomain($normalized);

        return $domain !== '' && in_array($domain, $domains, true);
    }

    private function isWaveActive(TurnstileService $turnstileService): bool
    {
        $now = time();
        $protectUntil = (int) Registry::getConfig()->getConfigParam(self::PROTECT_UNTIL_PARAM);
        if ($protectUntil > $now) {
            return true;
        }

        $threshold = $turnstileService->getRegisterThreshold();

        try {
            $count = $this->countAttempts(
                $this->getShopId(),
                $now - ($turnstileService->getRegisterWindowMinutes() * 60)
            );
        } catch (\Exception $exception) {
            Setup::createDatabaseTables();
            try {
                $count = $this->countAttempts(
                    $this->getShopId(),
                    $now - ($turnstileService->getRegisterWindowMinutes() * 60)
                );
            } catch (\Exception $retryException) {
                return true;
            }
        }

        $active = $count >= $threshold;
        if (!$active && $protectUntil > 0) {
            Registry::getLogger()->info(
                '[AdaptiveRegisterService] wave ended: count=' . $count . ' threshold=' . $threshold
            );
            $this->saveProtectUntil(0);
        }

        return $active;
    }

    private function refreshProtection(TurnstileService $turnstileService, $shopId, $now, $windowSeconds): void
    {
        $count = $this->countAttempts($shopId, $now - $windowSeconds);
        if ($count < $turnstileService->getRegisterThreshold()) {
            return;
        }

        $until = $now + $windowSeconds;
        $current = (int) Registry::getConfig()->getConfigParam(self::PROTECT_UNTIL_PARAM);
        if ($until <= $current) {
            return;
        }

        if ($current <= $now) {
            Registry::getLogger()->notice(
                '[AdaptiveRegisterService] wave triggered: count=' . $count
                . ' threshold=' . $turnstileService->getRegisterThreshold()
                . ' protect_until=' . date('c', $until)
            );
        }

        $this->saveProtectUntil($until);
    }

    private function upsertAttempt($shopId, $email, $now): void
    {
        $db = DatabaseProvider::getDb();
        $quotedShopId = (int) $shopId;
        $quotedEmail = $db->quote($email);
        $quotedTime = (int) $now;
        $oxid = $db->quote(Registry::getUtilsObject()->generateUId());

        $db->execute(
            'INSERT INTO ' . self::TABLE . ' (OXID, OXSHOPID, OXEMAIL, OXTIME)
             VALUES (' . $oxid . ', ' . $quotedShopId . ', ' . $quotedEmail . ', ' . $quotedTime . ')
             ON DUPLICATE KEY UPDATE OXTIME = ' . $quotedTime
        );
    }

    private function countAttempts($shopId, $since): int
    {
        $db = DatabaseProvider::getDb();
        $quotedShopId = (int) $shopId;
        $quotedSince = (int) $since;

        $count = $db->getOne(
            'SELECT COUNT(*) FROM ' . self::TABLE
            . ' WHERE OXSHOPID = ' . $quotedShopId
            . ' AND OXTIME >= ' . $quotedSince
        );

        return (int) $count;
    }

    private function cleanup($shopId, $olderThan): void
    {
        $db = DatabaseProvider::getDb();
        $db->execute(
            'DELETE FROM ' . self::TABLE
            . ' WHERE OXSHOPID = ' . (int) $shopId
            . ' AND OXTIME < ' . (int) $olderThan
        );
    }

    private function saveProtectUntil($until): void
    {
        $config = Registry::getConfig();
        $config->saveShopConfVar(
            'num',
            self::PROTECT_UNTIL_PARAM,
            (int) $until,
            $config->getShopId()
        );
        $config->setConfigParam(self::PROTECT_UNTIL_PARAM, (int) $until);
    }

    private function getShopId(): int
    {
        return (int) Registry::getConfig()->getShopId();
    }
}
