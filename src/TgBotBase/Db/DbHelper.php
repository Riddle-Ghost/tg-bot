<?php

namespace Riddle\TgBotBase\Db;

require_once __DIR__ . '/rb-sqlite.php';

class DbHelper
{
    public static function init(DbConfig $config): void
    {
        $migrationService = new MigrationService($config);
        $migrationService->migrateAll();

        $seedService = new SeedService($config);
        $seedService->seedAll();

        foreach ($config->sqlExecutions as $sql) {
            \R::exec($sql);
        }
        \R::freeze(true); // RedBean не будет пытаться менять структуру БД
    }
}
