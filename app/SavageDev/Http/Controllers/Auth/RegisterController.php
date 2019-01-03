<?php
namespace SavageDev\Http\Controllers\Auth;

use Respect\Validation\Validator as v;

use SavageDev\Database\User;
use SavageDev\Http\Controllers\Controller;
use SavageDev\Lib\Session;

class RegisterController extends Controller
{
    public function get()
    {
        return $this->render("auth/register");
    }
    
    public function post()
    {
        v::with('SavageDev\\Validation\\Rules\\');

        $username = $this->param("username");
        $email = $this->param("email");
        $password = $this->param("password");
        $confirm_password = $this->param("confirm_password");

        $validation = $this->validator->validate($this->_request, [
            "username" => v::notEmpty()->isUniqueUsername($this->auth)->length(null, 25),
            "email" => v::notEmpty()->email()->isUniqueEmail($this->auth)->length(null, 75),
            "password" => v::notEmpty()->length(8, null),
        ]);

        if($validation->failed()) {
            $this->flashNow("error", "Your account could not be created. Please fix any errors and try again.");
            return $this->render("auth/register", [
                "errors" => $validation->errors(),
            ]);
        } else {
            $passwordConfirm = v::keyValue('confirm_password', 'equals', 'password')->validate($_POST);
            if(!$passwordConfirm) {
                $this->flashNow("error", "Your account could not be created. Please fix any errors and try again.");
                return $this->render("auth/register", [
                    "errors" => [
                        "confirm_password" => [
                            "Confirm password must match password."
                        ]
                    ],
                ]);
            }
        }

        $user = User::create([
            "username" => $username,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
        ]);

        Session::set(env("APP_AUTH_ID"), $user->id);
        $this->flash("success", "Your account has been created!");
        return $this->redirect("home");
    }
}
