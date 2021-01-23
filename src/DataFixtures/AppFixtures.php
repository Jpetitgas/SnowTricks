<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Figure;
use App\Entity\Comment;
use App\Entity\Description;
use App\Entity\Media;
use App\Entity\Portrait;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

        $img = new Portrait();
        $fichier = "persona.jpg";
        $img->setName($fichier);
        $manager->persist($img);

        $admin = new User;
        $hash = $this->encoder->encodePassword($admin, "admin2021");
        $admin->setemail('admin@gmail.com')
            ->setPassword($hash)
            ->SetUsername('admin2021')
            ->Setroles(['ROLE_ADMIN'])
            ->setPortrait($img)
            ->setDate(new datetime);
        $manager->persist($admin);


        $style = array('grabs', 'rotations', 'flips', 'rotations désaxées', 'slides', 'one foot tricks', 'Old school');
        foreach ($style as $key => $value) {
            $description = new Description;
            $description->setDescription($value);
            $manager->persist($description);
            for ($p = 0; $p < 7; $p++) {
                $figure = new Figure();

                $figure->setName($faker->words(2, true))
                    ->setWriter($admin)
                    ->setDescription($faker->paragraphs(5, true))
                    ->setType($description)
                    ->setSlug(strtolower($this->slugger->slug($figure->getName())))
                    ->setDate($faker->dateTimeBetween('-6 months'));
                $manager->persist($figure);
                $nb = rand(1, 2);
                $b = rand(0, $nb);
                for ($m = 0; $m <=  $nb; $m++) {
                    $img = new Image();
                    $fichier = "main_" . $m . ".jpg";
                    $m == $b ? $img->setMain(TRUE) : $img->setMain(FALSE);
                    $img->setName($fichier);
                    $img->setFigure($figure);
                    $manager->persist($img);
                }
                for ($n=0; $n<4; $n++){
                    $media=new Media();
                    $lien= '5K3VXw9ywp8';
                    $media->setLien($lien);
                    $media->setFigure($figure);
                    $manager->persist($media);
                }

 

                for ($n = 0; $n < 15; $n++) {
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
