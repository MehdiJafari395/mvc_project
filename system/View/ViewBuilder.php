<?php

namespace System\View;

use System\View\Traits\HasExtendsContent;
use System\View\Traits\HasIncludeContent;
use System\View\Traits\HasViewLoader;

class ViewBuilder
{
    use HasViewLoader, HasExtendsContent, HasIncludeContent;

    public $content;
    public $vars = [];

    public function run($dir)
    {
        $this->content = $this->viewLoader($dir);
        $this->checkExtendsContent();
        $this->checkIncludesContent();
        Composer::setViews($this->viewNameArray);
        // اینجا یه مقدار کد هست که فعلا اضف نکردم. مربوط به قسمت 126
        $this->vars = Composer::getVars();
    }
}