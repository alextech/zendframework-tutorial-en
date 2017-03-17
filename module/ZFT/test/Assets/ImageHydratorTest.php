<?php

namespace ZFTest\Assets;

use ZFT\Assets\ImageHydrator;

class ImageHydratorTest extends \PHPUnit_Framework_TestCase {
    public function testCanOutputAsString() {
        $imageHydrator = new ImageHydrator();

        $data = ['id' => 1, 'path' => 'test/path'];
        $image = $imageHydrator->hydrate($data);
        $this->assertEquals('test/path', (string)$image);
    }
}
