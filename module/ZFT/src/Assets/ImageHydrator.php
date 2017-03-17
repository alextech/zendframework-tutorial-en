<?php

namespace ZFT\Assets;

use Zend\Hydrator\Strategy\StrategyInterface;

class ImageHydrator implements StrategyInterface {
    /**
     * @inheritDoc
     */
    public function extract($value) {
        // TODO: Implement extract() method.
    }

    /**
     * @param mixed $value
     * @return Image
     * @throws \InvalidArgumentException
     */
    public function hydrate($value) : Image {
        if(! array_key_exists('path', $value)) {
            throw new \InvalidArgumentException('Image data should contain path key');
        }

        return new Image($value['path']);
    }

}