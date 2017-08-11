<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 01.02.16
 * Time: 16:44
 */

namespace Core\Form;

use Zend\Form\Fieldset;

trait FieldsetExtendTrait
{
    /**
     * @param Fieldset $fieldset - модифицируемый филдсет/форма
     * @param string $whatElementName - имя перемещаемого элемента
     * @param string $afterWhatElementName - имя элемента, после которого вставляем перемещаемый элемент
     */
    public function moveAfter(Fieldset $fieldset, $whatElementName, $afterWhatElementName)
    {
        if ($this->canMoveAfter($fieldset, $whatElementName, $afterWhatElementName) == false) {
            return false;
        }

        $elArr = [];
        $whatElement = null;
        /** @var \Zend\Form\Element $element */
        foreach ($fieldset as $element) {
            $name = $element->getName();
            if ($name == $whatElementName) {
                $whatElement = $element;
            } else {
                $elArr[$name] = $element;
            }
            //$fieldset->remove($name);
        }

        $fieldset->remove($whatElementName);
        foreach ($elArr as $name => $element) {
            //NB при использовании итератора удалять нельзя, поэтому отдельный цикл
            $fieldset->remove($name);
        }

        if ($afterWhatElementName == null) {
            $fieldset->add($whatElement);
        }
        foreach ($elArr as $name => $element) {
            $fieldset->add($element);
            if ($name == $afterWhatElementName) {
                $fieldset->add($whatElement);
            }
        }
        return true;
    }

    /**
     * проверка возможности переместить элемент
     * @param Fieldset $fieldset
     * @param $whatElementName
     * @param $afterWhatElementName
     * @return bool
     */
    private function canMoveAfter(Fieldset $fieldset, $whatElementName, $afterWhatElementName)
    {
        if ($fieldset->has($whatElementName) == false) {
            return false;
        }
        if ($afterWhatElementName != null && $fieldset->has($afterWhatElementName) == false) {
            return false;
        }
        return true;
    }
}