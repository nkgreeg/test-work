<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Entity\TelegramSendLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TelegramStatusController
{
    #[Route('/shops/{shopId}/telegram/status', methods: ['GET'])]
    public function __invoke(
        #[MapEntity(id: 'shopId')] Shop $shop,
        EntityManagerInterface $em
    ): JsonResponse {
        $integration = $shop->getTelegramIntegration();

        if (!$integration) {
            return new JsonResponse([
                'enabled' => false,
                'chatId' => null,
                'lastSentAt' => null,
                'sentCount' => 0,
                'failedCount' => 0
            ]);
        }

        // Маскируем chatId (показываем последние 3 символа)
        $chatId = $integration->getChatId();
        $maskedChatId = strlen($chatId) > 3
            ? '***' . substr($chatId, -3)
            : '***';

        // Последняя отправка
        $lastLog = $em->getRepository(TelegramSendLog::class)
            ->findOneBy(
                ['shop' => $shop, 'status' => 'SENT'],
                ['sentAt' => 'DESC']
            );
        $lastSentAt = $lastLog ? $lastLog->getSentAt()->format('c') : null;

        // Статистика за 7 дней
        $sevenDaysAgo = new \DateTimeImmutable('-7 days');

        $sentCount = $em->getRepository(TelegramSendLog::class)
            ->count([
                'shop' => $shop,
                'status' => 'SENT',
                'sentAt' => ['$gte' => $sevenDaysAgo]
            ]);

        $failedCount = $em->getRepository(TelegramSendLog::class)
            ->count([
                'shop' => $shop,
                'status' => 'FAILED',
                'sentAt' => ['$gte' => $sevenDaysAgo]
            ]);

        return new JsonResponse([
            'enabled' => $integration->isEnabled(),
            'chatId' => $maskedChatId,
            'lastSentAt' => $lastSentAt,
            'sentCount' => $sentCount,
            'failedCount' => $failedCount
        ]);
    }
}
