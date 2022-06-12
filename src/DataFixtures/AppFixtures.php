<?php

namespace App\DataFixtures;


use Faker;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();


        for($i=0; $i<=25; $i++) {
           
         $product = new Product();
         $product->setName('Product 2');
         $product->setDescription('lorem ipsum');
         $product->setPrice(1000);
         $product->setStock(10);
         $manager->persist($product);

        }
        $manager->flush();
    }
}
