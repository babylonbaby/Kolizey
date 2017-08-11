<?php
namespace Core;

use Core\Module\AbstractModule;
use Core\ServiceManager\ServiceLocatorAwareInterface;
use Core\ServiceManager\ServiceManagerAwareInterface;
use Core\Utils\XmlConverter as XmlConverter;
use Core\EntityManager\EntityManagerAwareInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ServiceManager\ServiceManager;
use Doctrine\ORM\EntityManager;
use Core\Initializer as InitializerNS;

class Module extends AbstractModule implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array (
            'abstract_factories' => array (
                'Core\Mapper\Factory',
            ),
            'initializers' => array(
                'entityManager' => InitializerNS\EntityManager::class,
//                'entityManager' => function ($serviceManager, $service) {
//                    /** @var ServiceManager $serviceManager */
//                    if ($service instanceof EntityManagerAwareInterface) {
//                        /** @var EntityManager $entityManager */
//                        $entityManager = $serviceManager->get('Doctrine\ORM\EntityManager');
//                        $service->setEntityManager($entityManager);
//                    }
//                },
                'serviceLocator' => InitializerNS\ServiceLocator::class,
//                'serviceLocator' => function ($serviceManager, $service) {
//                    if ($service instanceof ServiceLocatorAwareInterface) {
//                        $service->setServiceLocator($serviceManager);
//                    }
//                },
                'serviceManager' => function ($serviceManager, $service) {
                    if ($service instanceof ServiceManagerAwareInterface) {
                        $service->setServiceManager($serviceManager);
                    }
                }
            ),
            'invokables' => array(
                'Core/Service/Ftp' => 'Core\Service\FtpService',
                'Core/Mapper/Base' => 'Core\Mapper\Base'
            ),
            'services' => array(
                'Core/Utils/XmlConverter' => new XmlConverter(),
            ),
        );
    }
}
