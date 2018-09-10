<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZFT\Migrations\Migrations;
use ZFT\User;

class AdminController extends AbstractActionController
{

    /** @var  Migrations */
    private $migrations;

    public function __construct(Migrations $migrations) {
        $this->migrations = $migrations;
    }

    public function indexAction()
    {
        return [
            'needsMigration' => $this->migrations->needsUpdate()
        ];
    }

    public function runmigrationsAction() {
        $this->migrations->run();
    }
}
