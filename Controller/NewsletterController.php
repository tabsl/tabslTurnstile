<?php

declare(strict_types=1);

namespace Tabsl\Turnstile\Controller;

use OxidEsales\Eshop\Core\Registry;
use Tabsl\Turnstile\Service\AdaptiveRegisterService;
use Tabsl\Turnstile\Service\TurnstileService;

class NewsletterController extends NewsletterController_parent
{
    public function send()
    {
        $turnstileService = new TurnstileService();

        // Only subscribing creates an account; unsubscribing stays open for every address.
        if (Registry::getRequest()->getRequestParameter('subscribeStatus')) {
            $params = Registry::getRequest()->getRequestParameter('editval');
            $email = is_array($params) ? ($params['oxuser__oxusername'] ?? null) : null;

            if (is_string($email) && AdaptiveRegisterService::isDomainBlocked($email, $turnstileService->getBlockedDomains())) {
                Registry::getLogger()->warning(
                    '[NewsletterController] submission rejected: blocked domain ' . AdaptiveRegisterService::extractDomain($email) . ' (ip=' . ($_SERVER['REMOTE_ADDR'] ?? '') . ')'
                );
                Registry::getUtilsView()->addErrorToDisplay('TURNSTILE_EMAIL_DOMAIN_BLOCKED');
                return;
            }
        }

        if ($turnstileService->isEnabledForNewsletter()) {
            $turnstileToken = Registry::getRequest()->getRequestEscapedParameter('cf-turnstile-response');
            $remoteIp = $_SERVER['REMOTE_ADDR'] ?? null;

            if (!$turnstileService->verifyToken($turnstileToken, $remoteIp)) {
                Registry::getUtilsView()->addErrorToDisplay('TURNSTILE_VERIFICATION_FAILED');
                return;
            }
        }

        return parent::send();
    }
} 