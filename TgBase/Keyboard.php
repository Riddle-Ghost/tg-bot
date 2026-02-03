<?php

namespace TgBase;

use Telegram\Bot\Keyboard\Keyboard as TelegramKeyboard;
use Telegram\Bot\Keyboard\Button;

class Keyboard
{
    private TelegramKeyboard $keyboard;

    public function __construct()
    {
        $this->keyboard = TelegramKeyboard::make()
            ->inline();
    }

    public static function button(string $text, string $callbackData): Button
    {
        return TelegramKeyboard::inlineButton(['text' => $text, 'callback_data' => $callbackData]);
    }

    /**
     * @param Button[] $buttons
     */
    public function addRow(array $buttons): self
    {
        $row = [];
        foreach ($buttons as $button) {
            $row[] = $button;
        }
        $this->keyboard->row($row);

        return $this;
    }

    public function generate(): TelegramKeyboard
    {
        return $this->keyboard;
    }
}
