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
use FormDecorator\View\Helper\FormElementBranch;

class ColModel extends BaseHelper
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
    public function __invoke(BaseElement $formElement, $branch, $mode='default', $content=null, array $options = [])
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
    public function render(\Zend\Form\Form $formElement, $branch, $mode='default', $content = null, array $options = [])
    {
        //$chain = $this->getHelperChain($formElement, $realBranch);
        $helperPM = $this->helperPM;
        /** @var FormElementBranch $branchHelper */
        $branchHelper  = $helperPM->get(FormElementBranch::class);

        $baseFieldset = $formElement->getBaseFieldset();
        if (($newBranch = $baseFieldset->getOption('branch')) != null) {
            $realBranch = $newBranch;
        } else {
            $realBranch = $branch;
        }
        $res = [];
        /** @var BaseElement $element */
        foreach ($baseFieldset as $element) {
            $res[] = $branchHelper($element, $realBranch, $mode, $content);
        }
        $content['colModel'] = $res;
        return $content;
    }
}