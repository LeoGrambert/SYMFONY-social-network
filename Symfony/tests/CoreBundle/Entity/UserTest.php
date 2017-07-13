<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGravatarPicture()
    {
        $user = new User();
        $user->setEmail('test@gmail.com');
        $this->assertNotNull($user->getGravatarPicture());
    }

    public function testNoAccreditDefault()
    {
        $user = new User();
        $this->assertFalse($user->getIsAccredit());
    }
}
