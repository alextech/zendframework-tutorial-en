<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZFT\User;

class UsersController extends AbstractActionController
{

    /** @var  User\Repository */
    private $userRepository;

    public function __construct(User\Repository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function indexAction() {

    }

    public function activityAction() {

    }

    public function timelineAction() {

    }

    public function settingsAction() {

    }

    public function groupsAction() {

    }
}
