<?php

namespace DevNajjary\laravelMultipleResponder;

use DevNajjary\laravelMultipleResponder\Console\DefaultResponder;
use DevNajjary\laravelMultipleResponder\Console\ResponderGenerator;
use DevNajjary\laravelMultipleResponder\Facade\Responder;
use Exception;
use Illuminate\Support\ServiceProvider;

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
                __DIR__ . '/config/config.php' => config_path('responder.php')
            ], 'responder');

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
        $this->app->singleton(DetectResponder::class);

        $responders = $this->app->make(DetectResponder::class)->find();

        $currentResponder = array_search(request()->Header(config('responder.header_key', 'Client')), $responders, true);

        if (!$currentResponder) {
            return Responder::proxyTo(Constants::DEFAULT_RESPONDER);
        }

        return Responder::proxyTo($currentResponder);

    }

}
