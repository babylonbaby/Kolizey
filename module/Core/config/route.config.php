<?php
return array(
    'routes' => array(
        'default' => array(
            'type' => 'Literal',
            'options' => array(
                'route'    => '/project-metadata',
                'defaults' => array(
                    '__NAMESPACE__' => 'Core\Controller',
                    'controller'    => 'Core\Controller\ProjectMetadata',
                    'action'        => 'get',
                ),
            ),
            'may_terminate' => true,
        )
    ),
);