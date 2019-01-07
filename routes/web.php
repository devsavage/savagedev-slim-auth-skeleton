<?php
use SavageDev\Http\Controllers\HomeController;

use SavageDev\Http\Controllers\Auth\AccountController;
use SavageDev\Http\Controllers\Auth\LoginController;
use SavageDev\Http\Controllers\Auth\PasswordController;
use SavageDev\Http\Controllers\Auth\RegisterController;

use SavageDev\Lib\Session;

use SavageDev\Http\Middleware\AuthMiddleware;
use SavageDev\Http\Middleware\GuestMiddleware;

$app->route(["GET"], "[/]", HomeController::class)->setName("home");

$app->group("/auth", function() {
    $this->route(["GET", "POST"], "/profile", AccountController::class)
        ->add(new AuthMiddleware($this->getContainer()))
        ->setName("auth.profile");
    $this->route(["GET", "POST"], "/password", PasswordController::class)
        ->add(new AuthMiddleware($this->getContainer()))
        ->setName("auth.password");

    $this->route(["GET", "POST"], "/login", LoginController::class)
        ->add(new GuestMiddleware($this->getContainer()))
        ->setName("auth.login");

    $this->route(["GET", "POST"], "/register", RegisterController::class)
        ->add(new GuestMiddleware($this->getContainer()))
        ->setName("auth.register");

    $this->get("/logout", function($req, $res, $args) {
        if(Session::exists(env("APP_AUTH_ID"))) {
            Session::destroy(env("APP_AUTH_ID"));
            $this["flash"]->addMessage("success", "You have been logged out.");
        }

        return $res->withRedirect($this["router"]->pathFor("home"));
    })->add(new AuthMiddleware($this->getContainer()))->setName("auth.logout");
});

