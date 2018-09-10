<?php

namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;

class ProfileController extends AbstractActionController {

    public function viewAction() {
        // view profile
    }

    public function editAction() {
        // edit form
    }

    public function submitAction() {
        // database insert/update

        // flashmessage

        /** @var FlashMessenger $flashMessenger */
        $flashMessenger = $this->flashMessenger();
        $success = true;
        if($success) {
            $destinationRoute = 'profile/view_profile';
            $flashMessenger->addMessage('Profile successfully saved', FlashMessenger::NAMESPACE_SUCCESS, 100);
            $flashMessenger->addMessage('This should be deleted', FlashMessenger::NAMESPACE_WARNING, 100);
            $flashMessenger->addMessage('I need to post tutorials more frequently', FlashMessenger::NAMESPACE_WARNING, 100);
            $flashMessenger->addMessage('Please use the donate button', FlashMessenger::NAMESPACE_WARNING, 100);
        } else {
            $destinationRoute = 'profile/edit_profile/form_profile';
            $flashMessenger->addErrorMessage('Invalid email');
            $flashMessenger->addErrorMessage('Invalid username');
        }

        return $this->redirect()->toRoute($destinationRoute);

    }
}
