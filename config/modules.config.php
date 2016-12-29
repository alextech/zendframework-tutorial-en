<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

//ZendDeveloperTools\Module::inject();

/**
 * List of enabled modules for this application.
 *
 * This should be an array of module namespaces used in the application.
 */
return [
    'Zend\Mvc\Plugin\FlashMessenger',
    'Zend\Session',
    'Zend\Log',
    'Zend\Db',
    'Zend\Cache',
    'Zend\Router',
    'Zend\Validator',
    'ZFT',
    'Admin',
    'Portal',
];
