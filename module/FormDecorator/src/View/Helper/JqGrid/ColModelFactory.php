<?php
/**
 * Created by F-Technology.
 * User: Vasyankin Alexey
 * Date: 07.02.2017
 * Time: 10:11
 * e-mail: vasyankin@f-technology.ru
 */
namespace FormDecorator\View\Helper\JqGrid;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ColModelFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $configKey = 'FormElementDecorators';
        $config = $container->get('config');

        if (array_key_exists($configKey, $config) == false) {
            throw new \InvalidArgumentException('missing config section '. $configKey);
        }
        $viewPM = $container->get('ViewHelperManager');
        $ret = new ColModel($viewPM, $config[$configKey]);
        return $ret;
    }
}