<?php

declare(strict_types=1);

namespace Tabsl\Turnstile\Core;

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
} 