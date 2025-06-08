<?php

declare(strict_types=1);

namespace Tabsl\Turnstile\Controller;

use OxidEsales\Eshop\Core\Registry;
use Tabsl\Turnstile\Service\TurnstileService;

class ForgotPasswordController extends ForgotPasswordController_parent
{
    public function forgotPassword()
    {
        $send = parent::forgotPassword();
        
        $turnstileService = new TurnstileService();
        
        if ($turnstileService->isEnabledForForgotPassword()) {
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