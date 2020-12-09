<?php


namespace DevNajjary\laravelMultipleResponder\Facade;


use Illuminate\Support\Facades\Facade;

class Responder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return __CLASS__;
    }

    static function proxyTo($class)
    {
        app()->singleton(self::getFacadeAccessor(),$class);
    }
}
