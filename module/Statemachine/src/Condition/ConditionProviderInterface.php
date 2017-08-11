<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 16.09.16
 * Time: 16:33
 */

namespace StateMachine\Condition;

/**
 * Interface ConditionProviderInterface для конфигурации плагин-менеджера функторов-условий
 * @package StateMachine\Condition
 */
interface ConditionProviderInterface {
    public function getConditionProviderConfig();
} 