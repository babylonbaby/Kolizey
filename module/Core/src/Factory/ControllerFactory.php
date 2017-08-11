<?php
/**
 * Created by F-Technology.
 * User: Vasyankin Alexey
 * Date: 07.02.2017
 * Time: 10:11
 * e-mail: vasyankin@f-technology.ru
 */

namespace Core\Factory;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName($container, $options);
    }
}