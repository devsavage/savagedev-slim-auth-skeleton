<?php
namespace SavageDev\Http\Middleware;

use SavageDev\Http\Middleware\Middleware;

class AuthMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if(!$this->_container->auth->check()) {
            $this->flash("warning", "You must be signed in to access that page.");
            return $this->redirect($response, "auth.login");
        }
        
        $response = $next($request, $response);
        return $response;
    }
}
