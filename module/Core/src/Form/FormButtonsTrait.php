<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 05.04.17
 * Time: 10:25
 */

namespace Core\Form;

use Core\Form\Element\ButtonWrapper;

trait FormButtonsTrait
{
    /** @var array спецификация кнопок */
    private $buttonSpecifications = [];

    /**
     * Возвращает спецификацию кнопок
     * @return array
     */
    protected function getButtonSpecifications()
    {
        return $this->buttonSpecifications;
    }

    /**
     * Устанавливает спецификацию кнопок
     * @param array $spec
     * @return $this
     */
    protected function setButtonSpecifications(array $spec)
    {
        $this->buttonSpecifications = $spec;
        return $this;
    }

    /**
     * добавляет кнопки в элемент (форму или филдсет)
     * @return ButtonWrapper
     * @throws \Exception
     */
    public function initButtons()
    {
        $btnWrap = new ButtonWrapper('buttons');
        $this->add($btnWrap);
        $factory = $this->getFormFactory();

        foreach ($this->getButtonSpecifications() as $name => $spec) {
            $spec['name'] = $name;
            $el = $factory->create($spec);
            $btnWrap->add($el);
        }
        return $btnWrap;
    }
} 