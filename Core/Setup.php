<?php

declare(strict_types=1);

namespace Tabsl\Turnstile\Core;

use OxidEsales\Eshop\Core\DatabaseProvider;

class Setup
{
    public static function onActivate(): void
    {
        self::createDatabaseTables();
    }

    public static function onDeactivate(): void
    {
        self::cleanupCache();
    }

    public static function createDatabaseTables(): void
    {
        $db = DatabaseProvider::getDb();
        $db->execute(
            'CREATE TABLE IF NOT EXISTS tabslturnstile_regattempt (
                OXID CHAR(32) NOT NULL,
                OXSHOPID INT(11) NOT NULL DEFAULT 1,
                OXEMAIL VARCHAR(255) NOT NULL,
                OXTIME INT(11) NOT NULL DEFAULT 0,
                PRIMARY KEY (OXID),
                UNIQUE KEY ux_shop_email (OXSHOPID, OXEMAIL),
                KEY ix_shop_time (OXSHOPID, OXTIME)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8'
        );
    }

    private static function cleanupCache(): void
    {
    }
}
