<?php
namespace SavageDev\Http\Controllers;

use SavageDev\Lib\Session;
use Psr\Container\ContainerInterface;
use Slim\Interfaces\RouteParserInterface;
use Psr\Http\Message\ResponseInterface as Response;

class Controller
{
    protected $_view;
    protected $_router;
    protected $_container;

    public function __construct(ContainerInterface $container)
    {
        $this->_view = $container->get("view");
        $this->_router = $container->get(RouteParserInterface::class);
        $this->_container = $container;
    }

    public function __get($property)
    {
        if ($this->_container->has($property)) {
            return $this->_container->get($property);
        }
    }
    
    public function flash($type, $message, $now = false) 
    {
        if($now) {
            return $this->flash->addMessageNow($type, $message);
        }
        
        return $this->flash->addMessage($type, $message);
    }

    public function param($request, $name)
    {
        if($request->getMethod() == "GET" || $urlParamsOnly) {
            return isset($request->getQueryParams()[$name]) ? $request->getQueryParams()[$name] : null;
        }
        
        return isset($request->getParsedBody()[$name]) ? $request->getParsedBody()[$name] : null;
    }

    protected function render(Response $response, string $template, array $params = []): Response
    {
        return $this->_view->render($response, $template . ".twig", $params);
    }

    protected function redirect(Response $response, $to, $data = [], $params = [], $query = null): Response
    {
        if($query) {
            return $response->withHeader("Location", $this->makeUri($this->_router->urlFor($to, $data, $params) . $query));
        }

        return $response->withHeader("Location", $this->makeUri($this->_router->urlFor($to, $data, $params)));
    }

    protected function makeUri($uri)
    {
        return env("APP_URL") . $uri;
    }
}
