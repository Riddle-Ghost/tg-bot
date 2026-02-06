<?php

namespace Riddle\TgBotBase\BotCore;

use Riddle\TgBotBase\Db\DbConfig;

class TgBotConfig
{
    public function __construct(
        public readonly string $tgBotToken,
        public readonly DbConfig $dbConfig
    ) {}

    // public function withDb(DbConfig $dbConfig): self
    // {
    //     $this->dbConfig = $dbConfig;

    //     return $this;
    // }
}
