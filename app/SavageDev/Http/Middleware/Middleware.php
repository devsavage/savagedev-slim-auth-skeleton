<?php
namespace SavageDev\Http\Middleware;

use Interop\Container\ContainerInterface;

class Middleware
{
    protected $_container;

    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
    }

    public function flash($type, $message)
    {
        $this->_container->flash->addMessage($type, $message);
    }

    protected function router()
    {
        return $this->_container->router;
    }

    protected function redirect($response, $path, array $data = [], array $queryParams = [])
    {
        return $response->withRedirect($this->router()->pathFor($path, $data, $queryParams));
    }
}
