<?php

namespace Riddle\TgBotBase\Db;

class DbConfig
{
    public readonly string $dbDir;
    private(set) array $sqlExecutions = [];
    
    public function __construct(
        string $dbDir
    )
    {
        $this->dbDir = $dbDir;
    }

    /**
     * Можно добавлять новые таблицы и индексы
     */
    public function addExecution(string $sql): self
    {
        $this->sqlExecutions[] = $sql;

        return $this;
    }
}
