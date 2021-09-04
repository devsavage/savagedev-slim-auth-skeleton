<?php
namespace SavageDev\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use SavageDev\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request, Response $response): Response
    {
        return $this->render($response, "home");
    }
}
