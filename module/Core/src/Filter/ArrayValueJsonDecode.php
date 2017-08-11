<?php
namespace Core\Filter;

use Zend\Filter\AbstractFilter as AbstractFilter;
use Traversable;

/**
 * разрезает строку по заданному разделителю
 * Class StringToArray
 * @package Core\Filter
 */
class ArrayValueJsonDecode extends AbstractFilter
{
    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        if (is_array($value) == false) {
            return $value;
        }
        foreach($value as $k => &$item) {
            $value[$k] = json_decode($item, true);
        }

        return $value;
    }

} 