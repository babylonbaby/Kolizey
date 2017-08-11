<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 28.09.15
 * Time: 14:14
 */
namespace Core\Form;

use Zend\InputFilter\InputFilterProviderInterface;

trait IsRequiredAwareTrait {
    /**
     * @brief проверяет является ли обязательным атрибут согласно спецификации фильтра
     *
     * @param $filterSpecification
     * @param $field
     * @return bool
     */
    protected function isRequired($filterSpecification, $field)
    {
        if(isset($filterSpecification[$field]['required'])) {
            return  $filterSpecification[$field]['required'];
        }
        return false;
    }

    /**
     * @brief расставляет аттрибут required всем полям, у которых в фильтре поставлено 'required' => true
     */
    public function setRequiredAttToElements()
    {
        $filterSpecification = $this->getInputFilterSpecification();
        $elList = $this->getElements();
        /**
         * @var  $name
         * @var \Zend\Form\ElementInterface $el
         */
        foreach($elList as $name => $el) {
            if($this->isRequired($filterSpecification, $name)) {
                if($el->getAttribute('NotRequired') == true) {
                    //required не ставим, это избавит от проверки на стороне клиента и от звездочки в label
                    continue;
                }
                $el->setAttribute('required', true);
            }
        }
    }
}

