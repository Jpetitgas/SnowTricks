<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FigureControllerTest extends WebTestCase
{
        public function testCreateFigure()
        {
                $client = static::createClient();

                $client->request('GET', '/figure/create');

                // Make sure we are redirected to the login page
                $this->assertEquals(302, $client->getResponse()->getStatusCode());
                $this->assertTrue($client->getResponse()->isRedirect('/connexion'));
               
        }
        
}
