<?php

declare(strict_types=1);

namespace Tabsl\Turnstile\Tests;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use Symfony\Component\String\UnicodeString;
use Tabsl\Turnstile\Service\TurnstileService;

require getenv('OXID_AUTOLOAD') ?: dirname(__DIR__, 3) . '/autoload.php';

if (!class_exists(TurnstileService::class)) {
    require dirname(__DIR__) . '/Service/TurnstileService.php';
}

final class FakeModuleSettings implements ModuleSettingServiceInterface
{
    public array $requestedModuleIds = [];

    public function __construct(private array $values)
    {
    }

    private function value(string $name, string $moduleId): mixed
    {
        $this->requestedModuleIds[] = $moduleId;

        return $this->values[$name];
    }

    public function getInteger(string $name, string $moduleId): int
    {
        return $this->value($name, $moduleId);
    }

    public function getFloat(string $name, string $moduleId): float
    {
        return $this->value($name, $moduleId);
    }

    public function getString(string $name, string $moduleId): UnicodeString
    {
        return new UnicodeString($this->value($name, $moduleId));
    }

    public function getBoolean(string $name, string $moduleId): bool
    {
        return $this->value($name, $moduleId);
    }

    public function getCollection(string $name, string $moduleId): array
    {
        return $this->value($name, $moduleId);
    }

    public function saveInteger(string $name, int $value, string $moduleId): void
    {
    }

    public function saveFloat(string $name, float $value, string $moduleId): void
    {
    }

    public function saveString(string $name, string $value, string $moduleId): void
    {
    }

    public function saveBoolean(string $name, bool $value, string $moduleId): void
    {
    }

    public function saveCollection(string $name, array $value, string $moduleId): void
    {
    }

    public function exists(string $name, string $moduleId): bool
    {
        return isset($this->values[$name]);
    }
}

$failed = 0;

function assertSame($expected, $actual, $message): void
{
    global $failed;
    if ($expected !== $actual) {
        $failed++;
        echo "FAIL: {$message}\n  expected: " . var_export($expected, true) . "\n  actual:   " . var_export($actual, true) . "\n";
        return;
    }
    echo "OK: {$message}\n";
}

$configured = new FakeModuleSettings([
    'tabslturnstile_site_key' => '1x00000000000000000000AA',
    'tabslturnstile_theme' => 'dark',
    'tabslturnstile_size' => 'compact',
    'tabslturnstile_enable_contact' => true,
    'tabslturnstile_enable_register' => false,
    'tabslturnstile_enable_register_adaptive' => true,
    'tabslturnstile_register_window' => 7,
    'tabslturnstile_register_threshold' => 20,
    'tabslturnstile_register_domains' => ['gmail.com'],
    'tabslturnstile_blocked_domains' => ['maxxspace.com'],
    'tabslturnstile_enable_honeypot' => true,
    'tabslturnstile_enable_newsletter' => false,
    'tabslturnstile_enable_forgotpassword' => true,
]);
$service = new TurnstileService($configured);

assertSame('1x00000000000000000000AA', $service->getSiteKey(), 'site key read from module settings');
assertSame('dark', $service->getTheme(), 'theme read from module settings');
assertSame('compact', $service->getSize(), 'size read from module settings');
assertSame(true, $service->isEnabledForContact(), 'contact toggle');
assertSame(false, $service->isEnabledForRegister(), 'register toggle');
assertSame(true, $service->isRegisterAdaptiveEnabled(), 'adaptive toggle');
assertSame(7, $service->getRegisterWindowMinutes(), 'window as int');
assertSame(20, $service->getRegisterThreshold(), 'threshold as int');
assertSame(['gmail.com'], $service->getRegisterDomains(), 'domains collection');
assertSame(false, $service->isEnabledForNewsletter(), 'newsletter toggle');
assertSame(true, $service->isEnabledForForgotPassword(), 'forgot password toggle');
assertSame(['maxxspace.com'], $service->getBlockedDomains(), 'blocked domains collection');
assertSame(true, $service->isHoneypotEnabled(), 'honeypot toggle');
assertSame(['tabslTurnstile'], array_values(array_unique($configured->requestedModuleIds)), 'all reads use module id tabslTurnstile');

$legacy = new TurnstileService(new FakeModuleSettings([
    'tabslturnstile_theme' => '',
    'tabslturnstile_size' => '',
    'tabslturnstile_register_window' => '12',
    'tabslturnstile_register_threshold' => '0',
]));

assertSame('auto', $legacy->getTheme(), 'empty theme falls back to auto');
assertSame('normal', $legacy->getSize(), 'empty size falls back to normal');
assertSame(12, $legacy->getRegisterWindowMinutes(), 'string window from 1.x config is read as int');
assertSame(10, $legacy->getRegisterThreshold(), 'threshold below 1 falls back to 10');

assertSame([], $legacy->getBlockedDomains(), 'missing block list after update blocks nothing');
assertSame(false, $legacy->isHoneypotEnabled(), 'missing honeypot setting after update stays off');

if ($failed > 0) {
    echo "\n{$failed} failed\n";
    exit(1);
}

echo "\nall passed\n";
exit(0);
