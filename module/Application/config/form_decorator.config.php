<?php
use Zend\Form\Element as ElementNS;
use Application\Form\Elements as ApplicationElementNS;
use Zend\Form\View\Helper as ZendElementHelperNS;
use Zend\View\Helper as ViewHelperNS;
use FormDecorator\View\Helper as HelperNS;
use Zend\Form as ZFormNS;
use Core\Form\Element as R5CoreElementNS;
return [
    'FormElementDecorators' => [
        'decoratorBranch' => [
            'leftPartTicket' => [
                ZFormNS\Fieldset::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/LeftPartTicket/fieldset']],
                ],
            ],
            'default' => [
                ElementNS\Csrf::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/default/hidden']],
                ],
                ElementNS\Captcha::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/default/list']],
                ],
                ElementNS\Number::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/text'] ],
                ],
                ElementNS\Email::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/text'] ],
                ],
            ],
            'bootstrap' => [
                ZFormNS\Form::class => [
                    'list' => [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/list'] ],
                    'form-wrap' => [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/form-wrap'] ],
                ],
                ZFormNS\Fieldset::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/default/list']],
                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/fieldset']],
                ],
                ElementNS\Captcha::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/captcha'] ],
                ],
                ElementNS\Csrf::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/row-hidden']],
                ],
                ElementNS\Checkbox::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/checkbox']],
                ],
                ElementNS\File::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/file']],
                ],
                ApplicationElementNS\Info::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/info']],
                ],
                ElementNS\Number::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/row']],
                ],
                ElementNS\Email::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/row']],
                ],
                R5CoreElementNS\CollectionTable::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => [
                            'template' => '/FormElementDecorators/default/list',
                            'branch' => 'bootstrap_table_tr'
                    ]],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/bootstrap/row-table-collection',
                        'branch' => 'bootstrap_table_tr'
                    ]],
                ],
                R5CoreElementNS\ExternalSelect::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/externalSelect']],
                ],
                R5CoreElementNS\ExternalSelectMulti::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/externalSelectMulti'] ],
                ],
                ElementNS\DateTime::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/row'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/bootstrap/tag', 'tag' => 'td',
                        'class' => 'form-grid-element form-grid-text',
                        //'style' => 'border:1px solid #000;',
                    ]],
                ],
                ElementNS\Textarea::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/row'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/bootstrap/tag', 'tag' => 'td',
                        'class' => 'form-grid-element form-grid-text',
                        //'style' => 'border:1px solid #000;',
                    ]],
                ],
            ],
            'bootstrap_table_tr' => [
                ZFormNS\Fieldset::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/list']],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/bootstrap/tag', 'tag' => 'tr'
                    ]],
                ],
                ElementNS\Text::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/text'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/bootstrap/tag', 'tag' => 'td',
                        'class' => 'form-grid-element form-grid-text',
                        //'style' => 'border:1px solid #000;',
                    ]],
                ],
                ElementNS\Textarea::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/textarea'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/bootstrap/tag', 'tag' => 'td',
                        'class' => 'form-grid-element form-grid-text',
                        //'style' => 'border:1px solid #000;',
                    ]],
                ],
                ElementNS\Select::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/select'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/bootstrap/tag', 'tag' => 'td',
                        'class' => 'form-grid-element form-grid-select',
                        //'style' => 'border:1px solid #000;',
                    ]],
                ],
                ElementNS\Radio::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/radio'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/bootstrap/tag', 'tag' => 'td',
                        'class' => 'form-grid-element form-grid-radio',
                        //'style' => 'border:1px solid #000;',
                    ]],
                ],
                ElementNS\Hidden::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/hidden'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/bootstrap/tag', 'tag' => 'td',
                        'class' => 'form-grid-element form-grid-hidden',
                        'style' => 'display:none;',
                    ]],
                ],
                R5CoreElementNS\ButtonWrapper::class => [
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
                            'template' => '/FormElementDecorators/bootstrap/tag',
                            'tag' => 'td',
                            'class' => 'form-grid-element form-grid-button-wrapper',
                        ]
                    ],
                ]
            ],
            'bootstrap_row' => [
//                ZFormNS\Form::class => [
//                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/default/list']],
//                    //[ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/form'] ],
//                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/form-wrap']],
//                ],
//                ElementNS\Collection::class => [
//                    [
//                        'name' => HelperNS\FormElementView::class,
//                        'options' => [
//                            'template' => '/FormElementDecorators/default/list',
//                            'branch' => 'bootstrap_row'
//                        ]
//                    ],
//                ],
//                ZFormNS\Fieldset::class => [
//                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/default/list']],
//                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/fieldset']],
//                ],
//                ElementNS\Text::class => [
//                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/row']],
//                ],
//                ElementNS\Select::class => [
//                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/row']],
//                ],
//                ElementNS\Radio::class => [
//                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/row']],
//                ],
//                ElementNS\Checkbox::class => [
//                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/row']],
//                ],
//                ElementNS\Password::class => [
//                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/row']],
//                ],
//                ElementNS\Hidden::class => [
//                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/row-hidden']],
//                ],
//                ElementNS\Submit::class => [
//                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/default/submit']],
//                ],
//                ElementNS\Button::class => [
//                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/bootstrap/inline-block']],
//                    ['name' => HelperNS\FormElementView::class, 'options' => ['template' => '/FormElementDecorators/default/button']],
//                ],
            ],
        ]
    ],
];