<?php

use classes\Classes\JsPlugin;
class captchaJs extends JsPlugin{
    
    public $file_sample = 'sample.php';
    public function load(){
        $this->LoadJsPlugin("formulario/jqueryvalidate", "js");
    }
    
    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance)) {
            self::$instance = new $class_name($plugin);
        }

        return self::$instance;
    }
    
    public function captcha($name){
        
        //inicializa os plugins
        $this->initCaptcha();
        
        //gera o formulario
        echo "<fieldset>";
        echo    "<div id='captchaimage'>";
        echo        "<a href='' id='refreshimg'title='Clique para carregar outra imagem'>";
        echo            "<img src='".URL_FORM."jqueryvalidate/demo/captcha/images/image.php?".time()."'";
        echo                 "width='132' height='46' alt='Captcha image' />";
        echo        "</a>";
        echo    "</div>";
        echo    "<label for='$name'>Digite o texto da imagem</label>";
        echo    "<input type='text' maxlength='6' name='$name' id='$name' />";
        echo "</fieldset>";
    }
    
    private function initCaptcha(){
        // Make the page validate
        @ini_set('session.use_trans_sid', '0');
        @session_start();
       
        if(!loadFormFile("jsplugins/jqueryvalidate/rand.php")) return false;
        
        $_SESSION['captcha_id'] = $str;
        
        //carrega os plugins
        $this->draw("");
        $function =
                "$('#refreshimg').click(function(){
                    $.post('".URL_FORM ."jqueryvalidate/demo/captcha/newsession.php');
                    $('#captchaimage').load('".URL_FORM ."jqueryvalidate/demo/captcha/image_req.php');
                    return false;
                });";
        $this->Html->LoadJsFunctions($function);

    }
}

?>
