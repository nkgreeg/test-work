<?php

namespace App\Controller;

use App\DTO\TelegramConnectDTO;
use App\Entity\Shop;
use App\Entity\TelegramIntegration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TelegramConnectController
{
    #[Route('/shops/{shopId}/telegram/connect', methods: ['POST'])]
    public function __invoke(
        #[MapEntity(id: 'shopId')] Shop $shop,
        Request $request,
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        $dto = $serializer->deserialize($request->getContent(), TelegramConnectDTO::class, 'json');

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string) $errors], 400);
        }

        $integration = $em->getRepository(TelegramIntegration::class)->findOneBy(['shop' => $shop]);
        if (!$integration) {
            $integration = new TelegramIntegration();
            $integration->setShop($shop);
            $integration->setCreatedAt(new \DateTimeImmutable());
        }

        $integration->setBotToken($dto->botToken);
        $integration->setChatId($dto->chatId);
        $integration->setEnabled($dto->enabled);
        $integration->setUpdatedAt(new \DateTimeImmutable());

        $em->persist($integration);
        $em->flush();

        return new JsonResponse([
            'id' => $integration->getId(),
            'shopId' => $integration->getShop()->getId(),
            'botToken' => $integration->getBotToken(),
            'chatId' => $integration->getChatId(),
            'enabled' => $integration->isEnabled(),
            'createdAt' => $integration->getCreatedAt()->format('c'),
            'updatedAt' => $integration->getUpdatedAt()->format('c'),
        ]);
    }
}
