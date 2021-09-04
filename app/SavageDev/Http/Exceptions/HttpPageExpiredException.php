<?php

namespace SavageDev\Http\Exceptions;

use Slim\Exception\HttpSpecializedException;


class HttpPageExpiredException extends HttpSpecializedException
{

    /**
     * @var int
     */
    protected $code = 419;

    /**
     * @var string
     */
    protected $message = "Page Expired";

    protected $title = "419 Page Expired";
    protected $description = "This page has expired. Most likely due to a multiple submissions causing validation to fail.";
}
