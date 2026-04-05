<?php

namespace App\Tests\Service;

use App\Entity\Order;
use App\Entity\Shop;
use App\Entity\TelegramIntegration;
use App\Entity\TelegramSendLog;
use App\Service\TelegramClientInterface;
use App\Service\TelegramNotifier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class TelegramNotifierTest extends TestCase
{
    public function testSendSuccess(): void
    {
        $client = $this->createStub(TelegramClientInterface::class);
        $client->method('sendMessage')->willReturn(true);

        $repository = $this->createStub(EntityRepository::class);

        $em = $this->createStub(EntityManagerInterface::class);
        $em->method('getRepository')->willReturn($repository);

        $notifier = new TelegramNotifier($client, $em);

        $shop = new Shop();
        $integration = new TelegramIntegration();
        $integration->setBotToken('token');
        $integration->setChatId('123');

        $order = new Order();
        $order->setShop($shop);

        $status = $notifier->send($integration, $order, 'test');
        $this->assertEquals('sent', $status);
    }

    public final function testIdempotency(): void
    {
        $client = $this->createMock(TelegramClientInterface::class);
        $client->expects($this->never())->method('sendMessage');

        $existingLog = $this->createStub(TelegramSendLog::class);
        $existingLog->method('getStatus')->willReturn('SENT');

        $repository = $this->createStub(EntityRepository::class);
        $repository->method('findOneBy')->willReturn($existingLog);

        $em = $this->createStub(EntityManagerInterface::class);
        $em->method('getRepository')->willReturn($repository);

        $notifier = new TelegramNotifier($client, $em);

        $shop = new Shop();
        $integration = new TelegramIntegration();
        $integration->setBotToken('token');
        $integration->setChatId('123');

        $order = new Order();
        $order->setShop($shop);

        $status = $notifier->send($integration, $order, 'test');
        $this->assertEquals('sent', $status);
    }

    public function testSendFailure(): void
    {
        $client = $this->createStub(TelegramClientInterface::class);
        $client->method('sendMessage')->willThrowException(new \Exception('API error'));

        $repository = $this->createStub(EntityRepository::class);
        $repository->method('findOneBy')->willReturn(null);

        $em = $this->createStub(EntityManagerInterface::class);
        $em->method('getRepository')->willReturn($repository);

        $notifier = new TelegramNotifier($client, $em);

        $shop = new Shop();
        $integration = new TelegramIntegration();
        $integration->setBotToken('token');
        $integration->setChatId('123');

        $order = new Order();
        $order->setShop($shop);

        $status = $notifier->send($integration, $order, 'test');
        $this->assertEquals('failed', $status);
    }
}
