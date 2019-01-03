<?php
namespace SavageDev\Http\Controllers;

class Controller
{
    protected $_request;
    protected $_response;
    protected $_args;
    protected $_container;

    public function __construct($request, $response, $args, $container)
    {
        $this->_request = $request;
        $this->_response = $response;
        $this->_args = $args;
        $this->_container = $container;
    }

    public function __get($property)
    {
        if ($this->_container->{$property}) {
            return $this->_container->{$property};
        }
    }
    
    public function flash($type, $message)
    {
        return $this->flash->addMessage($type, $message);
    }

    public function flashNow($type, $message)
    {
        return $this->flash->addMessageNow($type, $message);
    }

    public function param($name)
    {
        return $this->_request->getParam($name);
    }

    public function render($name, array $args = [])
    {
        return $this->_container->view->render($this->_response, $name . ".twig", $args);
    }

    public function redirect($path = null, $args = [], $params = [])
    {
        return $this->response->withRedirect($this->router->pathFor($path, $args, $params));
    }
}
