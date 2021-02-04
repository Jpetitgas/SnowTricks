<?php

namespace App\Image;

use App\Entity\Image;
use App\Repository\ImageRepository;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    public function changeMainImage($figure_id, $newMainImage)
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

    /**
     * @param mixed $figure
     *
     * @return [type]
     */
    public function mainImageNewFigure($figure)
    {
        $allImage = $this->imageRepository->findBy(['figure' => $figure]);
        $newMainImage = $allImage[0];
        $newMainImage->setmain(true);
        $this->em->flush();

        return true;
    }

    public function firstImageMain(Image $image, $allImage)
    {
        if ($image->getMain()) {
            if ($image == $allImage[0]) {
                $newMainImage = $allImage[1];
                $newMainImage->setmain(true);
            } else {
                $newMainImage = $allImage[0];
                $newMainImage->setmain(true);
            }
        }
        return $image->getName();
        
    }
}
