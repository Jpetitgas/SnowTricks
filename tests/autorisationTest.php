<?php


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTest extends WebTestCase
{
    public function testIndex()
    {
        $user = static::createClient();
        $goToIndex = $user->request('GET', '/');
        $this->assertEquals(200, $user->getResponse()->getStatusCode());
    }

    public function testLogin()
    {
        $user = static::createClient();
        $goToLogin = $user->request('GET', '/connexion');
        $this->assertEquals(200, $user->getResponse()->getStatusCode());
    }
}
