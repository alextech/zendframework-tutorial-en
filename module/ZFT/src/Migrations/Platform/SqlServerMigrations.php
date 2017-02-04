<?php

namespace ZFT\Migrations\Platform;

use Faker;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Platform\PlatformInterface;
use Zend\Db\Metadata\MetadataInterface;
use Zend\Db\Metadata\Object\TableObject;
use Zend\Db\Metadata\Source\Factory as MetadataFactory;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Ddl;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Where;
use Zend\EventManager\EventManager;
use ZFT\Migrations\Migrations;
use ZFT\Migrations\MigrationsEvent;

class SqlServerMigrations extends Migrations {

    const MINIMUM_SCHEMA_VERSION = 3;
    const INI_TABLE = 'ini';

    protected function getVersion() {
        $tables = $this->metadata->getTables();

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

    public function run() {
        $migrationsStartEvent = new MigrationsEvent();
        $migrationsStartEvent->setName(MigrationsEvent::MIGRATIONS_START);
        $migrationsStartEvent->setTarget($this);
        $migrationsStartParams['to'] = $this->getTargetVersion();

        $migrationCalss = new \ReflectionClass(Migrations::class);
        $methods = $migrationCalss->getMethods(\ReflectionMethod::IS_PROTECTED);

        $updates = [];
        array_walk($methods, function(\ReflectionMethod $method) use (&$updates) {
            $version = substr($method->getName(), strpos($method->getName(), '_')+1);
            $version = (int) $version;
            $updates[$version] = $method->getName();
        });

        ksort($updates);

        $currentVersion = (int) $this->getVersion();
        $migrationsStartParams['from'] = $currentVersion;
        $migrationsStartEvent->setParams($migrationsStartParams);
        $this->eventManager->triggerEvent($migrationsStartEvent);

        for($v = $currentVersion+1; $v <= self::MINIMUM_SCHEMA_VERSION; $v++) {
            $update = $updates[$v];
            $this->{$update}();

            $this->setVersion($v);
        }

        return;
    }

    protected function getTargetVersion() {
        return self::MINIMUM_SCHEMA_VERSION;
    }

    protected function setVersion($version){
        $sql = new Sql($this->adapter);
        $schemaVersionUpdate = $sql->update();
        $schemaVersionUpdate->table(self::INI_TABLE);
        $schemaVersionUpdate->set(['value' => $version]);

        $schemaVersionRow = new Where();
        $schemaVersionRow->equalTo('option', 'ZftSchemaVersion');

        $schemaVersionStatement = $sql->prepareStatementForSqlObject($schemaVersionUpdate);
        $schemaVersionStatement->execute();

    }

    public function attach($eventName, callable $listener) {
        $this->eventManager->attach($eventName, $listener);
    }

    protected function update_001() {
        $iniTable = new Ddl\CreateTable(self::INI_TABLE);

        $option = new Ddl\Column\Varchar('option', 32);
        $value  = new Ddl\Column\Varchar('value', 32);

        $iniTable->addColumn($option);
        $iniTable->addColumn($value);

        $this->execute($iniTable);

        $sql = new Sql($this->adapter);
        $insertInitialVersion = $sql->insert();
        $insertInitialVersion->into(self::INI_TABLE);
        $insertInitialVersion->columns(array('option', 'value'));
        $insertInitialVersion->values(array('ZftSchemaVersion', 1));
        $values = [
            'option' => 'ZftSchemaVersion',
            'value' => 1
        ];
        $insertInitialVersion->columns(array_keys($values));
        $insertInitialVersion->values(array_values($values));

        $insertStatement = $sql->prepareStatementForSqlObject($insertInitialVersion);
        $insertStatement->execute();
    }

    protected function update_002() {

        $this->adapter->query(
            'CREATE TABLE [users] (
    [id] INTEGER NOT NULL PRIMARY KEY IDENTITY(1, 1),
    [first_name] NVARCHAR(32) NOT NULL,
    [surname] NVARCHAR(32) NOT NULL,
    [patronymic] NVARCHAR(32),
    [email] NVARCHAR(128) NOT NULL 
)',
            Adapter::QUERY_MODE_EXECUTE);

        $faker = new Faker\Generator();
        $faker->addProvider(new Faker\Provider\en_US\Person($faker));
        $faker->addProvider(new Faker\Provider\en_GB\Internet($faker));

        $insert = new Insert('users');

        $sql = new Sql($this->adapter);
        for($i = 0; $i < 10; $i++) {
            $insert->values([
                'first_name' => $faker->firstName,
                'surname' => $faker->lastName,
                'email' => $faker->safeEmail
            ]);

            $insertStatement = $sql->prepareStatementForSqlObject($insert);
            $insertStatement->execute();
        }
    }

    protected function update_003() {
        $sql = new Sql($this->adapter);

        $this->adapter->query(
            'CREATE TABLE [assets] (
    [id] INTEGER NOT NULL PRIMARY KEY IDENTITY(1, 1),
    [path] NVARCHAR(128) NOT NULL
)',
            Adapter::QUERY_MODE_EXECUTE);

        $insertSampleImages = new Insert('assets');
        $insertSampleImages->values([
            'path' => ':path'
        ]);
        $stmt = $sql->prepareStatementForSqlObject($insertSampleImages);
        for($i = 1; $i <= 100; $i++) {
            $stmt->execute([':path' => 'user_'.$i.'.png']);
        }

        $this->adapter->query(
            'ALTER TABLE [users]
 ADD [profile_image] INTEGER,
 CONSTRAINT [userprofileimage_assets_relation] FOREIGN KEY ([profile_image]) REFERENCES [assets] ([id])',
            Adapter::QUERY_MODE_EXECUTE
        );

        $setProfileImage = new Update('users');
        $setProfileImage->set(['profile_image' => ':profile_image'])
            ->where('id = :id');
        $stmt = $sql->prepareStatementForSqlObject($setProfileImage);

        for($i = 1; $i <= 10; $i++) {
            $stmt->execute([':profile_image' => $i, 'id' => $i]);
        }


        $insertMoreUsers = new Insert('users');
        $insertMoreUsers->values([
            'first_name' => ':first_name',
            'surname' => ':surname',
            'patronymic' => ':patronymic',
            'email' => ':email',
            'profile_image' => ':profile_image'
        ]);

        $faker = new Faker\Generator();
        $faker->addProvider(new Faker\Provider\en_US\Person($faker));
        $faker->addProvider(new Faker\Provider\en_GB\Internet($faker));


        $insertStatement = $sql->prepareStatementForSqlObject($insertMoreUsers);
        for($i = 0; $i < 90; $i++) {
            $insertStatement->execute([
                'first_name' => $faker->firstName,
                'surname' => $faker->lastName,
                'email' => $faker->safeEmail,
                'profile_image' => $faker->unique()->numberBetween(11, 100)
            ]);
        }
    }

}
