<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FigureControllerTest extends WebTestCase
{
        public function testAuthorisationToCreateFigure()
        {
                $client = static::createClient();

                $client->request('GET', '/figure/create');

                // Make sure we are redirected to the login page
                $this->assertEquals(302, $client->getResponse()->getStatusCode());
                $this->assertTrue($client->getResponse()->isRedirect('/connexion'));
                $client->followRedirect();
                
                // submit login form
                $form = $client->getCrawler()->selectButton('submit')->form();
                                
                $client->submit($form, array(
                        'username' => 'user2021',
                        'password' => 'user2021',
                ));

                // Login is successful
                $this->assertEquals(302, $client->getResponse()->getStatusCode());
                $this->assertTrue($client->getResponse()->isRedirect("http://localhost/figure/create"));
                $client->followRedirect();

                // on the create figure page
                $this->assertEquals(200, $client->getResponse()->getStatusCode());

                return $client;
               
        }
        
}
