<?php

namespace ZFTest\User;

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

        $identityMapStub = new class() implements User\IdentityMapInterface {

        };

        $dataMapperStub = new class() implements User\DataMapperInterface {

        };

        $repository = new User\Repository($identityMapStub, $dataMapperStub);

        $this->assertInstanceOf(User\Repository::class, $repository);
    }
}
