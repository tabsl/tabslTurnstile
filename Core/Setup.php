<?php

declare(strict_types=1);

namespace Tabsl\Turnstile\Core;

class Setup
{
    public static function onActivate(): void
    {
        // Modul-spezifische Aktivierungslogik
        self::createDatabaseTables();
    }

    public static function onDeactivate(): void
    {
        // Modul-spezifische Deaktivierungslogik
        self::cleanupCache();
    }

    private static function createDatabaseTables(): void
    {
    }

    private static function cleanupCache(): void
    {
    }
} 