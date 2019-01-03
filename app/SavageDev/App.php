<?php
namespace SavageDev;

use Slim\App as Slim;

class App extends Slim
{
    public function route(array $methods, $uri, $controller, $func = null)
    {
        if($func) {
            return $this->map($methods, $uri, function($request, $response, $args) use ($controller, $func) {
                $callable = new $controller($request, $response, $args, $this);
                return call_user_func_array([$callable, $request->getMethod() . ucfirst($func)], $args);
            });
        }

        return $this->map($methods, $uri, function($request, $response, $args) use ($controller, $func) {
            $callable = new $controller($request, $response, $args, $this);
            return call_user_func_array([$callable, $request->getMethod()], $args);
        });
    }
}
