<?php

declare(strict_types=1);

namespace Tabsl\Turnstile\Core;

use Tabsl\Turnstile\Service\AdaptiveRegisterService;
use Tabsl\Turnstile\Service\TurnstileService;

class ViewConfig extends ViewConfig_parent
{
    private $turnstileService = null;
    private $turnstileScriptLoaded = false;

    public function getTurnstileService(): TurnstileService
    {
        if ($this->turnstileService === null) {
            $this->turnstileService = new TurnstileService();
        }
        
        return $this->turnstileService;
    }

    public function isTurnstileScriptLoaded(): bool
    {
        return $this->turnstileScriptLoaded;
    }

    public function setTurnstileScriptLoaded(): void
    {
        $this->turnstileScriptLoaded = true;
    }

    public function isTurnstileRegisterChallengeRequired(): bool
    {
        $user = $this->getUser();
        if ($user && $user->getId() && !empty($user->oxuser__oxpassword->value)) {
            return false;
        }

        return (new AdaptiveRegisterService())->isChallengeRequired();
    }

    public function getTurnstileInjectedFormFunction(): string
    {
        $turnstileService = $this->getTurnstileService();
        if ($turnstileService->getSiteKey() === '') {
            return '';
        }

        $controller = $this->getTopActiveClassName();
        if ($controller === 'newsletter' && $turnstileService->isEnabledForNewsletter()) {
            return 'send';
        }

        if ($controller === 'forgotpwd' && $turnstileService->isEnabledForForgotPassword()) {
            return 'forgotpassword';
        }

        return '';
    }
} 