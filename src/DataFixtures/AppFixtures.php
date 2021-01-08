<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use DateTime;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Figure;
use App\Entity\Description;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $encoder;
    protected $slugger;

    public function __construct(UserPasswordEncoderInterface $encoder, SluggerInterface $slugger)
    {
        $this->encoder = $encoder;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');


        $admin = new User;
        $hash = $this->encoder->encodePassword($admin, "admin2021");
        $admin->setemail('admin@gmail.com')
            ->setPassword($hash)
            ->SetUsername('admin2021')
            ->Setroles(['ROLE_ADMIN'])
            ->setDate(new datetime);
        $manager->persist($admin);
        $style = array('grabs', 'rotations', 'flips', 'rotations désaxées', 'slides', 'one foot tricks', 'Old school');
        foreach ($style as $key => $value) {
            $description = new Description;
            $description->setDescription($value);
            $manager->persist($description);
            for ($p = 0; $p < 3; $p++) {
                $figure = new Figure();
                $figure->setName($faker->words(3, true))
                    ->setWriter($admin)
                    ->setDescription($faker->paragraphs(5, true))
                    ->setType($description)
                    ->setMainpicture('main_' . $p)
                    ->setSlug(strtolower($this->slugger->slug($figure->getName())))
                    ->setDate(new DateTime());
                $manager->persist($figure);
                for ($n = 0; $n < 5; $n++) {
                    $comment = new Comment();
                    $comment->setFigure($figure)
                        ->setWriter($admin)
                        ->setContent($faker->paragraphs(2, true))
                        ->setDate(new DateTime());
                    $manager->persist($comment);
                }
            }
        }


        $manager->flush();
    }
}
