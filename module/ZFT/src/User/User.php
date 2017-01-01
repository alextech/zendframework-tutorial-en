<?php

namespace ZFT\User;

class User {

    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var Group[]
     */
    private $groups;

    /** @var  string */
    private $email;

    private $firstName;

    private $surname;

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

    /**
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param Group $groups
     */
    public function addToGroup(Group $group) {
        $this->groups[] = $group;
        $group->addUser($this);
    }

    /**
     * @return Group[]
     */
    public function getGroups(): array {
        return $this->groups;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email) {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getSurname() {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname) {
        $this->surname = $surname;
    }
}
