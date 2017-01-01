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

        $userResultSet = $this->usersTable->select(['id' => $id]); // SELECT * FROM users WHERE id = $id
        $userResult = $userResultSet->current();


        $user = new User();
        $user->setId($id);
        $user->setFirstName($userResult->first_name);
        $user->setSurname($userResult->surname);
        $user->setEmail($userResult->email);

        $this->identityMap[$id] = $user;

        return $user;
    }
}
