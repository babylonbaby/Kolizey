<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 08.02.17
 * Time: 15:14
 */

namespace Core\Factory;

use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\LazyControllerAbstractFactory;

class LazyControllerFactory extends LazyControllerAbstractFactory
{
    private $checkedInterface = LazyControllerInterface::class;

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $interfaces = class_implements($requestedName);
        if($interfaces && in_array($this->checkedInterface, $interfaces)) {
            return true;
        }
        return false;
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return parent::__invoke($container, $requestedName, $options);
    }
} 