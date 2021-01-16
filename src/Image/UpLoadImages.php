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
    public function uploadDefault(Figure $figure)
    {                  
                $url = 'C:\wamp64\www\SnowTricks\public\images\illustration\default_figure.jpg';
                $file_name= md5(uniqid()) . '.jpg';
                $file = 'C:\wamp64\www\SnowTricks\public\images\main\\'. $file_name;
                file_put_contents($file, file_get_contents($url));
                $img = new Image();
                $img->setName($file_name);
                $img->setMain(true);
                $figure->addImage($img);
    }
}
  