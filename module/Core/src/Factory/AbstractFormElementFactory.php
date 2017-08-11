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
use Zend\Form\Element;

class AbstractFormElementFactory extends LazyControllerAbstractFactory
{
    private $baseClass = Element::class;

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return (is_subclass_of($requestedName, $this->baseClass));
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if ($options === null) {
            $options = [];
        }

        if (isset($options['name'])) {
            $name = $options['name'];
        } else {
            // 'Zend\Form\Element' -> 'element'
            $parts = explode('\\', $requestedName);
            $name = strtolower(array_pop($parts));
        }

        if (isset($options['options'])) {
            $options = $options['options'];
        }

        return new $requestedName($name, $options);
    }
} 