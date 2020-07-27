<?php

/*
Arquivo de configuração do ambiente
*/

include('../funcoes.php');
include('../s_acessos.php');

$sql = "SELECT * FROM pagseguro_configuracao ";
$result = mysql_query($sql);

if ((mysql_num_rows($result) > 0)) {
	$row = mysql_fetch_assoc($result);
	$emailPagseguro = $row["email"];
	$tokenPagseguro = $row["token"];
	$urlNotificacao = $row["urlnotificacao"];
	$sandBox = $row['sandbox'];
}

if ($sandBox != 1) {
	$scriptPagseguro = "https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js";
	$urlPagseguro = "ws.pagseguro.uol.com.br/v2/";
} else {
	$scriptPagseguro = "https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js";
	$urlPagseguro = "ws.sandbox.pagseguro.uol.com.br/v2/";
}
