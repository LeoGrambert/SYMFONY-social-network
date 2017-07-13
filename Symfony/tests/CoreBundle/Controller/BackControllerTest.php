<?php

namespace CoreBundle\Tests\Controller;

use CoreBundle\Controller\BackController;
use CoreBundle\Entity\Observation;
use PHPUnit\Framework\TestCase;

class BackControllerTest extends TestCase
{
    public function testConfirmObservation($observationId)
    {
        $controller = new BackController();
        $observation = new Observation();
        $observationId = $observation->getId();
        $observation->setStatut('untreated');
        $actual = $controller->confirmObservation($observationId);
        $expected = 'accepted';
        $this->assertEquals($expected, $actual);

    }
}
