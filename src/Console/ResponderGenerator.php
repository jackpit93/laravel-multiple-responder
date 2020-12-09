<?php

 namespace  DevNajjary\laravelMultipleResponder\Console;

use DevNajjary\laravelMultipleResponder\Constants;
use Illuminate\Console\GeneratorCommand as Cmd;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class ResponderGenerator extends Cmd{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:responder {name : name of responder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function fire()
    {
        $this->handle();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!class_exists(Constants::DEFAULT_RESPONDER) && $this->getNameInput() !== 'DefaultResponder'){
            return  $this->error('-| please run [php artisan responder:generate]');
        }
        $responder = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($responder);
        if ($this->alreadyExists($this->getNameInput())) {
            $this->error($this->qualifyClass($this->getNameInput()) . '.php - Already exists.');
            return false;
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->buildClass($responder));
        $this->info(' - ' . $responder . '.php - was created.');

    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Responder';
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        if (!$this->argument('name')) {
            throw new InvalidArgumentException("Missing required argument responder name");
        }

        $stub = parent::replaceClass($stub, $name);

        return  str_replace(['DummyName','DummyResponder'], $this->getInput(), $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../stubs/Responder.stub';
    }

    protected function getInput()
    {
        $name = trim($this->argument('name'));
        return strpos($name,'/')?substr($name,strrpos($name,'/')+1):$name;
    }
}
