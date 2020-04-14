<?php 

include('topo.inc.php'); 

if($_GET['codigo'])
    $codigo = anti_injection($_GET['codigo']);
else
    $codigo = anti_injection($_POST['codigo']);

if($_GET['where'])
    $where = anti_injection($_GET['where']);
else
    $where = anti_injection($_POST['where']);

$cod_rastreamento = $_POST['cod_rastreamento'];


if($_POST['cmd'] == "enviar")
{
	
	$email = email_pedido($codigo);

	$str = "SELECT * FROM pedidos WHERE codigo = '$codigo'";
	$rs  = mysql_query($str) or die(mysql_error());
	$num = mysql_num_rows($rs);

	$vet = mysql_fetch_array($rs);
	$idcarrinho = $vet['idcarrinho'];
	
	$strD = "SELECT A.*, B.qtde, B.valor, B.idtamanho, B.idcor, C.numero AS tamanho, D.titulo AS cor
		FROM produtos A
		INNER JOIN carrinho B ON A.codigo = B.idproduto
		LEFT JOIN tamanhos C ON B.idtamanho = C.codigo
		LEFT JOIN cores D ON B.idcor = D.codigo
		WHERE B.idcarrinho = '$idcarrinho'";
	$rsD  = mysql_query($strD) or die(mysql_error());

	$tr_pedidos = '';
	$tr_frete = '';
	$tr_total = '';
	
	$total = 0;
	
	mysql_query('update tbl_pedidos set codRastreio = "'.$cod_rastreamento.'" where idcarrinho = "'.$idcarrinho.'"');
	
	while($vetD = mysql_fetch_array($rsD))
	{		
		$idproduto = $vetD['idproduto'];
		$idtamanho = $vetD['idtamanho'];
		$idcor = $vetD['idcor'];
		$tamanho = $vetD['tamanho'];
		$cor = stripslashes($vetD['cor']);
		$produto = stripslashes($vetD['nome']);
		$valor = $vetD['valor'];
		$qtde = $vetD['qtde'];

		$total += $valor;

		if(!$tamanho)
			$tamanho = 'Não informado';

		if(!$cor)
			$cor = 'Não informada';

		$str_cores = '';
		if($idtamanho > 0 || $idcor > 0)
			$str_cores = '<br>Tamanho: '.$tamanho.' - Cor: '.$cor;

		$str_valor_pedidos = 'R$ '.number_format($valor, 2, ',', '.');
		
		$tr_pedidos .= '
			<tr>
				<td height="20">'.$produto.$str_cores.'</td>
				<td>'.$str_valor_pedidos.'</td>
				<td>'.$qtde.'</td>
			</tr>';
	}
	
	$strF = "SELECT * FROM frete WHERE idpedido = '$codigo'";
	$rsF  = mysql_query($strF) or die(mysql_error());
	$vetF = mysql_fetch_array($rsF);

	$data_entrega = date("d/m/Y", mktime(0, 0, 0, substr($vet['data_geracao'], 5, 2), substr($vet['data_geracao'], 8, 2) + $vetF['prazo'], substr($vet['data_geracao'], 0, 4)));
	$valor_frete = $vetF['valor'];
	$prazo_entrega = $vetF['prazo'];
	
	if($valor_frete > 0)
	{
		$str_valor_frete = 'R$ '.number_format($valor_frete, 2, ',', '.');

		$tr_frete .= '
			<tr>
				<td><b>Valor frete</b></td>
				<td>'.$str_valor_frete.'</td>
			</tr>';
	}

	$str_valor_compra = 'R$ '.number_format($total, 2, ',', '.');
	$str_valor_total = 'R$ '.number_format(($total + $valor_frete), 2, ',', '.');
	$tr_total = '
		<table width="93%" border="1" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="30%"><b>Valor compra</b></td>
				<td>'.$str_valor_compra.'</td>
			</tr>
			'.$tr_frete.'
			<tr>
				<td><b>Valor total</b></td>
				<td>'.$str_valor_total.'</td>
			</tr>
			<tr>
				<td><b>Prazo de entrega</b></td>
				<td>'.$prazo_entrega.' dia(s)</td>
			</tr>
		</table>';

	$strF = "UPDATE pedidos SET status = '2', data_envio = CURDATE() WHERE codigo = '$codigo'";
	$rsF  = mysql_query($strF) or die(mysql_error());
	
	
	$corpo = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>'.$n_empresa.'</title>
			<style type="text/css">
			td {font-family: Arial, Helvetica, sans-serif;font-size: 16px;color: #494949; padding:5px;}
			.bordasimples {border-collapse: collapse;}
			.bordasimples {border:1px solid #d0d0d0;}
			</style>
			</head>
			<body>
			<table width="651" border="0" align="center" cellpadding="0" cellspacing="0" class="bordasimples">
			  	<tr>
					<td><p>&nbsp;</p>
				  		<table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
					  			<td>
								CÓDIGO DO PEDIDO #'.$idcarrinho.'<br />
								CÓDIGO DE RASTREAMENTO NOS CORREIOS #'.$cod_rastreamento.'<br />								
					  			++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
								</td>
							</tr>
				  		</table>
				  		<br />
						<table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
					  			<td>
									Olá,<br />
									Sua compra foi ENVIADA.<br />
									Entre em contato com o suporte para maiores detalhes.
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
		
	$assunto = "Compra ENVIADA | ".$n_empresa;
		
	$headers = "MIME-Version: 1.1\n";
    $headers .= "Content-type: text/html; charset=utf-8\n";
    $headers .= "From: ".$n_empresa." <".$n_email.">\n";
    $headers .= "Return-Path: ".$n_email."\n";   
	
	//Envia o email
	//mail("amunes@amunes.org.br", $assunto, $corpo, $headers);
	mail($email, $assunto, $corpo, $headers ,"-r ".$n_email);

    redireciona("pedidos.php?ind_msg=2&where=$where");
}

if($_GET['ind_msg'] == 1)
    $msg = '<div class="alert success">Compra enviada com sucesso!</div>';

$str = "SELECT DISTINCT A.*, B.nome AS str_nome 
    FROM pedidos A
    INNER JOIN cadastros B ON A.idcadastro = B.codigo
    WHERE A.codigo = '$codigo'";
$rs  = mysql_query($str) or die(mysql_error());
$vet = mysql_fetch_array($rs);

$strF = "SELECT * FROM frete WHERE idpedido = '$codigo'";
$rsF  = mysql_query($strF) or die(mysql_error());
$vetF = mysql_fetch_array($rsF);

$total = $vet['valor'] + $vetF['valor'];
$total_f += $total;
include('menu.inc.php');
?>
<script>
function valida()
{
    document.form.cmd.value = "enviar";
}
</script>
<section id="content">
<div class="g12">
    <h1>Enviar pedido #<?=$vet['idcarrinho']?></h1>
    <p>Confirme abaixo os dados do pedido e informe o código de rastreamento dos correios</p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <input type="hidden" name="codigo" value="<?=$codigo?>">
    <input type="hidden" name="where" value="<?=$where?>">
    <fieldset>
    	<section>
            <label for="text_field">Dados do pedido:</label>
            <div>
            	<p>
            		ID pedido: #<?=$vet['idcarrinho']?><br>
            		Cliente: <?=stripslashes($vet['str_nome'])?><br>
            		Valor: R$ <?=number_format($total, 2, ',', '.')?>
            	</p>
            	<br>
            	<input type="text" id="cod_rastreamento" name="cod_rastreamento" placeholder="Cód. de rastreamento" style="width:30%">
            	<br><span>Informar o código do restreamento pelos correios.</span>
            </div>
        </section>    	
        <section>
            <div><button class="i_tick icon" onClick="javascript: valida();" >Enviar</button></div>
        </section>
    </fieldset>
    </form>
</div>
</section>
<?php include('rodape.inc.php'); ?>    
