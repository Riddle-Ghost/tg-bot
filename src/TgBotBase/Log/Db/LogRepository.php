<?php

namespace Riddle\TgBotBase\Log\Db;

class LogRepository
{
    public const TYPE_AI_REQUEST = 'ai_request';

    public function save(string $type, string $text, ?int $userId = null): void
    {
        \R::selectDatabase('logs');

        $bean = \R::dispense('log');
        $bean->type = $type;
        $bean->user_id = $userId;
        $bean->text = $text;

        \R::store($bean);

        \R::selectDatabase('default');
    }
}
