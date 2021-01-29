<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Media;
use App\Entity\Figure;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\Portrait;
use App\Entity\Description;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    protected $encoder;
    protected $slugger;
    protected $userRepository;

    public function __construct(UserPasswordEncoderInterface $encoder, SluggerInterface $slugger, UserRepository $userRepository )
    {
        $this->userRepository= $userRepository;
        $this->encoder = $encoder;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $videos = array('0uGETVnkujA', '6cisNRS35gA', 'qsd8uaex-Is', 'h70kgLV2_Vg', '5K3VXw9ywp8');
        $style = array('grabs', 'rotations', 'flips', 'rotations désaxées', 'slides', 'one foot tricks', 'Old school');

        for ($n = 0; $n < 4; $n++) {
            $img = new Portrait();
            $fichier = 'persona_'. $n.'.jpg';
            $img->setName($fichier);
            $manager->persist($img);

            $admin = new User;
            $hash = $this->encoder->encodePassword($admin, 'user202' . $n);
            $admin->setemail('user'.$n.'@gmail.com')
                ->setPassword($hash)
                ->SetUsername('user202'.$n)
                ->Setroles(['ROLE_USER'])
                ->setPortrait($img)
                ->setDate(new datetime);
            $manager->persist($admin);
        }
        $manager->flush();
        $user=$this->userRepository->findAll();
        
        foreach ($style as $key => $value) {
            $category = new Category;
            $category->setcategory($value);
            $manager->persist($category);
            for ($p = 0; $p < 7; $p++) {
                $figure = new Figure();

                $figure->setName($faker->words(2, true))
                    ->setWriter($user[rand(0, 3)])
                    ->setDescription($faker->paragraphs(5, true))
                    ->setType($category)
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
                for ($n = 0; $n < 4; $n++) {
                    $media = new Media();
                    $lien = $videos[$n];
                    $media->setLien($lien);
                    $media->setFigure($figure);
                    $manager->persist($media);
                }
                for ($n = 0; $n < 15; $n++) {
                    $comment = new Comment();
                    $comment->setFigure($figure)
                        ->setWriter($user[rand(0, 3)])
                        ->setContent($faker->paragraphs(2, true))
                        ->setDate(new DateTime());
                    $manager->persist($comment);
                }
            }
        }


        $manager->flush();
    }
}
