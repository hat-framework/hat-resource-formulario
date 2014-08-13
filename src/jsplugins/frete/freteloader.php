<?php
try{
        require_once("FreteHelper.php");
        ini_set('default_charset','utf-8');
        $cep   = 0; $peso  = 0; $comp  = 0; $larg  = 0; $alt   = 0; $valor = 0;
        extract ($_GET, EXTR_OVERWRITE, "");
        if($cep == "" || $peso == "" || $comp == "" || $larg == "" || $alt == "" || $valor == ""){
            exit ("var resultado={'MsgErro' : 'Dados incorretos ou não informados'}");
        }

	// PAC sem contrato
	// Para usar com contrato basta adicionar os par�metros � construtora
	// $ship = new CorreiosShipping('123456', '654321');
	$ship = FreteHelper::getInstanceOf();
	$ship->setService('pacSC');
	$ship->setZipFrom('33.925-380');
	$ship->setZipTo($cep);
	$ship->setItemWeight($peso);
	$ship->setItemForm(1);
	$ship->setItemLength(intval($comp));
	$ship->setItemHeight(intval($alt));
	$ship->setItemWidth(intval($larg));
	$ship->setItemDiameter(0);
        $ship->setValor($valor);
	$ship->setOption('MaoPropria', 'S');
	$ship->setOption('Avis', 'S');
	$ship->request();
        
        $var = $ship->getResultAsJavascript();
        
	// Se n�o houver erro, retorna Erro como 0, e avalia como falso
	if(!$ship->getResult()->Erro)
	{
            echo $var;
	}
	else
	{
            echo 'var resultado={"MsgErro" : "' . $ship->getResult()->MsgErro . '"}';
	}
        
}catch (Exception $e){
        echo "var resultado={'MsgErro' : '". $e->getMessage() ."'}";
}
?>
