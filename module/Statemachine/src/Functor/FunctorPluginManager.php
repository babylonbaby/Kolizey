<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 16.09.16
 * Time: 16:32
 */
namespace StateMachine\Functor;

use Interop\Container\ContainerInterface;
use Core\EntityManager\EntityManagerAwareInterface;
use Core\EntityManager\EntityManagerTrait;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * Class FunctorPluginManager
 * @package StateMachine\Functor
 */
class FunctorPluginManager extends AbstractPluginManager
    implements FunctorPluginManagerInterface, EntityManagerAwareInterface
{
    use EntityManagerTrait;

    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws \RuntimeException if invalid
     */
    public function validate($plugin)
    {
        if (is_object($plugin) && $plugin instanceof FunctorInterface) {
            return;
        }
        throw new \RuntimeException('Can not load grid_adapter plugin');
    }
} 