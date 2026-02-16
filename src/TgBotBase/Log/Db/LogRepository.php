<?php

namespace Riddle\TgBotBase\Log\Db;

use Riddle\TgBotBase\Db\BaseRepository;

class LogRepository extends BaseRepository
{
    public const TYPE_AI_REQUEST = 'ai_request';

    protected function getDb(): string
    {
        return 'log';
    }

    protected function getTable(): string
    {
        return 'log';
    }

    public function save(string $type, string $text, ?int $userId = null): void
    {
        $this->switchDb();

        $bean = \R::dispense($this->getTable());
        $bean->type = $type;
        $bean->user_id = $userId;
        $bean->text = $text;

        \R::store($bean);
    }
}
