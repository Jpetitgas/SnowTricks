<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class AmountExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('media', [$this, 'media'])
        ];
    }
    public function media($media)
    {
        $finaleValue = $media;
        
        return $finaleValue ;
    }
}
