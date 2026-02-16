<?php

namespace Riddle\TgBotBase\Db;

abstract class BaseRepository
{
    abstract protected function getDb(): string;

    abstract protected function getTable(): string;

    protected function switchDb(): void
    {
        \R::selectDatabase($this->getDb());
    }
}
