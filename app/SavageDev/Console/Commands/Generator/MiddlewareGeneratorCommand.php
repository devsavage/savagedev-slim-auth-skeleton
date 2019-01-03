<?php
namespace SavageDev\Console\Commands\Generator;

use SavageDev\Console\Command;
use SavageDev\Console\Traits\Generatable;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class MiddlewareGeneratorCommand extends Command
{
    use Generatable;

    protected $command = 'make:middleware';

    protected $description = 'Generate some middleware.';

    public function handle(InputInterface $input, OutputInterface $output)
    {
        $middlewareBase = __DIR__ . '/../../../Http/Middleware';
        $path = $middlewareBase . '/';
        $namespace = 'SavageDev\\Http\\Middleware';

        $fileParts = explode('\\', trim($this->argument('name')));

        $fileName = array_pop($fileParts);

        $cleanPath = implode('/', $fileParts);

        if (count($fileParts) >= 1) {
            $path = $path . $cleanPath;

            $namespace = $namespace . '\\' . str_replace('/', '\\', $cleanPath);

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }

        $target = $path . $fileName . '.php';

        if (file_exists($target)) {
            return $this->error('Middleware already exists!');
        }

        $stub = $this->generateStub('middleware', [
            'DummyClass' => $fileName,
            'DummyNamespace' => $namespace,
        ]);

        file_put_contents($target, $stub);

        return $this->info('Middleware generated!');
    }

    protected function arguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the middleware to generate.']
        ];
    }

    protected function options()
    {
        return [

        ];
    }
}
