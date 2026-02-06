<?php

namespace Riddle\TgBotBase\Ai\Api;

use OpenAI;
use RuntimeException;
use GuzzleHttp\Exception\GuzzleException;
use Riddle\TgBotBase\Ai\Entity\AiContext;

class OpenaiPromptAPI implements ApiInterface
{
    private OpenAI\Client $client;

    public function __construct(
        protected string $token,
        protected string $promptId,
    )
    {
        $this->client = OpenAI::client($this->token);
    }

    /**
     * @throws RuntimeException|GuzzleException
     */
    public function request(AiContext $aiContext): string
    {
        $response = $this->client->responses()->create([
            // 'model' => 'gpt-4.1-mini',
            'prompt' => [
                'id'      => $this->promptId,
                // 'version' => '1',
            ],
            'input' => $aiContext->getContext(),
        ]);

        $text = '';
        foreach ($response->output as $item) {
            if ($item->type === 'message') {
                foreach ($item->content as $content) {
                    if ($content->type === 'output_text') {
                        $text .= $content->text;
                    }
                }
            }
        }
        
        return $text;
    }
}