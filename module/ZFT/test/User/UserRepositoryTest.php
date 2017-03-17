<?php

namespace ZFTest\User;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\TableGateway;
use ZFT\User;

/** PHP 5 version
class IdentityMapStub implements User\IdentityMapInterface {

}

class DataMapperStub implements User\DataMapperInterface {

}
*/

class UserRepositoryTest extends \PHPUnit_Framework_TestCase {
    public function testCanCreateUserRepositoryObject() {
        /* PHP 5 version
        $identityMapStub = new IdentityMapStub();
        $dataMapperStub = new DataMapperStub();
        */

        $usersTableMock = $this->createMock(User\UserDataMapper::class);

        $repository = new User\Repository($usersTableMock);

        $this->assertInstanceOf(User\Repository::class, $repository);
    }

    public function testGetSameObjectWithMultipleRequests() {
        $usersTableMock = $this->createMock(User\UserDataMapper::class);

        $stubUser2 = new User\User();
        $stubUser3 = new User\User();


        $resultSetMock = $this->createMock(ResultSet::class);
        $resultSetMock->expects($this->exactly(2))
            ->method('current')
            ->willReturnOnConsecutiveCalls([$stubUser2, $stubUser3]);

        $usersTableMock->expects($this->any())
            ->method('getUserById')
            ->willReturn($resultSetMock);
        $repository = new User\Repository($usersTableMock);

        $user2 = $repository->getUserById(2);
        $user3 = $repository->getUserById(3);
        $user4 = $repository->getUserById(2);

        $this->assertSame($user2, $user4);
    }
}
