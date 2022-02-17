<?php

return [
    'APP_TITLE' => 'mvc_project',
    'BASE_URL' => 'htp://localhost:8000',
    'BASE_DIR' => realpath(__DIR__ . '/../'),


    // register providers
    'providers' => [
        \App\Providers\SessionProvider::class,
        \App\Providers\AppServiceProvider::class,
    ],
];

//define('APP_TITLE', 'mvc_project');
//
//define('BASE_URL', 'htp://localhost:8000');
//
//define('BASE_DIR', realpath(__DIR__ . '/../'));
//
//$temprary = str_replace(BASE_URL, '', explode('?', $_SERVER['REQUEST_URI'])[0]);
//$temprary == '/' ? $temprary = '' : $temprary = substr($temprary, 1);
//define('CURRENT_ROUTE', $temprary);
//
//global $routes;
//$routes = [
//    'get' => [],
//    'post' => [],
//    'put' => [],
//    'delete' => [],
//];