<?php

namespace ZFTest\Assets;

use ZFT\Assets\Image;

class ImageTest extends \PHPUnit_Framework_TestCase {
    public function testCanOutputAsString() {
        $image = new Image('unitTest.jpg');
        $this->assertEquals('unitTest.jpg', (string)$image);
    }
}
