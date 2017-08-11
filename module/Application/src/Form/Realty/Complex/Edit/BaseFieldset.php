<?php
namespace Application\Form\Realty\Complex\Edit;

use Zend\Form\Fieldset;
use Zend\Form\Element as ZElementsNS;
use Zend\Validator;
use Application\Entity as EntityNS;
use Application\Form\Elements as ElementNS;
use Core\Form\Element as Rn5ElementNS;
use Core\Hydrator as HydratorNS;
use Application\Hydrator\Realty as BaseHydratorNS;
use Zend\InputFilter\InputFilterProviderInterface;
use Core\Form as Rn5CoreFormNS;

class BaseFieldset extends Fieldset implements
    HydratorNS\HydratorNameAwareInterface,
    InputFilterProviderInterface
{
    use HydratorNS\HydratorNameAwareTrait;
    use Rn5CoreFormNS\InputFilterSpecificationTrait;
    use Rn5CoreFormNS\IsRequiredAwareTrait;

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
        $this->add([
                'name' => 'name',
                'type' => ZElementsNS\Text::class,
                'options' => [
                    'label' => 'Название',
                    'required' => true,
                ],
            ]);

        $this->add([
                'name' => 'address',
                'type' => ZElementsNS\Text::class,
                'options' => [
                    'label' => 'Адрес',
                    'required' => true,
                ],
            ]);

        $this->add([
                'name' => 'distance',
                'type' => ZElementsNS\Text::class,
                'options' => [
                    'label' => 'Расстояние от МКАД',
                    'required' => true,
                ],
            ]);

        $this->add([
                'name' => 'commissioning',
                'type' => ZElementsNS\Date::class,
                'options' => [
                    'label' => 'Ввод в эксплуатацию',
                    'required' => true,
                ],
            ]);

        $this->add([
                'name' => 'shortDescription',
                'type' => ZElementsNS\Text::class,
                'options' => [
                    'label' => 'Краткое описание',
                    'required' => true,
                ],
            ]);

        $this->add([
                'name' => 'longDescription',
                'type' => ZElementsNS\Textarea::class,
                'options' => [
                    'label' => 'Описание',
                    'required' => true,
                ],
            ]);

        $this->add([
                'name' => 'square',
                'type' => ZElementsNS\Text::class,
                'options' => [
                    'label' => 'Площадь',
                    'required' => true,
                ],
            ]);
        $this->add([
                'name' => 'price',
                'type' => ZElementsNS\Text::class,
                'options' => [
                    'label' => 'Цена за кв.м.',
                    'required' => true,
                ],
            ]);
        $this->add([
                'name' => 'transport',
                'type' => ZElementsNS\Textarea::class,
                'options' => [
                    'label' => 'Транспорт',
                    'required' => true,
                ],
            ]);
        $this->add([
                'name' => 'location',
                'type' => ZElementsNS\Text::class,
                'options' => [
                    'label' => 'Координаты для карты',
                    'required' => true,
                ],
            ]);


        $this->initInputFilterSpecification();
        $this->setRequiredAttToElements();
    }

    public function initInputFilterSpecification()
    {
        $this->inputFilterSpecification = [
            'name' => [
                'required' => true,
                'filters' => [
                    [ 'name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'StringLength', 'options' => ['min'=> 1, 'max' => 255]]
                ]
            ],
            'address' => [
                'required' => true,
                'filters' => [
                    [ 'name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'StringLength', 'options' => ['min'=> 1, 'max' => 255]]
                ]
            ],
            'distance' => [
                'required' => true,
                'filters' => [
                    [ 'name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'StringLength', 'options' => ['min'=> 1, 'max' => 10]]
                ]
            ],
            'commissioning' => [
                'required' => true,
            ],
            'shortDescription' => [
                'required' => true,
                'filters' => [
                    [ 'name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'StringLength', 'options' => ['min'=> 1, 'max' => 255]]
                ]
            ],
            'longDescription' => [
                'required' => true,
                'filters' => [
                    [ 'name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'StringLength', 'options' => ['min'=> 1, 'max' => 10000]]
                ]
            ],
            'square' => [
                'required' => true,
                'filters' => [
                    [ 'name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'StringLength', 'options' => ['min'=> 1, 'max' => 50]]
                ]
            ],
            'price' => [
                'required' => true,
                'filters' => [
                    [ 'name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'StringLength', 'options' => ['min'=> 1, 'max' => 10]]
                ]
            ],
            'transport' => [
                'required' => true,
                'filters' => [
                    [ 'name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'StringLength', 'options' => ['min'=> 1, 'max' => 255]]
                ]
            ],
            'location' => [
                'required' => true,
                'filters' => [
                    [ 'name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'StringLength', 'options' => ['min'=> 1, 'max' => 255]]
                ]
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