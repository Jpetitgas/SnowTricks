<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Portrait;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
        for ($i = 0; $i < 4; $i++) {
            $img = new Portrait();
            $fichier = 'persona_' . $i . '.jpg';
            $img->setName($fichier);
            $manager->persist($img);

            $admin = new User;
            $hash = $this->encoder->encodePassword($admin, "admin2021");
            $admin->setemail('admin' . $i . '@gmail.com')
                ->setPassword($hash)
                ->SetUsername('admin202' . $i)
                ->Setroles(['ROLE_ADMIN'])
                ->setPortrait($img)
                ->setDate(new datetime);
            $manager->persist($admin);
        }
        $manager->flush();
        $user = $this->userRepository->findAll();
    }
}
