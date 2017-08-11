<?php
namespace Core;

use Core\Factory;
use Core\Service;
use Interop\Container\ContainerInterface;
use Core\Form\Element as CoreElement;
use FormDecorator\View\Helper as HelperNS;

$assets = include(__DIR__ . '/assetic.config.php');
$formDecorators = include(__DIR__ . '/form_element_decorator.config.php');

return array_merge(
    $assets,
    [
        'router' => include('route.config.php'),
        'controllers' => array(
            'abstract_factories' => [
                Factory\LazyControllerFactory::class,
            ],
            'invokables' => array(
                'Core\Controller\ProjectMetadata' => 'Core\Controller\ProjectMetadataController',
            ),
        ),
        'service_manager' => array(
            'factories' => [
                ContainerInterface::class => function($serviceManager) {
                    return $serviceManager;
                },
                //перенесено в rn5/grid
                //Service\JqGridDbalAdapter::class => Service\JqGridDbalAdapterFactory::class
            ],
            'invokables' => array(
                'Core\Service\ProjectMetadata' => 'Core\Service\ProjectMetadataService'
            )
        ),
        'hydrators' => [
            'delegators' => [
            ],
            'abstract_factories' => [
                Factory\AbstractDoctrineObjectHydratorFactory::class,
            ],
            'factories' => [
            ],
        ],
        'filters' => [
            'invokables' => [
                \Core\Filter\StringToArray::class => \Core\Filter\StringToArray::class,
                \Core\Filter\ArrayValueJsonDecode::class => \Core\Filter\ArrayValueJsonDecode::class,
            ],
        ],
        'view_helpers' => [
            'invokables' => [
                'rn5Buttons' => \Core\View\Buttons::class
            ]
        ],
        'view_manager' => [
            'template_path_stack' => [
                __DIR__ . '/../view',
            ],
        ],
        'FormElementDecorators' => $formDecorators,
    ]
);
