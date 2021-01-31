<?php

namespace App\Tests\Entity;

use App\Entity\Figure;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewFigureTest extends KernelTestCase
{
    
    public function testUniqueName()
    {
        $figure = new Figure();
        $figure->setName('360');
        self::bootKernel();
        $error= self::$container->get('validator')->validate($figure);
        $this->assertCount(1,$error);
    }
}