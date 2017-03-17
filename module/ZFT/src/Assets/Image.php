<?php

namespace ZFT\Assets;

class Image {

    /** @var  string */
    private $path;

    public function __construct($path) {
        $this->path = $path;
    }

    public function __toString() : string {
        return $this->path;
    }


}
