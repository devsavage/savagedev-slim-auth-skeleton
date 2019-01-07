<?php
namespace SavageDev\Http\Controllers\Auth;

use SavageDev\Http\Controllers\Controller;

use Respect\Validation\Validator as v;

class PasswordController extends Controller
{
    public function get()
    {
        return $this->render("auth/password");
    }
    
    public function post()
    {
        v::with('SavageDev\\Validation\\Rules\\');

        $currentPassword = $this->param("current_password");
        $password = $this->param("password");
        $confirmPassword = $this->param("confirm_password");

        $validation = $this->validator->validate($this->_request, [
            "current_password" => v::notEmpty()->matchesCurrentPassword($this->auth),
            "password" => v::notEmpty()->length(6, null),
            "confirm_password" => v::notEmpty(),
        ]);

        if($validation->failed()) {
            $this->flashNow("error", "Your password could not be updated.");
            return $this->render("auth/password", [
                "errors" => $validation->errors(),
            ]);
        } else {
            $passwordConfirm = v::keyValue('confirm_password', 'equals', 'password')->validate($_POST);
            if(!$passwordConfirm) {
                $this->flashNow("error", "Your password could not be updated.");
                return $this->render("auth/password", [
                    "errors" => [
                        "confirm_new_password" => [
                            "Confirm new password must match new password"
                        ]
                    ],
                ]);
            }
        }

        $this->auth->user()->update([
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        $this->flash("success", "Your password has been updated!");
        return $this->redirect("auth.password");
    }
}
