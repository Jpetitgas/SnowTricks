<?php

namespace App\Media;


use App\Entity\Media;
use App\Entity\Figure;
use App\Repository\MediaRepository;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;

class AddMedia
{
    protected $mediaRepository;
    protected $figureRepository;
    protected $em;

    /**
     * @param MediaRepository $mediaRepository
     * @param EntityManagerInterface $em
     * @param FigureRepository $figureRepository
     */
    public function __construct(MediaRepository $mediaRepository, EntityManagerInterface $em, FigureRepository $figureRepository)
    {

        $this->mediaRepository = $mediaRepository;
        $this->figureRepository = $figureRepository;
        $this->em = $em;
    }

    /**
     * @param mixed $url
     * @param Figure $figure
     * 
     * @return [type]
     */
    public function addUrl($url,Figure $figure)
    {
        $url= substr($url, strlen('https://youtu.be/'));
        
        $media = new Media();
        $media->setLien($url);
        $figure->addMedium($media);

    }
}
