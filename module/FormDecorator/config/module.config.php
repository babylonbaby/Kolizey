<?php
namespace FormDecorator;

use FormDecorator\View\Helper as HelperNS;
use Zend\Form\Element as ElementNS;
use Zend\Form\View\Helper as ZendElementHelperNS;
use Zend\View\Helper as ViewHelperNS;
use Zend\Form as ZFormNS;

return [
    'view_helpers' => [
        'aliases' => [
            'formBranchRender' => HelperNS\FormElementBranch::class,
            'formElementView' => HelperNS\FormElementView::class,
            'collectionButtonsSpec' => HelperNS\CollectionButtonsSpec::class,
        ],
        'factories' => [
            HelperNS\FormElementBranch::class => HelperNS\FormElementBranchFactory::class,
            HelperNS\FormElementView::class => HelperNS\FormElementViewFactory::class,
            HelperNS\JqGrid\Params::class => HelperNS\JqGrid\ParamsFactory::class,
            HelperNS\JqGrid\ColModel::class => HelperNS\JqGrid\ColModelFactory::class,
            HelperNS\JqGrid\SubGrid::class => HelperNS\JqGrid\SubGridFactory::class,
        ],
        'invokables' => [
            HelperNS\JqGrid\ColModel\Text::class => HelperNS\JqGrid\ColModel\Text::class,
            HelperNS\JqGrid\ColModel\TextArea::class => HelperNS\JqGrid\ColModel\TextArea::class,
            HelperNS\JqGrid\ColModel\Select::class => HelperNS\JqGrid\ColModel\Select::class,
            HelperNS\JqGrid\ColModel\Hidden::class => HelperNS\JqGrid\ColModel\Hidden::class,
            HelperNS\JqGrid\ColModel\Checkbox::class => HelperNS\JqGrid\ColModel\Checkbox::class,
            HelperNS\JqGrid\ColModel\Date::class => HelperNS\JqGrid\ColModel\Date::class,
            HelperNS\JqGrid\ColModel\DateTime::class => HelperNS\JqGrid\ColModel\DateTime::class,
            HelperNS\CollectionButtonsSpec::class => HelperNS\CollectionButtonsSpec::class,
        ],
        'shared' => [
        ]
    ],
    'FormElementDecorators' => [
        'decoratorBranch' => [
            'default' => [
                ZFormNS\Form::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/list'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/form'] ],
                ],
                ZFormNS\Fieldset::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/list-label'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/fieldset'] ],
                ],
                ElementNS\Collection::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/list-label'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/template'] ],
                    //[ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/collection'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/fieldset'] ],
                ],
                ElementNS\Text::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/text'] ],
                ],
                ElementNS\Textarea::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/textarea'] ],
                ],
                ElementNS\Select::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/select'] ],
                ],
                ElementNS\Radio::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/radio'] ],
                ],
                ElementNS\Checkbox::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/checkbox'] ],
                ],
                ElementNS\Hidden::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/hidden'] ],
                ],
                ElementNS\Submit::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/submit'] ],
                ],
                ElementNS\Button::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/button'] ],
                ],
                ElementNS\Password::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/password'] ],
                ],
                ElementNS\Date::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/date'] ],
                ],
                ElementNS\DateTime::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/date'] ],
                ],
            ],
            'bootstrap' => [
                ZFormNS\Form::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/list'] ],
                    //[ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/form'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/form-wrap'] ],
                ],
                ElementNS\Collection::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => [
                            'template' => '/FormElementDecorators/default/list',
                            'branch' => 'bootstrap_row'
                    ]],
                ],
                ZFormNS\Fieldset::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/list']],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/fieldset']],
                ],
                ElementNS\Text::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/row'] ],
                ],
                ElementNS\Select::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/row'] ],
                ],
                ElementNS\Radio::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/row'] ],
                ],
                ElementNS\Checkbox::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/row'] ],
                ],
                ElementNS\Password::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/row'] ],
                ],
                ElementNS\Hidden::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/row-hidden'] ],
                ],
                ElementNS\Submit::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/submit']],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/row-colspan'] ],
                ],
                ElementNS\Button::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/button']],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/bootstrap/row-colspan'] ],
                ],
            ],
            'minimal' => [
                ZFormNS\Form::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/minimal/form'] ],
                ],
                ElementNS\Text::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/minimal/text'] ],
                ],
                ElementNS\Select::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/minimal/select'] ],
                ],
                ElementNS\Radio::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/minimal/radio'] ],
                ],
            ],
            'table' => [
                ZFormNS\Form::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/list'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/table'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/form'] ],
                ],
                ElementNS\Collection::class => [
                    ['name' => HelperNS\FormElementView::class, 'options' => [
                            'template' => '/FormElementDecorators/default/list',
                            'branch' => 'table_tr'
                    ]],
                    //[ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row-fieldset']],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/table/row-collection',
                        'branch' => 'table_tr'
                    ]],
                    //[ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/collection'] ],
                ],
                ZFormNS\Fieldset::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/list']],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row-fieldset']],
                ],
                ElementNS\Text::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row'] ],
                ],
                ElementNS\Textarea::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row'] ],
                ],
                ElementNS\Select::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row'] ],
                ],
                ElementNS\Radio::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row'] ],
                ],
                ElementNS\Checkbox::class => [
                    //NB если нужно ревертнуть, тогда поместить это в другую ветку
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row'] ],
                ],
                ElementNS\Password::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row'] ],
                ],
                ElementNS\Hidden::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row-hidden'] ],
                ],
                ElementNS\Submit::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/submit']],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row-colspan'] ],
                ],
                ElementNS\Button::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/button']],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row-colspan'] ],
                ],
                ElementNS\Date::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row'] ],
                    //[ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row-date'] ],
                ],
                ElementNS\DateTime::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row'] ],
                    //[ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row-date'] ],
                ]
            ],
            'table_revert' => [
                ElementNS\Checkbox::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row-revert'] ],
                ],
            ],
            'table_tr' => [
                ZFormNS\Fieldset::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/list']],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/table/tag', 'tag' => 'tr'
                    ]],
                ],
                ElementNS\Text::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/text'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/table/tag', 'tag' => 'td',
                        'class' => 'form-grid-element form-grid-text',
                        //'style' => 'border:1px solid #000;',
                    ]],
                ],
                ElementNS\Textarea::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/textarea'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/table/tag', 'tag' => 'td',
                        'class' => 'form-grid-element form-grid-text',
                        //'style' => 'border:1px solid #000;',
                    ]],
                ],
                ElementNS\Select::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/select'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/table/tag', 'tag' => 'td',
                        'class' => 'form-grid-element form-grid-select',
                        //'style' => 'border:1px solid #000;',
                    ]],
                ],
                ElementNS\Radio::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/radio'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/table/tag', 'tag' => 'td',
                        'class' => 'form-grid-element form-grid-radio',
                        //'style' => 'border:1px solid #000;',
                    ]],
                ],
                ElementNS\Hidden::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/hidden'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [
                        'template' => '/FormElementDecorators/table/tag', 'tag' => 'td',
                        'class' => 'form-grid-element form-grid-hidden',
                        'style' => 'display:none;',
                    ]],
                ],
//                ElementNS\Button::class => [
//                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/button']],
//                ],
            ],
            'table_colspan' => [
                ElementNS\Radio::class => [
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/default/radio'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/table/row-colspan'] ],
                ],
            ],
            'jqGrid' => [
                \FormDecorator\Form\SubGridForm::class => [
                    [ 'name' => HelperNS\JqGrid\ColModel::class ],
                    [ 'name' => HelperNS\JqGrid\Params::class ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/jqgrid/subgrid-function'] ],
                    //[ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/jqgrid/grid'] ],
                ],
                ZFormNS\Form::class => [
                    [ 'name' => HelperNS\JqGrid\ColModel::class ],
                    [ 'name' => HelperNS\JqGrid\Params::class ],
                    [ 'name' => HelperNS\JqGrid\SubGrid::class ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/jqgrid/grid-function'] ],
                    [ 'name' => HelperNS\FormElementView::class, 'options' => [ 'template' => '/FormElementDecorators/jqgrid/grid'] ],
                ],
                ElementNS\Text::class => [
                    [ 'name' => HelperNS\JqGrid\ColModel\Text::class ],
                ],
                ElementNS\Textarea::class => [
                    [ 'name' => HelperNS\JqGrid\ColModel\TextArea::class ],
                ],
                ElementNS\Select::class => [
                    [ 'name' => HelperNS\JqGrid\ColModel\Select::class ],
                ],
                ElementNS\Radio::class => [
                    [ 'name' => HelperNS\JqGrid\ColModel\Text::class ],
                ],
                ElementNS\Hidden::class => [
                    [ 'name' => HelperNS\JqGrid\ColModel\Hidden::class ],
                ],
                ElementNS\Checkbox::class => [
                    [ 'name' => HelperNS\JqGrid\ColModel\Checkbox::class ],
                ],
                ElementNS\Date::class => [
                    [ 'name' => HelperNS\JqGrid\ColModel\Date::class],
                ],
                ElementNS\DateTime::class => [
                    [ 'name' => HelperNS\JqGrid\ColModel\DateTime::class],
                ]
            ],
        ]
    ],
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
];