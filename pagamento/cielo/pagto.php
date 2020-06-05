<?
include("includes/header.php");

if(!$_SESSION['user_verifica'])
{
	redireciona("login.php?ind_msg=2");
}

//if($_SESSION['finalizar_pagto'])
if(isset($_GET['finalizado']) || $_SESSION['pagamento'] == 2)
{
	$ch = curl_init("https://cieloecommerce.cielo.com.br/api/public/v1/orders/08d1e1df-5d18-41d5-93e8-f95435586695/".$idcarrinho);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$jResp = json_decode(curl_exec($ch));
	
	$idcadastro = $c_codigo;
	$idcarrinho = $_SESSION['idcarrinho'];

	if($n_pagamento == 1)
		$pagamento = 1;
	elseif($n_pagamento == 2)
		$pagamento = 2;

	if($_SESSION['pagamento'])
		$pagamento = $_SESSION['pagamento'];
	
	if($_SESSION['idpedido'])
	{
		$str = "DELETE FROM pedidos WHERE codigo = '".$_SESSION['idpedido']."'";
		$rs  = mysql_query($str) or die(mysql_error());
		
		$str = "DELETE FROM pedidos_detalhe WHERE idpedido = '".$_SESSION['idpedido']."'";
		$rs  = mysql_query($str) or die(mysql_error());
		
		$str = "DELETE FROM frete WHERE idpedido = '".$_SESSION['idpedido']."'";
		$rs  = mysql_query($str) or die(mysql_error());
	}
	
	$valStatus = ($_SESSION['pagamento'] == 2)? 0 : 1;
	
	$valor_compra = subtotal_carrinho($c_codigo, $_SESSION['idcarrinho'], $_SESSION['c_servico'], $c_cep, $n_pac);
	
	$str = "INSERT INTO pedidos (entrega, status, idcadastro, idcarrinho, valor, pagamento, data_geracao) VALUES ('".$jResp->shipping_name."',$valStatus, '$idcadastro', '$idcarrinho', '$valor_compra', '$pagamento', CURDATE())";
	$rs  = mysql_query($str) or die(mysql_error());
	$idpedido = mysql_insert_id();
	
	$_SESSION['idpedido'] = $idpedido;

	$str = "INSERT INTO pedidos_enderecos (idpedido, idcadastro, endereco, numero, complemento, referencia, bairro, cidade, estado, cep)
    	VALUES ('$idpedido', '$c_codigo', '$c_endereco', '$c_numero', '$c_complemento', '$c_referencia', '$c_bairro', '$c_cidade', '$c_estado', '$c_cep')";
	$rs  = mysql_query($str) or die(mysql_error());
			
	$str = "SELECT A.*, B.qtde, B.valor AS valor_pedido, B.idtamanho, B.idcor, C.numero AS tamanho, D.titulo AS cor
		FROM produtos A
		INNER JOIN carrinho B ON A.codigo = B.idproduto
		LEFT JOIN tamanhos C ON B.idtamanho = C.codigo
		LEFT JOIN cores D ON B.idcor = D.codigo
		WHERE B.idcarrinho = '$idcarrinho'
		ORDER BY A.nome";
	$rs  = mysql_query($str) or die(mysql_error());
	
	$tr_pedidos = '';
	$tr_frete = '';
	$tr_total = '';
	
	$total = 0;
	
	while($vet = mysql_fetch_array($rs))
	{		
		$idproduto = $vet['codigo'];
		$idtamanho = $vet['idtamanho'];
		$idcor = $vet['idcor'];
		$tamanho = $vet['tamanho'];
		$cor = addslashes($vet['cor']);
		$produto = addslashes($vet['nome']);
		$qtde = $vet['qtde'];	
		$valor = $vet['valor_pedido'] * $qtde;

		if(!$tamanho)
			$tamanho = 'Não informado';

		if(!$cor)
			$cor = 'Não informada';

		$str_cores = '';
		if($idtamanho > 0 || $idcor > 0)
			$str_cores = '<br>Tamanho: '.$tamanho.' - Cor: '.$cor;
		
		$total += $valor;
		
		$strF = "INSERT INTO pedidos_detalhe (idpedido, idproduto, idtamanho, idcor, descricao, valor, qtde) VALUES ('$idpedido', '$idproduto', '$idtamanho', '$idcor', '$produto', '$valor', '$qtde')";
		$rsF  = mysql_query($strF) or die(mysql_error());
		
		$tr_pedidos .= '
			<tr>
				<td height="20">'.stripslashes($produto).$str_cores.'</td>
				<td>R$ '.number_format($valor, 2, ',', '.').'</td>
				<td>'.$qtde.'</td>
			</tr>';
	}

	$valor_frete = 0;
	$prazo_entrega = 0;

	if($config_frete > 0 && $c_idendereco > 0 && $n_pac == 2)
	{
		$frete = calcula_frete($_SESSION['c_servico'], $vetF['cep_origem'], $c_cep, $vetF['peso'], $vetF['altura'], $vetF['largura'], $vetF['comprimento'], $vetF['mao_propria']);
		$array_frete = explode(";", $frete);

		$valor_frete = str_replace(",", ".", $array_frete[0]);
		$prazo_entrega = $array_frete[1];

		$tr_frete .= '
			<tr>
				<td><b>Valor frete</b></td>
				<td>R$ '.number_format($valor_frete, 2, ',', '.').'</td>
			</tr>';
	}

	$_SESSION['frete'] = str_replace(".", ",", $valor_frete);

	if($servico == 41106)
		$servico = "PAC";
	elseif($servico == 40010)
		$servico = "SEDEX";

	$str = "INSERT INTO frete (idpedido, cep_destino, servico, valor, prazo) VALUES ('$idpedido', '$c_cep', '$servico', '$valor_frete', '$prazo_entrega')";
	$rs  = mysql_query($str) or die(mysql_error());
	
	$tr_total = '
		<table width="93%" border="1" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="30%"><b>Valor compra</b></td>
				<td>R$ '.number_format($total, 2, ',', '.').'</td>
			</tr>
			<tr>
				<td width="30%"><b>Forma pagamento</b></td>
				<td>'.(($valStatus == 1)? 'Pelo site': 'Presencial').'</td>
			</tr>
			<tr>
				<td width="30%"><b>Forma de envio</b></td>
				<td>'.$jResp->shipping_name.'</td>
			</tr>
		</table>';
	
	ini_set ('mail_filter', '0');
	
	$corpo = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>'.$n_empresa.'</title>
			<style type="text/css">
			td {font-family: Arial, Helvetica, sans-serif;font-size: 12px;color: #494949; padding:5px;}
			.bordasimples {border-collapse: collapse;}
			.bordasimples {border:1px solid #d0d0d0;}
			</style>
			</head>
			<body>
			<table width="651" border="0" align="center" cellpadding="0" cellspacing="0" class="bordasimples">
			  	<tr>
					<td>
						<p>&nbsp;</p>
				  		<table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
					  			<td>
								CÓDIGO DO PEDIDO #'.$idcarrinho.'<br />
					  			++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
								</td>
							</tr>
				  		</table>
				  		<br />
						<table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
					  			<td>
									Olá '.$c_nome.',<br><br>
									Seu pedido foi finalizado com sucesso.<br>
									Entraremos em contato em breve!
								</td>
							</tr>
				  		</table>
				  		<br />
				  		<table width="93%" border="1" align="center" cellpadding="0" cellspacing="0" border="1">
							<tr>
					  			<td height="20"><b>Nome do produto</b></td>
								<td><b>Valor R$</b></td>
								<td><b>Qtde de itens</b></td>
							</tr>
							'.$tr_pedidos.'
				  		</table>
				  		<br />
				  		'.$tr_total.'
				  		<br />
					</td>
			  	</tr>
			</table>
			</body>
		</html>';
	
	$assunto = "Pedido finalizado | ".$n_empresa;
	
	$headers = "MIME-Version: 1.1\n";
    $headers .= "Content-type: text/html; charset=utf-8\n";
    $headers .= "From: ".$n_empresa." <".$n_email.">\n";
    $headers .= "Return-Path: ".$n_email."\n";  
	
	//Envia o email
	mail($c_email, $assunto, $corpo, $headers ,"-r ".$n_email);
	
	
	$corpo = '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>'.$n_empresa.'</title>
			<style type="text/css">
			td {font-family: Arial, Helvetica, sans-serif;font-size: 12px;color: #494949; padding:5px;}
			.bordasimples {border-collapse: collapse;}
			.bordasimples {border:1px solid #d0d0d0;}
			</style>
			</head>
			<body>
			<table width="651" border="0" align="center" cellpadding="0" cellspacing="0" class="bordasimples">
			  	<tr>
					<td>
						<p>&nbsp;</p>
				  		<table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
					  			<td>
								CÓDIGO DO PEDIDO #'.$idcarrinho.'<br />
					  			++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
								</td>
							</tr>
				  		</table>
				  		<br />
						<table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
					  			<td>
									Olá '.$n_empresa.',
									você tem um novo pedido!
									Acesse <a href="https://eletrotonon.com.br/admin">aqui</a> para ter mais informações sobre o pedido.
								</td>
							</tr>
				  		</table>
				  		<br />
				  		<table width="93%" border="1" align="center" cellpadding="0" cellspacing="0" border="1">
							<tr>
					  			<td height="20"><b>Nome do produto</b></td>
								<td><b>Valor R$</b></td>
								<td><b>Qtde de itens</b></td>
							</tr>
							'.$tr_pedidos.'
				  		</table>
				  		<br />
				  		'.$tr_total.'
				  		<br />
					</td>
			  	</tr>
			</table>
			</body>
		</html>';
	
	$assunto = "Pedido finalizado | ".$idcarrinho;
	
	$headers = "MIME-Version: 1.1\n";
    $headers .= "Content-type: text/html; charset=utf-8\n";
    $headers .= "From: ".$n_empresa." <".$n_email.">\n";
    $headers .= "Return-Path: ".$n_email."\n";  
	
	//Envia o email
	mail($n_email, $assunto, $corpo, $headers);
	
	

	$_SESSION['idcarrinho_auxiliar'] = $_SESSION['idcarrinho'];
	$_SESSION['idpedido_auxiliar'] = $_SESSION['idpedido'];
	$_SESSION['pagamento_auxiliar'] = $_SESSION['pagamento'];

	unset($_SESSION['finalizar_pagto']);
	unset($_SESSION['idcarrinho']);
	unset($_SESSION['idpedido']);
	unset($_SESSION['pagamento']);
	
	redireciona("index.php");
	
}

$idpedido = $_SESSION['idpedido_auxiliar'];
$valor = subtotal_carrinho($c_codigo, $_SESSION['idcarrinho_auxiliar'], $servico, $c_cep, $n_pac);
$valor = number_format($valor, 2, ',', '.');
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- static-right-social-area end-->
<div class="container">
	<!-- page-heading start-->
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="page-heading">
				<h1>Finalizar compra</h1>
			</div>
		</div>
	</div>
	<!-- page-heading end-->
	<?
	include("includes/menu_pagto.php");
	?>

	<div class="row">
		<div class="col-lg-12 col-md-12">
			<br><h3 class="text-center">Finalizar o Pagamento</h3>
			<br>
			
			<?php 
			
				if($_SESSION['pagamento'] == 1){
					$url = "https://cieloecommerce.cielo.com.br/api/public/v1/orders";
					$data = array(
						"OrderNumber" => $idcarrinho,
						"Cart" => array(
							"Items" => array()
						),
						"Shipping" => array(
							"Type" => "FixedAmount",
							"Services" => array(  
								 array(  
									  "Name" => "Sedex",
									  "Price" => 2500,
									  "Deadline" => 15,
									  "Carrier" => null
								 ),
								 array(  
									  "Name" => "Retirar na loja",
									  "Price" => 0,
									  "Deadline" => 15,
									  "Carrier" => null
								 )
							),
						),
						"Options" => array(
							"AntifraudEnabled" => true,
							"ReturnUrl" => "http://eletrotonon.com.br/pagto.php?finalizado"
						)
					);
				
					$resp = mysql_query("select * from carrinho where idcarrinho like '".$_SESSION['idcarrinho']."'");
					while($row = mysql_fetch_array($resp)){
						$produto = mysql_fetch_array(mysql_query("select nome from produtos where codigo like ".$row['idproduto']))['nome'];
						array_push($data['Cart']['Items'],array("Name"=>$produto,"UnitPrice"=>($row['valor']*100),"Quantity"=>$row['qtde'],"Type"=>"Asset"));
					}

					$options=array('http'=>array('header'=>"Content-type: application/json\r\nMerchantId: 08d1e1df-5d18-41d5-93e8-f95435586695\r\n",'method'=>'POST','content'=>json_encode($data)));
				
					$context=stream_context_create($options);
					$result=file_get_contents($url, false, $context);

					echo '<a href="'.json_decode($result)->settings->checkoutUrl.'"><div class="btn btn-success w-25 mx-auto d-block">Pagar</div></a>';
				}
				else{
					echo '<p class="text-center">Pagamento presencial</p>';
				}
				
			?>
			
			<br>
		</div>
	</div>
	
</div>
<?
include("includes/footer.php");
?>
