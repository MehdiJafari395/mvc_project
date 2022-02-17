<?php

namespace System\Request\Traits;

trait HasFileValidationRules
{
    protected function fileValidation($name, $ruleArray)
    {
        foreach ($ruleArray as $rule){
            if ($rule == 'required'){
                $this->fileRequired($name);
            }elseif (strpos($rule, 'mimes:') === 0){
                $rule = str_replace('mimes:', '', $rule);
                $rule = explode(' ,', $rule);
                $this->fileType($name, $rule);
            }elseif (strpos($rule, 'min:') === 0){
                $rule = str_replace('min:', '', $rule);
                $this->minFile($name, $rule);
            }elseif (strpos($rule, 'max:') === 0){
                $rule = str_replace('max:', '', $rule);
                $this->maxFile($name, $rule);
            }
        }
    }

    protected function fileRequired($name)
    {
        if(!isset($this->files[$name]['name']) OR empty($this->files[$name]['name']) AND $this->checkFirstError($name)){
            $this->setError($name, "$name is required");
        }
    }

    protected function fileType($name, $fileTypes)
    {
        if($this->checkFirstError($name) AND $this->checkFileExist($name)){
            $currentType = explode('/', $this->files[$name]['type'])[1];
            if(!in_array($currentType, $fileTypes)){
                $this->setError($name, "$name must be one of" . implode(', ', $fileTypes) . "types");
            }
        }
    }

    protected function maxFile($name, $fileSize)
    {
        $fileSize = $fileSize * 1024;
        if($this->checkFirstError($name) AND $this->checkFileExist($name)){
            if($this->files[$name]['size'] > $fileSize){
                $this->setError($name, "$name size must be lower than" . $fileSize / 1024 . "Kb");
            }
        }
    }

    protected function minFile($name, $fileSize)
    {
        $fileSize = $fileSize * 1024;
        if($this->checkFirstError($name) AND $this->checkFileExist($name)){
            if($this->files[$name]['size'] < $fileSize){
                $this->setError($name, "$name size must be upper than" . $fileSize / 1024 . "Kb");
            }
        }
    }
}