<?php
/**
 * Created by F-Technology.
 * User: Vasyankin Alexey
 * Date: 08.02.2017
 * Time: 12:04
 * e-mail: vasyankin@f-technology.ru
 */

namespace Core\Controller;


use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class AbstractActionControllerWithServiceLocator extends AbstractActionController
{
    private $sm = null;

    public function __construct(ContainerInterface $container)
    {
        $this->sm = $container;
    }

    public function getServiceLocator()
    {
        return $this->sm;
    }
}