<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Shop $shop = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total = null;

    #[ORM\Column(length: 255)]
    private ?string $customerName = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, TelegramSendLog>
     */
    #[ORM\OneToMany(targetEntity: TelegramSendLog::class, mappedBy: 'order')]
    private Collection $telegramSendLogs;

    public function __construct()
    {
        $this->telegramSendLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): static
    {
        $this->shop = $shop;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): static
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, TelegramSendLog>
     */
    public function getTelegramSendLogs(): Collection
    {
        return $this->telegramSendLogs;
    }

    public function addTelegramSendLog(TelegramSendLog $telegramSendLog): static
    {
        if (!$this->telegramSendLogs->contains($telegramSendLog)) {
            $this->telegramSendLogs->add($telegramSendLog);
            $telegramSendLog->setOrder($this);
        }

        return $this;
    }

    public function removeTelegramSendLog(TelegramSendLog $telegramSendLog): static
    {
        if ($this->telegramSendLogs->removeElement($telegramSendLog)) {
            // set the owning side to null (unless already changed)
            if ($telegramSendLog->getOrder() === $this) {
                $telegramSendLog->setOrder(null);
            }
        }

        return $this;
    }
}
