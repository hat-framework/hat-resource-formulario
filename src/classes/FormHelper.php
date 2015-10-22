<?php

use classes\Classes\Object;
class FormHelper extends classes\Classes\Object{

    public $Js = array();
    public $validator;
    public $html;
    private $formulario = "";
    private $fieldset = 0;
    private $isprintable;
    private $autoprint = false;
    private $autobr = true;
    private $omitir; 
    private $button;
    private $flush = "";
    private $ajax = false;
    private $vars = array();
    private $concat = "";
    private $model  = "";
    private $dados = array();
    private $nnform = array();
    private $post = array();
    private $action = "";
    private static $i = 0;
    
    public function  __construct() {
        if(DEBUG) $this->concat = "\n\t\t";
        //$this->LoadHelper("Js"          , "Js");
        $this->LoadResource("html", "html")->addSytle("#antispam, #lantispam {display: none !important;} .description-text{display:none;}");;
        $this->validator = validatorResource::getInstanceOf();
        $this->isprintable = true;
        $this->omitir      = false;
        $this->button      = false;
    }

    static private $instance;
    public static function getInstanceOf(){
        if (!isset(self::$instance)){
            $class_name = __CLASS__;
            self::$instance = new $class_name;
        }
        return self::$instance;
    }

    private function Enctype($enctype){
        if    ($enctype == "arquivo") {return("multipart/form-data");}
        elseif($enctype == "text"   ) {return("text/plain");}
        return ("application/x-www-form-urlencoded");
    }
    
    private $method = "post";
    public function setMethod($method){
        if(!in_array($method, array('post', 'get'))) {return;}
        $this->method = $method;
        if($this->method == 'get'){
            $this->post = $_GET;
            $this->absurl = true;
        }else{$this->post = $_POST;}
    }

    /*
     * Cria um novo formulario
     */
    public function Open($action = "", $enctype="", $ajax = true, $abs_url = false){
        self::$i++;
        $i = self::$i;
        $this->html->LoadJs(URL_JS."/lib/formulario/description");
        $this->act    = $action;
        $this->absurl = $abs_url;
        if($this->formulario == "" && defined('LINK')&& defined('CURRENT_ACTION')){
            $this->formulario = ($this->model == "")?str_replace('/','_',LINK)."_".CURRENT_ACTION."_formulario$i":str_replace("/", "_", $this->model);
        }else{$this->formulario = "formulario$i";}
        $this->ajax = $ajax;
	$this->enctype = $enctype;
        $this->started = false;
        $this->LoadJsPlugin('formulario/jqueryvalidate', 'jval');
        $this->setCurrentForm($this->formulario, true);
    }
    
    public $jval;
    public function setCurrentForm($form, $force_assign = false){
        if($form != $this->formulario || $force_assign){
            $this->formulario = $form;
            $this->LoadJsPlugin('formulario/jqueryvalidate', 'jval')->setCurrentForm($this->formulario);
        }
    }
    
    private function header(){
        $enctype = $this->Enctype($this->enctype);
        static $i = 1;
        $var = "<div id='c_$this->formulario' class='div-formulario col-xs-12'>";
        
        $v = $this->getMethod();
        if(!$this->omitir){
            $var .=    "<form action='$this->action' method='$this->method' id='$this->formulario' class='formulario' enctype='$enctype'>$v";
            $var .=         $this->text("antispam", "Não Preencha");
        }
        $this->flush =  $var . $this->flush;
        
    }
    
    private function getMethod(){
        $v    = "";
        if($this->method !== 'get'){
            if($this->act == "") $this->act = CURRENT_URL;
            if((!$this->absurl))$this->action = $this->html->getLink($this->act, false, true);
            else                $this->action = $this->act;
        }
        else{
            $u = (defined('URL'))?URL:"";
            $this->action = "{$u}index.php";
            $v = $this->hidden("url", "$this->act");
        }
        return $v;
    }

    /*
     * Fecha uma formulario aberto
     */
    public function Close(){
        $this->html->loadCss('formulario');
        $this->header();
        if(!$this->button && $this->started){
            $this->addToForm("<br/>");
            if(!$this->omitir){
                $this->SenderButton();
            }
            //$this->ClearButton();
            //$this->CloseFieldSet();
        }
        
        $this->CloseAllFieldSets();
        $var = "";
        if(!$this->omitir){$var = "</form>";}
        $var .= "</div> $this->append";
        $this->printScreen($var);
        
        $v = $this->flush($this->isprintable);
        $this->genValidation();
        //$this->html->Flush(); 
        $this->reset();
        return $v;
        
    }
    
    private function reset(){
        $this->button     = false;
        $this->started    = false;
        $this->flush      = "";
        $this->vars       = array();
        $this->model      = "";
        $this->formulario = "";
    }
    
    public function genValidation(){
        $this->LoadJsPlugin("formulario/jqueryvalidate", "jsValidator");
        $this->jsValidator->validate($this->formulario ,$this->ajax);
    }
    
    public function getFormName(){
        return $this->formulario;
    }
    
    public function flush($print = true){
        if(!$this->started) return;
        $this->drawNNForm();
        
        $this->LoadScripts();
        $flush = $this->flush;
        $this->flush = "";
        
        if(!DEBUG)  $flush = str_replace(array("  ", "\n", "\t"), "", $flush);
        if(!$print) return $flush;
        echo $flush;
        return "";
        
    }
    
    public function setVars($vars = "", $valor = "", $force_null = false){
        if($valor != "" || $force_null) {$this->vars[$vars] = $valor;}
        elseif(is_array($vars))         {$this->vars        = $vars;}
    }

    public function setDados($dados = ""){
        $this->dados = $dados;
    }
    
    public function getDados(){
        return $this->dados;
    }

    public function getDado($key){
        return (array_key_exists($key, $this->dados))? $this->dados[$key]:"";
    }

    public function getVars(){
        return $this->vars;
    }
    
    public function getVar($key){
        $ret = "";
        if(array_key_exists($key, $this->vars)){
           $ret = $this->vars[$key];
        }
        return $ret;
    }
    
    public function setVar($key, $value){
        if(array_key_exists($key, $this->vars)){
           $this->vars[$key] = $value;
        }
    }
    
    public function unsetVar($key){
        if(array_key_exists($key, $this->vars)){
           unset($this->vars[$key]);
        }  
    }
    
    public function hasVar($key){
        return array_key_exists($key, $this->vars);
    }
    
    public function setModel($Model){
    	$this->model = $Model;
    }
    
    public function getModel(){
        return $this->model;
    }
    
    public function SetCustomCamp($camp){
        $this->printScreen($camp);
    }
    
    public function setExternData($name, $caption, $data, $desc){
        $this->printfield($name, $caption, $data, $desc);
    }
    
    public function setData($value){
        $this->printScreen($value);
    }
    
    public function setNNForm($link, $value){
        $arr['link']  = $link;
        $arr['value'] = $value;
        $this->nnform[] = $arr;
    }
    
    private function drawNNForm(){
        $before = $flush = "";
        
        if(!empty ($this->nnform)){
            $flush = '<div class="painel-item">';
                foreach ($this->nnform as $var){
                    $flush .= '<div class="fkn1 border" style="overflow:hidden;">';
                    $flush      .= '<div class="link">'.$var['link'].'</div><br/><br/><br/>';
                    $flush      .= '<div class="grid">'.$var['value'].'</div>';
                    $flush .= '</div>';
                }
            $flush .= '</div>';
            $this->flush = "$this->flush $flush $before";
            $this->nnform = array();
        }
        
    }
    
    private $append = "";
    public function append($html){
        $this->append .= $html;
    }
    
    
    /*
     * Cria um textfield
     */
    public function text($name, $caption = "", $value = "", $desc = "", $extras = "", $type='text'){
        
        $id = GetPlainName($name, false);
        //$question  =  $this->validator->FieldText($id);
        //$temp      = array_key_exists($id, $this->post) ? $this->post[$id] : "";
        $temp      = array_key_exists($name, $this->vars) ? $this->vars[$name] : "";
        $valor     =  ($temp == "")? $value : $temp;
        $this->hasPlaceholder($name, $caption, $extras);
        if(is_array($valor)) {$valor = "";}
        $var = "<input type='$type' $extras id='$id' name='$id' class='form-control' value='$valor' description='$desc'/>";
        $this->started = true;
        $this->printfield($name, $caption, $var, $desc);
    }


    /*
     * Cria uma text area
     */
    public function textarea($name, $caption = "", $value = "", $desc = "", $extra = "", $class=''){

            //if(MOBILE) $this->LoadJsPlugin ('formulario/autosize', 'asz');
            $id = GetPlainName($name, false);
            $temp  = array_key_exists($name, $this->vars) ? $this->vars[$name] : $value;
            $valor = (array_key_exists($id, $this->post))? $this->post[$id] : $temp;
            $ex    = "description='$desc' cols='23' rows='8'";
            $extra = trim((trim($extra) == "")?$ex:"$extra $ex");
            $var = "<textarea class='form-control $class' $extra id='$id' name='$id'>$valor</textarea>";

            $this->started = true;
            $this->printfield($name, $caption, $var, $desc, "<br/>");
    }

    /*
     * cria um select
     */
    public function select($name, $arr, $selected = "", $caption = "", $desc = "", $extra = ""){
            $multi = strstr($name, '[]');
            $id = GetPlainName($name, false) ;
            $idname = "$id$multi";
            
            $var  = "<select id='$id' class='$id form-control' name='$idname'>";
            $selected_tag = " class='selected' selected=true ";
            /*if($selected != "")
                $var .= "<option value=''>Selecione uma opção</option>";*/
            if(!empty ($arr)){
                foreach($arr as $value => $key){
                    $var .= "<option value='".$value."'";
                    
                    //se é uma variavel post
                    if(array_key_exists($id, $this->post) && $this->post[$id] == $value){
                        $var .= $selected_tag;
                    }
                    
                    //se valor foi selecionado previamente
                    elseif(array_key_exists ($name, $this->vars)){
                        if(is_array($this->vars[$name])){
                            if(array_key_exists($value, $this->vars[$name])){
                                $var .= $selected_tag;
                            }
                        }
                        elseif($this->vars[$name] == $value){
                            $var .= $selected_tag;
                        }
                    }
                    
                    //se o valor deveria ser selecionado por default
                    elseif($value == $selected && $selected != ""){
                         $var .= $selected_tag;
                    }
                    
                    $var .= ">".$key."</option>";
                }
            }
            $var .= "</select>";
            $this->started = true;
            $desc = is_array($desc)?implode("<br/>", $desc):$desc;
            $this->printfield($name, $caption, $var, $desc);
    }
    
    public function radioCamp($name, $array, $selected = "", $caption = "", $desc = "", $extra = "", $br = false){
        $this->lastField = "";
        $id = GetPlainName($name);
        $this->printScreen("<div class='form_item radiocamp' id='f_$id'>");
        $descricao = $desc;
        if(is_array($desc)){
            if(array_key_exists($name, @$desc)){
                $descricao = $desc[$name]."<br/><br/>";
                unset($desc[$name]);
            }else $descricao = "";
            foreach($desc as $d => $v){
                $nm         = @$array[$d];
                $descricao .= "<b>$nm: </b> $v <br/><br/>";
            }
        }
        
        $desc = $this->MakeDescription($name, $descricao);
        $this->MakeLabel($name, "$caption $desc");
        foreach($array as $valor => $nome){
            
            //if($br && $this->autobr) $this->printScreen("<br/>");
            $vl = is_array($desc)? array_key_exists($valor, $desc)?$desc[$valor]:"":"";
            $this->radio($name, $valor, $nome, $selected, $vl);
            //if($br && $this->autobr) $this->printScreen("<br/>");
            
        }
        //if($br && $this->autobr) $this->printScreen("<br/>");
        $this->MakeAlert($name);
        $this->printScreen("</div>");
        $this->started = true;    	
        
    }

    /*
     * cria um radioButton
     */
    public function radio($field_name, $valor, $texto = "", $selected = ""){
        $id = GetPlainName($field_name);
        $in = GetPlainName($valor);
        $v    = "<input type='radio' name='$id' class='$id' id='$in' value='$valor'";
        
        $formval =  $this->getVar("__$field_name");
        //Deveria estar checkado?
        //echo "$field_name - $valor - $selected - $formval<br/>";
        if("$valor" != ""){
            if(array_key_exists($id, $this->post)){
                if($this->post[$id] == "$valor"){$v .= " checked ";}
            }
            elseif($formval != ""){
                if("$valor" == "$formval") $v .= " checked ";
            }
            elseif($selected != ""){
                if("$valor" == "$selected") $v .= " checked ";
            }
        }
        //$desc = $this->MakeDescription($field_name, $description);
        $v .= " /><span>$texto</span>";
        $this->lastField .= $v;
        $var = "<div class='radiooption $in'>$v</div>";
        $this->started = true;
        $this->printScreen($var);
    }
    
   public function checkCamp($name, $array, $selected = array(), $caption = "", $desc = "", $extra = ""){
        $this->lastField = "";
        $id   = GetPlainName($name, false);
        $this->printScreen("<div class='form_item radiocontainer' id='f_$id'>");
        $descricao = $desc;
        if(is_array($desc)){
            $descricao = $desc[$name]."<br/><br/>";
            unset($desc[$name]);
            foreach($desc as $d => $v){
                $descricao .= "<b>$d: </b> $v <br/><br/>";
            }
        }
        $this->MakeLabel($name, $caption, $descricao);
        $name = "$name"."[]";
        
        $this->printScreen("<div class='radiocamp'>");
        foreach($array as $valor => $nome){
            $select = (is_array($selected) && in_array($valor, $selected))?$valor:$selected;
            //$vl = is_array($desc)? array_key_exists($valor, $desc)?$desc[$valor]:"":"";
            $vl = "";
            $this->checkbox($name, $valor, $nome, $select, $vl);
        }
        $this->printScreen("</div>");
        $this->started = true;
        $this->printScreen("</div>");
        
   }

    /*
     * Cria um checkbox
     */
     public function checkbox($field_name, $valor, $texto, $selected = "", $description = '', $extra = ""){
        $id      = GetPlainName($valor, false);
        $class   = GetPlainName($field_name, false);
        $name    = $class . "[]";
        $var     = "<input type='checkbox' $extra name='$name' class='$class' id='$id' value='$valor'";
	$nome    = str_replace("[]", "", $name);
        $formval =  $this->getVar($field_name);
        if($formval === ""){
            $formval = $this->getVar($nome);
        }
        
        if(array_key_exists($nome, $this->post)){
            if(is_array($this->post[$nome])){
                $qtd = count($this->post[$nome]);
                for($i = 0; $i < $qtd; $i++ ){
                    if($this->post[$nome][$i] == $valor){
                        $var .= " checked='checked' ";
                        break;
                    }
                }
            }
            elseif($this->post[$nome] == $valor) $var .= " checked='checked' ";
        }
        elseif($valor == $formval  && $formval  != "")          {$var .= "checked='checked' ";}
        elseif($valor == $selected && $selected != "")          {$var .= "checked='checked' ";}
        elseif(is_array($formval) && in_array($valor, $formval)){$var .= "checked='checked' ";}
        
        $desc = $this->MakeDescription($field_name, $description);
        $var .= " /> <span>$texto</span> ";
        $this->lastField .= $var;
        $var .= "$desc<br/>";
        $this->started = true;
        $this->printScreen($var);
    }

    /*
     * Cria um campo hidden
     */
    public function hidden($name, $valor){

        $multi = strstr($name, '[]');
        $id = GetPlainName($name, false) ;
        $id = "$id$multi";
        
        $temp      = array_key_exists($id, $this->post) ? $this->post[$id] : "";
        $temp      = array_key_exists($name, $this->vars) ? $this->vars[$name] : $temp;
        $valor     = ($valor != "")? $valor : $temp;
        if(is_array($valor)) $valor = "";
        //if($valor != ""){
            $var = "<input type='hidden' id='$id' name='$id' value='$valor' />";
            $this->lastField = $var;
            $this->started = true;
            $this->printScreen($var);
        //}
    }

    /*
     * Cria um campo do tipo password
     */
    public function password($field_name, $label = "", $maxlength = 32, $desc = "", $extras = ""){
        $id = GetPlainName($field_name, false);
        $this->hasPlaceholder($field_name, $label, $extras);
        $var = "<input type='password' id='$id' name='$id' class='form-control' maxlength='$maxlength' $extras/>";
        $this->started = true;
        $this->printfield($field_name, $label, $var, $desc);
        
    }
    
    /*
     * Botao de upload
     */
    public function file($field_name, $texto = "", $desc = ""){
    	$this->enctype = 'arquivo';
    	$this->ajax    = false;
        $id = GetPlainName($field_name, false);
        $var = "<input name='$id' type='file' id='$id'/>";
        $this->printfield($field_name, $texto, $var, $desc);
    }

    /*
     * Funcoes do formulario
     */
    public function FieldSet($fieldset = "", $legend = ""){

        $this->CloseFieldSet();
        $this->fieldset++;
        $var = "<fieldset id='$fieldset'>";
        if($legend != ""){
            $var .= "<legend>$legend</legend>";
        }
        $this->printScreen($var);
    }
    
    public function CloseFieldSet(){
        if($this->fieldset > 0){
            $this->fieldset--;
            $var =  "</fieldset>";
            $this->printScreen($var);
        }
    }

    public function CloseAllFieldSets(){
        while($this->fieldset > 0){
            $this->CloseFieldSet();
        }
    }

   //Limpa formulario
    public function ClearButton(){
        $this->Button($name = 'limpar', $value = 'Limpar');
    }
    
    public function Button($name, $value, $class = "", $icon = ''){
        if($icon !== ''){$icon = "<i class='$icon'></i>";}
        $var = "<div class='btn-container'>".
                    "<button $class id='$name'>$icon $value</button>".
                "</div>";
        $this->lastField = $var;
        $this->printScreen($var);
    }

    /*
     * Envia itens
     */
    public function SenderButton(){
        $this->submit("enviar", "Enviar");
    }

    /*
     * Cria um novo botao
     */
    public function submit($field_name, $value, $extra = ""){
        $this->button = true;
        
        $class = classes\Classes\Template::getClass('formbutton');
        if($class === ""){$class = 'btn btn-inverse';}
        $cls = (strstr($extra, 'class'))?"":"class='$class'";
        $var = "<div class='btn-container'><input name='$field_name' type='submit' id='$field_name' value='$value' $extra $cls/></div>";
        $this->lastField = $var;
        $this->printScreen($var);
    }
    
    public function addToForm($var){
        $this->printScreen($var);
    }
    
    private $lastField = "";
    public function getLastField($clear = false){
        $v = $this->lastField;
        if($clear) $this->lastField = "";
        return $v;
    }

    /*
     * carrega os scripts necessarios
     */
    private function LoadScripts(){
        if(!DISABLE_EXTERN_CSS)$this->html->LoadCss("formulario"); 
        foreach($this->jscript as $type => $fn){
            if($type)
                $this->html->LoadJqueryFunction("function __{$this->formulario}_jqfn(){ $fn } __{$this->formulario}_jqfn(); ");
            else $this->html->LoadJsFunction("function __{$this->formulario}_jsfn(){ $fn } __{$this->formulario}_jsfn();");
        }
    }
    
    private $jscript = array();
    public function addAction2Form($function, $jquery = true){
        if(!isset($this->jscript[$jquery])) $this->jscript[$jquery] = "";
        $this->jscript[$jquery] .= " $function";
    }
    
    private function printfield($field_name, $label, $var, $desc, $alert_extra = ""){
        $this->lastField = $var;        
        $id = GetPlainName($field_name);
        $this->printScreen("<div class='form_item form-group' id='f_$id'>");
        $this->MakeLabel($field_name, $label . $this->MakeDescription($field_name, $desc));
        $this->printScreen($var);
        $this->MakeAlert($field_name, $alert_extra);
        $this->printScreen("</div>");
    }
    
    /*
     * Cria um novo label
     */
    private function MakeDescription($campo, $descricao){
        if(is_array($descricao) || trim($descricao) == "") return;
        
        $c = (DEBUG)?"\t":"";
        $campo = GetPlainName($campo, false);
            $var = '<a '
                    . 'data-toggle="tooltip" '
                    . 'data-placement="right" '
                    . 'title="'.$descricao.'">'
                    . '<span class="glyphicon glyphicon-question-sign desc"></span>'
                    . '</a>';
            $this->LoadResource('html', 'html')->LoadJQueryFunction('$("[data-toggle=tooltip]").tooltip();');
        
        return ($c . $var);
    }
    
    /*
     * Cria um novo label
     */
    private function MakeLabel($campo, $Texto){
        $var    = "";
        $campo  = GetPlainName($campo, false);
        if($Texto != ""){$Texto = "<span>$Texto</span>"; }
        if($Texto != ""){
            $var = "<label for='$campo' id='l$campo' class='control-label caption'>{$Texto}</label>";
        }
        $this->printScreen($var);
    }

     /*
     * Cria um novo alerta
     */
    private function MakeAlert($field_name, $alert_extra = ""){
        $field_name = GetPlainName($field_name, false);
        $answer = $this->validator->Answer($field_name);
        if($answer != "") $answer = "<br/>$answer<br/>";
        $var = "$alert_extra<span id='v$field_name' class='control-label response-msg'>$answer</span>";
        $this->printScreen($var);
    }
    
    private function hasPlaceholder($name, &$caption, &$str){
        $dado    = $this->getDado($name);
        if(!isset($dado['placeholder']) || $dado['placeholder'] === false){return false;}
        
        if($dado['placeholder'] === true){
            $str    .= "placeholder='$caption'";
            $caption = "";
        }else{ $str .= "placeholder='{$dado['placeholder']}'";}
        return true;
    }
    
    private function printScreen($value){
        $this->flush .= $this->concat . $value;
    }
    
    public function printable(){
    	$this->isprintable = false;
    }
    
    public function autoprint(){
        $this->autobr    = false;
    	$this->autoprint = true;
    }
    
    public function omitir_cabecalho(){
    	$this->omitir = true;
    }
    
    public function getFlush(){
        $var = $this->flush;
        $this->flush = "";
    	return $var;
    }

}