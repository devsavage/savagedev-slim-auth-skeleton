<?php

namespace SavageDev\Http\Exceptions;

use Slim\Exception\HttpSpecializedException;


class HttpServiceUnavailableException extends HttpSpecializedException
{

    /**
     * @var int
     */
    protected $code = 503;

    /**
     * @var string
     */
    protected $message = "Service Unavailable";

    protected $title = "503 Service Unavailable";
    protected $description = "The server is currently unable to handle the request due to a temporary overload or scheduled maintenance, which will likely be alleviated after some delay.";
}
