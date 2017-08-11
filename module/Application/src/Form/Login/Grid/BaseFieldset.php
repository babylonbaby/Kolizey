<?php
namespace Application\Form\Login\Grid;

use Zend\Form\Fieldset;
use Zend\Form\Element as ZElementNS;
use Application\Entity as EntityNS;
use Zend\Json\Expr;
use Application\Form\Elements as ElementNS;
use Core\Form\Element as R5ElementNS;

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
        $this->add([
                'name' => 'id',
                'type' => ZElementNS\Hidden::class,
                'options' => [
                    'label' => 'id',
                    'required' => true,
                    'sql_expr' => 't.id',
                    'jqGrid' => [
                        'classes' => 'grid-cell',
                        'searchoptions' => [
                            'sopt' => ["cn", 'eq','ne','bw', "bn", "ew", "en", "nc"],
                        ],
                    ],
                ],
            ]);
        $this->add(
            [
                'name' => 'fio',
                'type' => ZElementNS\Text::class,
                'options' => [
                    'label' => 'ФИО',
                    'required' => true,
                    'sql_expr' => "t.fio",
                    'jqGrid' => [
                        'classes' => 'grid-cell',
                    ],
                ],
                'attributes' => [
                    'required' => true,
                ],
            ]
        );

        $this->add(
            [
                'name' => 'userName',
                'type' => ZElementNS\Text::class,
                'options' => [
                    'label' => 'Логин',
                    'required' => true,
                    'sql_expr' => "t.username",
                    'jqGrid' => [
                        'classes' => 'grid-cell',
                    ],
                ],
                'attributes' => [
                    'required' => true,
                ],
            ]
        );

        $this->add(
            [
                'name' => 'role',
                'type' => ZElementNS\Text::class,
                'options' => [
                    'label' => 'Роль',
                    'required' => true,
                    'sql_expr' => "r.title",
                    'jqGrid' => [
                        'classes' => 'grid-cell',
                    ],
                ],
                'attributes' => [
                    'required' => true,
                ],
            ]
        );
        $this->add([
                'name' => 'active',
                'type' => ZElementNS\Select::class,
                'options' => [
                    'label' => 'Статус',
                    'required' => true,
                    'value_options' => [
                        '0' => 'Включено',
                        '1' => 'Выключено',
                    ],
                    'sql_expr' => 'IF(t.active=0, 0, 1)',
                    'jqGrid' => [
                        'formatter' => 'checkboxOnOffFormatter',
                        'classes' => 'grid-cell',
                        'searchoptions' => [
                            'sopt' => ["cn", 'eq','ne','bw', "bn", "ew", "en", "nc"],
                        ],
                        'width' => 100,
                        'fixed' => true
                    ],
                ],
            ]);
        $this->add([
                'name' => 'actions',
                'type' => R5ElementNS\ButtonWrapper::class,
                'options' => [
                    'label' => ' ',
                    'sql_expr' => "''",
                    'jqGrid' => [
                        'classes' => 'grid-cell',
                        'width' => 40,
                        'fixed' => true
                    ],
                ]
            ]);
        $this->initRowButtons($this->get('actions'));
    }

    public function initRowButtons(R5ElementNS\ButtonWrapper $btnWrapper)
    {
        $el = new ZElementNS\Button('update');
        $el->setAttributes([
                'data-url' => '/user/edit',
                'data-ajax' => true,
                'class' => 'glyphicon glyphicon-edit',
                'title' => 'Изменить',
            ]);
        $btnWrapper->add($el);

//        $el = new ZElementNS\Button('remove');
//        $el->setAttributes([
//            'data-url' => '/kpp/dict/okei-grp/remove',
//            'data-ajax' => true,
//            'class' => 'glyphicon glyphicon-remove',
//            'title' => 'Удалить',
//        ]);
//        $btnWrapper->add($el);
    }
}