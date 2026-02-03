<?php

namespace TgBase;

use Telegram\Bot\Keyboard\Keyboard;

class Output
{
    public function __construct(
        public readonly string $text,
        public readonly ?Keyboard $keyboard = null
    ) {}
}
