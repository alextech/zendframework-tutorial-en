<?php

namespace ZFTest\Migrations;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Platform\PlatformInterface;
use Zend\Db\Sql\Platform\Platform;
use Zend\EventManager\EventInterface;
use ZFT\Migrations\Migrations;
use ZFT\Migrations\MigrationsEvent;

final class MigrationsStub extends Migrations {

    const MINIMUM_SCHEMA_VERSION = 2;

    public $testVersion;

    protected function update_001() {

    }

    protected function update_002() {

    }

    protected function setVersion($version) {

    }

    protected function getVersion() {
        return $this->testVersion;
    }

    protected function getTargetVersion() {
        return 2;
    }


}

class MigrationsTest extends \PHPUnit_Framework_TestCase {
    public function testListenersCalled() {
        $platformInterface = $this->createMock(PlatformInterface::class);
        $platformInterface->method('getName')
            ->willReturn('SQLite');

        $adapterMock = $this->createMock(Adapter::class);
        $adapterMock->method('getPlatform')
            ->willReturn($platformInterface);

        $migration = new MigrationsStub($adapterMock);

        $handleMigrationsStartRan = false;
        $migration->attach(MigrationsEvent::MIGRATIONS_START, function(MigrationsEvent $e) use(&$handleMigrationsStartRan) {
            $handleMigrationsStartRan =true;

            $params = $e->getParams();
            $this->assertEquals(0, $params['from']);
            $this->assertEquals(2, $params['to']);
        });
        $migration->run();

        $this->assertEquals(true, $handleMigrationsStartRan);
    }
}
