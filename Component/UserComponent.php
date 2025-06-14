<?php

declare(strict_types=1);

namespace Tabsl\Turnstile\Component;

use OxidEsales\Eshop\Core\Registry;
use Tabsl\Turnstile\Service\TurnstileService;

class UserComponent extends UserComponent_parent
{

    public function createUser()
    {
        $send = parent::createUser();

        $turnstileService = new TurnstileService();

        if ($turnstileService->isEnabledForRegister()) {
            $turnstileToken = Registry::getRequest()->getRequestEscapedParameter('cf-turnstile-response');
            $remoteIp = $_SERVER['REMOTE_ADDR'] ?? null;

            if (!$turnstileService->verifyToken($turnstileToken, $remoteIp)) {
                Registry::getUtilsView()->addErrorToDisplay('TURNSTILE_VERIFICATION_FAILED');
                return;
            }
        }

        return $send;
    }
}
