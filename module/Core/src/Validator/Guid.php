<?php
namespace Core\Validator;

use Zend\Validator\AbstractValidator as AbstractValidator;
use Zend\Validator\Exception;

class Guid extends AbstractValidator
{

    private $guidPattern = '/^(\{)?[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}(?(1)\})$/i';
    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        $result =  (preg_match($this->guidPattern, $value))? true: false;
        return $result;
    }
}