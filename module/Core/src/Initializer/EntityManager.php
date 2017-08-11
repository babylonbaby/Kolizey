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
use Core\ServiceManager\ServiceLocatorAwareInterface;
use Core\EntityManager\EntityManagerAwareInterface;

/**
 * Для инициализации элементов типа реализующих ServiceLocatorAwareInterface
 * или \DoctrineORMModule\Form\Element\EntityRadio
 * Class ServiceLocator
 * @package Core\Initializer
 */
class EntityManager implements InitializerInterface
{
    /**
     * Initialize the given instance
     *
     * @param  \Interop\Container\ContainerInterface $container
     * @param  object $instance
     * @return void
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        if ($instance instanceof EntityManagerAwareInterface) {
            /** @var \Doctrine\ORM\EntityManager $entityManager */
            $entityManager = $container->get('Doctrine\ORM\EntityManager');
            $instance->setEntityManager($entityManager);
        }
    }
} 