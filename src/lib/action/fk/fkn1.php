<?php

include_once __DIR__ . "/../especialAction.php";
class fkn1 extends fk{

    public function validar($value, &$array) {
        if(!array_key_exists("fkn1", $_SESSION)) return true;
        if(!array_key_exists($value['model'], $_SESSION['fkn1'])) return true;
        $out = array();
        foreach($_SESSION['fkn1'][$value['model']] as $arr){
            $out[] = unserialize($arr);
        }
        $array = $out;
        return true;
    }
    
    public function geraForm(){
        $model = $this->getModel();
        $this->LoadComponent($model, 'comp');
        $this->type       = new typeAction();
        $this->especial   = new especialAction();
        $this->fkaction   = new fkeyAction();
        $this->form       = new FormHelper();
        $this->form->autoprint();

        $data  = array();
        $this->executeFields($data);
        $html  = $this->getHtmlForm($model, $data);
        $this->add2Form($html, $model);
    }
    
    private $firstfield = "";
    private $addbefore = "";
    private $refmd = "";
    private function executeFields(&$data){
        $this->form->getFlush();
        $ref       = $this->getArray();
        $campos    = $this->model_obj->getDados();
        $lastinput = "";
        $dt        = array();
        $this->refmd      = isset($ref['fkey']['refmodel'])?$ref['fkey']['refmodel']:'';
        $this->firstfield = "";
        foreach($campos as $field => $camp){
            $this->executeCamp($campos, $field, $camp, $lastinput, $dt);
        }
        if($this->firstfield != ""){
            if(!isset($dt[$this->firstfield])) $dt[$this->firstfield] = "";
            $dt[$this->firstfield] .= $this->addbefore;
        }
        array_unshift($data, $dt);
    }
    
    private function getHtmlForm($model, $data){
        $this->comp->keepTags();
        $this->comp->removeListAction();
        $this->comp->addListAction('Salvar' , "#/$model/edit");
        $this->comp->addListAction('Remover', "#/$model/apagar");
        $this->comp->disableFormat();
        
        ob_start();
        $this->comp->listInTable($model, $data, '', '', true);
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
    
    private function toActions($field, &$camp, &$campos){
        $toactions = false;
        if(array_key_exists('fkey', $camp) && $this->refmd == $camp['fkey']['model'] && $this->refcamp == ""){
            $this->refcamp = $field;
            $camp['especial'] = 'hidden';
            $toactions = true;
        }elseif(array_key_exists('ai', $camp) && $camp['ai'] == true){
            $camp['especial'] = 'hidden';
            $toactions = true;
        }
        elseif(array_key_exists('display', $camp) && $camp['display'] == false  ||
            array_key_exists('button' , $camp)                               ||
            array_key_exists('private', $camp) && $camp['private'] == true) {
            unset($campos[$field]);
        }elseif($this->firstfield == "") {$this->firstfield = $field;}
        
        if(isset($camp['especial']) && $camp['especial'] == 'session'){
            unset($camp['especial']);
        }
        return $toactions;
    }
    
    private function setFormField($field, $camp){
        if(array_key_exists('especial', $camp) && $camp['especial'] == 'editor') return;
        if(array_key_exists('fkey', $camp)){
           $this->fkaction->executar($field, $camp['fkey'], $camp, $this->form);
        }
        elseif(array_key_exists('especial', $camp)){
            $this->especial->executar($field, $camp['especial'], $camp, $this->form);
        }
        elseif(array_key_exists('type', $camp)){
            $this->type->executar($field, $camp['type'], $camp, $this->form);
        }
        $this->added[$camp['name']] = true;
        return $this->form->getLastField(true);
    }
    /**
     *
     * @var string O refcamp é utilizado como referência do modelo de origem no modelo do formulário
     * Por ex.: Se categorias contém produtos, então refcamp será a coluna cat da tabela produtos
     */
    private $refcamp = "";
    private $added = array();
    private function executeCamp($campos, $field, $camp, &$lastinput, &$dt){
        if(!isset($camp['name']) || isset($this->added[$camp['name']])) return;
        $toactions = $this->toActions($field, $camp, $campos);
        if(!isset($campos[$field])) return;
        $input = $this->setFormField($field, $camp);
        if($input == "") {return;}
        if($lastinput == $input) {return;}
        $dt[$field] = $lastinput = $input;
        if($toactions){
            //$this->addbefore .= $lastinput;
        }
    }
    
    public function getData(){
        $dt = parent::getData();
        if(!is_array($dt)) $dt = array();
        return $dt;
    }
    
    private function add2Form($html, $model){
        $form       = $this->getForm();
        $array_data = $this->getArray();
        $name       = $this->getName();
        $caption    = @$array_data['name'];
        $desc       = @$array_data['description'];
        //$mdd        = str_replace("/", "_", $model);
        $this->LoadModel($model, 'model');
        $pkey = $this->model->getPkey();
        
        $val = $this->LoadModel($this->refmd, 'refmodel', false);
        if($val == null){throw new InvalidArgumentException(__CLASS__ . " - RefModel não definido no fkn1 $model");}
        $pkref = $this->refmodel->getPkey();
        if(is_array($pkref)) throw new InvalidArgumentException(__CLASS__ . " - Esta classe não suporta chaves compostas");
        
        $lk = $this->html->getActionLinkIfHasPermission("#/adicionar", "Adicionar", 'fkadd');
        $form->setExternData($name, $caption. " [$lk]", $html, $desc);
        
        $dt = json_encode($this->getData());
        $this->html->LoadJs(URL_JS . 'lib/core/min');
        $this->html->LoadJs(URL_JS . 'lib/formulario/fkn1');
        $pkey = (is_array($pkey))?json_encode($pkey):"'$pkey'";
        
        $pkvalue = $this->getForm()->getVar($pkref);
        $this->html->LoadJQueryFunction("
            var fk = new fkn1('$model', '#f_$name', '#$pkref', $pkey, '#{$this->refcamp}', '".URL."', '$pkvalue'); fk.bind($dt);
        ");
        $this->added = array();
    }
    
}