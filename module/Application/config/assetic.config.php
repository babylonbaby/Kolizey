<?php

use Application\Controller as ControllerNS;

return [
    'assetic_configuration' => [
        'default' => array(
            'assets' => array(
//                '@head_jquery',
//                '@head_jquery_ui',
//                '@head_bootstrap_js',
//                '@head_stepbase_js',
//                '@head_stepbase_css',
//                '@head_app_css',
//                '@head_app_js',
//                '@stepcrypto_js',
//                '@head_userform_css',
//                '@head_userform_js',
//                '@jqgrid_css'
//                '@rn5_core_formCollection_js',
//                '@rn5_core_formTokenField_js',
//                '@rn5_core_inlineFieldset_css',
//                '@application_printPlugin_js',
//                '@application_printForm_js',
            ),
            'options' => array(
                'mixin' => true
            ),
        ),
        'routes' => array(
//            'home' => array(
//                '@home_js',
//            ),
        ),
        'controllers' => [
            ControllerNS\UserController::class => [
                //'@rn5_grid_common_js',
                'actions' => [
                    'index' => [
                        '@rn5_core_dialogBootstrap_js',
//                        '@rn5_core_dialogUI_js',
                        '@user_index_js',
                    ]
                ]
            ],
            ControllerNS\RealtyController::class => [
                'actions' => [
                    'complex-grid' => [
//                        '@rn5_core_dialogBootstrap_js',
//                        '@rn5_core_dialogUI_js',
                        '@head_app_css',
                        '@head_app_js',
                        '@_grid_common_js',
                        '@_grid_common_css',
                        '@rn5_core_externalSelect_css',
                        '@rn5_core_externalSelect_js',
                        '@rn5_core_dialogBootstrap_js',
                        '@realty_complex_grid_js',

                    ]
                ]
            ],
        ],
        'modules' => array(
            'application' => [
                'root_path' => __DIR__ . '/../assets',
                'collections' => [
                    'head_app_css' => array('assets' => array(
                        'css/index.css',
                        //'css/bootstrap-tokenfield.css',
                        //'css/tokenfield-typeahead.css',
                        'css/app/formDecorator.css',
//                        'css/app/ticket.css'

                    )),
                    'head_app_js' => ['assets' => [
                        'js/app/common.js',
                        //'js/bootstrap-tokenfield.js',
                        'js/app/syncronizeElementValue.js',
                        'js/app/formDecorator.js',
                        'js/app/calendar.js',
                        'js/app/datetimePicker.js',
                    ]],
                    'application_printPlugin_js' => ['assets' => [
                        'js/app/jQuery.print.min.js',
                    ]],
                    'application_printForm_js' => ['assets' => [
                        'js/app/formPrint.js',
                    ]],
                    'user_index_js' => [
                        'assets' => [
                            'js/user/index.js',
                        ]
                    ],
                    'realty_complex_grid_js' => [
                        'assets' => [
                            'js/realty/complex/grid.js',
                        ]
                    ],
                ],
            ],
        )

    ]
];

