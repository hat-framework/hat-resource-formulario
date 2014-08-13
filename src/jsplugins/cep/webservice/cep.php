<?php 
require_once '../../../../../init.php';
include('phpQuery-onefile.php');

$cep = $_GET['cep'];
$html = simple_curl('http://m.correios.com.br/movel/buscaCepConfirma.do',array(
	'cepEntrada'=>$cep,
	'tipoCep'=>'',
	'cepTemp'=>'',
	'metodo'=>'buscarCep'
));

phpQuery::newDocumentHTML($html, $charset = 'utf-8');

$dados = 
array(
	'logradouro'=> trim(pq('.caixacampobranco .resposta:contains("Logradouro: ") + .respostadestaque:eq(0)')->html()),
	'bairro'=> trim(pq('.caixacampobranco .resposta:contains("Bairro: ") + .respostadestaque:eq(0)')->html()),
	'cidade/uf'=> trim(pq('.caixacampobranco .resposta:contains("Localidade / UF: ") + .respostadestaque:eq(0)')->html()),
	'cep'=> trim(pq('.caixacampobranco .resposta:contains("CEP: ") + .respostadestaque:eq(0)')->html())
);

$dados['cidade/uf'] = explode('/',$dados['cidade/uf']);
$dados['cidade']    = isset($dados['cidade/uf'][0])?trim($dados['cidade/uf'][0]):"";
$dados['uf']        = isset($dados['cidade/uf'][1])?trim($dados['cidade/uf'][1]):"";
$dados['result']    = ($dados['uf'] == "")?"0":"1";

unset($dados['cidade/uf']);

die(json_encode($dados));