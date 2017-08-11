<?php
namespace StateMachine\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class ArchiverAbstractFactory
 * @package ImcPlan\PlanIntegration\Factory
 */
class StateMachineAbstractFactory implements AbstractFactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        if (is_subclass_of($requestedName, 'StateMachine\Service\StateMachine')) {
            return true;
        }
        return false;
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $em */
        $em = $container->get('archiverDoctrineEm');
        $validatorPM = $container->get('ValidatorManager');
        /** @var \StateMachine\Functor\FunctorPluginManagerInterface $functorPM */
        $functorPM = $container->get('StateMachine\Functor\FunctorPluginManagerInterface');

        return new $requestedName(
            $em,
            $validatorPM,
            $functorPM
        );
    }
}
