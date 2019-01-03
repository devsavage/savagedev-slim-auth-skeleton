<?php
namespace SavageDev\Http\Middleware;

use SavageDev\Http\Middleware\Middleware;

use SavageDev\Lib\Session;

class OldInputMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        Session::set("old", $request->getParams());

        $this->_container->view->getEnvironment()->addGlobal("old", Session::get("old"));

        $response = $next($request, $response);
        return $response;
    }
}
