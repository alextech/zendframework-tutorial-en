<?php

namespace ZFT\User;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\Feature\MetadataFeature;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\Aggregate\AggregateHydrator;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\ObjectProperty;
use Zend\Hydrator\Strategy\ClosureStrategy;
use ZFT\Assets\Image;
use ZFT\Assets\ImageHydrator;
use ZFT\CompositeHydrator;

class UserDataMapper extends TableGateway {

    protected $table = 'users';

    protected $columns = [
        'id', 'first_name', 'surname', 'email'
    ];

    public function __construct(AdapterInterface $adapter) {
        $hydrator = new CompositeHydrator(true);
        $hydrator->addStrategy('profileImage', new ImageHydrator());

        $resultSetPrototype = new HydratingResultSet($hydrator, new User());
        parent::__construct($this->table, $adapter, [], $resultSetPrototype, null);
    }

    public function getUserById($id) {
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
