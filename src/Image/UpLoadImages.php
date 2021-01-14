<?php

namespace App\Image;

use App\Entity\Figure;
use App\Entity\Image;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpLoadImages extends AbstractController
{
    public function Upload($images, Figure $figure)
    {
        foreach ($images as $image) {
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            $img = new Image();
            $img->setName($fichier);
            $img->setMain(false);
            $figure->addImage($img);
        }
    }
}
