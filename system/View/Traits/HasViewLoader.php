<?php

namespace System\View\Traits;

use \Exception;

trait HasViewLoader
{
    private $viewNameArray = [];

    private function viewLoader($dir)
    {
        $dir = trim($dir, ' .');
        $dir = str_replace('.', '/', $dir);
        $fileDirectory = dirname(dirname(dirname(__DIR__)))."/resources/view/$dir.blade.php";
        if(file_exists($fileDirectory)){
            $this->registerView($dir);
            $content = htmlentities(file_get_contents($fileDirectory));
            return $content;
        }
        throw new Exception('view not found');
    }

    private function registerView($view)
    {
        array_push($this->viewNameArray, $view);
    }
}