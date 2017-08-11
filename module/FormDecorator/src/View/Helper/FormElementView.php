<?php
namespace FormDecorator\View\Helper;

use Zend\View\Helper\AbstractHelper as BaseHelper;
use Zend\Form\Element as BaseElement;
use Zend\View\HelperPluginManager;

class FormElementView extends BaseHelper
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
    public function render(BaseElement $formElement, $branch, $mode='default', $content = '', array $options = [])
    {
        if (array_key_exists('template', $options) == false) {
            $msg = sprintf('Missing "template" key in options = %s, branch="%s" classname="%s"',
                print_r($options, 1),
                $branch,
                get_class($formElement)
            );
            throw new \InvalidArgumentException($msg);
        }
        $viewScript = $options['template'];

        $view = $this->getView();
        $content = $view->render(
            $viewScript,
            [
                'formElement' => $formElement,
                'branch' => $branch,
                'mode' => $mode,
                'content' => $content,
                'options' => $options
            ]
        );
        return $content;
    }

}