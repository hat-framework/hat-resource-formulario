<?php

class dualEspecial extends especialInterface {
    
    public function validate($campo, &$valor){
        return true;
    }
    
    public function js($campo, $array, $form){
		$total = floor(12/count($array['dual']));
		if($total == 0){$total = 1;}
		$class = "col-xs-12 col-sm-6 col-md-$total";
		$form->addToForm("
			<div class=\"form_item form-group\" id=\"f_$campo\">
				<label for=\"$campo\" id=\"l$campo\" class=\"control-label caption col-xs-12\" style=\"padding:0;\">
					<span>{$array['name']}</span>
				</label>");
		foreach($array['dual'] as $name => $array){
			$array['placeholder'] = true;
			$form->setDado($name, $array);
			$form->addToForm("<div class=\"$class\" style=\"padding:0;\">");
			$this->printOneField($name, $array, $form);
			$form->addToForm("</div>");
		}
		$form->addToForm("</div>");
    }	
	
			private function printOneField($name, $array, $form){
				if($this->printEspecialField($name, $array, $form)){return;}
				$this->printTypeField($name, $array, $form);
			}
			
					private function printEspecialField($name, $array, $form){
						if(!array_key_exists("especial", $array)){return false;}
						$class = $array['especial'] . "Especial";
						$file  = dirname(__FILE__) . "/".$class . ".php";
						if(!file_exists($file)){return false;}
						require_once $file;
						if(!class_exists($class)){return false;}
						$obj = new $class();
						$obj->Js($name, $array, $form);
						return true;
					}
					
					private function printTypeField($name, $array, $form){
						if(!array_key_exists("type", $array)){return false;}
						$class = $array['type'] . "Type";
						$file  = dirname(__FILE__) . "/../types/".$class . ".php";
						if(!file_exists($file)){die($file);return false;}
						require_once $file;
						if(!class_exists($class)){die($class);return false;}
						$obj = new $class();
						$obj->setForm($form);
						$obj->formulario($name, $array, $array['name'], "", "");
						return true;
					}
	
	public function filter($name, $array){
		return;
	}
	
	public function format($dados, &$value){
		return;
	}
}