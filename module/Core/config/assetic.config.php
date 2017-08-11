<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 10.02.17
 * Time: 18:24
 */

return [
    'assetic_configuration' => [
        'modules' => [
            'rn5-core' => [
                'root_path' => __DIR__ . '/../assets',
                'collections' => [
                    'rn5_core_externalSelect_js' => ['assets'=>[
                        'js/externalSelect.js'
                    ]],
                    'rn5_core_dialogUI_js' => ['assets'=>[
                        'js/dialogUI.js',
                        'js/dialogUIAjax.js',
                    ]],
                    'rn5_core_dialogBootstrap_js' => ['assets'=>[
                        'js/dialogBootstrap.js',
                    ]],
                    'rn5_core_externalSelect_css' => [
                        'assets' => [
                            'css/externalSelect.css'
                        ]
                    ],
                    'rn5_core_formTokenField_js' => [
                        'assets' => [
                            'js/form_tokenfield.js',
                        ]
                    ],
                    'rn5_core_formCollection_js' => [
                        'assets' => [
                            'js/form_collection.js',
                        ]
                    ],
                    'rn5_core_inlineFieldset_css' => [
                        'assets' => [
                            'css/inlineFieldset.css'
                        ],
                    ],
                ],
            ],
        ],
    ]
];

