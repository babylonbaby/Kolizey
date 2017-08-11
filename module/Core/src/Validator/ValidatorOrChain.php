<?php
namespace Core\Validator;

use Zend\Validator as ValidatorNS;
use Zend\Validator\ValidatorChain as BaseChain;

class ValidatorOrChain extends  BaseChain
{
    /**
     * Возвращает true если хотя бы один из валидаторов возвращает true
     *
     * Validators are run in the order in which they were added to the chain (FIFO).
     *
     * @param  mixed $value
     * @param  mixed $context Extra "context" to provide the validator
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        $this->messages = array();
        $result         = false;
        foreach ($this->validators as $element) {
            /** @var ValidatorNS\ValidatorInterface $validator */
            $validator = $element['instance'];
            if ($validator->isValid($value, $context)) {
                $result = true;
                break;
            }

            $messages       = $validator->getMessages();
            $this->messages = array_replace_recursive($this->messages, $messages);
        }
        return $result;
    }
}
