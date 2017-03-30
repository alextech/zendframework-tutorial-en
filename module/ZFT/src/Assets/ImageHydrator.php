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
        $value = json_decode($value);
        if(is_array($value)) {
            $value = $value[0];
        }

        return new Image($value->path);
    }

}