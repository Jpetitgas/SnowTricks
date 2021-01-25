<?php

namespace App\Image;

use App\Entity\Figure;
use App\Entity\Image;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class upLoadImages extends AbstractController
{
    public function upLoad($images, Figure $figure)
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
    public function upLoadDefault(Figure $figure)
    {
        $url = $this->getParameter('images_default') . '/' . 'default_figure.jpg';
        $file_name = md5(uniqid()) . '.jpg';
        $file = $this->getParameter('images_directory') . '/' . $file_name;
        copy($url, $file);
        $img = new Image();
        $img->setName($file_name);
        $img->setMain(true);
        $figure->addImage($img);
    }
}
