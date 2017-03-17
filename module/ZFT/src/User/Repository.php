<?php

namespace ZFT\User;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator;

class Repository {

    private $identityMap = [];

    /** @var  UserDataMapper */
    private $usersTable;

    public function __construct(UserDataMapper $usersTable) {
        $this->usersTable = $usersTable;
    }

    public function getUserById($id) {
        if(array_key_exists($id, $this->identityMap)) {
            return $this->identityMap[$id];
        }

        $userResultSet = $this->usersTable->getUserById($id);
        $user = $userResultSet->current();

        $this->identityMap[$id] = $user;

        return $user;
    }
}
