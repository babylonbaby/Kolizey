<?php
namespace Application\Form\Login\Fieldset;

use Zend\Form\Fieldset;
use Zend\Form\Element as ZElementsNS;
use Zend\InputFilter\InputFilterProviderInterface;


class SetPasswordFieldset extends Fieldset implements InputFilterProviderInterface
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
                'type' => 'text',
                'name' => 'userName',
                'options' => [
                    'label' => 'Имя пользователя',
                ],
                'attributes' => [
                    'placeholder' => 'Имя пользователя',
                ],
            ]
        );

        // Add "password" field
        $this->add(
            [
                'name' => 'password',
                'type' => 'text',
                'options' => [
                    'label' => 'Пароль',
                ],
                'attributes' => [
                    'placeholder' => 'Пароль',
                ],
            ]
        );

        // Add the CAPTCHA field
        $this->add(
            [
                'type' => 'captcha',
                'name' => 'captcha',
                'options' => [
                    'label' => 'Введите код с картинки',
                    'captcha' => [
                        'class' => 'Image',
                        'imgDir' => 'public/img/captcha',
                        'suffix' => '.png',
                        'imgUrl' => '/img/captcha/',
                        'imgAlt' => 'CAPTCHA Image',
                        'font' => './data/font/thorne_shaded.ttf',
                        'fsize' => 24,
                        'width' => 350,
                        'height' => 100,
                        'expiration' => 600,
                        'dotNoiseLevel' => 40,
                        'lineNoiseLevel' => 3
                    ],
                ],
                'attributes' => [
                    'placeholder' => 'Введите код с картинки',
                ],
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
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 5,
                            'max' => 64
                        ],
                    ],
                ],
            ],
            'password' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 6,
                            'max' => 64
                        ],
                    ],
                ],
            ],
        ];
    }
}