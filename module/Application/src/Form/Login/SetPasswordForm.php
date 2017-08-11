<?php
namespace Application\Form\Login;

use FormDecorator\View\Helper\JqGrid as JqGridNS;
use Application\Form\Login\Fieldset\SetPasswordFieldset as BaseFieldset;
use Core\Form\Element\ButtonWrapper;
use Zend\Form\Form as BaseForm;
use Zend\InputFilter\InputFilter;
use Zend\Json\Expr;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\InputFilter\InputFilterProviderInterface;

class SetPasswordForm extends BaseForm implements InputFilterProviderInterface
{
    public function init()
    {
        parent::init();

        $this->add([
                'type' => BaseFieldset::class,
                'name' => 'base',
            ]);
        $this->setBaseFieldset($this->get('base'));

        // Add the buttons
        $this->add(
            [
                'type' => 'collection',
                'name' => 'buttons',
            ]
        );

        $buttons = $this->get('buttons');

        // Add the Save button
        $buttons->add(
            [
                'type' => 'submit',
                'name' => 'save',
                'attributes' => [
                    'value' => 'Установить пароль',
                    'id' => 'save',
                ],
            ]
        );

        // Add the Close button
        $buttons->add(
            [
                'type' => 'button',
                'options' => [
                    'label' => 'Закрыть',
                ],
                'name' => 'close',
            ]
        );

        // Set POST method for this form
        $this->setAttribute('method', 'post');
    }

    /**
     * @return AbstractPluginManager
     */
    public function getFormElementManager()
    {
        return $this->getFormFactory()->getFormElementManager();
    }

    public function getInputFilterSpecification()
    {
        return [];
    }
}

