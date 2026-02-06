<?php

namespace Riddle\TgBotBase\BotCore;

class TgBotConfig
{
    public function __construct(
        public readonly string $tgBotToken
    ) {}
}
