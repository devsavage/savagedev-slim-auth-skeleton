<?php
namespace SavageDev\Console\Commands\Generator;

use SavageDev\Console\Command;
use SavageDev\Console\Traits\Generatable;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ConsoleGeneratorCommand extends Command
{
    use Generatable;

    protected $command = "make:command";

    protected $description = "Generate console command.";

    public function handle(InputInterface $input, OutputInterface $output)
    {
        $stub = $this->generateStub("command", [
            "DummyClass" => $this->argument("name"),
        ]);

        $target = __DIR__ . "/../" . $this->argument("name") . ".php";

        if(file_exists($target)) {
            return $this->error("Command already exists.");
        }

        file_put_contents($target, $stub);

        return $this->info("Console command generated.");
    }

    protected function arguments()
    {
        return [
            ["name", InputArgument::REQUIRED, "The name of the command to generate."]
        ];
    }

    protected function options()
    {
        return [

        ];
    }
}
