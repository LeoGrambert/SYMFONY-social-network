<?php

namespace CoreBundle\Tests\Entity;

use CoreBundle\Entity\Picture;
use PHPUnit\Framework\TestCase;

class PictureTest extends TestCase
{
    public function testUploadRootDir()
    {
        $picture = new Picture();
        $actual = $picture->getUploadDir();
        $expected = "uploads/img";
        $this->assertEquals($expected, $actual);
    }
}
