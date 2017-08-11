<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 18.11.16
 * Time: 10:36
 */

namespace Core\Service;

use Zend\ServiceManager\AbstractPluginManager;

/**
 * вынимает корневой сервис локатор (когда в качестве локатора выступает pluginManager)
 * Class RootServiceLocatorTrait
 * @package Core\Service
 *
 * @method \Zend\ServiceManager\ServiceLocatorInterface getServiceLocator()
 */
trait RootServiceLocatorTrait
{
    /**
     * возвращает корневой сервис локатор
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected function getRootServiceLocator()
    {
        if (($sm = $this->getServiceLocator())instanceof AbstractPluginManager) {
            /** @var AbstractPluginManager $sm */
            $sm = $sm->getServiceLocator();
        }
        return $sm;
    }
}
