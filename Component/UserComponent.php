<?php

declare(strict_types=1);

namespace Tabsl\Turnstile\Component;

use OxidEsales\Eshop\Core\Registry;
use Tabsl\Turnstile\Service\AdaptiveRegisterService;
use Tabsl\Turnstile\Service\TurnstileService;

class UserComponent extends UserComponent_parent
{
    public function createUser()
    {
        if (!$this->guardTurnstile()) {
            return false;
        }

        return parent::createUser();
    }

    public function changeUser()
    {
        if (!$this->guardTurnstileIfGuest()) {
            return false;
        }

        return parent::changeUser();
    }

    public function changeuser_testvalues()
    {
        if (!$this->guardTurnstileIfGuest()) {
            return false;
        }

        return parent::changeuser_testvalues();
    }

    private function guardTurnstileIfGuest(): bool
    {
        if (!$this->shouldRecordGuestAttempt()) {
            return true;
        }

        return $this->guardTurnstile();
    }

    private function guardTurnstile(): bool
    {
        $adaptiveService = new AdaptiveRegisterService();
        $challengeRequired = $adaptiveService->isChallengeRequired();

        $adaptiveService->recordAttempt($this->getSubmittedEmail());

        if (!$challengeRequired) {
            return true;
        }

        $turnstileToken = Registry::getRequest()->getRequestEscapedParameter('cf-turnstile-response') ?? null;
        $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';

        if (!(new TurnstileService())->verifyToken($turnstileToken, (string) $remoteIp)) {
            Registry::getUtilsView()->addErrorToDisplay('TURNSTILE_VERIFICATION_FAILED');
            return false;
        }

        return true;
    }

    private function shouldRecordGuestAttempt(): bool
    {
        $user = $this->getUser();
        if (!$user || !$user->getId()) {
            return true;
        }

        return empty($user->oxuser__oxpassword->value);
    }

    private function getSubmittedEmail(): string
    {
        $request = Registry::getRequest();
        $email = $request->getRequestParameter('lgn_usr');
        if (!empty($email)) {
            return (string) $email;
        }

        $invadr = $request->getRequestParameter('invadr');
        if (is_array($invadr) && !empty($invadr['oxuser__oxusername'])) {
            return (string) $invadr['oxuser__oxusername'];
        }

        return '';
    }
}
