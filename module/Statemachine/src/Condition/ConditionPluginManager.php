<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 16.09.16
 * Time: 16:32
 */

namespace StateMachine\Condition;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception as SMExceptionNS;

/**
 * Class ConditionPluginManager
 * @package StateMachine\Condition
 * @deprecated Not use PluginManager
 */
class ConditionPluginManager extends AbstractPluginManager
{
    const CONFIG_KEY = 'state_machine_condition';

    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed                      $plugin
     * @return void
     * @throws SMExceptionNS\InvalidServiceException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof ConditionInterface) {
            return;
        }
        throw new SMExceptionNS\InvalidServiceException(sprintf(
            'Plugin  of type %s is invalid; must implement ConditionInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
} 