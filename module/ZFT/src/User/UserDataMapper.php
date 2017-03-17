<?php

namespace ZFT\User;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\Feature\MetadataFeature;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\ObjectProperty;
use Zend\Hydrator\Strategy\ClosureStrategy;
use ZFT\Assets\Image;
use ZFT\Assets\ImageHydrator;

class UserDataMapper extends TableGateway {

    protected $table = 'users';

    protected $columns = [
        'id', 'first_name', 'surname', 'email'
    ];

    public function __construct(AdapterInterface $adapter) {
        $resultSetPrototype = new HydratingResultSet(new ClassMethods(true), new User());
        $resultSetPrototype = null;


        parent::__construct($this->table, $adapter, [], $resultSetPrototype, null);
    }

    public function getUserById($id) {
        $userResultSet = $this->select(['users.id' => $id]);

        $user = $userResultSet->current();
        $user = (array)$user;
        $image = [
            'id' => $user['image_id'],
            'path' => $user['image_path']
        ];
        $user['profileImage'] = $image;

        $hydrator = new ClassMethods(true);
        $hydrator->addStrategy('profileImage', new ImageHydrator());

        unset($user['image_id']);
        unset($user['image_path']);

        $user = $hydrator->hydrate($user, new User());

        return $user;
    }

    /**
     * @inheritDoc
     */
    protected function executeSelect(Select $select) {
        $select->join(
            'assets',
            'users.profile_image = assets.id',
            ['image_id' => 'id', 'image_path' => 'path']
        );

        return parent::executeSelect($select);
    }


}
