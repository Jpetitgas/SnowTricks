<?php

namespace App\Tests\Entity;

use App\Entity\Figure;
use PHPUnit\Framework\TestCase;

class FigureTest extends TestCase
{
    
    public function testGetterSetter(): void
    {
        $figure = new Figure();

        $this->assertInstanceOf(Figure::class, $figure);
        $this->assertEquals(null, $figure->getName());
    
        $figure->setName('400');
        $this->assertEquals('400', $figure->getName());

        
    }
    
}