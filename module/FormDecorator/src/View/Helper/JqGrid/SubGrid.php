<?php
namespace FormDecorator\View\Helper\JqGrid;
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 29.01.17
 * Time: 21:11
 */

use Zend\View\Helper\AbstractHelper as BaseHelper;
use Zend\View\HelperPluginManager;
use Zend\Form\Element as BaseElement;
use FormDecorator\Form\SubGridForm;
use Zend\Json\Expr;

class SubGrid extends BaseHelper
{
    /**
     * @var array
     */
    protected $config;
    /**
     * @var HelperPluginManager
     */
    protected $helperPM;

    /**
     * @var array ветки с конфигурацией рендеринга элементов
     */
    protected $branchesConfig;

    public function __construct(HelperPluginManager $helperPM, $config, array $options = [])
    {
        $this->helperPM = $helperPM;
        $this->config = $config;
        if (array_key_exists('decoratorBranch', $this->config) == false) {
            throw new \Exception('Missing "decoratorBranch" section in confihguration');
        }
        $this->branchesConfig = $this->config['decoratorBranch'];
    }

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
    public function render(BaseElement $formElement, $branch, $mode='default', $content = array(), array $options = [])
    {
        $ret = $content;
        /** @var \Zend\Form\Form $formElement */
        foreach ($formElement->getFieldsets() as $element) {
            if ($element instanceof SubGridForm) {
                $res = $this->renderSubGrid($element, $branch, $mode, $content, $options);
                $ret = array_merge($content, $res);
                break;
            }
        }
        return $ret;
    }

    protected function renderSubGrid(SubGridForm $element, $branch, $mode='default', $content = array(), array $options = [])
    {
        /** @var \FormDecorator\View\Helper\FormElementBranch $renderer */
        $renderer = $this->helperPM->get('formBranchRender');
        $renderResult = $renderer->render($element, $branch, $mode, [], $options);

        $ret = [
            'subGrid' => true,
            'subGridRowExpanded' => new Expr($renderResult)
        ];
        return $ret;
    }
}