<?php

namespace ZFTest\Assets;

use ZFT\Assets\ImageHydrator;

class ImageHydratorTest extends \PHPUnit_Framework_TestCase {
    public function testImageHydratorCreateImage() {
        $imageHydrator = new ImageHydrator();

        $data = ['id' => 1, 'path' => 'test/path'];
        $image = $imageHydrator->hydrate($data);
        $this->assertEquals('test/path', (string)$image);
    }

    public function testImageHydratorThrowsExceptionInvalidData() {
        $imageHydrator = new ImageHydrator();

        $data = ['id' => 1];
        $this->expectException(\InvalidArgumentException::class);
        $image = $imageHydrator->hydrate($data);
    }
}
