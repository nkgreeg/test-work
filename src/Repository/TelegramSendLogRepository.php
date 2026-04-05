<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\TelegramSendLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TelegramSendLog>
 */
class TelegramSendLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramSendLog::class);
    }

    public final function hasLog(Order $order): bool
    {
        return $this->findOneBy([
            'shop' => $order->getShop(),
            'order' => $order
        ]) !== null;
    }
}
