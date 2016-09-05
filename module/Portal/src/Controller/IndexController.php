<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Portal\Controller;

use Zend\Authentication\Adapter\Ldap;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZFT\User;

class IndexController extends AbstractActionController
{

    /** @var  User\Repository */
    private $userRepository;

    /** @var  AuthenticationService */
    private $authService;

    public function __construct(User\Repository $userRepository, AuthenticationService $authService) {
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    public function indexAction()
    {
        /** @var Ldap $adapter */
        $adapter = $this->authService->getAdapter();
        $adapter->setIdentity('zftutorial'); // username
        $adapter->setCredential('ZFT2016!'); // password
        $result = $this->authService->authenticate();
        $isSuccessful = $result->isValid();

//        $user = new User();

        $user = $this->userRepository->getUserById(5);

        return new ViewModel();
    }
}
