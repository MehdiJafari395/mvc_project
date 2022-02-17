<?php

namespace System\View\Traits;

trait HasExtendsContent
{
    private $extendsContent;

    private function checkExtendsContent()
    {
        $layOutsFilePath = $this->findExtends();
        if($layOutsFilePath){
            $this->extendsContent = $this->viewLoader($layOutsFilePath);
            $yieldNamesArray = $this->findYields();
            if($yieldNamesArray){
                foreach ($yieldNamesArray as $yieldName){
                    $this->initialYields($yieldName);
                }
            }
            $this->content = $this->extendsContent;
        }
    }

    private function findExtends()
    {
        $filePathArray = [];
        preg_match("/s*@extends+\('([^])+'\)/", $this->content, $filePathArray);
        return isset($filePathArray[1]) ? $filePathArray[1] : false;
    }

    private function findYields()
    {
        $yieldNamesArray = [];
        preg_match_all("/@yield+\('([^])+'\)/", $this->extendsContent, $yieldNamesArray,
            PREG_UNMATCHED_AS_NULL);
        return isset($yieldNamesArray[1]) ? $yieldNamesArray[1] : false;
    }

    private function initialYields($yieldName)
    {
        $fileContent = $this->content;
        $startWorld = "@section(' . $yieldName .')";
        $endWorld = "@endsection";
        $startPos = strpos($fileContent, $startWorld);
        if ($startPos === false){
            return $this->extendsContent = str_replace("@yield(' . $yieldName . ')", '', $fileContent);
        }
        $startPos += strlen($startWorld);
        $endPos = strpos($endWorld, $fileContent);
        if ($endPos === false){
            return $this->extendsContent = str_replace("@yield(' . $yieldName . ')", '', $fileContent);
        }
        $length = $endPos - $startPos;
        $sectionContent = substr($fileContent, $startPos, $length);
        return $this->extendsContent = str_replace("@yield(' . $yieldName . ')", $sectionContent, $fileContent);
    }
}