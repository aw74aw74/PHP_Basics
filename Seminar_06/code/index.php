<?php

require_once('./vendor/autoload.php');

use Geekbrains\Application1\Application;
use Geekbrains\Application1\Render;

try{
    $app = new Application();
    echo $app->run();
}
catch(\Throwable $e){
    echo Render::renderExceptionPage($e);
}