<?php
namespace SavageDev\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class IsUniqueEmail extends AbstractRule
{
    protected $_auth;

    public function __construct($auth)
    {
        $this->_auth = $auth;
    }

    public function validate($input)
    {
        $user = $this->_auth->where("email", $input);

        if($this->_auth->check() && $this->_auth->user()->email === $input) {
            return true;
        }

        return !(bool) $user->count();
    }
}
