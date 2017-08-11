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

use DoctrineORMModule\Form\Element as ElementNS;
//use Doctrine\ORM\EntityManager;

/**
 * Для инициализации элементов типа \DoctrineORMModule\Form\Element\EntitySelect
 * или \DoctrineORMModule\Form\Element\EntityRadio
 * Class EntitySelect
 * @package Core\Initializer
 */
class EntitySelect implements InitializerInterface
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * Initialize the given instance
     *
     * @param  \Interop\Container\ContainerInterface $container
     * @param  object $instance
     * @return void
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        if (($instance instanceof ElementNS\EntitySelect) == false && ($instance instanceof ElementNS\EntityRadio) == false) {
            return;
        }
        /** @var ElementNS\EntitySelect $instance */
        $objectManager = $instance->getOption('object_manager');
        if ($objectManager != null) {
            return;
        }
        if ($this->em == null) {
            $this->em = $container->get('doctrine');
        }
        $instance->setOption('object_manager', $this->em);
    }
} 