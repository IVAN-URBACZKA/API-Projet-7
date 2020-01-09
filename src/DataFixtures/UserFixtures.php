<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class UserFixtures extends Fixture
{

 

    public function load(ObjectManager $manager)
    {


        for($i = 1; $i <= 20; $i++) {

            $user = new User();

           

            $user->setName("jean")
                 ->setFirstname("robert")
                 ->setEmail("jean@gmail.com")
                 ->setCity("paris")
                 ->setAdress("3 rue de la la la lere");
            $manager->persist($user);
            $manager->flush();
    }
}

}
