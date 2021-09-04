<?php
namespace SavageDev\Http\Middleware;

use Slim\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Interfaces\RouteParserInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GuestMiddleware implements MiddlewareInterface
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
        if($this->_container->get("auth")->check()) {
            $response = new Response();
            return $response->withHeader("Location", full_uri($this->_router->urlFor("auth.profile")));
        }

        return $handler->handle($request);
    }
}
