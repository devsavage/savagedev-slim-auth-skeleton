<?php
namespace SavageDev\Http\Middleware;

use SavageDev\Http\Middleware\Middleware;

class GuestMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if($this->_container->auth->check()) {
            return $this->redirect($response, "auth.profile");
        }

        $response = $next($request, $response);
        return $response;
    }
}
