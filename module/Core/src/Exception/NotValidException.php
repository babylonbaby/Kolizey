<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 25.09.15
 * Time: 11:08
 */
namespace Core\Exception;


class NotValidException extends \Exception
{
    private $messages = array();

    public function __construct($messages = array(), $code = 0, $previous = null)
    {
        parent::__construct('Ошибка валидации формы', $code, $previous);
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }
}
