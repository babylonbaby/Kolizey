<?php
namespace Application\Form\Login\Fieldset;

use Zend\Form\Fieldset;
use Zend\Form\Element as ZElementsNS;
use Zend\InputFilter\InputFilterProviderInterface;


class BaseLoginFieldset extends Fieldset implements InputFilterProviderInterface
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
        // Add "userName" field
        $this->add(
            [
                'name' => 'userName',
                'type' => 'text',
                'options' => [
                    'label' => 'Имя пользователя',
                ],
            ]
        );

        // Add "password" field
        $this->add(
            [
                'type' => 'password',
                'name' => 'password',
                'options' => [
                    'label' => 'Пароль',
                ],
            ]
        );

        // Add "remember_me" field
        $this->add(
            [
                'type' => 'checkbox',
                'name' => 'remember_me',
                'options' => [
                    'label' => 'Запомнить',
                ],
            ]
        );

        // Add "redirect_url" field
        $this->add(
            [
                'type' => 'hidden',
                'name' => 'redirect_url'
            ]
        );

        // Add the CSRF field
        $this->add(
            [
                'type' => 'csrf',
                'name' => 'csrf',
                'options' => [
                    'csrf_options' => [
                        'timeout' => 600
                    ]
                ],
            ]
        );

    }

    public function getInputFilterSpecification()
    {
        return [
            'userName' => [
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 5,
                            'max' => 64
                        ],
                    ],
                ],
            ],
            'password' => [
                'required' => true,
                'filters'  => [
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 6,
                            'max' => 64
                        ],
                    ],
                ],
            ],
            'remember_me' => [
                'required' => false,
                'filters'  => [
                ],
                'validators' => [
                    [
                        'name'    => 'InArray',
                        'options' => [
                            'haystack' => [0, 1],
                        ]
                    ],
                ],
            ],
            'redirect_url' => [
                'required' => false,
                'filters'  => [
                    ['name'=>'StringTrim']
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 0,
                            'max' => 2048
                        ]
                    ],
                ],
            ],
        ];
    }
}