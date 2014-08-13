<?php

use classes\Classes\JsPlugin;
class jqtokeninputJs extends JsPlugin{

    static private $instance;
    private $prePopulate = array();
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance)) {
            self::$instance = new $class_name($plugin);
        }

        return self::$instance;
    }
    
    private $ref = "";
    public $file_sample  = "index.php";
    public $project_link = "http://loopj.com/jquery-tokeninput/";
    public function init(){
        $this->Html->LoadJs("$this->url/src/jquery.tokeninput");
        $this->Html->loadExternCss("$this->url/styles/token-input");
        //$this->Html->loadExternCss("$this->url/styles/token-input-facebook");
    }
    
    public function setForm($form){
        $this->form = $form;
    }
    
    private $query = "";
    public function setQuery($query){
        $this->query = $query;
    }
    
    public function autocomplete($campo, $model_search, $keys, $max_show = 10,
            $max_add_itens = "", $model_src = ""){
        $k1  = $keys[0];
        $k2  = $keys[1];
        $count = count($keys);
        $extra = "";
        if($count > 2){
            for($i = 2; $i < $count; $i++){
                $j = $i+1;
                $extra .= "&k$j=$keys[$i]";
            }
        }
        $query = ($this->query == "")?'':'&query='.base64_encode($this->query);
        $this->query = "";
        $pre = (empty ($this->prePopulate)?"":  "prePopulate: [".implode(",", $this->prePopulate) ."],");
        $url = "$this->url/php/actions/select.php?model=$model_search&k1=$k1&k2=$k2$extra$query&limit=$max_show";
        $max_add_itens = ($max_add_itens == "")? "": "tokenLimit: $max_add_itens,";
        $this->setPkey($model_src, $model_search, $keys);
        $action = $this->actions($model_src, $campo);
        $inputname = $this->getInputName($campo);
        $this->Html->LoadJQueryFunction(
            "$('$inputname').tokenInput('$url', {
                $pre $max_add_itens $action
                hintText: 'Digite um texto...',
                noResultsText: 'Nenhum resultado encontrado',
                searchingText: 'Pesquisando...',
                searchDelay: 50,
                minChars: 0,
                preventDuplicates: true
            });"
        );
        
        $this->LoadJsPlugin('formulario/jqueryvalidate', 'jsval');
        
        $add = '';
        foreach($this->prePopulate as $pre){
            $add .= "$('$inputname').tokenInput('add',$pre);";
        }
        $this->jsval->addToReset(" $('$inputname').tokenInput('clear'); $add");
        $this->prePopulate = array();
    }
    
    public function getInputName($campo){
        return "form#".$this->form->getFormName()." input#$campo";
    }
    
    public function addItem($id, $name, $readyonly = false){
        $readyonly = ($readyonly)?",'readyonly':true":'';
        $this->prePopulate[] = "{id:'$id',name:'$name'$readyonly}";
    }
    
    private function setPkey($model, $model_search, $keys){
        $this->LoadModel($model, 'fkmodel');
        $pkey  = $this->fkmodel->getPkey();
        //print_r($pkey);
        //print_r(array_keys($this->form->getVars()));
        //echo "<hr/>";
        $dados = $this->fkmodel->getDados();
        $this->pk1 = (is_array($pkey)? array_shift($pkey): $pkey);
        $this->pk2 = (is_array($pkey)? array_shift($pkey): $pkey);
        if(array_key_exists('fkey', $dados[$this->pk1])){
            $k = $dados[$this->pk1]['fkey']['keys'][0];
            $this->ref = ($k == $this->pk1)?$this->pk2:$this->pk1;
            //debugarray($dados);
            //die("1: $this->pk1 2:$this->pk2 ref:$this->ref ");
            if($this->ref == $this->pk2){
                $temp = $this->pk1;
                $this->pk1 = $this->pk2;
                $this->pk2 = $temp;
            }
        }
        /*echo "<br/><br/>";
        print_r($dados);
        echo "($model)<br/><br/>";*/
    }
    
    private function actions($model, $campo){
        $valor = $this->form->getVar($this->ref);
        //debugarray($this->form->getVars());
        //die("$this->ref - $model - $campo - $valor");
        if($valor == "") {
            $valor = $this->form->getVar($this->pk2);
            if($valor == "") return "";
            $temp = $this->pk1;
            $this->pk1 = $this->pk2;
            $this->pk2 = $temp;
        }
        
        $msg = $this->onDelete($model, $valor, $campo);
        if($msg != "") $msg .= " , ";

        $ms = $this->onAdd($model, $valor, $campo);
        if($ms != "") $msg .= "$ms , ";
        
        return $msg;
    }
    
    private function onAdd($model, $valor, $campo){
        
        $camp = $this->getInputName($campo);
        $action = "model=$model&pk1=$this->pk1&v1=$valor&pk2=$this->pk2";
        return "
            onAdd: function (item) {
                $.ajax({
                    url: '$this->url/php/actions/add.php?$action',
                    type: 'POST',
                    data: 'v2='+item.id,
                    dataType: 'json',
                    beforeLoad: function(){
                        $('#alert').html('Enviando Requisição').fadeIn('fast');
                    },
                    success: function(json) {
                        $('#alert').html('').fadeOut('fast');
                        if(json.status == 0){
                            if (typeof json.msg != 'undefined') {
                                $('$camp').tokenInput('remove', {id: item.id});
                                blockUI_error(json.msg);
                            }
                        }else{
                            if (typeof json.msg != 'undefined') {
                                $('#success').html(json.msg).fadeIn('fast', function(){
                                    $(this).delay('1500').fadeOut('slow', function(){
                                        $(this).html('');
                                    });
                                });
                            }
                        }
                    }
                });
            }";
    }
    
    private function onDelete($model, $valor){
        
        $action = "model=$model&pk1=$this->pk1&v1=$valor&pk2=$this->pk2";
        return "
            onDelete: function (item) {
                $.ajax({
                    url: '$this->url/php/actions/delete.php?$action',
                    type: 'POST',
                    data: 'v2='+item.id,
                    dataType: 'json',
                    beforeLoad: function(){
                        $('#alert').html('Removendo os dados...').fadeIn('fast');
                    },
                    success: function(json) {
                        $('#alert').html('').fadeOut('fast');
                        if(json.status != 0){
                            if (typeof json.msg != 'undefined') {
                                $('#success').html(json.msg).fadeIn('fast', function(){
                                    $(this).delay('1500').fadeOut('slow', function(){
                                        $(this).html('');
                                    });
                                });
                            }
                        }
                        else{ if (typeof json.msg != 'undefined') blockUI_error(json.msg); }
                    }
                });
            }";
    }
}

?>