<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class TelegramConnectDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 45)]
    public string $botToken;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 64)]
    public string $chatId;

    #[Assert\Type('bool')]
    public bool $enabled = true;
}
