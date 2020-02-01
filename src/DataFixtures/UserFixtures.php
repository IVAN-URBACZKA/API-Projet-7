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
        $users = [];

        $name = ['jean','bryan','vladimir'];

        $firstname = ['martin','urbaczka','irholci'];

        for($i = 1; $i <= 10; $i++) {

            $user = new User();

            $user->setName($name[mt_rand(0,2)])
                 ->setFirstname($firstname[mt_rand(0,2)])
                 ->setEmail("jean@gmail.com")
                 ->setCity("paris")
                 ->setAdress("3 rue de la la la lere");
            $manager->persist($user);
            array_push($users,$user);
            
    }

    $manager->flush();

    foreach($users as $user) {

        

        $user->setLinks(array(array(
            'self' => '/api/user/'.$user->getId().'',
            'delete' => '/api/user/'.$user->getId().''
        )));
        
        
    }
    

    $manager->flush();

}

}
