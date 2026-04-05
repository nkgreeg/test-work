<?php

namespace App\Service;

interface TelegramClientInterface
{
    public function sendMessage(string $botToken, string $chatId, string $text): bool;
}
