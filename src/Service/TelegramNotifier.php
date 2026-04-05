<?php

namespace App\Service;

use App\Entity\TelegramIntegration;
use App\Entity\TelegramSendLog;
use App\Entity\Order;
use App\Enum\TelegramStatus;
use Doctrine\ORM\EntityManagerInterface;

class TelegramNotifier
{
    public function __construct(
        private TelegramClientInterface $telegramClient,
        private EntityManagerInterface $em
    ) {}

    public final function send(TelegramIntegration $integration, Order $order, string $message): string
    {
        $existingLog = $this->em->getRepository(TelegramSendLog::class)->findOneBy([
            'shop' => $order->getShop(),
            'order' => $order
        ]);

        if ($existingLog) {
            return $existingLog->getStatus() === 'SENT' ? TelegramStatus::SENT->value : TelegramStatus::FAILED->value;
        }


        $log = new TelegramSendLog();
        $log->setShop($order->getShop());
        $log->setOrder($order);
        $log->setMessage($message);
        $log->setSentAt(new \DateTimeImmutable());
        try {
            $success = $this->telegramClient->sendMessage(
                $integration->getBotToken(),
                $integration->getChatId(),
                $message
            );
            $log->setStatus($success ? TelegramStatus::SENT->value : TelegramStatus::FAILED->value);

            if (!$success) {
                $log->setError('Telegram API returned non-200');
            }
        } catch (\Exception $e) {
            $log->setStatus(TelegramStatus::FAILED->value);
            $log->setError($e->getMessage());
        }

        $this->em->persist($log);
        $this->em->flush();

        return $log->getStatus() === TelegramStatus::SENT->value ? TelegramStatus::SENT->value : TelegramStatus::FAILED->value;
    }
}
