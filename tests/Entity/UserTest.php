<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class UserTest extends TestCase
{
    public function testGettersSetters()
    {
        $entity = new User();

        $entity->setUsername('Test');
        $this->assertEquals('Test', $entity->getUsername());
    }
}
