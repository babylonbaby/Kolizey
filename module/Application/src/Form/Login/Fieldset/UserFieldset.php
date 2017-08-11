<?php
namespace Application\Form\Login\Fieldset;

use Zend\Form\Fieldset;
use Zend\Form\Element as ZElementNS;
use Application\Entity as EntityNS;
use Application\Form\Elements as ElementNS;
use Core\Form\Element as R5ElementNS;
use Core\Hydrator as HydratorNS;
use Application\Hydrator\Login as BaseHydratorNS;
use Zend\InputFilter\InputFilterProviderInterface;
use Core\Form as CoreFormNS;

class UserFieldset extends Fieldset implements
    HydratorNS\HydratorNameAwareInterface,
    InputFilterProviderInterface
{
    use HydratorNS\HydratorNameAwareTrait;
    use CoreFormNS\InputFilterSpecificationTrait;
    use CoreFormNS\IsRequiredAwareTrait;

    private $inputFilterSpecification;

    /**
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->setHydratorName(BaseHydratorNS\Edit::class);
    }

    public function init()
    {
// Add "lastName" field
        $this->add(
            [
                'name' => 'lastName',
                'type' => 'text',
                'options' => [
                    'label' => 'Фамилия',
                ],
                'attributes' => [
                    'class' => 'form-control',
                    'placeholder' => 'Фамилия',
                ],
            ]
        );

        // Add "firstName" field
        $this->add(
            [
                'name' => 'firstName',
                'type' => 'text',
                'options' => [
                    'label' => 'Имя',
                ],
                'attributes' => [
                    'class' => 'form-control',
                    'placeholder' => 'Имя',
                ],
            ]
        );

        // Add "middleName" field
        $this->add(
            [
                'name' => 'middleName',
                'type' => 'text',
                'options' => [
                    'label' => 'Отчество',
                ],
                'attributes' => [
                    'class' => 'form-control',
                    'placeholder' => 'Отчество',
                ],
            ]
        );

        // Add "role" field
        $this->add([
                'name' => 'roleId',
                'type' => ElementNS\UserRole::class,
                'options' => [
                    'label' => 'Роль',
                    'required' => true,
                ],
            ]);

        // Add "login" field
        $this->add(
            [
                'name' => 'userName',
                'type' => 'text',
                'options' => [
                    'label' => 'Логин',
                ],
                'attributes' => [
                    'class' => 'form-control',
                    'placeholder' => 'Логин',
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
                    'class' => 'form-control',
                    'placeholder' => 'Пароль',
                ],
            ]
        );
        $this->initInputFilterSpecification();
        $this->setRequiredAttToElements();
    }

    public function initInputFilterSpecification()
    {
        $this->inputFilterSpecification = [
            'lastName' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
            ],
            'firstName' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
            ],
            'login' => [
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

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return $this->trimInputFilterSpecification($this->inputFilterSpecification);
    }

    /**
     * @param array $spec
     * @return $this
     */
    public function setInputFilterSpecification(array $spec)
    {
        $this->inputFilterSpecification = $spec;
        return $this;
    }

}