<?php
session_start();

use DI\ContainerBuilder;
use DI\Bridge\Slim\Bridge;

use Respect\Validation\Factory;

use SavageDev\Lib\Factory\LoggerFactory;
use SavageDev\Http\Handlers\HttpErrorsHandler;

use Slim\Csrf\Guard;
use Slim\Views\TwigMiddleware;

define("INC_ROOT", __DIR__);

require INC_ROOT . "/../vendor/autoload.php";

if(file_exists(INC_ROOT . "/../.env")) {
    $dotenv = \Dotenv\Dotenv::createImmutable(INC_ROOT . "/../");
    $dotenv->load();
}

Factory::setDefaultInstance(
    (new Factory())
        ->withRuleNamespace("SavageDev\\Validation\\Rules")
        ->withExceptionNamespace("SavageDev\\Validation\\Exceptions")
);

$builder = new ContainerBuilder();

$production = require INC_ROOT . "/../bootstrap/production.php";
$production($builder);

$container = $builder->build();

$settings = $container->get("settings");

$app = Bridge::create($container);
$app->setBasePath($settings["base_path"]);

$responseFactory = $app->getResponseFactory();
$container->set(\Psr\Http\Message\ResponseFactoryInterface::class, $responseFactory);

$container->get("database")->bootEloquent();

$app->add($container->get("csrf"));

$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();

$routeParser = $app->getRouteCollector()->getRouteParser();
$container->set(Slim\Interfaces\RouteParserInterface::class, $routeParser);

$app->add(TwigMiddleware::createFromContainer($app));
$app->addMiddleware(new \SavageDev\Http\Middleware\OldInputMiddleware($container));

$errorMiddleware = $app->addErrorMiddleware(true, true, true, $container->get("logger"));
$errorMiddleware->setDefaultErrorHandler(new HttpErrorsHandler($container));

$webRoutes = require INC_ROOT . "/../routes/web.php";
$webRoutes($app);
