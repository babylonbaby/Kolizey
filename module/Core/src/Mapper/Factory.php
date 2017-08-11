<?php
namespace Core\Mapper;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManager as EntityManager;
use Core\Mapper\Base as BaseMapper;
use Core\Mapper\BaseInterface as BaseMapperInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

class Factory implements AbstractFactoryInterface
{
    /**
     * Determine if we can create a service with name
     *
     * @param ContainerInterface $container
     * @param $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $nameByPart = explode(':', $requestedName);
        if (count($nameByPart) == 2)
            return true;

        return false;
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $serviceLocator
     * @param  string             $requestedName
     * @param  null|array         $options
     * @throws \Exception
     * @return object
     */
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        try {
            $repository = $entityManager->getRepository($requestedName);
        } catch(\Exception $e) {
            throw new ServiceNotCreatedException('Not fount entity repository by namespace ' . $requestedName);
        }

        $requestedNameByPart = explode(':', $requestedName);
        $configuration = $entityManager->getConfiguration();
        $pathToEntity = $configuration->getEntityNamespace($requestedNameByPart[0]);

        $pattern = '/^(?P<namespace>[a-zA-Z0-9\\\]*)Entity.*$/';
        if (preg_match($pattern, $pathToEntity, $matches)) {
            $pathToMapper = $matches['namespace'] . 'Mapper\\' . $requestedNameByPart[1];
            if (class_exists($pathToMapper)) {
                $mapper = new $pathToMapper();
                if (!$mapper instanceof BaseMapperInterface)
                    throw new ServiceNotCreatedException('Mapper class can be instance \Core\Mapper\BaseInterface');
            } else {
                $mapper = new BaseMapper();
            }
        } else {
            throw new ServiceNotCreatedException('Wrong namespace for entity - ' . $pathToEntity);
        }
        $mapper->setRepository($repository);
        return $mapper;
    }
}