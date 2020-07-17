<?php

include 'env.php';

$c_codigo = $_POST['idcadastro'];
$idcarrinho = $_POST['idcarrinho'];
$valor = $_POST['valor'];

$endereco = mysql_fetch_assoc(mysql_query('select * from cadastros_enderecos WHERE idcadastro = ' . $c_codigo));

$cadastro = mysql_fetch_assoc(mysql_query('select * from cadastros WHERE codigo = "' . $c_codigo . '"'));

$_POST['cpf'] = str_replace(".", "", $_POST['cpf']);
$_POST['cpf'] = str_replace("-", "", $_POST['cpf']);


	//$idcarrinho = $_SESSION['idcarrinho'];
/*var_dump('select * from cadastros_enderecos WHERE idcadastro = ' . $c_codigo);
var_dump('select * from cadastros WHERE codigo = "' . $c_codigo . '"');
var_dump($endereco);
var_dump($cadastro);
die('cc');*/
//$valor = $dadosProduto->valor;
//$valor = str_replace(",", ".", $valor);
if($urlPagseguro=="https://ws.sandbox.pagseguro.uol.com.br/v2/"){
    $_POST['email'] = "c55645138300414230185@sandbox.pagseguro.com.br";
}
$valor = number_format($valor, 2, '.', '');
include 'gerarXml.php';
$xml = gerarXmlBoleto($_POST['id'], "Produtos", $valor, $_POST["nome"].' '. $_POST["sobrenome"], $_POST['cpf'], substr($_POST['telefone'], 0, 2), substr($_POST['telefone'], 2), $_POST['email'], $_POST['senderHash'], $_POST['endereco'], $_POST['numero'], $_POST['complemento'], $_POST['bairro'], $_POST['cep'], $_POST['cidade'], $_POST['estado']);

/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $urlPagseguro . "transactions/?email=" . $emailPagseguro . "&token=" . $tokenPagseguro);
curl_setopt($ch, CURLOPT_POST, true );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml; charset=ISO-8859-1'));

$data = curl_exec($ch);
$dataXML = simplexml_load_string($data);

if (empty($dataXML->paymentLink)) {
	header('Content-Type: application/json; charset=UTF-8');
	$errosOcorridos = array('erro' => '1');
	echo json_encode($dataXML);
} else {
	header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($dataXML);
}
curl_close($ch);*/

/*
include 'templates/dadosProduto.php';
$dadosProduto = json_decode(decodificar($_POST['id']));

$_POST['cpf'] = str_replace(".", "", $_POST['cpf']);
$_POST['cpf'] = str_replace("-", "", $_POST['cpf']);

$valor = $dadosProduto->valor;
$valor = str_replace(",", ".", $valor);

include 'gerarXml.php';
$xml = gerarXmlBoleto($_POST['id'], $dadosProduto->desc, $valor, $_POST['nome'], $_POST['cpf'], $_POST['ddd'], $_POST['telefone'], $_POST['email'], $_POST['senderHash'], $_POST['endereco'], $_POST['numero'], $_POST['complemento'], $_POST['bairro'], $_POST['cep'], $_POST['cidade'], $_POST['estado']);*/



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $urlPagseguro . "transactions/?email=" . $emailPagseguro . "&token=" . $tokenPagseguro);
curl_setopt($ch, CURLOPT_POST, true );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml; charset=ISO-8859-1'));

$data = curl_exec($ch);
$dataXML = simplexml_load_string($data);

if (empty($dataXML->paymentLink)) {
	header('Content-Type: application/json; charset=UTF-8');
	$errosOcorridos = array('erro' => '1');
	echo json_encode($dataXML);
} else {
	header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($dataXML);
}
curl_close($ch);