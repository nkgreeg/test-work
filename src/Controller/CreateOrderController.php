<?php

namespace App\Controller;

use App\DTO\CreateOrderDTO;
use App\Entity\Shop;
use App\Entity\Order;
use App\Entity\TelegramSendLog;
use App\Enum\TelegramStatus;
use App\Service\TelegramNotifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateOrderController
{
    #[Route('/shops/{shopId}/orders', methods: ['POST'])]
    public function __invoke(
        #[MapEntity(id: 'shopId')] Shop $shop,
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        TelegramNotifier $telegramNotifier
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $dto = new CreateOrderDTO();
        $dto->number = $data['number'] ?? '';
        $dto->total = $data['total'] ?? 0;
        $dto->customerName = $data['customerName'] ?? '';

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string) $errors], 400);
        }

        $order = new Order();
        $order->setShop($shop);
        $order->setNumber($dto->number);
        $order->setTotal($dto->total);
        $order->setCustomerName($dto->customerName);
        $order->setCreatedAt(new \DateTimeImmutable());

        $em->persist($order);
        $em->flush();

        $telegramStatus = TelegramStatus::SKIPPED->value;
        $integration = $shop->getTelegramIntegration();
        if ($integration && $integration->isEnabled()) {
            $message = sprintf(
                'Новый заказ %s на сумму %.2f ₽, клиент %s',
                $order->getNumber(),
                $order->getTotal(),
                $order->getCustomerName()
            );
            $telegramStatus = $telegramNotifier->send($integration, $order, $message);
        }

        return new JsonResponse([
            'id' => $order->getId(),
            'shopId' => $order->getShop()->getId(),
            'number' => $order->getNumber(),
            'total' => $order->getTotal(),
            'customerName' => $order->getCustomerName(),
            'createdAt' => $order->getCreatedAt()->format('c'),
            'telegramStatus' => $telegramStatus
        ]);
    }
}
