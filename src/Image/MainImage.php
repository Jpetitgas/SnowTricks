<?php

namespace App\Image;

use App\Repository\ImageRepository;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;


class MainImage
{
    protected $imageRepository;
    protected $figureRepository;
    protected $em;

    public function __construct(ImageRepository $imageRepository, EntityManagerInterface $em, FigureRepository $figureRepository)
    {
        $this->imageRepository = $imageRepository;
        $this->figureRepository = $figureRepository;
        $this->em = $em;
    }

    public function ChangeMainImage($figure_id, $id)
    {
        $figure = $this->figureRepository->findOneBy(['id' => $figure_id]);
        $images = $this->imageRepository->findBy(['figure' => $figure]);
        $cpt = 0;
        foreach ($images as $image) {
            if ($id) {
                if ($image->getId() == $id) {
                    $image->setMain(TRUE);
                } else $image->setMain(FALSE);
            } else {
                if ($cpt == 0) {
                    $image->setMain(TRUE);
                } else  $image->setMain(FALSE);
            }
            $cpt++;
        }
        $this->em->flush();
        return true;
    }
}
