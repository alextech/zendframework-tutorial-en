<?php

namespace ZFT\User;

use Zend\Db\TableGateway\TableGateway;

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

        $user = new User();
        $user->setId($id);
        $user->setFirstName('Nina');

        $this->identityMap[$id] = $user;

//        $userResultSet = $this->usersTable->select(['id' => $id]);

        return $user;
    }
}
