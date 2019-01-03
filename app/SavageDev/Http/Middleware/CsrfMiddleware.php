<?php
namespace SavageDev\Http\Middleware;

use SavageDev\Http\Middleware\Middleware;

class CsrfMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $this->_container->view->getEnvironment()->addGlobal('csrf', [
            'field' => '
                <input type="hidden" name="' . $this->_container->csrf->getTokenNameKey() . '" value="' . $this->_container->csrf->getTokenName() . '">
                <input type="hidden" name="' . $this->_container->csrf->getTokenValueKey() . '" value="' . $this->_container->csrf->getTokenValue() . '">
            '
        ]);
        
        $response = $next($request, $response);
        return $response;
    }
}
