<?php

namespace ZFT\Migrations;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Platform\PlatformInterface;
use Zend\Db\Metadata\MetadataInterface;
use Zend\Db\Metadata\Object\TableObject;
use Zend\Db\Metadata\Source\Factory as MetadataFactory;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Ddl;

class Migrations {

    const MINIMUM_SCHEMA_VERSION = 1;
    const INI_TABLE = 'ini-dev';

    /** @var  Adapter */
    private $adapter;

    /** @var  PlatformInterface */
    private $platform;

    /** @var  MetadataInterface */
    private $metadata;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->platform = $adapter->getPlatform();
        $this->metadata = MetadataFactory::createSourceFromAdapter($adapter);
    }

    public function needsUpdate() {
        return ($this->getVersion() < self::MINIMUM_SCHEMA_VERSION);
    }

    private function execute(Ddl\SqlInterface $ddl) {
        $sql = new Sql($this->adapter);
        $sqlString = $sql->buildSqlString($ddl);

        $this->adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);
    }

    private function getVersion() {
        $tables = $this->metadata->getTables('public');

        $iniTable = array_filter($tables, function (TableObject $table) {
            return strcmp($table->getName(), self::INI_TABLE) === 0;
        });

        if (count($iniTable) === 0) {
            return 0;
        }

        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select->from(self::INI_TABLE);
        $select->where(['option' => 'ZftSchemaVersion']);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $result = $result->current();
        $version = $result['value'];


//        $sql = 'SELECT value '.
//        'FROM '.$this->platform->quoteIdentifier(self::INI_TABLE)." ".
//        'WHERE '.$this->platform->quoteIdentifier('option').' = :option';

//        $result = $this->adapter->query($sql, ['option' => 'ZftSchemaVersion']);
//        $result = $result->toArray();
//        $version = $result[0]['value'];

        return $version;

    }

    protected function update_1_001() {
        $iniTable = new Ddl\CreateTable('ini');

        $option = new Ddl\Column\Varchar('option');
        $value  = new Ddl\Column\Varchar('value');

        $iniTable->addColumn($option);
        $iniTable->addColumn($value);

        $this->execute($iniTable);
    }

}
