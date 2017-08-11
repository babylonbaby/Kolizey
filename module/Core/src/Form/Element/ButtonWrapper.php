<?php
namespace Core\Form\Element;

use Zend\Form\Element;
use Zend\Form\ElementInterface;
use Zend\Form\ElementPrepareAwareInterface;
use Zend\Form\Factory;
use Zend\Form\FormInterface;

class ButtonWrapper extends Element implements ElementPrepareAwareInterface
{
    /**
     * @var array
     */    
    protected $buttons = array();

    public function add($element)
    {
        if (is_array($element)) {
            $factory = new Factory();
            $element = $factory->create($element);
        }
        if (! $element instanceof ElementInterface) {
            throw new \Exception("Элемент должен реализовывать интерфейс ElementInterface");
        }
        
        $name = $element->getName();
        if (null === $name || '' === $name) {
            throw new \InvalidArgumentException(sprintf(
                '%s: у элемента нет имени',
                __METHOD__
            ));
        }
        
        $this->buttons[$name] = $element;
    }
    
    public function remove($element)
    {
        if(is_string($element) && $this->has($element)) {
            unset($this->buttons[$element]);
        }
    }

    public function has($element)
    {
        if(is_string($element) && isset($this->buttons[$element])) {
            return true;
        }
        return false;
    }

    public function get($element)
    {
        if(is_string($element) && isset($this->buttons[$element])) {
            return $this->buttons[$element];
        }
        return null;
    }
    
    public function getButtons()
    {
        return $this->buttons;
    }

    public function prepareElement(FormInterface $form)
    {
        $this->setAttribute('class', 'buttons-wrapped');
    }
}