<?php

namespace App\DataFixtures;


use App\Entity\Category;
use Faker;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();


        for($i=10; $i<=49; $i++) {
           
         $product = new Product();
         $product->setName('Product '.$i);
         $product->setDescription($faker->text(30));
         $product->setPrice(rand(10,100));
         $product->setStock(rand(5, 30));
         $product->setCategory($manager->getRepository(Category::class)->find((rand(3,4))));
         $manager->persist($product);

        }
        $manager->flush();
    }
}
