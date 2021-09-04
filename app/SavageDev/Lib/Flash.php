<?php
namespace SavageDev\Lib;

use Slim\Flash\Messages;

class Flash extends Messages
{
    protected $_forNow = [];

    public function addMessageNow($key, $message)
    {
        if(!isset($this->_forNow[$key])) {
            $this->_forNow[$key] = [];
        }

        $this->_forNow[$key][] = $message;
    }

    public function getMessages()
    {
        /**
         * @var string|array
         */
        $messages = $this->fromPrevious;

        foreach($this->_forNow as $key => $values) {
            if(!isset($messages[$key])){
                $messages[$key] = [];
            }

            foreach($values as $value){
                array_push($messages[$key], $value);
            }
        }

        return $messages;
    }

    public function getMessage($key)
    {
        $messages = $this->getMessages();
        return (isset($messages[$key])) ? $messages[$key] : null;
    }
}
