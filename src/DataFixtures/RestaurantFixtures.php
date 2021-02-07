<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RestaurantFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for($i = 1 ; $i <= 10; $i++)
        {
            $restaurant = new Restaurant();
            $restaurant ->setRestauName("Restau Name n $i")
                        ->setRestauMail("Restau$i@gmail.com")
                        ->setRestauPhone("Restau phone n $i")
                        ->setLocalisation("Restau Location n $i")
                        ->setRestauPassword("Restau Password n $i")
                        ->setRestauDescription("Restau description n $i")
                        ->setRate($i);
                        $manager->persist($restaurant);
        }

        $manager->flush();
    }
}
