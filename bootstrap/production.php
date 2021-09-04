<?php
use DI\ContainerBuilder;

use PHPMailer\PHPMailer\PHPMailer;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use SavageDev\Lib\Factory\LoggerFactory;

use Slim\App;
use Slim\Views\Twig;
use Slim\Psr7\Response;

return function (ContainerBuilder $builder)
{
	$builder->addDefinitions([
        "settings" => [
            "base_path" => "",
            "base_url" => env("APP_URL"),
            "debug" => env("APP_ENV") == "development" ? true : false,
            "view" => [
                "template_path" => INC_ROOT . "/../resources/views",
                "twig" => [
                    "debug" => env("APP_ENV") == "development" ? true : false,
                    "cache" => false,
                ],
            ],
            "logger" => [
                "name" => "app",
                "path" => INC_ROOT . "/../logs",
                "filename" => "app.log",
                "level" => \Monolog\Logger::DEBUG,
                "file_permission" => 0775,
            ]
        ],

        "twig" => function(ContainerInterface $container) {
            $settings = $container->get("settings");

            $twig = Twig::create($settings["view"]["template_path"], $settings["view"]["twig"]);

            return $twig;
        },

        "view" => function(ContainerInterface $container) {
            $view = $container->get("twig");

            $view->addExtension(new \Twig\Extension\DebugExtension);
            $view->addExtension(new \SavageDev\Lib\TwigExtension);

            $view->getEnvironment()->addGlobal("auth", [
                "check" => $container->get("auth")->check(),
                "user" => $container->get("auth")->user(),
            ]);

            $view->getEnvironment()->addGlobal("flash", $container->get("flash"));

            $container->get("csrf")->generateToken();

            $nameKey = $container->get("csrf")->getTokenNameKey();
            $valueKey = $container->get("csrf")->getTokenValueKey();
            $name = $container->get("csrf")->getTokenName();
            $value = $container->get("csrf")->getTokenValue();

            $view->getEnvironment()->addGlobal("csrf", [
                "field" => '
                    <input type="hidden" name="' . $nameKey . '" value="' . $name . '">
                    <input type="hidden" name="' . $valueKey . '" value="' . $value . '">
                '
            ]);

            return $view;
        },

        "database" => function() {
            $capsule = new \Illuminate\Database\Capsule\Manager;

            $capsule->addConnection([
                "driver" => env("DB_DRIVER", "mysql"),
                "host" => env("DB_HOST", "127.0.0.1"),
                "port" => env("DB_PORT", "3336"),
                "username" => env("DB_USERNAME", "root"),
                "password" => env("DB_PASSWORD", ""),
                "database" => env("DB_DATABASE", "slim4-auth-skeleton"),
                "charset" => env("DB_CHARSET", "utf8"),
                "collation" => env("DB_COLLATION", "utf8_unicode_ci"),
                "prefix"      => "",
                "strict"      => true,
                "engine"      => null,
                "modes"       => [
                    "ONLY_FULL_GROUP_BY",
                    "STRICT_TRANS_TABLES",
                    "NO_ZERO_IN_DATE",
                    "NO_ZERO_DATE",
                    "ERROR_FOR_DIVISION_BY_ZERO",
                    "NO_ENGINE_SUBSTITUTION",
                ],
            ], "default");

            $capsule->setAsGlobal();

            return $capsule;
        },

        "flash" => function() {
            return new \SavageDev\Lib\Flash();
        },

        "auth" => function() {
            return new \SavageDev\Auth\Auth();
        },

        "csrf" => function(ContainerInterface $container) {
            $guard = new \Slim\Csrf\Guard($container->get(App::class)->getResponseFactory());
            $guard->setPersistentTokenMode(true);
            $guard->setStorageLimit(100);
            $guard->setFailureHandler(function(ServerRequestInterface $request, RequestHandlerInterface $handler) use ($container, $guard) {
                $container->get("flash")->addMessage("error", "CSRF verification has failed, your request has been terminated. This could be caused via a double request, please only click the button once.");
                $response = new Response();
                return $response->withStatus(403)->withHeader("Location", full_uri($request->getUri()->getPath()));
            });

            return $guard;
        },

        "validator" => function() {
            return new \SavageDev\Validation\Validator;
        },

        "security" => function() {
            return new \SavageDev\Lib\Security;
        },

        LoggerFactory::class => function(ContainerInterface $container) {
            return new LoggerFactory($container->get("settings")["logger"]);
        },

        "logger" => function($container) {
            return $container->get(LoggerFactory::class)->addFileHandler("error.log")->createLogger();
        },
    ]);
};
