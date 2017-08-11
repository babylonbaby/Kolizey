<?php
namespace Application\Form\Realty\Complex\Edit;

use Core\Form\Element\ButtonWrapper;
use Core\Form\FormValidateTrait;
use Zend\Form\Element as ZElementNS;
use Zend\Form\Form as BaseForm;

class Form extends BaseForm
{
    use FormValidateTrait;
    protected $buttonSpecifications = [];

    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->buttonSpecifications = array_replace($this->buttonSpecifications, [
            'save' => [
                'type' => 'submit',
                'options' => [
                    //'label' => 'Сохранить',
                ],
                'attributes' => [
                    'value' => 'Сохранить',
                    'class' => 'save',
                ],
            ],
            'close' => [
                'type' => ZElementNS\Button::class,
                'options' => [
                    'label' => 'Закрыть',
                ],
                'attributes' => [
                    'class' => 'button',
                ],
            ],
            'msg' => [
                'type' => ZElementNS\Text::class,
                'options' => [
                    'mode' => 'view',
                    'label' => ''
                ],
                'attributes' => [
                    'class' => 'pull-right form-info-message',
                ],
            ],
        ]);
    }

    public function init()
    {
        parent::init();

        $this->add(
            [
                'type' => BaseFieldset::class,
                'name' => 'base',
                'attributes' => [
                    'class' => 'center',
                ]
            ]
        );
        $this->setBaseFieldset($this->get('base'));
        $this->initButtons();
    }

    public function initButtons()
    {
        $btnWrap = new ButtonWrapper('buttons');

        $classAtt = $btnWrap->getAttribute('class');
        $classAtt = $classAtt . ' form-button-wrapper';
        $btnWrap->setAttribute('class', $classAtt);

        $this->add($btnWrap);
        $factory = $this->getFormFactory();

        foreach ($this->getButtonSpecifications() as $name => $spec) {
            $spec['name'] = $name;
            $el = $factory->create($spec);
            $btnWrap->add($el);
        }
    }

    /**
     * @return array
     */
    protected function getButtonSpecifications()
    {
        return $this->buttonSpecifications;
    }

    /**
     * @param array $spec
     * @return $this
     */
    protected function setButtonSpecifications(array $spec)
    {
        $this->buttonSpecifications = $spec;
        return $this;
    }
} 