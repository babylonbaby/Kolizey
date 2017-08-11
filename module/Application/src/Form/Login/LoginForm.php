<?php
namespace Application\Form\Login;

use Application\Form\Login\Fieldset\BaseLoginFieldset as BaseFieldset;
use Zend\Form\Form as BaseForm;
use Zend\InputFilter\InputFilter;
use FormDecorator\View\Helper\JqGrid as JqGridNS;
use Zend\Json\Expr;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * This form is used to collect user's login, password and 'Remember Me' flag.
 */
class LoginForm extends BaseForm implements InputFilterProviderInterface
{
    public function init()
    {
        parent::init();

        $this->add([
                'type' => BaseFieldset::class,
                'name' => 'base',
            ]);
        $this->setBaseFieldset($this->get('base'));

        // Add the Submit button
        $this->add(
            [
                'type' => 'submit',
                'name' => 'submit',
                'attributes' => [
                    'value' => 'Авторизоваться',
                    'id' => 'submit',
                ],
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

