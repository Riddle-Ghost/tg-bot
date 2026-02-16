<?php

namespace Riddle\TgBotBase\Ai;

use Riddle\TgBotBase\Ai\Entity\AiContext;
use Riddle\TgBotBase\Log\Helpers\LogHelper;

class AiServiceLogDecorator extends AiService
{
    private AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function request(AiContext $aiContext): string
    {
        $userText = $aiContext->context[count($aiContext->context) - 1]['content'];
        
        $responseText = $this->aiService->request($aiContext);

        $log = 'USER: ' . $userText . PHP_EOL . 'AI: ' . $responseText;

        LogHelper::aiRequest($log, $aiContext->tgId);

        return $responseText;
    }
}
