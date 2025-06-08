<?php

declare(strict_types=1);

namespace Tabsl\Turnstile\Application\Model;

use OxidEsales\Eshop\Core\Registry;
use Tabsl\Turnstile\Service\TurnstileService;

class TurnstileValidator
{
    private TurnstileService $turnstileService;

    public function __construct()
    {
        $this->turnstileService = new TurnstileService();
    }

    public function validateForContact(): bool
    {
        if (!$this->turnstileService->isEnabledForContact()) {
            return true;
        }

        return $this->validateTurnstileToken();
    }

    public function validateForRegister(): bool
    {
        if (!$this->turnstileService->isEnabledForRegister()) {
            return true;
        }

        return $this->validateTurnstileToken();
    }

    public function validateForNewsletter(): bool
    {
        if (!$this->turnstileService->isEnabledForNewsletter()) {
            return true;
        }

        return $this->validateTurnstileToken();
    }

    public function validateForForgotPassword(): bool
    {
        if (!$this->turnstileService->isEnabledForForgotPassword()) {
            return true;
        }

        return $this->validateTurnstileToken();
    }

    private function validateTurnstileToken(): bool
    {
        $turnstileToken = Registry::getRequest()->getRequestEscapedParameter('cf-turnstile-response');
        $remoteIp = $_SERVER['REMOTE_ADDR'] ?? null;

        if (empty($turnstileToken)) {
            return false;
        }

        return $this->turnstileService->verifyToken($turnstileToken, $remoteIp);
    }
} 