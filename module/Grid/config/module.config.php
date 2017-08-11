<?php
namespace Grid;

use Grid\Factory;
use Grid\Service;
use Interop\Container\ContainerInterface;
use Grid\GridAdapterPluginManager as GridAdapterPM;
use Grid\Service\JqGridDbalAdapter;

$gridAdapter = include(__DIR__ . '/grid_adapter.config.php');
$assets = include(__DIR__ . '/assetic.config.php');

return array_merge(
    $assets,
    $gridAdapter,
    array(
        'controllers' => array(
            'abstract_factories' => [
            ],
            'invokables' => array(
            ),
        ),
        'service_manager' => array(
            'factories' => [
                GridAdapterPM\GridAdapterPluginManagerInterface::class => GridAdapterPM\GridAdapterPluginManagerFactory::class,
            ],
            'invokables' => array(
            )
        ),
        'hydrators' => [
            'delegators' => [
            ],
            'abstract_factories' => [
                //Factory\AbstractDoctrineObjectHydratorFactory::class,
            ],
            'factories' => [
            ],
        ],
        'view_helpers' => [
            'invokables' => [
            ]
        ],
    )
);
