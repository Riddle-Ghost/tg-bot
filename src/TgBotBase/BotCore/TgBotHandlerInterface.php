<?php

namespace Riddle\TgBotBase\BotCore;

use Riddle\TgBotBase\BotCore\Dto\Input;
use Riddle\TgBotBase\BotCore\Dto\Output;

interface TgBotHandlerInterface
{
    public function handleStart(Input $input): Output;

    public function handleButton(Input $input): Output;

    public function handleMessage(Input $input): Output;

    public function handleCommand(Input $input): Output;
}
