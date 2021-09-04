<?php
namespace SavageDev\Http\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Interfaces\RouteParserInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SavageDev\Lib\Session;
use Slim\Psr7\Response;

class AuthMiddleware implements MiddlewareInterface
{
    protected $_container;
    protected $_router;
    
    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
        $this->_router = $container->get(RouteParserInterface::class);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if(!$this->_container->get("auth")->check()) {
            $response = new Response();

            $this->_container->get("flash")->addMessage("warning", "You must be logged in before viewing that page.");
            return $response->withHeader("Location", full_uri($this->_router->urlFor("auth.login")));
        }

        return $handler->handle($request);
    }
}
