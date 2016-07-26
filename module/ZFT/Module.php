<?php

namespace ZFT;

use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ServiceProviderInterface {
    public function getServiceConfig() {
        return [];
    }

}
