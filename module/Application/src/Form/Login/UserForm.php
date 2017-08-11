<?php
namespace Application\Form\Login;

use Zend\Form\Form as BaseForm;
use Core\Form\FormValidateTrait;
use Application\Form\Login\Fieldset\UserFieldset as BaseFieldset;


class UserForm extends BaseForm
{
    use FormValidateTrait;

    public function init()
    {
        parent::init();

        $this->add([
                'type' => BaseFieldset::class,
                'name' => 'base',
            ]);
        $this->setBaseFieldset($this->get('base'));
    }
} 