<?php

declare(strict_types=1);

namespace Tabsl\Turnstile\Service;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

class TurnstileService
{
    public const MODULE_ID = 'tabslTurnstile';

    private const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    private ModuleSettingServiceInterface $moduleSettings;

    public function __construct(?ModuleSettingServiceInterface $moduleSettings = null)
    {
        $this->moduleSettings = $moduleSettings
            ?? ContainerFactory::getInstance()->getContainer()->get(ModuleSettingServiceInterface::class);
    }

    public function verifyToken($token, string $remoteIp): bool
    {
        $secretKey = $this->getString('tabslturnstile_secret_key');

        if (empty($secretKey)) {
            Registry::getLogger()->warning('[TurnstileService] verification skipped: no secret key configured');
            return false;
        }

        if (empty($token)) {
            Registry::getLogger()->warning('[TurnstileService] verification failed: no token submitted (ip=' . $remoteIp . ')');
            return false;
        }

        $postData = [
            'secret' => $secretKey,
            'response' => $token,
        ];

        if ($remoteIp) {
            $postData['remoteip'] = $remoteIp;
        }

        $response = $this->makeRequest(self::VERIFY_URL, $postData);

        if (!$response) {
            Registry::getLogger()->warning('[TurnstileService] verification failed: no response from Cloudflare (ip=' . $remoteIp . ')');
            return false;
        }

        $result = json_decode($response, true);
        $success = isset($result['success']) && $result['success'] === true;

        if (!$success) {
            $errorCodes = isset($result['error-codes']) && is_array($result['error-codes'])
                ? implode(',', $result['error-codes'])
                : 'unknown';
            Registry::getLogger()->warning('[TurnstileService] verification failed: ' . $errorCodes . ' (ip=' . $remoteIp . ')');
        }

        return $success;
    }

    public function getSiteKey(): string
    {
        return $this->getString('tabslturnstile_site_key');
    }

    public function getTheme(): string
    {
        return $this->getString('tabslturnstile_theme') ?: 'auto';
    }

    public function getSize(): string
    {
        return $this->getString('tabslturnstile_size') ?: 'normal';
    }

    public function isEnabledForContact(): bool
    {
        return $this->moduleSettings->getBoolean('tabslturnstile_enable_contact', self::MODULE_ID);
    }

    public function isEnabledForRegister(): bool
    {
        return $this->moduleSettings->getBoolean('tabslturnstile_enable_register', self::MODULE_ID);
    }

    public function isRegisterAdaptiveEnabled(): bool
    {
        return $this->moduleSettings->getBoolean('tabslturnstile_enable_register_adaptive', self::MODULE_ID);
    }

    public function getRegisterWindowMinutes(): int
    {
        $window = $this->getInteger('tabslturnstile_register_window');

        return $window >= 1 ? $window : 5;
    }

    public function getRegisterThreshold(): int
    {
        $threshold = $this->getInteger('tabslturnstile_register_threshold');

        return $threshold >= 1 ? $threshold : 10;
    }

    public function getRegisterDomains(): array
    {
        return $this->moduleSettings->getCollection('tabslturnstile_register_domains', self::MODULE_ID);
    }

    public function getBlockedDomains(): array
    {
        // Setting is missing until oe:module:install ran after an update; getCollection() would throw and break every registration.
        if (!$this->moduleSettings->exists('tabslturnstile_blocked_domains', self::MODULE_ID)) {
            return [];
        }

        return $this->moduleSettings->getCollection('tabslturnstile_blocked_domains', self::MODULE_ID);
    }

    public function isHoneypotEnabled(): bool
    {
        if (!$this->moduleSettings->exists('tabslturnstile_enable_honeypot', self::MODULE_ID)) {
            return false;
        }

        return $this->moduleSettings->getBoolean('tabslturnstile_enable_honeypot', self::MODULE_ID);
    }

    public function isEnabledForNewsletter(): bool
    {
        return $this->moduleSettings->getBoolean('tabslturnstile_enable_newsletter', self::MODULE_ID);
    }

    public function isEnabledForForgotPassword(): bool
    {
        return $this->moduleSettings->getBoolean('tabslturnstile_enable_forgotpassword', self::MODULE_ID);
    }

    private function getString(string $name): string
    {
        return $this->moduleSettings->getString($name, self::MODULE_ID)->toString();
    }

    private function getInteger(string $name): int
    {
        try {
            return $this->moduleSettings->getInteger($name, self::MODULE_ID);
        } catch (\TypeError) {
            // Configurations carried over from 1.x store num settings as strings ('5'),
            // which ModuleSettingService::getInteger() rejects under strict_types.
            return (int) $this->getString($name);
        }
    }

    private function makeRequest(string $url, array $postData): ?string
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($postData),
                'timeout' => 10,
            ],
        ]);

        $response = @file_get_contents($url, false, $context);

        return $response !== false ? $response : null;
    }
}
