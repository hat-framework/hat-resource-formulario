<?php

interface actionInterface{
    public function executar($name, $value, $array, $form);
    public function validar($name, $value, &$array);
	public function filter($name, $array);
    public function flush();
}
