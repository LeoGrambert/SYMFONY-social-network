<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\Observation;
use CoreBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class ObservationTest extends TestCase
{
    public function testBirdExist()
    {
        $observation = new Observation();
        $this->assertNull($observation->getBird());
    }

    public function testUserExist()
    {
        $observation = new Observation();
        $this->assertNull($observation->getUser());
    }

}
