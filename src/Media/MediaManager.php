<?php

namespace App\Media;

use App\Video\AddVideo;
use App\Image\MainImage;
use App\Image\UpLoadImages;

class MediaManager
{
    protected $upLoadImages;
    protected $addVideo;
    

    public function __construct(UpLoadImages $upLoadImages, AddVideo $addVideo)
    {
        $this->addVideo = $addVideo;
        $this->upLoadImages = $upLoadImages;
        
    }

    public function Manager($images, $video, $figure, $route)
    {
        if (!$images) {
            if ($route == 'create') {
                $this->upLoadImages->upLoadDefault($figure);
            }
        } else {
            $this->upLoadImages->upLoad($images, $figure);
        }
       
        if ($video) {
            $this->addVideo->addUrl($video, $figure);
        }
    }
}
