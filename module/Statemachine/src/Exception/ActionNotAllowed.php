<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 16.09.16
 * Time: 13:39
 */

namespace StateMachine\Exception;

/**
 * Если для данного состояния нет заданного действия
 * Class InvalidStateAction
 * @package StateMachine\Exception
 */
class ActionNotAllowed extends \LogicException
{

} 