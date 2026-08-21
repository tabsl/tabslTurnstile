<?php

declare(strict_types=1);

namespace Tabsl\Turnstile\Tests;

use Tabsl\Turnstile\Service\AdaptiveRegisterService;

if (!class_exists(AdaptiveRegisterService::class)) {
    require dirname(__DIR__) . '/Service/AdaptiveRegisterService.php';
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

assertSame('max@gmail.com', AdaptiveRegisterService::normalizeEmail('  Max@Gmail.COM  '), 'normalize trims and lowercases');
assertSame('', AdaptiveRegisterService::normalizeEmail('nodomain'), 'normalize rejects missing @');
assertSame('', AdaptiveRegisterService::normalizeEmail(''), 'normalize rejects empty');
assertSame('gmail.com', AdaptiveRegisterService::extractDomain('Max@Gmail.COM'), 'extract domain');
assertSame('', AdaptiveRegisterService::extractDomain('nodomain'), 'extract empty without @');

assertSame(['gmail.com', 'yahoo.de'], AdaptiveRegisterService::parseDomainList("gmail.com\n@Yahoo.DE\n\n"), 'parse string list');
assertSame(['gmail.com'], AdaptiveRegisterService::parseDomainList([' gmail.com ', '', '@gmail.com']), 'parse array list unique');
assertSame([], AdaptiveRegisterService::parseDomainList([]), 'parse empty array');
assertSame([], AdaptiveRegisterService::parseDomainList(''), 'parse empty string');

assertSame(true, AdaptiveRegisterService::isEmailCountable('a@gmail.com', []), 'empty list counts all');
assertSame(true, AdaptiveRegisterService::isEmailCountable('a@gmail.com', ['gmail.com']), 'listed domain counts');
assertSame(false, AdaptiveRegisterService::isEmailCountable('a@firma.de', ['gmail.com']), 'unlisted domain skipped');
assertSame(false, AdaptiveRegisterService::isEmailCountable('a@googlemail.com', ['gmail.com']), 'exact domain, not googlemail');
assertSame(false, AdaptiveRegisterService::isEmailCountable('nodomain', []), 'invalid email never counts');
assertSame(false, AdaptiveRegisterService::isEmailCountable('a@gmail.com', ['yahoo.com']), 'other listed domain only');

if ($failed > 0) {
    echo "\n{$failed} failed\n";
    exit(1);
}

echo "\nall passed\n";
exit(0);
