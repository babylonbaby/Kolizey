<?php
use Core\Form\Element as CoreElement;
use FormDecorator\View\Helper as HelperNS;
use Core\View\Helper as CoreHelperNS;
use Core\Form\Element as FromElementNS;

return [
    'decoratorBranch' => [
        'default' => [
            CoreElement\InlineFieldset::class => [
                [ 'name' => HelperNS\FormElementView::class, 'options' => [
                    'template' => '/FormElementDecorators/default/list-label'
                ]],
            ],
            CoreElement\ExternalSelect::class => [
                [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/externalSelect'] ],
            ],
            CoreElement\ExternalSelectMulti::class => [
                [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/externalSelectMulti'] ],
            ],
            CoreElement\ButtonWrapper::class => [
                [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/button-wrapper'] ],
            ]
        ],
        'bootstrap' => [
            CoreElement\InlineFieldset::class => [
                //[ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/list']],
                [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/row']],
            ],
            CoreElement\ButtonWrapper::class => [
                [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/row-colspan12'] ],
            ],
        ],
        'table' => [
            CoreElement\ButtonWrapper::class => [
                ['name' => HelperNS\FormElementView::class, 'options' => [
                    'template' => '/FormElementDecorators/default/button-wrapper',
                    'branch' => 'default'
                ]],
                [ 'name' => HelperNS\FormElementView::class, 'options' => [
                    'template' => '/FormElementDecorators/table/tag',
                    'tag' => 'td',
                    'class' => 'form-button-wrapper',
                    //'style' => 'border:1px solid #000;',
                ]],
                [ 'name' => HelperNS\FormElementView::class, 'options' => [
                    'template' => '/FormElementDecorators/table/tag',
                    'tag' => 'tr',
                ]],
            ],
            CoreElement\ExternalSelect::class => [
                [
                    'name' => HelperNS\FormElementView::class,
                    'options' => [
                        'template' => '/FormElementDecorators/table/row'
                    ]
                ]
            ],
            CoreElement\ExternalSelectMulti::class => [
                [
                    'name' => HelperNS\FormElementView::class,
                    'options' => [
                        'template' => '/FormElementDecorators/table/row'
                    ]
                ]
            ],
        ],
        'table_tr' => [
            CoreElement\ButtonWrapper::class => [
                [
                    'name' => HelperNS\FormElementView::class,
                    'options' => [
                        'template' => '/FormElementDecorators/default/button-wrapper',
                        'branch' => 'default'
                    ]
                ],
                [
                    'name' => HelperNS\FormElementView::class,
                    'options' => [
                        'template' => '/FormElementDecorators/table/tag',
                        'tag' => 'td',
                        'class' => 'form-grid-element form-grid-button-wrapper',
                    ]
                ],
            ]
        ],
        'jqGrid' => [
            FromElementNS\ButtonWrapper::class => [
                [ 'name' => CoreHelperNS\JqGrid\ColModel\ButtonWrapper::class],
            ],
        ]
    ],
];