<?php
namespace SavageDev\Http\Controllers\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Respect\Validation\Validator as v;

use SavageDev\Database\User;
use SavageDev\Http\Controllers\Controller;
use SavageDev\Lib\Session;

class RegisterController extends Controller
{
    public function get(Response $response): Response
    {
        return $this->render($response, "auth/register");
    }
    
    public function post(Request $request, Response $response): Response
    {
        $username = $this->param($request, "username");
        $email = $this->param($request, "email");
        $password = $this->param($request, "password");
        $confirm_password = $this->param($request, "confirm_password");

        $validation = $this->validator->validate($request, [
            "username" => v::notEmpty()->isUniqueUsername($this->auth)->length(null, 25),
            "email" => v::notEmpty()->email()->isUniqueEmail($this->auth)->length(null, 75),
            "password" => v::notEmpty()->length(8, null),
        ]);

        if($validation->failed()) {
            $this->flash("error", "Your account could not be created. Please fix any errors and try again.");
            $this->flash("errors", Session::get("errors"));
            return $this->redirect($response, "auth.register");
        } else {
            $passwordConfirm = v::keyValue('confirm_password', 'equals', 'password')->validate($_POST);
            if(!$passwordConfirm) {
                $this->flash("error", "Your account could not be created. Please fix any errors and try again.");
                $this->flash("errors", [
                    "confirm_password" => "Confirm password must match password"
                ]);
                return $this->redirect($response, "auth.register");
            }
        }

        $user = User::create([
            "username" => $username,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
        ]);

        Session::set(env("APP_AUTH_ID"), $user->id);

        $this->flash("success", "Your account has been created!");

        return $this->redirect($response, "home");
    }
}
