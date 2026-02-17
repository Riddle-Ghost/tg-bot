<?php

use Riddle\TgBotBase\Ai\AiService;
use Riddle\TgBotBase\BotCore\Dto\Input;
use Riddle\TgBotBase\BotCore\Dto\Output;
use Riddle\TgBotBase\Ai\Entity\AiContext;
use Riddle\TgBotBase\BotCore\TgBotHandlerInterface;

class TestTgBotHandler implements TgBotHandlerInterface
{
    public function __construct(
        private readonly AiService $aiService,
    ) {}

    public function handleStart(Input $input): Output
    {
        return new Output('Start');
    }

    public function handleButton(Input $input): Output
    {
        return new Output('handleButton');
    }

    public function handleMessage(Input $input): Output
    {
        $context = new AiContext($input->user->tgId)
            ->addUserContext($input->text);

        $responseText = $this->aiService->request($context);

        return new Output($responseText);

    }

    public function handleCommand(Input $input): Output
    {
        if ($input->text === '/info') {
            return new Output('handleInfo');
        }

        if (str_starts_with($input->text, '/settings')) {
            return $this->handleSettings($input);
        }
        
        return new Output('Команда: ' . $input->text . ' не поддерживается');
    }

    private function handleSettings(Input $input): Output
    {
        return new Output('handleSettings');
    }
}
