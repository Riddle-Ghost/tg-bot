<?php

namespace Riddle\TgBotBase\Ai;

use RuntimeException;
use Riddle\TgBotBase\Ai\Entity\AiContext;
use Riddle\TgBotBase\Ai\Db\AiContextRepository;

class AiServiceContextDecorator extends AiService
{
    private AiService $aiService;
    private AiContextRepository $aiContextRepository;
    private ?int $trimContextSize = null;

    public function __construct(AiService $aiService, ?int $trimContextSize = null)
    {
        $this->aiService = $aiService;
        $this->aiContextRepository = new AiContextRepository();
        $this->trimContextSize = $trimContextSize;
    }

    public function request(AiContext $aiContext): string
    {
        $aiContext = $this->withAllContext($aiContext);

        $responseText = $this->aiService->request($aiContext);

        $aiContext->addAssistantContext($responseText);
        $this->aiContextRepository->save($aiContext);

        return $responseText;
    }

    private function withAllContext(AiContext $userContext): AiContext
    {
        if (!isset($userContext->context[0]['content'])) {
            throw new RuntimeException('User context is empty');
        }

        $allContext = $this->aiContextRepository->getByTgId($userContext->tgId);
        $allContext->addUserContext($userContext->context[0]['content']);

        if ($this->trimContextSize) {
            $allContext->trimContext($this->trimContextSize);
        }

        return $allContext;
    }
}
