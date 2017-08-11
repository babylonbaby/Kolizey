<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 01.02.16
 * Time: 16:44
 */
namespace Core\Form;

use Core\Exception;

trait FormValidateTrait
{
    public function validate($isDraft = false)
    {
        if ($this->isValid($isDraft) == false) {
            throw new Exception\NotValidException($this->getMessages());
        }
    }
}