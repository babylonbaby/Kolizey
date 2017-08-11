<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 16.09.16
 * Time: 16:33
 */

namespace StateMachine\Functor;

/**
 * Interface ConditionProviderInterface для конфигурации плагин-менеджера функторов-условий
 * @package StateMachine\Condition
 */
interface FunctorProviderInterface {

    const CONFIG_KEY = 'state_machine_functors';

    public function getFunctorProviderConfig();
} 