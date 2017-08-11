<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\IndexController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DoctrineORMModule\Service\DoctrineObjectHydratorFactory;
use FormDecorator\View\Helper as HelperNS;
use Application\Hydrator\UserHydrator;
use Application\Hydrator\Factory\FactoryUserHydrator;
use Zend\Authentication\AuthenticationService;
use Zend\Form\Element as ElementNS;
use Zend\Form\View\Helper as ZendElementHelperNS;
use Zend\View\Helper as ViewHelperNS;
use Zend\Form as ZFormNS;
use Application\Service as ServiceNS;
use Zend\Navigation\Navigation;
use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\I18n\Translator;
use Grid\Factory;
use Core\Factory as CoreFactory;

//$route = include(__DIR__ . '/route.config.php');
$permission = include(__DIR__ . '/permissions.config.php');
$decorator = include(__DIR__ . '/form_decorator.config.php');
$assetic = include(__DIR__ . '/assetic.config.php');
//$hydrator = include(__DIR__ . '/hydrator.config.php');
//$validator = include(__DIR__ . '/validators.config.php');

return array_merge(
//    $route,
    $permission,
    $decorator,
    $assetic,
//    $hydrator,
//    $validator,
    [
        'form_elements' => [
            'delegators' => [

            ],
            'abstract_factories' => [
                /**
                 * ZF3 при создании элементов через FormElementManager по уполчанию в первый аргумент подается массив опций
                 * при этом стандартный конструктор элементов ожидает там name, а options в качестве второго аргумента
                 * данная абстрактная фабрика должна это исправлять.
                 */
                CoreFactory\AbstractFormElementFactory::class,
            ],
            'factories' => [
            ],
            'initializers' => [
                'hydrator' => \Core\Initializer\Hydrator::class,
                'entitySelect' => \Core\Initializer\EntitySelect::class,
            ]
        ],
        'grid_adapter_manager' => [
            'aliases' => [],
            'abstract_factories' => [
                /**
                 * объявить в приложении при необходимости
                 */
                Factory\AbstractJqGridDbalAdapterFactory::class
            ],
            'factories' => []
        ],
        //        Реализован механизм регулирования ролей-привелегий. Доступ к экшенам и контроллерам описывать в конфиге.
//        // The 'access_filter' key is used by the Application module to restrict or permit
//        // access to certain controller actions for unauthorized visitors.
        'access_filter' => [
            'options' => [
                'mode' => 'permissive', // разрешающий режим, закомментировать чтобы отключить.
            ],
//            'controllers' => [
//                Controller\UserController::class => [
//                    // Give access to "resetPassword", "message" and "setPassword" actions
//                    // to anyone.
//                    ['actions' => ['resetPassword', 'message', 'setPassword'], 'allow' => '*'],
//                    // Give access to "index", "add", "edit", "view", "changePassword" actions to authorized users only.
//                    ['actions' => ['index', 'add', 'edit', 'view', 'changePassword'], 'allow' => '@']
//                ],
//            ]
        ],
        'router' => [
            'routes' => [
                'home' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/',
                        'defaults' => [
                            'controller' => IndexController::class,
                            'action' => 'index',
                        ],
                    ],
                ],
                'about' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/about',
                        'defaults' => [
                            'controller' => IndexController::class,
                            'action' => 'about',
                        ],
                    ],
                ],
                'ipoteka' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/ipoteka',
                        'defaults' => [
                            'controller' => IndexController::class,
                            'action' => 'mortgage',
                        ],
                    ],
                ],
                'order' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/order',
                        'defaults' => [
                            'controller' => IndexController::class,
                            'action' => 'order',
                        ],
                    ],
                ],
                'feedback' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/otzivy[/page/:page]',
//                    'route' => '/user[/:action[/id/:id][/del/:del]]',
                        'constraints' => [
//                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'page' => '[0-9]*',
                        ],
                        'defaults' => [
                            'controller' => IndexController::class,
                            'action' => 'feedback',
                        ],
                    ],
                ],
//            'application' => [
//                'type' => Segment::class,
//                'options' => [
//                    'route' => '/application[/:action]',
//                    'defaults' => [
//                        'controller' => Controller\IndexController::class,
//                        'action' => 'index',
//                    ],
//                ],
//            ],
                'service' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/uslugi[/:action]',
                        'defaults' => [
                            'controller' => Controller\ProviderController::class,
                            'action' => 'index',
                        ],
                    ],
                ],
                'residential' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/nedvizhimost/complex-toggle-remove[/id/:id][/del/:del]',
                        'constraints' => [
                            'id' => '[0-9]+',
                            'del' => '[0-1]',
                        ],
                        'defaults' => [
                            'controller' => Controller\RealtyController::class,
                            'action' => 'complexToggleRemove',
                        ],
                    ],
                ],
                'realty' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/nedvizhimost[/:action][/id/:id][/:city][/sort/:sort][/:page]',
                        'constraints' => [
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'city' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'sort' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'page' => '[0-9]+',
                            'id' => '[0-9]+',
//                        'del' => '[0-1]',
                        ],
                        'defaults' => [
                            'controller' => Controller\RealtyController::class,
                            'action' => 'index',
                        ],
                    ],
                ],
                'integration' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/integration[/:action]',
                        'defaults' => [
                            'controller' => Controller\IntegrationController::class,
                            'action' => 'index',
                        ],
                    ],
                ],
                'login' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/login',
                        'defaults' => [
                            'controller' => Controller\LoginController::class,
                            'action' => 'login',
                        ],
                    ],
                ],
                'logout' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/logout',
                        'defaults' => [
                            'controller' => Controller\LoginController::class,
                            'action' => 'logout',
                        ],
                    ],
                ],
                'user' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/user[/:action[/id/:id][/del/:del]]',
                        'constraints' => [
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id' => '[a-zA-Z0-9_-]*',
                        ],
                        'defaults' => [
                            'controller' => Controller\UserController::class,
                            'action' => 'index',
                        ],
                    ],
                ],
//            'file' => [
//                'type'    => Segment::class,
//                'options' => [
//                    'route' => '/file[/:action[/id/:id][/del/:del][/name/:name]]',
//                    'constraints' => [
//                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                        'id' => '[a-zA-Z0-9_-]*',
//                    ],
//                    'defaults' => [
//                        'controller'    => Controller\FileController::class,
//                        'action'        => 'index',
//                    ],
//                ],
//            ],
            ],
        ],
        'service_manager' => [
            'aliases' => [
                'doctrine' => 'doctrine.entitymanager.orm_default',
            ],
            'factories' => [
//            Navigation::class => DefaultNavigationFactory::class,
//            Translator\Translator::class => Translator\TranslatorServiceFactory::class,
                AuthenticationService::class => Service\Factory\AuthentificationServiceFactory::class,
                Service\AuthAdapter::class => Service\Factory\AuthAdapterFactory::class,
                Service\AuthManager::class => Service\Factory\AuthManagerFactory::class,
                Service\UserManager::class => Service\Factory\UserManagerFactory::class,
                DoctrineObject::class => DoctrineObjectHydratorFactory::class,
                UserHydrator::class => FactoryUserHydrator::class,
            ],
            'invokables' => [
//            ServiceNS\ImageManager::class => ServiceNS\ImageManager::class,
            ]

        ],
        'controllers' => [
            'factories' => [
//            Controller\IndexController::class => InvokableFactory::class,
                Controller\ProviderController::class => InvokableFactory::class,
//            Controller\RealtyController::class => InvokableFactory::class,
//            Controller\IntegrationController::class => InvokableFactory::class,
            ],
        ],
        'view_manager' => [
            'display_not_found_reason' => true,
            'display_exceptions' => true,
            'doctype' => 'HTML5',
            'not_found_template' => 'error/404',
            'exception_template' => 'error/index',
            'template_map' => [
                'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
                'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
                'error/404' => __DIR__ . '/../view/error/404.phtml',
                'error/index' => __DIR__ . '/../view/error/index.phtml',
                "layout/ajax" => __DIR__ . '/../view/layout/layoutAjax.phtml',
            ],
            'template_path_stack' => [
                __DIR__ . '/../view',
            ],
            'strategies' => [
                'ViewJsonStrategy',
            ],
        ],
        'doctrine' => [
            'driver' => array(
                // defines an annotation driver with two paths, and names it `my_annotation_driver`
                'application_entity' => array(
                    'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                    'cache' => 'array',
                    'paths' => array(
                        __DIR__ . '/../src/Application/Entity',
                    ),
                ),
                // default metadata driver, aggregates all other drivers into a single one.
                // Override `orm_default` only if you know what you're doing
                'orm_default' => array(
                    'drivers' => array(
                        // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                        'Application\Entity' => 'application_entity',
                    )
                )
            ),
        ],
    ]
);
