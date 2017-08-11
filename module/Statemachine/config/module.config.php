<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 16.09.16
 * Time: 10:59
 */
return [
    'doctrine' => [
        'driver' => [
            'StateMachineEntity' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/Entity',
            ],
            'orm_default' => array(
                'drivers' => array(
                    'StateMachine\Entity' => 'StateMachineEntity'
                ),
            ),
        ]
    ],
    StateMachine\Functor\FunctorProviderInterface::CONFIG_KEY => [

    ],

];
