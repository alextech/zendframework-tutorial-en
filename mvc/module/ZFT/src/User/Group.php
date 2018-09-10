<?php

namespace ZFT\User;

class Group {

    private $id;

    /**
     * @var User[]
     */
    private $users;

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    public function addUser(User $user) {
        $this->users[] = $user;
    }


}
