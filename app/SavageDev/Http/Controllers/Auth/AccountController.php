<?php
namespace SavageDev\Http\Controllers\Auth;

use Respect\Validation\Validator as v;

use SavageDev\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function get()
    {
        return $this->render("auth/profile");
    }
    
    public function post()
    {
        v::with('SavageDev\\Validation\\Rules\\');
        
        $username = $this->param("username");
        $email = $this->param("email");

        $validation = $this->validator->validate($this->_request, [
            "username" => v::notEmpty()->isUniqueUsername($this->auth)->length(null, 25),
            "email" => v::notEmpty()->email()->isUniqueEmail($this->auth)->length(null, 75),
        ]);

        if($validation->failed()) {
            $this->flashNow("error", "Your profile was not updated. Please fix any errors and try again.");
            return $this->render("auth/profile", [
                "errors" => $validation->errors(),
            ]);
        }

        $this->auth->user()->update([
            "username" => $username, 
            "email" => $email,
        ]);

        $this->flash("success", "Your profile was updated!");
        return $this->redirect("auth.profile");
    }
}
