<?php

namespace SavageDev\Lib;

class TwigExtension extends \Twig\Extension\AbstractExtension
{
    public function getName()
    {
        return "savagedev";
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction("getenv", [$this, "getenv"]),
            new \Twig\TwigFunction("asset", [$this, "asset"]),
        ];
    }

    public function getenv($key, $default = null)
    {
        return env($key, $default);
    }

    public function asset($path)
    {
        return env("APP_URL") . "/" . $path;
    }
}
