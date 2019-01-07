<?php
session_start();

use Dotenv\Dotenv;

use SavageDev\App;

use SavageDev\Http\Middleware\CsrfMiddleware;
use SavageDev\Http\Middleware\OldInputMiddleware;

use Slim\Container;

define("INC_ROOT", __DIR__);

require INC_ROOT . "/../vendor/autoload.php";

if(file_exists(INC_ROOT . "/../.env")) {
    $env = new Dotenv(INC_ROOT . "/../");
    $env->load();
}

$app = new App(new Container(
    include INC_ROOT . "/container.php"
));

$container = $app->getContainer();

$container->db->bootEloquent();

$app->add(OldInputMiddleware::class);
$app->add(CsrfMiddleware::class);
$app->add($container->csrf);

require INC_ROOT . "/../routes/web.php";
