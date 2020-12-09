<?php

 namespace  DevNajjary\laravelMultipleResponder\Console;

use DevNajjary\laravelMultipleResponder\Constants;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DefaultResponder extends Command {


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'responder:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (class_exists(Constants::DEFAULT_RESPONDER)){
            return  $this->warn('-| Responder has already been generated.');
        }

        Artisan::call('make:responder DefaultResponder');
        $this->info(' -| Let\'s go responder is ready to use. ');

    }

}
