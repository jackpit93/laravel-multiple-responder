<?php


namespace DevNajjary\laravelMultipleResponder;


use FilesystemIterator;
use ReflectionClass;

class DetectResponder
{

    public function find(): array
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
