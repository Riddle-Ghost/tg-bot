<?php

namespace Riddle\TgBotBase\Db;

class DbConfig
{
    public function __construct(
        public readonly string $dbDir,
        public private(set) array $migrations = [],
        public private(set) array $seedFiles = [],
        public private(set) array $sqlExecutions = [],
    ) {}

    /**
     * Можно добавлять новые таблицы и индексы
     */
    public function addMigration(MigrationDto $dto): self
    {
        $this->migrations[] = $dto;

        return $this;
    }
    
    /**
     * Можно заполнять таблицы
     */
    public function addSeedFile(string $filePath): self
    {
        $this->seedFiles[] = $filePath;

        return $this;
    }

    /**
     * SQL выполняется при запуске скрипта.
     */
    public function addExecution(string $sql): self
    {
        $this->sqlExecutions[] = $sql;

        return $this;
    }    
}
