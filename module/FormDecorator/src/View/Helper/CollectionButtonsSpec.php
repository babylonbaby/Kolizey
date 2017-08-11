<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 04.04.17
 * Time: 14:09
 */

namespace FormDecorator\View\Helper;

use Zend\View\Helper\AbstractHelper as BaseHelper;
use Zend\Form\ElementInterface as BaseElement;

class CollectionButtonsSpec extends BaseHelper
{
    /**
     * @param BaseElement $formElement
     * @param string $branch
     * @param string $mode - режим показа (просмотр, редактирование и т.п.)
     * @param mixed $content
     * @param array $options
     * @return $this|string
     */
    public function __invoke(BaseElement $formElement, $mode='default')
    {
        return $this->render($formElement, $mode);
    }

    /**
     * @param \Zend\Form\Element\Collection $element
     * @param string $branch
     * @param string $mode - режим показа (просмотр, редактирование и т.п.)
     * @param mixed $content
     * @param array $options
     * @return mixed
     */
    public function render(BaseElement $element, $mode='default')
    {
        if (($buttonSpec = $element->getOption('buttons_specification')) == null) {
            $buttonSpec = [];
        }
        if ($mode == 'view') {
            if (array_key_exists('addButton', $buttonSpec)) {
                unset($buttonSpec['addButton']);
            }
        } else {
            if ($element->allowAdd() && $element->getOption('add_button') != null) { //добавление кнопки
                if (array_key_exists('addButton', $buttonSpec) == false) {
                    $buttonSpec = ['addButton' => [
                        'label' => 'Добавить',
                        'attributes' => [
                            'class' => 'addButton'
                        ]
                    ]];
                }
            }
        }
        return $buttonSpec;
    }
} 