<?php

use classes\Classes\JsPlugin;
class meiomaskJs extends JsPlugin{
    
    
    public function init() {
        $this->LoadResource("html", "Html");
        $this->Html->LoadJquery();
    }
    
    
}

?>