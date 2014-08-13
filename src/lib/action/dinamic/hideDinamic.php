<?php

class hideDinamic extends DinamicForm{

    public function execute(){
        $this->LoadResource('html', 'html');
        $this->html->LoadJqueryFunction("
            if($('.$this->target:checked, select.$this->target option:selected').attr('value') == '$this->option'){
                $('#$this->id').hide();
            }
            $('.$this->target').change(function() {
                if($(this).attr('value') != '$this->option'){
                   $('#$this->id').slideDown('fast');
                }else{
                   $('#$this->id').slideUp('fast');
                }
            });
        ");
    }
    
    public function flush() {
        
    }
    
}

?>