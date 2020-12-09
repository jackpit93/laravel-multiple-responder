<?php

namespace DevNajjary\laravelMultipleResponder;

use DevNajjary\laravelMultipleResponder\Console\DefaultResponder;
use DevNajjary\laravelMultipleResponder\Console\ResponderGenerator;
use DevNajjary\laravelMultipleResponder\Facade\Responder;
use Exception;
use FilesystemIterator;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class laravelMultipleResponderServiceProvider extends ServiceProvider
{

    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ResponderGenerator::class,
                DefaultResponder::class
            ]);
            $this->publishes([
                __DIR__.'/config/config.php' => config_path('responder.php')
            ],'responder');

        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws Exception
     */
    public function boot()
    {
        $responders = $this->findResponders();

        $currentResponder = array_search(request()->Header(config('responder.header_key','Client')), $responders, true);

        if (!$currentResponder) {
            return Responder::proxyTo(Constants::DEFAULT_RESPONDER);
        }

        return Responder::proxyTo($currentResponder);

    }

    private function findResponders(): array
    {

        if (is_dir(app_path(Constants::RESPONDER_PATH))){
            $iterator = new \RecursiveDirectoryIterator(app_path(Constants::RESPONDER_PATH), FilesystemIterator::SKIP_DOTS);
            $iterator = new \RecursiveIteratorIterator($iterator);
            return $this->pathParser($iterator);
        }
        return [];
    }


    private function getHeader($path)
    {
        $reflector = new ReflectionClass($path);
        return $reflector->getConstant('HEADER');

    }

    private function pathParser(\RecursiveIteratorIterator $iterator)
    {
        $paths = array_keys(iterator_to_array($iterator));

        $paths = array_map(function ($item){
            return ucfirst(str_replace('/','\\',str_replace(['/var/www/','.php'],'',$item)));
        },$paths);

        $parsedPath = [];
        foreach ($paths as $path ){
            $parsedPath[$path] = $this->getHeader($path);
        }

        return $parsedPath;
    }
}
