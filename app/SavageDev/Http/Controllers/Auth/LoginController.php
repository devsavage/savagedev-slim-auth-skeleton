<?php
namespace SavageDev\Http\Controllers\Auth;

use Respect\Validation\Validator as v;

use SavageDev\Database\User;
use SavageDev\Http\Controllers\Controller;
use SavageDev\Lib\Session;

class LoginController extends Controller
{
    public function get()
    {
        return $this->render("auth/login");
    }
    
    public function post()
    {
        $identifier = $this->param("identifier");
        $password = $this->param("password");

        $validation = $this->validator->validate($this->_request, [
            "identifier" => v::notEmpty(),
            "password" => v::notEmpty(),
        ]);

        if($validation->failed()) {
            $this->flash("error", "Please enter your credentials to continue.");
            return $this->redirect("auth.login");
        } else {
            $user = User::where("username", $identifier)->orWhere("email", $identifier)->first();

            if(!$user || !password_verify($password, $user->password)) {
                $this->flash("error", "You have supplied invalid credentials. Please try again.");
                return $this->redirect("auth.login");
            } else if($user && password_verify($password, $user->password)) {
                Session::set(env("APP_AUTH_ID"), $user->id);

                return $this->redirect("home");
            }
        }
    }
}
