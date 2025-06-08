[{$smarty.block.parent}]

[{assign var="turnstileService" value=$oViewConf->getTurnstileService()}]
[{if $turnstileService && $turnstileService->isEnabledForContact() && $turnstileService->getSiteKey()}]
    <div class="form-group">
        <label class=" control-label col-lg-2">
            &nbsp;
        </label>
        <div class="col-lg-10 controls">
            <div class="turnstile-wrapper" style="margin: 15px 0;">
                <div class="cf-turnstile" data-sitekey="[{$turnstileService->getSiteKey()}]"
                    data-theme="[{$turnstileService->getTheme()}]" data-size="[{$turnstileService->getSize()}]">
                </div>
            </div>
        </div>
    </div>
    [{if !$oViewConf->isTurnstileScriptLoaded()}]
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        [{$oViewConf->setTurnstileScriptLoaded()}]
    [{/if}]
[{/if}]