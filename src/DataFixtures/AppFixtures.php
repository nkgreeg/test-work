<?php

namespace App\DataFixtures;

use App\Entity\Shop;
use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private const SHOPS_MIN = 2;
    private const SHOPS_MAX = 5;
    private const ORDERS_MIN = 5;
    private const ORDERS_MAX = 10;

    private static Generator $faker;

    public function __construct()
    {
        self::$faker = Factory::create('ru_RU');
    }

    public function load(ObjectManager $manager): void
    {
        $shopCount = rand(self::SHOPS_MIN, self::SHOPS_MAX);
        for ($s = 1; $s <= $shopCount; $s++)
            $this->createShop($manager);
        $manager->flush();
    }

    private function createShop(ObjectManager $manager): void
    {
        $shop = new Shop();
        $shop->setName(self::$faker->company);
        $manager->persist($shop);

        $orderCount = rand(self::ORDERS_MIN, self::ORDERS_MAX);
        for ($i = 1; $i <= $orderCount; $i++) {
            $this->createOrder($manager, $shop);
        }
    }

    private function createOrder(ObjectManager $manager, Shop $shop): void
    {
        $order = new Order();
        $order->setShop($shop);
        $order->setNumber(self::$faker->unique()->numerify('ORDER-#####'));
        $order->setTotal($this->randomDecimal(100, 10000));
        $order->setCustomerName(self::$faker->name);
        $order->setCreatedAt(\DateTimeImmutable::createFromMutable(self::$faker->dateTimeBetween('-30 days', 'now')));
        $manager->persist($order);
    }

    private function randomDecimal(int $min, int $max, int $decimals = 2): float
    {
        $factor = 10 ** $decimals;
        return rand($min * $factor, $max * $factor) / $factor;
    }
}
