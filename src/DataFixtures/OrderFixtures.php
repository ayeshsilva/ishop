<?php

namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\Order;
use App\Entity\User;
use Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class OrderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $user = $manager->getRepository(User::class)->find(2);

        $order = new Order();
        $order->setStatus(Order::STEP1);
        $order->setUser($user);
         $manager->persist($order);


        $manager->flush();
    }
}
