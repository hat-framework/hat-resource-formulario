<?php
$arr = array(
            'peso'  => 1,
            'comp'  => 20,
            'larg'  => 20,
            'alt'   => 20,
            'valor' => 1535
);

$this->Js->LoadPlugin("frete", "formulario", "frete");
$this->Js->frete->draw($arr);
