<?php
namespace Core\Filter;

use Zend\Filter\AbstractFilter as AbstractFilter;


/**
 * Class FileFilter
 * Фильтрует POST файлов от IE8
 * @package Core\Filter
 */
class FileFilter extends AbstractFilter{

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        if(isset($value["name"]) && $value["name"] == ''){
            $value = '';
        }

        return $value;
    }
} 