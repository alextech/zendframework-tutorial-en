<?php

namespace ZFTest;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\Strategy\StrategyInterface;
use ZFT\CompositeHydrator;

class Stub {
    public function setKey1($value) {}
    public function setKey2($value) {}
    public function setPrefix($value) {}
    public function setAnotherPrefix($value) {}
}

class CompositeHydratorTest extends TestCase {
    public function testGroupByGivenKeys() {

        $data = [
            'key1' => 'val1',
            'key2' => 'val2',
            'prefix_key1' => 'extraval1',
            'prefix_key2' => 'extraval2',
            'anotherPrefix_key1' => 'anotherExtraVal1',
        ];

        $strategy1 = $this->getMockBuilder(StrategyInterface::class)->getMock();
        $strategy1->expects($this->once())
            ->method('hydrate')
            ->with($this->equalTo(['key1' => 'extraval1', 'key2' => 'extraval2']));

        $strategy2 = $this->getMockBuilder(StrategyInterface::class)->getMock();
        $strategy2->expects($this->once())
            ->method('hydrate')
            ->with($this->equalTo(['key1' => 'anotherExtraVal1']));

        $hydtrator = new CompositeHydrator();
        $hydtrator->addStrategy('prefix',    $strategy1);
        $hydtrator->addStrategy('anotherPrefix', $strategy2);
        $hydtrator->hydrate($data, new Stub());
    }
}
