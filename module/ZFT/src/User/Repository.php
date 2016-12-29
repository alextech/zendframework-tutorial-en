<?php

namespace ZFT\User;

class Repository {
    public function __construct(IdentityMapInterface $identityMap, DataMapperInterface $dataMapper) {

    }

    public function getUserById($id) {
        return new User();
    }
}
