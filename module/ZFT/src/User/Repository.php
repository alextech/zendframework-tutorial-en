<?php

namespace ZFT\User;

use ZFT\User;

class Repository {
    function __construct(IdentityMapInterface $identityMap, DataMapperInterface $dataMapper) {

    }

    public function getUserById($id) {
        return new User();
    }
}
