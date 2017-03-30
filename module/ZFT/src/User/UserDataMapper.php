<?php

namespace ZFT\User;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\ClassMethods;
use ZFT\Assets\ImageHydrator;
use ZFT\JsonSelect;

class UserDataMapper extends TableGateway {

    protected $table = 'users';

    protected $columns = [
        'id', 'first_name', 'surname', 'email'
    ];

    public function __construct(AdapterInterface $adapter) {
        $hydrator = new ClassMethods(true);
        $hydrator->addStrategy('profile_image', new ImageHydrator());

        $resultSetPrototype = new HydratingResultSet($hydrator, new User());
        parent::__construct($this->table, $adapter, [], $resultSetPrototype, null);

        if(strcmp($this->adapter->platform->getName(), 'SQLServer') === 0) {
            $sqlPlatform = $this->sql->getSqlPlatform();
            $sqlPlatform->setTypeDecorator(JsonSelect::class, new JsonSelect($this->table));
        }
    }

    /**
     * @see https://www.postgresql.org/docs/current/static/functions-json.html
     * @see https://mariadb.com/kb/en/mariadb/json_object/
     * @param $id
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getUserById($id) {
        $platformName = $this->adapter->platform->getName();
        if(strcmp($platformName, 'PostgreSQL') === 0) {
            $profileImageColumn = new Expression("json_build_object('id', assets.id, 'path', assets.path)");
        } if(strcmp($platformName, 'MySQL') === 0) {
            $profileImageColumn = new Expression('json_object("id", assets.id, "path", assets.path)');
        } if(strcmp($platformName, 'SQLServer') === 0) {
            $assetQuery = new JsonSelect('assets');
            $assetQuery->where('users.profile_image = assets.id');

            $profileImageColumn = new Expression('?', [$assetQuery]);
        }

        $this->columns['profile_image'] = $profileImageColumn;
        return $this->select(['users.id' => $id]);
    }

    /**
     * @inheritDoc
     */
    protected function executeSelect(Select $select) {
        $select->join(
            'assets',
            'users.profile_image = assets.id',
            ['profileImage_id' => 'id', 'profileImage_path' => 'path']
        );

        return parent::executeSelect($select);
    }


}
