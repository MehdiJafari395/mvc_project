<?php

namespace System\Router;

use System\Config\Config;

class Routing
{
    private $current_route;
    private $method_field;
    private $routes;
    private $values = [];

    public function __construct()
    {
        $this->current_route = explode('/', Config::get('app.CURRENT_ROUTE'));
        $this->method_field = $this->methodField();

        global $routes;
        $this->routes = $routes;
    }

    public function run()
    {
        $match = $this->match();
        if(empty($match)){
            $this->error404();
        }

        $classPath = str_replace("\\", "/", $match['class']);
        $path = BASE_DIR . '/app/Http/Controllers/' . $classPath . '.php';
        if(!file_exists($path)){
            $this->error404();
        }

        $class = "\App\Http\Controllers\\" . $match['class'];
        $object = new $class();

        if (method_exists($object, $match['method'])){
            $reflection = new \ReflectionMethod($class, $match['method']);
            $parameterCount = $reflection->getNumberOfParameters();
            if($parameterCount <= count($this->values)){
                call_user_func_array(array($class, $match['method']), $this->values);
            }
        }else{
            $this->returnError('there is no method like ' . $match['method'] . 'in' . $match['class'] . 'class');
        }
    }

    private function match()
    {
        $reserveRoutes = $this->routes[$this->methodField()];
        foreach ($reserveRoutes as $key=>$reserveRoute){
            if($this->compare($reserveRoute['url']) == true){
                return array("class"=>$reserveRoute['class'], "method"=>$reserveRoute['method']);
            }else{
                $this->values = [];
            }
        }
        return [];
    }

    private function compare($reserveRouteUrl)
    {
        // check for " / " route
        if(trim($reserveRouteUrl, "/") === ''){
            return trim($this->current_route[0], "/") === '' ? true : false ;
        }
        // check for length of current and reserve route
        $reserveRouteUrlArray = explode("/", $reserveRouteUrl);
        if(sizeof($reserveRouteUrlArray) != sizeof($this->current_route)){
            return false;
        }
        // check for values in url and compare all part of current route with reserve route
        foreach ($this->current_route as $key=>$currentRouteElement){
            $reserveRouteUrlElement = $reserveRouteUrlArray[$key];
            if(substr($reserveRouteUrlElement, 0, 1) == "{" and substr($reserveRouteUrlElement, -1) == "}"){
                array_push($this->values, $currentRouteElement);
            }elseif ($currentRouteElement != $reserveRouteUrlElement){
                return false;
            }
        }

        return true;
    }

    public function error404()
    {
        http_response_code(404);
        include __DIR__ . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . '404.php';
        exit;
    }

    public function returnError($message)
    {
        echo 'there is error : ' . $message;
        exit;
    }

    public function methodField()
    {
        $method_field = strtolower($_SERVER['REQUEST_METHOD']);

        if($method_field === 'post'){
            if(isset($_POST['_method'])){
                if($_POST['_method'] === 'put'){
                    $method_field = 'put';
                }elseif ($_POST['_method'] === 'delete'){
                    $method_field = 'delete';
                }
            }
            $method_field = 'post';
        }else{
            $method_field = 'get';
        }

        return $method_field;
    }
}