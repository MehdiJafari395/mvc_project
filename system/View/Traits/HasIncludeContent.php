<?php

namespace System\View\Traits;

trait HasIncludeContent
{
    private function checkIncludesContent()
    {
        while (1){
            $includeNamesArray = $this->findIncludes();
            if(!empty($includeNamesArray)){
                foreach ($includeNamesArray as $includeName){
                    $this->initialIncludes($includeName);
                }
            }else{
                break;
            }
        }
    }

    private function findIncludes()
    {
        $yieldNamesArray = [];
        preg_match_all("/@yield+\('([^])+'\)/", $this->extendsContent, $yieldNamesArray,
            PREG_UNMATCHED_AS_NULL);
        return isset($yieldNamesArray[1]) ? $yieldNamesArray[1] : false;
    }

    private function initialIncludes($includeName)
    {
        $this->content = str_replace("@include(' . $includeName . ')", $this->viewLoader($includeName),
            $this->content);
    }
}