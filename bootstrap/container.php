<?php
return [
    "settings" => [
        "displayErrorDetails" => getenv("APP_ENV") === "development" ? true : false,
        "determineRouteBeforeAppMiddleware" => true,
        "viewTemplatesDirectory" => INC_ROOT . '/../resources/views',
    ],

    "view" => function($container) {
        $view = new \Slim\Views\Twig($container["settings"]["viewTemplatesDirectory"], [
            "debug" => $container["settings"]["displayErrorDetails"]
        ]);

        $view->getEnvironment()->addGlobal("auth", [
            "check"=> $container->auth->check(),
            "user" => $container->auth->user(),
        ]);
        $view->getEnvironment()->addGlobal("flash", $container["flash"]);

        $view->addExtension(new \Slim\Views\TwigExtension($container["router"], $container["request"]->getUri()));
        $view->addExtension(new \Twig_Extension_Debug);

        return $view;
    },

    "csrf" => function($container) {
        $guard = new \Slim\Csrf\Guard;

        $guard->setFailureCallable(function($request, $response, $next) use ($container) {
            $request = $request->withAttribute("csrf_status", false);
            if($request->getAttribute("csrf_status") === false) {
                $container["flash"]->addMessage("error", "CSRF verification failed, terminating your request.");

                return $response->withStatus(400)->withRedirect($container["router"]->pathFor("home"));
            } else {
                return $next($request, $response);
            }
        });

        return $guard;
    },

    "db" => function($container) {
        $capsule = new \Illuminate\Database\Capsule\Manager;

        $capsule->addConnection([
            "driver" => env("DB_DRIVER", "mysql"),
            "host" => env("DB_HOST", "127.0.0.1"),
            "port" => env("DB_PORT", "3306"),
            "username" => env("DB_USERNAME", "root"),
            "password" => env("DB_PASSWORD", ""),
            "database" => env("DB_DATABASE", "slim-auth-skeleton"),
            "charset" => env("DB_CHARSET", "utf8"),
            "collation" => env("DB_COLLATION", "utf8_unicode_ci"),
            'prefix'      => '',
            'strict'      => true,
            'engine'      => null,
            'modes'       => [
                'ONLY_FULL_GROUP_BY',
                'STRICT_TRANS_TABLES',
                'NO_ZERO_IN_DATE',
                'NO_ZERO_DATE',
                'ERROR_FOR_DIVISION_BY_ZERO',
                'NO_ENGINE_SUBSTITUTION',
            ],
        ], 'default');

        $capsule->setAsGlobal();

        return $capsule;
    },

    "auth" => function($container) {
        return new \SavageDev\Auth\Auth;
    },

    "flash" => function($container) {
        return new \SavageDev\Lib\Flash;
    },
    
    "hash" => function($container) {
        return new \SavageDev\Lib\Hash;
    },

    "validator" => function($container) {
        return new \SavageDev\Validation\Validator;
    },
];
