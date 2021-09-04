<?php
namespace SavageDev\Http\Middleware;

use SavageDev\Lib\Session;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class OldInputMiddleware implements MiddlewareInterface
{
    protected $_container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if($request->getMethod() == "POST") {
            Session::set("old", $request->getParsedBody());
        }

        $this->_container->get("view")->getEnvironment()->addGlobal("old", Session::get("old"));

        return $handler->handle($request);
    }
}
