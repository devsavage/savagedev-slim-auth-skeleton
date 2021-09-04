<?php
namespace SavageDev\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class IsUniqueUsername extends AbstractRule
{
    protected $_auth;

    public function __construct($auth)
    {
        $this->_auth = $auth;
    }

    public function validate($input) : bool
    {
        $user = $this->_auth->where("username", $input);

        if($this->_auth->check() && $this->_auth->user()->username === $input) {
            return true;
        }

        return !(bool) $user->count();
    }
}
