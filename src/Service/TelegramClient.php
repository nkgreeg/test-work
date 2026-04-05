<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TelegramClient implements TelegramClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger
    ) {}

    public function sendMessage(string $botToken, string $chatId, string $text): bool
    {
        try {
            $response = $this->httpClient->request('POST', "https://api.telegram.org/bot{$botToken}/sendMessage", ['json' => ['chat_id' => $chatId, 'text' => $text]]);
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            $this->logger->error('Telegram API error: ' . $e->getMessage());
            return false;
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('HTTP error: ' . $e->getMessage());
            return false;
        }
    }
}
