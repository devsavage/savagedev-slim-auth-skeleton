<?php
namespace SavageDev\Http\Handlers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use SavageDev\Http\Exceptions\HttpPageExpiredException;
use SavageDev\Http\Exceptions\HttpServiceUnavailableException;

use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Psr7\Response;

use Throwable;

class HttpErrorsHandler implements ErrorHandlerInterface
{
    protected $_view;
    protected $_response;
    
    public function __construct(ContainerInterface $container)
    {
        $this->_view = $container->get("view");
        $this->_response = $container->get(\Psr\Http\Message\ResponseFactoryInterface::class);
    }

    public function __invoke(ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails): ResponseInterface
    {
        if($exception instanceof HttpNotFoundException) {
            $response = $this->_response->createResponse($exception->getCode());
            return $this->_view->render($response, "errors/404.twig");
        } else if($exception instanceof HttpMethodNotAllowedException) {
            $response = $this->_response->createResponse($exception->getCode());
            return $this->_view->render($response, "errors/405.twig");
        } else if($exception instanceof HttpServiceUnavailableException) {
            $response = $this->_response->createResponse($exception->getCode());
            return $this->_view->render($response, "errors/503.twig");
        } else if($exception instanceof HttpPageExpiredException) {
            $response = $this->_response->createResponse($exception->getCode());
            return $this->_view->render($response, "errors/419.twig");
        }

        $response = new Response();
        return $response->withHeader("Location", "/");
    }
}
