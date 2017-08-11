<?php
namespace Core\Module;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

abstract class AbstractModule implements AutoloaderProviderInterface, ConfigProviderInterface
{
    /**
     * @var \ReflectionObject
     */
    private $reflectionObject = null;

    /**
     * @return string
     */
    public function getDir()
    {
        return dirname($this->getReflectionObject()->getFileName());
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->getReflectionObject()->getNamespaceName();
    }

    /**
     * @see \Zend\ModuleManager\Feature\ConfigProviderInterface::getConfig()
     */
    public function getConfig()
    {
        return include $this->getDir() . '/config/module.config.php';
    }

    /**
     * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
     */
    public function getAutoloaderConfig()
    {
        return array(
//            'Zend\Loader\ClassMapAutoloader' => array(
//                $this->getDir() . '/autoload_classmap.php',
//            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    $this->getNamespace() => $this->getDir() . '/src',
                ),
            ),
        );
    }

    /**
     * @return \ReflectionObject
     */
    private function getReflectionObject()
    {
        if (is_null($this->reflectionObject)) {
            $this->reflectionObject = new \ReflectionObject($this);
        }

        return $this->reflectionObject;
    }
}