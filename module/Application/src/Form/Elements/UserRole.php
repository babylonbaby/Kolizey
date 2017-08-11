<?php
namespace Application\Form\Elements;

use DoctrineORMModule\Form\Element\EntitySelect;
use Application\Entity;

class UserRole extends EntitySelect
{
    protected $defaultOptions = [
        'target_class'   => Entity\Rbacrole::class,
        'property'       => 'title',
        'find_method' => [
            'name' => 'findBy',
            'params' => [
                'criteria' => [],
                'orderBy' => [
                    'name' => 'ASC'
                ]
            ],
        ],
    ];

    public function __construct($name = null, $options = [])
    {
        $opt = array_merge($this->defaultOptions, $options);
        parent::__construct($name, $opt);
    }
}