<?php
namespace Application\Form\Realty\Complex\Grid;

use Zend\Form\Fieldset;
use Zend\Form\Element as ZElementNS;
use Application\Entity as EntityNS;
use Zend\Json\Expr;
use Application\Form\Elements as ElementNS;
use Core\Form\Element as Rn5ElementNS;

class BaseFieldset extends Fieldset
{
    /**
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
    }

    public function init()
    {
        $this->add(
            [
                'name' => 'id',
                'type' => ZElementNS\Hidden::class,
                'options' => [
                    'label' => 'id',
                    'required' => true,
                    'sql_expr' => 't.id',
                    'jqGrid' => [
                        'classes' => 'grid-cell',
                        'searchoptions' => [
                            'sopt' => ["cn", 'eq', 'ne', 'bw', "bn", "ew", "en", "nc"],
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name' => 'name',
                'type' => ZElementNS\Text::class,
                'options' => [
                    'label' => 'Название',
                    'required' => true,
                    'sql_expr' => 't.name',
                    'jqGrid' => [
                        'classes' => 'grid-cell',
                        'searchoptions' => [
                            'sopt' => ["cn", 'eq', 'ne', 'bw', "bn", "ew", "en", "nc"],
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name' => 'address',
                'type' => ZElementNS\Text::class,
                'options' => [
                    'label' => 'Адрес',
                    'required' => true,
                    'sql_expr' => 't.address',
                    'jqGrid' => [
                        'classes' => 'grid-cell',
                        'searchoptions' => [
                            'sopt' => ["cn", 'eq', 'ne', 'bw', "bn", "ew", "en", "nc"],
                        ],
                    ],
                ],
            ]
        );


        $this->add(
            [
                'name' => 'active',
                'type' => ZElementNS\Select::class,
                'options' => [
                    'label' => 'Статус',
                    'required' => true,
                    'value_options' => [
                        '1' => 'Активно',
                        '0' => 'Неактивно',
                    ],
                    'sql_expr' => 'IF(t.del=0, 1, 0)',
                    'jqGrid' => [
                        'formatter' => 'checkboxOnOffFormatter',
                        'classes' => 'grid-cell',
                        'searchoptions' => [
                            'sopt' => ["cn", 'eq', 'ne', 'bw', "bn", "ew", "en", "nc"],
                        ],
                        'width' => 150,
                        'fixed' => true
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name' => 'actions',
                'type' => Rn5ElementNS\ButtonWrapper::class,
                'options' => [
                    'label' => ' ',
                    'sql_expr' => "''",
                    'jqGrid' => [
                        'classes' => 'grid-cell',
                        'width' => 40,
                        'fixed' => true
                    ],
                ]
            ]
        );
        $this->initRowButtons($this->get('actions'));
    }

    public function initRowButtons(Rn5ElementNS\ButtonWrapper $btnWrapper)
    {
        $el = new ZElementNS\Button('update');
        $el->setAttributes(
            [
                'data-url' => '/nedvizhimost/edit-complex',
                'data-ajax' => true,
                'class' => 'glyphicon glyphicon-edit',
                'title' => 'Изменить',
            ]
        );
        $btnWrapper->add($el);
    }
}