<?php 
namespace SavageDev\Auth;

use SavageDev\Database\User;
use SavageDev\Lib\Session;

class Auth extends User
{
    public function check()
    {
        return Session::exists(env("APP_AUTH_ID"));
    }

    public function user()
    {
        return User::find(Session::get(env('APP_AUTH_ID')));
    }
}
