<?php

use classes\Classes\JsPlugin;
class mobiscrollJs extends JsPlugin{
    
    public $file_sample = '';
    
    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance))self::$instance = new $class_name($plugin);
        return self::$instance;
    }
    
    public function init() {
        
        $this->Html->LoadJs("$this->url/js/mobis-2.7.min");
        //$this->Html->LoadJs("$this->url/js/mobis-2.7");
        //$this->Html->LoadJs("$this->url/js/mobiscroll-2.2.custom.min");
        $this->Html->LoadJs("$this->url/js/br");
        //$this->Html->LoadCss("plugins/formulario/mobiscroll");
        $this->Html->LoadCss("plugins/formulario/mobiscroll-2.7");
        $this->Html->LoadJs("$this->url/js/mscroller");
        
    }
    

    public function datetime($camp){
        $this->mscroll($camp, 'datetime');
    }
    
    public function time($camp){
        $this->mscroll($camp, 'time');
    }
    
    public function date($camp){
        $this->mscroll($camp, 'date');
    }

    public function draw($camp, $mindate = "", $maxdate = ''){
        $this->date($camp, $mindate, $maxdate);
    }
    
    private function mscroll($camp, $type){
        if($camp == ""){throw new \classes\Exceptions\resourceException(__CLASS__, "Campo não pode ser uma string vazia");}
        $opt = implode(", ", $this->options);
        $this->Html->LoadJQueryFunction("mScroller('input#$camp', '$type', { $opt });");
    }
    
    private $events  = "";
    private $options = array();
    public function setEvents($camp, $mindate, $maxdate){
        if($mindate != ""){
            $var = $this->selectType($camp, $mindate);
            $this->options[] = "min:function(){ return $var; }"; 
        }

        if($maxdate != ""){
            $var = $this->selectType($camp, $maxdate);
            $this->options[] = "max:function(){ return $var; }"; 
        }
    }
    
    public function clearEvents(){
        $this->options = array();
    }
    
    private function selectType($camp, $date){
        require_once DIR_BASIC. 'recursos/formulario/lib/especial/calendarEspecial.php';
        $cal = new calendarEspecial();
        
        if($cal->validate($camp, $date)){$r = "new Date('$date')";}
        elseif(strtolower($date) == 'now') {$r = "new Date()";}
        else {$r = "$('#$date').scroller('getDate')";}
        return $r;
    }
    
}

?>