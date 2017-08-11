<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 14.02.17
 * Time: 11:28
 */
namespace Core\Initializer;

use Zend\ServiceManager\Initializer\InitializerInterface;
use Interop\Container\ContainerInterface;
use Core\Hydrator\HydratorNameAwareInterface;

class Hydrator implements InitializerInterface
{
    /**
     * @var \Zend\Hydrator\HydratorPluginManager
     */
    private $hydratorManager;
    /**
     * Initialize the given instance
     *
     * @param  \Interop\Container\ContainerInterface $container
     * @param  object $instance
     * @return void
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        if (($instance instanceof HydratorNameAwareInterface) == false) {
            return;
        }

        if ($this->hydratorManager == null) {
            $this->hydratorManager = $container->get('HydratorManager');
        }
        /** @var HydratorNameAwareInterface $instance */
        $hydratorName = $instance->getHydratorName();
        $hydrator = $this->hydratorManager->get($hydratorName);
        $instance->setHydrator($hydrator);
    }

} 