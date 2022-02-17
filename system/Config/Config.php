<?php

namespace System\Config;

use \Exception;

class Config
{
    private static $instance;
    private $config_nested_array = [];
    private $config_dot_array = [];

    private function __construct()
    {
        $this->initialConfigArrays();
    }
    
    private function initialConfigArrays()
    {
        $configPath = dirname(dirname(__DIR__)) . "/config/";
        foreach (glob($configPath . "*.php") as $fileName){
            $config = require $fileName;
            $key = $fileName;
            $key = str_replace($configPath, '', $key);
            $key = str_replace(".php", '', $key);
            $this->config_nested_array[$key] = $config;
        }
        $this->initialDefaultValues();
        $this->config_dot_array = $this->arrayDot($this->config_nested_array);
    }

    private function initialDefaultValues()
    {
        $temporary = str_replace($this->config_nested_array['app']['BASE_URL'], '', explode('?',
            $_SERVER['REQUEST_URI'])[0]);
        $temporary == '/' ? $temporary = '' : $temporary = substr($temporary, 1);
        $this->config_nested_array['app']['CURRENT_ROUTE'] = $temporary;
    }

    private function arrayDot($array, $returnArray = [], $returnKey = '')
    {
        foreach ($array as $key => $value){
            if(is_array($value)){
                $returnArray = array_merge($returnArray, $this->arrayDot($value, $returnArray, $returnKey . $key . '.'));
            }else{
                $returnArray[$returnKey . $key] = $value;
            }
        }
        return $returnArray;
    }

    private static function getInstance()
    {
        if(empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function get($key)
    {
        $instance = self::getInstance();
        if(isset($instance->config_dot_array[$key])){
            return $instance->config_dot_array[$key];
        }
        throw new Exception("$key not exist in config array");
    }
}