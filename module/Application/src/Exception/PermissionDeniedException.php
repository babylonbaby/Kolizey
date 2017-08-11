<?php
namespace Application\Exception;
 class PermissionDeniedException extends \Exception
 {
     private $messages = array();

     public function __construct($messages = array(), $code = 0, $previous = null)
     {
         parent::__construct('Не достаточно прав', $code, $previous);
         $this->messages = $messages;
     }

     public function getMessages()
     {
         return $this->messages;
     }
 }