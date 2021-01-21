<?php

namespace App\Image;


use App\Entity\Figure;
use App\Repository\ImageRepository;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;


class MainImage
{
    protected $imageRepository;
    protected $figureRepository;
    
    protected $em;

    public function __construct( ImageRepository $imageRepository, EntityManagerInterface $em, FigureRepository $figureRepository)
    {
        
        $this->imageRepository = $imageRepository;
        $this->figureRepository = $figureRepository;
        $this->em = $em;
    }

    public function ChangeMainImage($figure_id, $newMainImage)
    {
        $figure = $this->figureRepository->findOneBy(['id' => $figure_id]);
        $images = $this->imageRepository->findBy(['figure' => $figure]);

        if ($newMainImage) {
            foreach ($images as $image) {
                $image->setMain($image->getId() == $newMainImage);
            }
            $this->em->flush();
        }
        return true;
    }
    
   
    public function mainImageNewFigure($figure)
    {
        $allImage = $this->imageRepository->findBy(['figure' => $figure]);
        $newMainImage = $allImage[0];
        $newMainImage->setmain(TRUE);
        $this->em->flush();
        return true;
    }
}
