<?php

use classes\Classes\JsPlugin;
class calendarJs extends JsPlugin{
    
    public $file_sample = 'index.php';
    
    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance))self::$instance = new $class_name($plugin);
        return self::$instance;
    }
    
    public function init() {
        $this->LoadJsPlugin('jqueryui/jqueryui', 'ui');
        $this->Html->LoadJs("$this->url/jquery-calendar");
        $this->Html->LoadJs("$this->url/time_picker");
        $this->Html->LoadJs("$this->url/jquery-ui-timepicker-addon");
    }
    
    public function draw($camp){
        if($camp == ""){
            throw new \classes\Exceptions\resourceException(__CLASS__, "Campo não pode ser uma string vazia");
        }
        $function = "$('#$camp').calendar({autoPopUp: 'both', buttonImageOnly: true, buttonText: 'Calendar'});";
        $this->Html->LoadJQueryFunction($function);
    }
    
    
    private $addthis = "
        timeOnlyTitle: 'Horário',
	timeText: 'Horário',
	hourText: 'Hora',
	minuteText: 'Minuto',
	secondText: 'Segundo',
	currentText: 'Agora',
	closeText: 'Concluir',
        addSliderAccess: true,
        sliderAccessArgs: { touchonly: false },
        hourGrid: 4,
        minuteGrid: 10,
        dateFormat: 'dd/mm/yy',
    ";
    public function datetime($camp){
        if($camp == ""){throw new \classes\Exceptions\resourceException(__CLASS__, "Campo não pode ser uma string vazia");}
        $function = "$('#$camp').datetimepicker({
            autoPopUp: 'both', buttonImageOnly: true, buttonText: 'Calendar',
            $this->addthis
         });";
        $this->Html->LoadJQueryFunction($function);
    }
    
    public function time($camp){
        if($camp == ""){throw new \classes\Exceptions\resourceException(__CLASS__, "Campo não pode ser uma string vazia");}
        $this->Html->LoadJQueryFunction(
        "$('#$camp').timepicker({ $this->addthis });");
        
    }
    /*
    public function restrictDate($startDateTextBox, $endDateTextBox){
        $this->Html->LoadJQueryFunction(
        "var $startDateTextBox = $('#$startDateTextBox');
         var $endDateTextBox = $('#$endDateTextBox');
         $startDateTextBox.datetimepicker({ 
                onClose: function(dateText, inst) {
                        if ($endDateTextBox.val() != '') {
                                var testStartDate = $startDateTextBox.datetimepicker('getDate');
                                var testEndDate = $endDateTextBox.datetimepicker('getDate');
                                if (testStartDate > testEndDate)
                                        $endDateTextBox.datetimepicker('setDate', testStartDate);
                        }
                        else {
                        alert(dateText);
                                $endDateTextBox.val(dateText);
                        }
                },
                onSelect: function (selectedDateTime){
                        $endDateTextBox.datetimepicker('option', 'minDate', $startDateTextBox.datetimepicker('getDate') );
                }
         });
         $endDateTextBox.datetimepicker({ 
                onClose: function(dateText, inst) {
                        if ($startDateTextBox.val() != '') {
                                var testStartDate = $startDateTextBox.datetimepicker('getDate');
                                var testEndDate = $endDateTextBox.datetimepicker('getDate');
                                if (testStartDate > testEndDate)
                                        $startDateTextBox.datetimepicker('setDate', testEndDate);
                        }
                        else {
                                $startDateTextBox.val(dateText);
                        }
                },
                onSelect: function (selectedDateTime){
                        startDateTextBox.datetimepicker('option', 'maxDate', $endDateTextBox.datetimepicker('getDate') );
                }
         });
        ");
    }
    */
}

?>