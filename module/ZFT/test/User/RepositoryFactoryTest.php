<?php

namespace ZFTest\User;

use Zend\ServiceManager\ServiceManager;
use ZFT\User\MemoryIdentityMap;
use ZFT\User\PostgresDataMapper;
use ZFT\User\Repository;
use ZFT\User\RepositoryFactory;
use ZFT\User\IdentityMapInterface;
use ZFT\User\DataMapperInterface;

class RepositoryFactoryTest extends \PHPUnit_Framework_TestCase {
    function testCanCreateUserRepository() {
        $sm = new ServiceManager();
        $sm->setFactory(MemoryIdentityMap::class, function() {
            return new class() implements IdentityMapInterface {

            };
        });

        $sm->setFactory(PostgresDataMapper::class, function() {
            return new class() implements DataMapperInterface {

            };
        });


        $factory = new RepositoryFactory();
        $repository = $factory($sm, RepositoryFactory::class);

        $this->assertInstanceOf(Repository::class, $repository);
    }
}
