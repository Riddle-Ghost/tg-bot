<?php

namespace TgBase;

use TgBase\Output;
use Telegram\Bot\Api;
use TgBase\TgBotConfig;
use Telegram\Bot\Objects\Update;

class TgBot
{
    private Api $api;

    public function __construct(
        private TgBotHandlerInterface $tgBotHandler,
        private TgBotConfig $tgBotConfig
    )
    {
        $this->api = new Api($this->tgBotConfig->tgBotToken);
        $this->api->deleteWebhook();
    }

    public function run()
    {
        $updates = $this->getUpdates();

        while (count($updates) > 0) {
            foreach ($updates as $update) {

                // var_dump($update);die;
                $output = $this->handleEvent($update);

                $chatId = $update->getChat()->getId();

                if ($output) {
                    $response = $this->api->sendMessage([
                        'chat_id' => $chatId,
                        'text' => $output->text,
                        'reply_markup' => $output->keyboard
                    ]);
                }
            }

            $offset = $update->getUpdateId() + 1;
            $updates = $this->getUpdates($offset);
        }
    }

    /**
     * @return Update[]
     */
    private function getUpdates(?int $offset = null): array
    {
        $updates = $this->api->getUpdates([
            'timeout' => 1, //Check for new messages every ... seconds
            'offset' => $offset,
        ]);

        return $updates;
    }

    private function handleEvent(Update $update): ?Output
    {
        if ($update->isType('callback_query')) {
            $callbackQuery = $update->getRelatedObject();

            $input = new Input(
               $callbackQuery->getData(),
               Input::TYPE_BUTTON,
               $callbackQuery->getChat()->getId(),
               $callbackQuery->getChat()->getUsername()
            );
           
            return $this->tgBotHandler->handleButton($input);
        }

        if ($update->isType('message')) {
            $message = $update->getMessage();

            if ($message->hasCommand()) {

                $input = new Input(
                    $message->getText(),
                    Input::TYPE_COMMAND,
                    $message->getChat()->getId(),
                    $message->getChat()->getUsername()
                );


                if ($message->getText() === '/start') {
                    return $this->tgBotHandler->handleStart($input);
                }

                return $this->tgBotHandler->handleCommand($input);
            }

            if ($message->isType('text')) {
                $input = new Input(
                    $message->getText(),
                    Input::TYPE_MESSAGE,
                    $message->getChat()->getId(),
                    $message->getChat()->getUsername()
                );

                return $this->tgBotHandler->handleMessage($input);
            }
        }

        return null;
    }
}
