<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class MockTelegramClient implements TelegramClientInterface
{
    public function __construct(private readonly LoggerInterface $logger) {}

    public final function sendMessage(string $botToken, string $chatId, string $text): bool
    {
        $this->logger->info('[mock] Telegram message', ['chat_id' => $chatId, 'text' => $text ]);
        return true;
    }
}
