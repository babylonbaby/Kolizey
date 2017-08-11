<?php
namespace Application\Hydrator\Factory;

use DoctrineORMModule\Service\DoctrineObjectHydratorFactory;
use Interop\Container\ContainerInterface;
use Application\Hydrator\UserHydrator;

class FactoryUserHydrator extends DoctrineObjectHydratorFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UserHydrator($container->get('doctrine.entitymanager.orm_default'));
    }

}
