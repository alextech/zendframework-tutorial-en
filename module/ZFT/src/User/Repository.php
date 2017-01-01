<?php

namespace ZFT\User;

use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator;

class Repository {

    private $identityMap = [];

    /** @var  TableGateway */
    private $usersTable;

    public function __construct(TableGateway $usersTable) {
        $this->usersTable = $usersTable;
    }

    public function getUserById($id) {
        if(array_key_exists($id, $this->identityMap)) {
            return $this->identityMap[$id];
        }

        $userResultSet = $this->usersTable->select(['id' => $id]); // SELECT * FROM users WHERE id = $id
        $user = $userResultSet->current();

        $this->identityMap[$id] = $user;

        return $user;
    }
}
