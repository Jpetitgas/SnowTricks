<?php

namespace App\Image;

use App\Entity\User;
use App\Entity\Image;
use App\Entity\Portrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class upLoadPortrait extends AbstractController
{
    public function upLoad($portrait, User $user)
    {

        $fichier = md5(uniqid()) . '.' . $portrait->guessExtension();
        $portrait->move(
            $this->getParameter('images_directory'),
            $fichier
        );
        $img = new Portrait();
        $img->setName($fichier);

        $user->setPortrait($img);
    }
    public function upLoadDefault(User $user)
    {
        $url = $this->getParameter('images_default') . '/' . 'default_user.png';
        $file_name = md5(uniqid()) . '.jpg';
        $file = $this->getParameter('images_directory') . '/' . $file_name;
        copy($url, $file);
        $img = new Portrait();
        $img->setName($file_name);

        $user->setPortrait($img);
    }
}
