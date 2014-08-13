<?php

use classes\Classes\JsPlugin;
class cepJs extends JsPlugin{

    public $file_sample = "sample.php";

    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance))self::$instance = new $class_name($plugin);
        return self::$instance;
    }

    public function init(){
        $this->Html->LoadJs("$this->url/scripts/cep");
        //$this->Html->LoadJs(URL."static/js/lib/baseurl");
    }

    /*
     * recebe os ids dos elementos cep, rua, bairro,..
     */
    public function endereco($cep, $rua, $bairro, $cidade, $estado){
        $function = "            
        $('#$cep').live('keyup', function(){
            getEndereco('$cep', '$rua', '$bairro', '$cidade', '$estado');
        });";
        $this->Html->LoadJQueryFunction($function);
    }

    public function frete($arr){
        $this->Js->LoadPlugin("frete", "formulario", "frete");
        $this->Js->frete->draw($arr);
    }
}

?>