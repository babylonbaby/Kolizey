<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 08.02.17
 * Time: 15:14
 */

namespace Core\Factory;

use Interop\Container\ContainerInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class AbstractDoctrineObjectHydratorFactory implements AbstractFactoryInterface
{
    private $baseClass = DoctrineObject::class;

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return (is_subclass_of($requestedName, $this->baseClass));
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if ($options === null) {
            $options = [];
        }
        $byValue = true;
        if (isset($options['byValue'])) {
            $byValue = $options['byValue'];
        }

        if (isset($options['objectManager'])) {
            $objectManager = $options['objectManager'];
        } else {
            $objectManager = $container->get('doctrine');
        }

        return new $requestedName($objectManager, $byValue);
    }
} 