<?php

namespace ZFT\Migrations;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\MetadataInterface;
use Zend\Db\Metadata\Source\Factory as MetadataFactory;

class Migrations {

    const MINIMUM_SCHEMA_VERSION = 1;

    /** @var  Adapter */
    private $adapter;

    /** @var  MetadataInterface */
    private $metadata;

    public function __construct(Adapter $adapter) {
        $this->metadata = MetadataFactory::createSourceFromAdapter($adapter);
    }

}
