<?php

namespace Riddle\TgBotBase\Ai;

use Riddle\TgBotBase\Ai\Api\ApiInterface;
use Riddle\TgBotBase\Ai\Entity\AiContext;

class AiService
{
    private ApiInterface $api;

    public function __construct(ApiInterface $api)
    {
        $this->api = $api;
    }

    public function request(AiContext $aiContext): string
    {
        $responseText = $this->api->request($aiContext);

        return $responseText;
    }
}
