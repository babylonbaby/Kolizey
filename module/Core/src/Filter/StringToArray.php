<?php
namespace Core\Filter;

use Zend\Filter\AbstractFilter as AbstractFilter;
use Traversable;

/**
 * разрезает строку по заданному разделителю
 * Class StringToArray
 * @package Core\Filter
 */
class StringToArray extends AbstractFilter
{
    protected $separator = '@@';
    protected $isTrim = true;

    public function __construct($options = array()) {
        $this->setOptions($options);
        //$a = 1;
    }

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        $items = explode($this->getSeparator(), $value);
        foreach($items as &$item) {
            $item = $this->postProcessItem($item);
        }

        return $items;
    }

    /**
     * возвращает разделитель для разрезания на массив
     * @return string
     */
    protected function getSeparator()
    {
        $opt = $this->getOptions();
        if (array_key_exists('separator', $opt)) {
            return $opt['separator'];
        }
        return $this->separator;
    }

    protected function postProcessItem($item)
    {
        if ($this->isTrim) {
            $item = trim($item);
        }
        return $item;
    }

    /**
     * @param boolean $isTrim
     * @return self
     */
    public function setIsTrim($isTrim)
    {
        if ($isTrim === null) {
            return;
        }
        $this->isTrim = $isTrim;
        return $this;
    }

    /**
     * @param string $separator
     * @return self
     */
    public function setSeparator($separator)
    {
        if ($separator == '') {
            return;
        }
        $this->separator = $separator;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsTrim()
    {
        return $this->isTrim;
    }
} 