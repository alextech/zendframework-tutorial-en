<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Interop\Container\ContainerInterface;
use Admin\Controller\AdminControllerFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Method;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use ZFT\User;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'dbtools' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/db',
                            'defaults' => [
                                'needsDatabase' => false
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'runmigrations' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/runmigrations',
                                    'defaults' => [
                                        'needsDatabase' => false,
                                        'action' => 'runmigrations'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AdminController::class => AdminControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'view_helper_config' => [
        'flashmessenger' => [
            'message_open_format' => '<div%s>',
            'message_separator_string' => '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div><div%s>',
            'message_close_string' => '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>'
        ]
    ]
];
