<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 10.02.17
 * Time: 18:24
 */

use StateProgram\Controller as ControllerNS;

return [
    'assetic_configuration' => [
        'modules' => [
            '-grid' => [
                'root_path' => __DIR__ . '/../assets',
                'collections' => [
                    '_grid_common_js' => ['assets' => [
                        'js/common.js'
                    ]],
                    '_grid_common_css' => ['assets' => [
                        'css/common.css'
                    ]],
                ],
            ],
        ],
    ]
];

