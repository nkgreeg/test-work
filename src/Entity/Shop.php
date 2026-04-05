<?php

namespace App\Entity;

use App\Repository\ShopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShopRepository::class)]
class Shop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(targetEntity: TelegramIntegration::class, mappedBy: 'shop', cascade: ['persist', 'remove'])]
    private ?TelegramIntegration $telegramIntegration = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'shop')]
    private Collection $orders;

    /**
     * @var Collection<int, TelegramSendLog>
     */
    #[ORM\OneToMany(targetEntity: TelegramSendLog::class, mappedBy: 'shop')]
    private Collection $telegramSendLogs;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->telegramSendLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTelegramIntegration(): ?TelegramIntegration
    {
        return $this->telegramIntegration;
    }

    public function setTelegramIntegration(?TelegramIntegration $telegramIntegration): static
    {
        $this->telegramIntegration = $telegramIntegration;
        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setShop($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getShop() === $this) {
                $order->setShop(null);
            }
        }

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
            $telegramSendLog->setShop($this);
        }

        return $this;
    }

    public function removeTelegramSendLog(TelegramSendLog $telegramSendLog): static
    {
        if ($this->telegramSendLogs->removeElement($telegramSendLog)) {
            // set the owning side to null (unless already changed)
            if ($telegramSendLog->getShop() === $this) {
                $telegramSendLog->setShop(null);
            }
        }

        return $this;
    }
}
