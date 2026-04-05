<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateOrderDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $number;

    #[Assert\NotBlank]
    #[Assert\Type('float')]
    #[Assert\Positive]
    public float $total;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $customerName;
}
