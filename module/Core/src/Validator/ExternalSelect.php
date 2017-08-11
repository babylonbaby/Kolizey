<?php
namespace Core\Validator;

use Zend\Validator\AbstractValidator as AbstractValidator;
use Zend\Validator\Exception;

class ExternalSelect extends AbstractValidator
{
    const MSG_NOT_ARRAY = 'not_array';
    const MSG_MISSING_ID = 'missing_id';
    const MSG_WRONG_ID = 'wrong_id';

    protected $messageTemplates = array(
        self::MSG_NOT_ARRAY => "Некорректный формат переданных данных (предполагается массив)",
        self::MSG_MISSING_ID => "Отсутствует идентификатор",
        self::MSG_WRONG_ID => 'Не выбрано или некорректное значение',
    );

    public function isValid($value)
    {
        if (is_array($value) == false) {
            $this->error(self::MSG_NOT_ARRAY);
            return false;
        }
        if (array_key_exists('id', $value) == false) {
            $this->error(self::MSG_MISSING_ID);
            return false;
        }
        if ($value['id']<= 0) {
            $this->error(self::MSG_WRONG_ID);
            return false;
        }
        return true;
    }
}