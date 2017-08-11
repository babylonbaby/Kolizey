<?php
namespace StateMachine;

use Zend\ModuleManager\Feature as FeatureNS;
use Zend\ModuleManager as ModuleManagerNS;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ModuleManager\Listener\ServiceListenerInterface;
use StateMachine\Functor as FunctorNS;

class Module implements
    FeatureNS\ConfigProviderInterface,
    FeatureNS\AutoloaderProviderInterface,
    FeatureNS\ServiceProviderInterface,
    FeatureNS\InitProviderInterface
{
    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return include __DIR__ . '/config/services.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        $config = [];
        $autoloadFile = __DIR__ . '/autoload_classmap.php';
        if (file_exists($autoloadFile)) {
            $config['Zend\Loader\ClassMapAutoloader'] = [
                $autoloadFile
            ];
        }
        $config['Zend\Loader\StandardAutoloader'] = [
            'namespaces' => [
                __NAMESPACE__ => __DIR__ . '/src',
            ],
        ];
        return $config;
    }

    /**
     * @param ModuleManagerNS\ModuleManagerInterface $manager
     *
     * @throws \InvalidArgumentException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function init(ModuleManagerNS\ModuleManagerInterface $manager)
    {
        if (!$manager instanceof ModuleManagerNS\ModuleManager) {
            $errMsg = sprintf('Module manager not implement %s', 'ModuleManager');
            throw new \InvalidArgumentException($errMsg);
        }

        /** @var ServiceLocatorInterface $sm */
        $sm = $manager->getEvent()->getParam('ServiceManager');

        if (!$sm instanceof ServiceLocatorInterface) {
            $errMsg = sprintf('Service locator not implement Zend\ServiceManager\ServiceLocatorInterface');
            throw new \InvalidArgumentException($errMsg);
        }
        /** @var ServiceListenerInterface $serviceListener */
        $serviceListener = $sm->get('ServiceListener');
        if (!$serviceListener instanceof ServiceListenerInterface) {
            $errMsg = sprintf('ServiceListener not implement %s', 'ServiceListenerInterface');
            throw new \InvalidArgumentException($errMsg);
        }

        $serviceListener->addServiceManager(
            'StateMachine\Functor\FunctorPluginManagerInterface',
            FunctorNS\FunctorProviderInterface::CONFIG_KEY,
            'StateMachine\Functor\FunctorProviderInterface',
            'getFunctorProviderConfig'
        );
    }
}
