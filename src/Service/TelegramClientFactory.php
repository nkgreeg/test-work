<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TelegramClientFactory
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger
    ) {}

    public function create(): TelegramClientInterface
    {
        $mock = filter_var($_ENV['TELEGRAM_MOCK'] ?? false, FILTER_VALIDATE_BOOLEAN);
        return ($mock) ? new MockTelegramClient($this->logger) : new TelegramClient($this->httpClient, $this->logger);
    }
}
