<?php
namespace SavageDev\Http\Controllers\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Respect\Validation\Validator as v;

use SavageDev\Http\Controllers\Controller;
use SavageDev\Lib\Session;

class AccountController extends Controller
{
    public function get(Response $response): Response
    {
        return $this->render($response, "auth/profile");
    }
    
    public function post(Request $request, Response $response): Response
    { 
        $username = $this->param($request, "username");
        $email = $this->param($request, "email");

        $validation = $this->validator->validate($request, [
            "username" => v::notEmpty()->isUniqueUsername($this->auth)->length(null, 25),
            "email" => v::notEmpty()->email()->isUniqueEmail($this->auth)->length(null, 75),
        ]);

        if($validation->failed()) {
            $this->flash("error", "Your profile was not updated. Please fix any errors and try again.", true);
            return $this->render($response, "auth/profile", [
                "errors" => $validation->errors(),
            ]);
        }

        $this->auth->user()->update([
            "username" => $username, 
            "email" => $email,
        ]);

        $this->flash("success", "Your profile was updated!");
        return $this->redirect($response, "auth.profile");
    }
}
