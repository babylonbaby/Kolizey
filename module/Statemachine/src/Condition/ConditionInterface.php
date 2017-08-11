<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 16.09.16
 * Time: 16:41
 */

namespace StateMachine\Condition;


interface ConditionInterface
{
    /**
     * @param $objE
     * @return bool
     */
    public function __invoke($objE);
} 