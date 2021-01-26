<?php
namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{

    public function testCount()
        
    {
        $kernel=self::bootKernel();
        $users=self::$container->get(UserRepository::class)->count([]);
        $this->assertEquals(4, $users);
    }
}
