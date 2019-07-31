<?php
namespace SavageDev;

use Slim\App as Slim;

class App extends Slim
{
    public function route(array $methods, $uri, $controller, $func = null)
    {
        return $this->map($methods, $uri, function($request, $response, $args) use ($controller, $func) {
            $callable = new $controller($request, $response, $args, $this);
            $method = is_callable($func, true) ? $func : $request->getMethod();

            if(method_exists($callable, $method)) {
                return call_user_func_array([$callable, $method], $args);
            }

            throw new \Exception("The method '$method' is not defined within '$controller'!");
        });
    }
}
