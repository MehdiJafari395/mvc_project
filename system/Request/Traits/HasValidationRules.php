<?php

namespace System\Request\Traits;

use System\Database\DBConnection\DBConnection;

trait HasValidationRules
{
    public function normalValidation($name, $ruleArray)
    {
        foreach ($ruleArray as $rule){
            if($rule == "required"){
                $this->required($name);
            }elseif (strpos($rule, "max:" === 0)){
                $rule = str_replace("max:", '', $rule);
                $this->maxStr($name, $rule);
            }elseif (strpos($rule, "min:") === 0){
                $rule = str_replace("min:", "", $rule);
                $this->minStr($name, $rule);
            }elseif (strpos($rule, "exists:") === 0){
                $rule = str_replace("exists:", "", $rule);
                $rule = explode(' ,', $rule);
                $key = isset($rule[1]) == false ? null : $rule[1];
                $this->existsIn($name, $rule[0], $rule[1]);
            }elseif ($rule == 'email'){
                $this->email($name);
            }elseif ($rule == 'date'){
                $this->date($name);
            }
        }
    }

    public function numberValidation($name, $ruleArray)
    {
        foreach ($ruleArray as $rule){
            if($rule == "required"){
                $this->required($name);
            }elseif (strpos($rule, "max:" === 0)){
                $rule = str_replace("max:", '', $rule);
                $this->maxNumber($name, $rule);
            }elseif (strpos($rule, "min:") === 0){
                $rule = str_replace("min:", "", $rule);
                $this->minNumber($name, $rule);
            }elseif (strpos($rule, "exists:") === 0){
                $rule = str_replace("exists:", "", $rule);
                $rule = explode(' ,', $rule);
                $key = isset($rule[1]) == false ? null : $rule[1];
                $this->existsIn($name, $rule[0], $rule[1]);
            }elseif ($rule == 'number'){
                $this->number($name);
            }
        }
    }

    protected function maxStr($name, $count)
    {
        if($this->checkFieldExist($name)){
            if(strlen($this->request[$name]) >= $count && $this->checkFirstError($name)){
                $this->setError($name, "max length is equal or lower than $count character");
            }
        }
    }

    protected function minStr($name, $count)
    {
        if($this->checkFieldExist($name)){
            if(strlen($this->request[$name]) <= $count && $this->checkFirstError($name)){
                $this->setError($name, "min length is equal or upper than $count character");
            }
        }
    }

    protected function maxNumber($name, $count)
    {
        if($this->checkFieldExist($name)){
            if($this->request[$name] >= $count AND $this->checkFirstError($name)){
                $this->setError($name, "max number is equal or lower than $count number");
            }
        }
    }

    protected function minNumber($name, $count)
    {
        if($this->checkFieldExist($name)){
            if($this->request[$name] <= $count && $this->checkFirstError($name)){
                $this->setError($name, "min number is equal or upper than $count number");
            }
        }
    }

    protected function required($name)
    {
        if((!isset($this->request[$name]) OR $this->request[$name] == '') AND $this->checkFirstError($name)){
            $this->setError($name, "$name is required");
        }
    }

    protected function number($name)
    {
        if($this->checkFieldExist($name)){
            if(!is_numeric($this->request[$name]) AND $this->checkFirstError($name)){
                $this->setError($name, "$name must be number");
            }
        }
    }
    
    protected function date($name)
    {
        if($this->checkFieldExist($name)){
            if(!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $this->request[$name])){
                $this->setError($name, "$name has not correct format");
            }
        }
    }

    protected function email($name)
    {
        if($this->checkFieldExist($name)){
            if(!filter_var($this->request[$name], FILTER_VALIDATE_EMAIL)){
                $this->setError($name, "$name must be email format");
            }
        }
    }

    protected function existsIn($name, $table, $field = 'id')
    {
        if($this->checkFieldExist($name)){
            if($this->checkFirstError($name)){
                $value = $this->$field;
                $sql = "SELECT COUNT(*) FROM $table WHERE $field = ?";
                $dbInstance = DBConnection::getDBConnectionInstance();
                $statement = $dbInstance->prepare($sql);
                $statement->execute([$value]);
                $result = $statement->fetchColumn();
                if($result == 0 OR $result === false){
                    $this->setError($name, "$name not already exist");
                }
            }
        }
    }
}