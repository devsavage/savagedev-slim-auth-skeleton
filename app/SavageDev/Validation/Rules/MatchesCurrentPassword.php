<?php
namespace SavageDev\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class MatchesCurrentPassword extends AbstractRule
{
    protected $_auth;

    public function __construct($auth)
    {
        $this->_auth = $auth;
    }

    public function validate($input) : bool
    {
        if($this->_auth->check() && password_verify($input, $this->_auth->user()->password)) {
            return true;
        }
    
        return false;
    }
}
