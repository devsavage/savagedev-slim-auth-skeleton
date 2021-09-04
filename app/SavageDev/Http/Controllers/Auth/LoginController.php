<?php
namespace SavageDev\Http\Controllers\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Respect\Validation\Validator as v;

use SavageDev\Database\User;
use SavageDev\Http\Controllers\Controller;
use SavageDev\Lib\Session;

class LoginController extends Controller
{
    public function get(Response $response): Response
    {
        return $this->render($response, "auth/login");
    }
    
    public function post(Request $request, Response $response): Response
    {
        $identifier = $this->param($request, "identifier");
        $password = $this->param($request, "password");

        $validation = $this->validator->validate($request, [
            "identifier" => v::notEmpty(),
            "password" => v::notEmpty(),
        ]);

        if($validation->failed()) {
            $this->flash("error", "Please enter your credentials to continue.");
            return $this->redirect($response, "auth.login");
        } else {
            $user = User::where("username", $identifier)->orWhere("email", $identifier)->first();

            if(!$user || !password_verify($password, $user->password)) {
                $this->flash("error", "You have supplied invalid credentials. Please try again.");
                return $this->redirect($response, "auth.login");
            } else if($user && password_verify($password, $user->password)) {
                Session::set(env("APP_AUTH_ID"), $user->id);

                return $this->redirect($response, "home");
            }
        }
    }
}
