[{$smarty.block.parent}]

[{assign var="turnstileService" value=$oViewConf->getTurnstileService()}]
[{if $turnstileService && $turnstileService->isEnabledForForgotPassword() && $turnstileService->getSiteKey()}]
    <div class="turnstile-wrapper" style="margin: 15px 0;">
        <div class="cf-turnstile" data-sitekey="[{$turnstileService->getSiteKey()}]"
            data-theme="[{$turnstileService->getTheme()}]" data-size="[{$turnstileService->getSize()}]">
        </div>
    </div>
    [{if !$oViewConf->isTurnstileScriptLoaded()}]
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        [{$oViewConf->setTurnstileScriptLoaded()}]
    [{/if}]
[{/if}]