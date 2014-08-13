<?php

class freteJs extends PluginComponent{

        public $file_sample = "example.php";
        public $project_url = "http://origin-webmasters.com.br/webmaster/plugins/formulario/frete";
        private $itens = array(
            'peso'  => '',
            'comp'  => '',
            'larg'  => '',
            'alt'   => '',
            'valor' => ''
        );
        
        static private $instance;
        public static function getInstanceOf($plugin){
            $class_name = __CLASS__;
            if (!isset(self::$instance)) {
                self::$instance = new $class_name($plugin);
            }

            return self::$instance;
        }
    
        public function draw($config = array()){
            
            //carrega os scripts
            $this->load();
            $this->LoadHelper("Form", "form");
            
            //se nao foram enviadas as configuracoes
            if(!is_array($config) || empty($config)){
                return false;
            }
            
            //verifica se todas as conf existem
            foreach($this->itens as $n => $v){
                if(!array_key_exists($n, $config)){
                    return false;
                }
                $this->form->HiddenField($n, $config[$n]);
            }
            
            //formulario
            $extras = "maxlength='8' size='9' onKeyUp='getFrete()'";
            $this->form->TextField('cep', "CEP" , "", $extras);
            
            $extra = "id='loading' width='20' height='20'";
            $this->Html->LoadImageAccessory("loading.gif", $legenda = "carregando", true, $extra);
            
            echo "<div id='calculo_frete'>
                    <hr>
                    <h3>Frete: R$ <b id='valor_frete'></b></h3><br/>
                    <h4>Prazo: <b id='prazo_frete'></b> dia uteis<h4>
                  </div>
                  <div id='erro_frete' class='response-msg error'>
                        <h3>Erro ao calcular Frete</h3>
                        <p id='erro_frete_msg'></p>
                  </div>
                  ";
        }
        
        public function load(){
            echo "<script>
                $(document).ready(function(){
                        $('#loading').hide();
                        $('#calculo_frete').hide();
                        $('#erro_frete').hide();
                   });
                   </script>";
            $this->Html->LoadJsFromPlugins("cep", $this->base_url ."frete.js");
            if(!DISABLE_EXTERN_CSS)$this->Html->LoadCss("formulario");
        }
}

?>