<?php

namespace Riddle\TgBotBase\Db;

require_once __DIR__ . '/rb-sqlite.php';

class MigrationService
{
    public const string DB_USERS = 'users';
    public const string DB_TECH = 'tech';
    public const string DB_AI = 'ai';

    public function __construct(
        public readonly DbConfig $config,
    ) {}

    public function migrateAll(): void
    {
        // \R::setup(self::getDsn(self::DB_USERS));
        $this->migrate(self::users());
        $this->migrate(self::aiContexts());
        $this->migrate(self::logs());
        $this->migrate(self::seeds());
        
        foreach ($this->config->migrations as $migration) {
            $this->migrate($migration);
        }
    }

    public function migrate(MigrationDto $dto): void
    {
        if (!\R::hasDatabase($dto->dbName)) {
            \R::addDatabase($dto->dbName, self::getDsn($dto->dbName));
        }

        \R::selectDatabase($dto->dbName);
        \R::exec($dto->createTableSql);
        foreach ($dto->indexSql as $indexSql) {
            \R::exec($indexSql);
        }
    }

    public function getDsn(string $dbName): string
    {
        return 'sqlite:' . $this->getPath($dbName);
    }

    public function getPath(string $dbName): string
    {
        return $this->config->dbDir . '/' . $dbName . '.sqlite';
    }

    private function users(): MigrationDto
    {
        $createTableSql = "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tg_id BIGINT UNIQUE,
            username VARCHAR(255),
            is_premium BOOLEAN DEFAULT 0,
            is_blocked BOOLEAN DEFAULT 0,
            settings JSON DEFAULT '{}',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";

        return new MigrationDto(
            dbName: self::DB_USERS,
            createTableSql: $createTableSql,
            indexSql: [
                "CREATE UNIQUE INDEX IF NOT EXISTS idx_tg_id ON users (tg_id)",
            ]
        );
    }

    private function aiContexts(): MigrationDto
    {
        $createTableSql = "CREATE TABLE IF NOT EXISTS ai_contexts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tg_id BIGINT UNIQUE,
            context TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";

        return new MigrationDto(
            dbName: self::DB_AI,
            createTableSql: $createTableSql,
            indexSql: [
                "CREATE UNIQUE INDEX IF NOT EXISTS idx_tg_id ON ai_contexts (tg_id)",
            ]
        );
    }

    private function logs(): MigrationDto
    {
        $createTableSql = "CREATE TABLE IF NOT EXISTS logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            type VARCHAR(20) UNIQUE,
            user_id INTEGER,
            text TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";

        return new MigrationDto(
            dbName: self::DB_TECH,
            createTableSql: $createTableSql,
            indexSql: [
                "CREATE UNIQUE INDEX IF NOT EXISTS idx_type ON logs (type)",
                "CREATE  INDEX IF NOT EXISTS idx_user_id ON logs (user_id)",
            ]
        );
    }

    private function seeds(): MigrationDto
    {
        $createTableSql = "CREATE TABLE IF NOT EXISTS seeds (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            file VARCHAR(255) UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";

        return new MigrationDto(
            dbName: self::DB_TECH,
            createTableSql: $createTableSql,
            indexSql: [],
        );
    }
}
