<?php
namespace SavageDev\Console;

class Kernel
{
    protected $commands = [

    ];

    protected $defaultCommands = [
        \SavageDev\Console\Commands\Generator\ConsoleGeneratorCommand::class,
        \SavageDev\Console\Commands\Generator\ControllerGeneratorCommand::class,
        \SavageDev\Console\Commands\Generator\MiddlewareGeneratorCommand::class,
    ];

    public function getCommands()
    {
        return array_merge($this->commands, $this->defaultCommands);
    }
}
