<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ApplicationTest\Controller;

use Portal\Controller\IndexController;
use Portal\Controller\UserRelatedControllerFactory;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Platform\Sql92;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use ZendTest\Db\TestAsset\PdoStubDriver;
use ZendTest\Db\TestAsset\TrustingSql92Platform;
use ZFT\User;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('config', ['db' => []]);

        $adapter = $this->createMock(Adapter::class);
        $adapter->expects($this->any())
            ->method('getPlatform')
            ->will($this->returnValue(new Sql92()));

        $serviceManager->setService('dbcon', $adapter);

        $userRelatedFactoryMock = $this->createMock(User\Repository::class);
        $userRelatedFactoryMock->expects($this->any())
            ->method('getUserById')
            ->withAnyParameters()
            ->willReturn(new User\User());

        $serviceManager->setService(User\Repository::class, $userRelatedFactoryMock);
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('portal');
        $this->assertControllerName(IndexController::class); // as specified in router's controller name alias
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }

    public function testIndexActionViewModelTemplateRenderedWithinLayout()
    {
        $this->dispatch('/', 'GET');
        $this->assertQuery('.container .jumbotron');
    }

    public function testInvalidRouteDoesNotCrash()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }
}
