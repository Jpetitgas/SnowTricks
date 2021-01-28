<?php

namespace App\Tests\Entity;

use App\Entity\Figure;
use PHPUnit\Framework\TestCase;


class ArticleTest extends TestCase
{
    public function testName()
    {
        $figure = new Figure();
        $name = "nom de figure";

        $figure->setName($name);
        $this->assertEquals("nom de figure", $figure->getName());
    }

    public function testDescription()
    {
        $figure = new Figure();
        $description = "description de figure";

        $figure->setDescription($description);
        $this->assertEquals("description de figure", $figure->getDescription());
    }

    public function testDate()
    {
        $figure = new Figure();
        $date = new \DateTime();
        $createdAt = $date;

        $figure->setDate($createdAt);
        $this->assertEquals($date, $figure->getDate());
    }

    public function testDateMod()
    {
        $figure = new Figure();
        $date = new \DateTime();
        $updatedAt = $date;

        $figure->setDateMod($updatedAt);
        $this->assertEquals($date, $figure->getDateMod());
    }
}
