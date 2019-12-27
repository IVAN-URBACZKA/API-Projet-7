<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{

    private $name = ['Dupont', 'Dubois','Delapierre'];
    private $firstname = ['Jean', 'Pierre','marie'];


    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 20; $i++) {

            $user = new User();
            $user->setName($this->name[rand(0,2)]);
            $user->setFirstname($this->firstname[rand(0,2)]);
            $user->setEmail('rondoudou@gmail.com');

            $manager->persist($user);

        $manager->flush();
    }
}

}
