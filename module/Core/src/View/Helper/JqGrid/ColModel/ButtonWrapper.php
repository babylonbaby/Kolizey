<?php
namespace Core\View\Helper\JqGrid\ColModel;

use Zend\View\Helper\AbstractHelper as BaseHelper;
use Zend\View\HelperPluginManager;
use Zend\Form\Element as BaseElement;
use Zend\Json\Expr;

class ButtonWrapper extends BaseHelper
{

    /**
     * @param BaseElement $formElement
     * @param string $branch
     * @param string $mode - режим показа (просмотр, редактирование и т.п.)
     * @param mixed $content
     * @param array $options
     * @return $this|string
     */
    public function __invoke(BaseElement $formElement, $branch, $mode='default', $content='', array $options = [])
    {
        return $this->render($formElement, $branch, $mode, $content, $options);
    }

    /**
     * @param BaseElement $formElement
     * @param string $branch
     * @param string $mode - режим показа (просмотр, редактирование и т.п.)
     * @param mixed $content
     * @param array $options
     * @return mixed
     */
    public function render(BaseElement $formElement, $branch, $mode='default', $content = '', array $options = [])
    {
        $res = [
            "edittype" => "text",
            "index" => $formElement->getName(),
            "label" => $formElement->getLabel(),
            "name" => $formElement->getName(),
            'search' => false,
            "stype" => "text",
            "formatter" => 'buttonWrapperFormatter',
        ];

        if (($opt = $formElement->getOption('jqGrid')) != null) {
            $res = array_replace_recursive($res, $opt);
        }

        return $res;
    }
}
