<?php

namespace App\Video;

use App\Entity\Figure;
use App\Entity\Media;
use App\Repository\FigureRepository;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;

class AddVideo
{
    protected $mediaRepository;
    protected $figureRepository;
    protected $em;

    public function __construct(MediaRepository $mediaRepository, EntityManagerInterface $em, FigureRepository $figureRepository)
    {
        $this->mediaRepository = $mediaRepository;
        $this->figureRepository = $figureRepository;
        $this->em = $em;
    }

    /**
     * @param mixed $url
     *
     * @return [type]
     */
    public function addUrl($url, Figure $figure)
    {
        $url = substr($url, strlen('https://youtu.be/'));

        $media = new Media();
        $media->setLien($url);
        $figure->addMedium($media);
    }
}
