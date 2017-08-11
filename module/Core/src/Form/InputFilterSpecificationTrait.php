<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 09.02.16
 * Time: 18:46
 */

namespace Core\Form;

use Zend\Form\FieldsetInterface;

/**
 * Убирает из спецификации фильтра, задаваемого InputFilterProviderInterface те ключи,
 * которым нет соответствующих элементов.
 * Class InputFilterProviderTrait
 * @package Plan\Form
 */
trait InputFilterSpecificationTrait
{
    public function trimInputFilterSpecification(array $inputFilterData)
    {
        $ret = [];
        /** @var FieldsetInterface $element */
        foreach ($this as $element) {
            $name = $element->getName();
            if (array_key_exists($name, $inputFilterData) == false) {
                continue;
            }

            if ($element->hasAttribute('disabled')) {
                $inputFilterData[$name]['required'] = false;
            }
            $ret[$name] = $inputFilterData[$name];
        }
        return $ret;
    }
}
