<?php
include "env.php";

//include 'templates/dadosProduto.php';
//$dadosProduto = json_decode(decodificar($_POST['id']));

$c_codigo = $_POST['idcadastro'];
$idcarrinho = $_POST['idcarrinho'];
$valor = $_POST['valor'];
$itens = json_decode($_POST['itens']);

$_POST['cpf'] = str_replace(".", "", $_POST['cpf']);
$_POST['cpf'] = str_replace("-", "", $_POST['cpf']);

$_POST['cardCPF'] = str_replace(".", "", $_POST['cardCPF']);
$_POST['cardCPF'] = str_replace("-", "", $_POST['cardCPF']);

/*$valor = $dadosProduto->valor;
$valor = str_replace(",", ".", $valor);
$valor = number_format($valor, 2, '.', '');
*/
if (!(isset($_POST['valorParcelas'])) || empty($_POST['valorParcelas'])) {
	$_POST['valorParcelas'] = $valor;
}

if (!(isset($_POST['numParcelas'])) || empty($_POST['numParcelas'])) {
	$_POST['numParcelas'] = 1;
}


$_POST['valorParcelas'] = (number_format($_POST['valorParcelas'], 2));
$_POST['valorParcelas'] = str_replace(",", ".", $_POST['valorParcelas']);

$_POST['numParcelas'] = intval($_POST['numParcelas']);

$itens_array = array();
include('modelo/gerarXml.php');
foreach($itens as $i=>$item){
    array_push($itens_array, (array)$item);

}
 
 /*
var_dump('id' . $_POST['id']); 

var_dump('valor' . $valor); 
var_dump('nome' . $_POST['nome']); 
var_dump('cpf' . $_POST['cpf']); 
var_dump('ddd' . $_POST['ddd']); 
var_dump('telefone' . $_POST['telefone']); 
var_dump('email' . $_POST['email']); 
var_dump('senderHash' . $_POST['senderHash']); 
var_dump('endereco' . $_POST['endereco']); 
var_dump('numero' . $_POST['numero']); 
var_dump('complemento' . $_POST['complemento']);
var_dump('bairro' . $_POST['bairro']);
var_dump('cep' . $_POST['cep']);
var_dump('cidade' . $_POST['cidade']);
var_dump('estado' . $_POST['estado']);
var_dump('enderecoPagamento' . $_POST['enderecoPagamento']);
var_dump('numeroPagamento' . $_POST['numeroPagamento']);
var_dump('complementoPagamento' . $_POST['complementoPagamento']);
var_dump('bairroPagamento' . $_POST['bairroPagamento']);
var_dump('cepPagamento' . $_POST['cepPagamento']);
var_dump('cidadePagamento' . $_POST['cidadePagamento']);
var_dump('estadoPagamento' . $_POST['estadoPagamento']);
var_dump('creditCardToken' . $_POST['creditCardToken']);
var_dump('cardNome' . $_POST['cardNome']);
var_dump('cardCPF' . $_POST['cardCPF']);
var_dump('cardNasc' . $_POST['cardNasc']);
var_dump('cardFoneArea' . $_POST['cardFoneArea']);
var_dump('cardFoneNum' . $_POST['cardFoneNum']);
var_dump('numParcelas' . $_POST['numParcelas']);
var_dump('valorParcelas:' . $_POST['valorParcelas']);*/

/*$xml = gerarXmlCartao($_POST['id'], "Produtos", $valor, $_POST['nome'], $_POST['cpf'], $_POST['ddd'], $_POST['telefone'], $_POST['email'], $_POST['senderHash'], $_POST['endereco'], $_POST['numero'], $_POST['complemento'], $_POST['bairro'], $_POST['cep'], $_POST['cidade'], $_POST['estado'], $_POST['enderecoPagamento'], $_POST['numeroPagamento'], $_POST['complementoPagamento'], $_POST['bairroPagamento'], $_POST['cepPagamento'], $_POST['cidadePagamento'], $_POST['estadoPagamento'], $_POST['creditCardToken'], $_POST['cardNome'], $_POST['cardCPF'], $_POST['cardNasc'], $_POST['cardFoneArea'], $_POST['cardFoneNum'], $_POST['numParcelas'], $_POST['valorParcelas'], $itens_array);
   var_dump($xml);*/
$xml = "<payment>
  <mode>default</mode>
  <currency>BRL</currency>
  <notificationURL>" . $notificationURL . "</notificationURL>
  <receiverEmail>" . $emailPagseguro . "</receiverEmail>
  <sender>
    <hash>". $_POST['senderHash'] . "</hash>
    <ip>" . $_SERVER['REMOTE_ADDR'] . "</ip>
    <email>". $_POST['email'] . "</email>
    <documents>
      <document>
        <type>CPF</type>
        <value>" . $_POST['cpf'] . "</value>
      </document>
    </documents>
    <phone>
      <areaCode>" . $_POST['ddd'] . "</areaCode>
      <number>" . substr($_POST['telefone'],2) . "</number>
    </phone>
    <name>" . $_POST['nome'] . "</name>
  </sender>
  <creditCard>
    <token>". $_POST['cardToken'] ."</token>
    <creditCardToken>". $_POST['creditCardToken'] ."</creditCardToken>
    <holder>
      <name>" . $_POST['cardNome'] . "</name>
      <birthDate>" . $_POST['cardNasc'] ."</birthDate>
        <documents>
          <document>
            <type>CPF</type>
            <value>" . $_POST['cardCPF'] . "</value>
          </document>
        </documents>
      <phone>
        <areaCode>" . $_POST['cardFoneArea'] . "</areaCode>
        <number>" . substr($_POST['cardFoneNum'],2) . "</number>
      </phone>
    </holder>
    <billingAddress>
        <street>" . $_POST['endereco'] . "</street>
        <number>" . $_POST['numero'] . "</number>
        <complement>" . $_POST['complemento'] . "</complement>
        <district>" . $_POST['bairro'] . "</district>
        <city>" . $_POST['cidade'] . "</city>
        <state>" . $_POST['estado'] . "</state>
        <postalCode>" . $_POST['cep'] . "</postalCode>
        <country>BRA</country>
    </billingAddress>
    <installment>
      <quantity>" . $_POST['numParcelas'] . "</quantity>
      <value>" . number_format($_POST['valorParcelas'],2,'.','') . "</value>
      <noInterestInstallmentQuantity>2</noInterestInstallmentQuantity>
    </installment>
  </creditCard>
  <items>";

  foreach($itens_array as $i=>$item):
    $xml .= "<item>
      <id>" . $item['id'] . "</id>
      <description>" . $item['description'] . "</description>
      <amount>" .number_format($item['amount'],2,'.',''). "</amount>
      <quantity>".$item['quantity']."</quantity>
    </item>
  </items>";
  endforeach;
  
  $xml .="
  <reference>" . $_POST['id'] . "</reference>
  <shipping>
    <address>
      <street>" . $_POST['enderecoPagamento'] . "</street>
      <number>" . $_POST['numeroPagamento'] . "</number>
      <complement>" . $_POST['complementoPagamento'] . "</complement>
      <district>" . $_POST['bairroPagamento'] . "</district>
      <city>" . $_POST['cidadePagamento'] . "</city>
      <state>" . $_POST['estadoPagamento'] . "</state>
      <country>BRA</country>
      <postalCode>" . $_POST['cepPagamento'] . "</postalCode>
    </address>
    <type>1</type>
    <cost>0.00</cost>
    <addressRequired>true</addressRequired>
  </shipping>
  <extraAmount>0.00</extraAmount>
  <method>creditCard</method>
  <dynamicPaymentMethodMessage>
    <creditCard>infoEnem</creditCard>
    <boleto>infoEnem</boleto>
  </dynamicPaymentMethodMessage>
</payment>";


/*$xml2 = gerarXmlCartao(9, "produto", 1, "nome", "36780896829", 19, 981575567, "c55645138300414230185@sandbox.pagseguro.com.br", "cfea40fad20e0e77cebae4016724676fbae0de79fa56ef25233b29804d1248db", "Endereco", "1080", "", "bairro", "13183250", "Hortolândia", "SP", "Endereco", "1080", "", "bairro", "13183250", "Hortolândia", "SP", "", "Gabriel Souza", "36780896829", "20/20/2000", "19", "981575567", "1", 1, array());
var_dump($xml2);die('fim1');*/



/*$xml = gerarXmlCartao($_POST['id'], "Produtos", $valor, $_POST['nome'], $_POST['cpf'], $_POST['ddd'], $_POST['telefone'], $_POST['email'], $_POST['senderHash'], $_POST['endereco'], $_POST['numero'], $_POST['complemento'], $_POST['bairro'], $_POST['cep'], $_POST['cidade'], $_POST['estado'], $_POST['enderecoPagamento'], $_POST['numeroPagamento'], $_POST['complementoPagamento'], $_POST['bairroPagamento'], $_POST['cepPagamento'], $_POST['cidadePagamento'], $_POST['estadoPagamento'], $_POST['creditCardToken'], $_POST['cardNome'], $_POST['cardCPF'], $_POST['cardNasc'], $_POST['cardFoneArea'], $_POST['cardFoneNum'], $_POST['numParcelas'], $_POST['valorParcelas'], $itens_array);
   var_dump($xml);*/

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $urlPagseguro . "transactions/?email=" . $emailPagseguro . "&token=" . $tokenPagseguro);
curl_setopt($ch, CURLOPT_POST, true );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml; charset=ISO-8859-1'));

$data = curl_exec($ch);
$dataXML = simplexml_load_string($data);

header('Content-Type: application/json; charset=UTF-8');
echo json_encode($dataXML);
 	
curl_close($ch);
