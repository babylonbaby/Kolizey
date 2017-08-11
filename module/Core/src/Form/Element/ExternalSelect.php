<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 01.03.17
 * Time: 0:06
 */
namespace Core\Form\Element;

use Zend\Form\Element;

/**
 * Внешний селектор
 * Class ExternalSelect
 * @package Core\Form\Element
 */
class ExternalSelect extends Element
{
    protected $onClick = '';

    public function getOnClick()
    {
        return $this->onClick;
    }

    public function setOptions($options)
    {
        parent::setOptions($options);
        if (array_key_exists('onClick', $options)) {
            $this->onClick = $options['onClick'];
        }
    }
}
