<?php

namespace Riddle\TgBotBase\Ai\Api;

use Riddle\TgBotBase\Ai\Entity\AiContext;

interface ApiInterface
{
    public function request(AiContext $aiContext): string;
}