<?php

declare(strict_types=1);

namespace Tabsl\Turnstile\Service;

use OxidEsales\Eshop\Core\Registry;

class TurnstileService
{
    private const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function verifyToken($token, string $remoteIp): bool
    {
        $secretKey = Registry::getConfig()->getConfigParam('tabslturnstile_secret_key');

        if (empty($secretKey) || empty($token)) {
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
            return false;
        }

        $result = json_decode($response, true);

        return isset($result['success']) && $result['success'] === true;
    }

    public function getSiteKey(): string
    {
        return Registry::getConfig()->getConfigParam('tabslturnstile_site_key') ?: '';
    }

    public function getTheme(): string
    {
        return Registry::getConfig()->getConfigParam('tabslturnstile_theme') ?: 'auto';
    }

    public function getSize(): string
    {
        return Registry::getConfig()->getConfigParam('tabslturnstile_size') ?: 'normal';
    }

    public function isEnabledForContact(): bool
    {
        return (bool) Registry::getConfig()->getConfigParam('tabslturnstile_enable_contact');
    }

    public function isEnabledForRegister(): bool
    {
        return (bool) Registry::getConfig()->getConfigParam('tabslturnstile_enable_register');
    }

    public function isEnabledForNewsletter(): bool
    {
        return (bool) Registry::getConfig()->getConfigParam('tabslturnstile_enable_newsletter');
    }

    public function isEnabledForForgotPassword(): bool
    {
        return (bool) Registry::getConfig()->getConfigParam('tabslturnstile_enable_forgotpassword');
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
