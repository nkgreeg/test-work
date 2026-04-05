<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TelegramPageController extends AbstractController
{
    #[Route('/shops/{shopId}/growth/telegram', name: 'telegram_page', methods: ['GET'])]
    public function __invoke(int $shopId): Response
    {
        return $this->render('telegram.html.twig', ['shopId' => $shopId]);
    }
}
