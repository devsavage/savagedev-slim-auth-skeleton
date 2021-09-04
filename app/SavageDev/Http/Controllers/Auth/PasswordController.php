<?php
namespace SavageDev\Http\Controllers\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use SavageDev\Http\Controllers\Controller;
use SavageDev\Lib\Session;

use Respect\Validation\Validator as v;

class PasswordController extends Controller
{
    public function get(Response $response): Response
    {
        return $this->render($response, "auth/password");
    }
    
    public function post(Request $request, Response $response): Response
    {
        $currentPassword = $this->param($request, "current_password");
        $password = $this->param($request, "password");
        $confirmPassword = $this->param($request, "confirm_password");

        $validation = $this->validator->validate($request, [
            "current_password" => v::notEmpty()->matchesCurrentPassword($this->auth),
            "password" => v::notEmpty()->length(6, null),
            "confirm_password" => v::notEmpty(),
        ]);

        if($validation->failed()) {
            $this->flash("error", "Your password could not be updated.");
            $this->flash("errors", Session::get("errors"));
            return $this->redirect($response, "auth.password");
        } else {
            $passwordConfirm = v::keyValue("confirm_password", "equals", "password")->validate($_POST);
            if(!$passwordConfirm) {
                $this->flash("error", "Your password could not be updated.");
                $this->flash("errors", [
                    "confirm_password" => "Confirm password must match password.",
                ]);

                return $this->redirect($response, "auth.password");
            }
        }

        $this->auth->user()->update([
            "password" => password_hash($password, PASSWORD_DEFAULT),
        ]);

        $this->flash("success", "Your password has been updated!");
        return $this->redirect($response, "auth.password");
    }
}
