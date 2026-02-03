<?php

namespace TgBase;

class Input
{
    public const TYPE_MESSAGE = 'message';
    public const TYPE_COMMAND = 'command';
    public const TYPE_BUTTON = 'button';

    public function __construct(
        public readonly string $text,
        public readonly string $type,
        public readonly int $fromUserId,
        public readonly string $fromUsername,
    ) {}
}
