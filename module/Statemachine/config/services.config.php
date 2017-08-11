<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 16.09.16
 * Time: 11:10
 */
return [
    'aliases' => [
        'archiverDoctrineEm' => 'doctrine.entitymanager.orm_default',
        \StateMachine\Functor\FunctorPluginManager::class => \StateMachine\Functor\FunctorPluginManagerInterface::class
    ],
    'factories' => [
        \StateMachine\Functor\FunctorPluginManagerInterface::class => function ($container) {
            $pm = new \StateMachine\Functor\FunctorPluginManager($container);
            return $pm;
        }
    ],
    'abstract_factories' => [
        'StateMachine\Factory\StateMachineAbstractFactory'
    ],
    'invokables' => [

    ],
    'shared' => [],
];
