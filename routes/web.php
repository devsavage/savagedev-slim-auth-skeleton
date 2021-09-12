<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use SavageDev\Http\Middleware\AuthMiddleware;
use SavageDev\Http\Middleware\GuestMiddleware;
use SavageDev\Lib\Session;

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) 
{
    $app->get("/", [SavageDev\Http\Controllers\HomeController::class, "index"])->setName("home");

    $app->get("/login", [SavageDev\Http\Controllers\Auth\LoginController::class, "get"])
        ->add(new GuestMiddleware($app->getContainer()))
        ->setName("auth.login");
    $app->post("/login", [SavageDev\Http\Controllers\Auth\LoginController::class, "post"])
        ->add(new GuestMiddleware($app->getContainer()));

    $app->get("/register", [SavageDev\Http\Controllers\Auth\RegisterController::class, "get"])
        ->add(new GuestMiddleware($app->getContainer()))
        ->setName("auth.register");
    $app->post("/register", [SavageDev\Http\Controllers\Auth\RegisterController::class, "post"])
        ->add(new GuestMiddleware($app->getContainer()));


    $app->group("/auth", function (Group $group) use ($app) {
        $group->get("/profile", [SavageDev\Http\Controllers\Auth\AccountController::class, "get"])->setName("auth.profile");
        $group->post("/profile", [SavageDev\Http\Controllers\Auth\AccountController::class, "post"]);

        $group->get("/password", [SavageDev\Http\Controllers\Auth\PasswordController::class, "get"])->setName("auth.password");
        $group->post("/password", [SavageDev\Http\Controllers\Auth\PasswordController::class, "post"]);

        $group->get("/logout", function(Request $request, Response $response) use ($app) {    
            if(Session::exists(env("APP_AUTH_ID"))) {
                Session::destroy(env("APP_AUTH_ID"));
                $app->getContainer()->get("flash")->addMessage("success", "You have been logged out.");
            }
    
            return $response->withHeader("Location", $app->getContainer()->get(\Slim\Interfaces\RouteParserInterface::class)->urlFor("auth.login"));
        })->setName("auth.logout");
    })->add(new AuthMiddleware($app->getContainer()));
};

