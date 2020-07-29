<?php 
include('topo.inc.php'); 

if($_GET['codigo'])
    $codigo = anti_injection($_GET['codigo']);
else
    $codigo = anti_injection($_POST['codigo']);
	
if($_GET['status'])
    $status = anti_injection($_GET['status']);
else
    $status = anti_injection($_POST['status']);

if($_GET['chave'])
    $chave = anti_injection($_GET['chave']);
else
    $chave = anti_injection($_POST['chave']);

$where = anti_injection($_GET['where']);

if($_GET['cmd'] == "confirmar")
{
	$email = email_pedido($codigo);

	$str = "SELECT * FROM pedidos WHERE codigo = '$codigo'";
	$rs  = mysql_query($str) or die(mysql_error());
	$num = mysql_num_rows($rs);

	$vet = mysql_fetch_array($rs);
	$idcarrinho = $vet['idcarrinho'];
	
	$strC = "SELECT A.*, B.qtde, B.valor, B.idtamanho, B.idcor
		FROM produtos A
		INNER JOIN carrinho B ON A.codigo = B.idproduto
		WHERE B.idcarrinho = '$idcarrinho'
		ORDER BY A.nome";
	$rsC  = mysql_query($strC) or die(mysql_error());
	
	while($vetC = mysql_fetch_array($rsC))
	{
		if($vetC['ind_cores'] != 1)
        {			
			$idproduto = $vetC['codigo'];
			$qtde = $vetC['qtde'];
		
			$strU = "UPDATE produtos SET estoque = estoque - '$qtde' WHERE codigo = '$idproduto'";
			$rsU  = mysql_query($strU) or die(mysql_error());
		}
		else
		{
			$idproduto = $vetC['codigo'];
			$idtamanho = $vetC['idtamanho'];
			$idcor = $vetC['idcor'];
			$qtde = $vetC['qtde'];
		
			$strU = "UPDATE produtos_estoque SET estoque = estoque - '$qtde' WHERE idproduto = '$idproduto' AND idtamanho = '$idtamanho' AND idcor = '$idcor'";
			$rsU  = mysql_query($strU) or die(mysql_error());
		}
	}
					
	$strF = "UPDATE pedidos SET status = '1', data_baixa = CURDATE() WHERE codigo = '$codigo'";
	$rsF  = mysql_query($strF) or die(mysql_error());
	
	ini_set ('mail_filter', '0');
	
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
									++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
									</td>
								</tr>
							</table>
							<br />
							<table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										Olá,<br><br>
										Sua compra foi CONFIRMADA.<br />
										Enviaremos os itens do pedido em breve para você.
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				</body>
			</html>';
		
	$assunto = "Compra CONFIRMADA | ".$n_empresa;
		
	$headers = "MIME-Version: 1.1\n";
    $headers .= "Content-type: text/html; charset=utf-8\n";
    $headers .= "From: ".$n_empresa." <".$n_email.">\n";
    $headers .= "Return-Path: ".$n_email."\n";   
	
	//Envia o email
	//mail("amunes@amunes.org.br", $assunto, $corpo, $headers);
	mail($email, $assunto, $corpo, $headers ,"-r ".$n_email);
	mail($n_email,$assunto, $corpo, $headers);

    redireciona("pedidos.php?ind_msg=1&where=$where");
}

if($_GET['cmd'] == "cancelar")
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
	
	while($vetD = mysql_fetch_array($rsD))
	{
		$idproduto = $vetD['codigo'];
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
		
		if($vetD['ind_cores'] != 1)
        {		
			$strU = "UPDATE produtos SET estoque = estoque + '$qtde' WHERE codigo = '$idproduto'";
			$rsU  = mysql_query($strU) or die(mysql_error());
		}
		else
		{
			$strU = "UPDATE produtos_estoque SET estoque = estoque + '$qtde' WHERE idproduto = '$idproduto' AND idtamanho = '$idtamanho' AND idcor = '$idcor'";
			$rsU  = mysql_query($strU) or die(mysql_error());
		}
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
	
    $str = "UPDATE pedidos SET status = '3', data_cancelamento = CURDATE() WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
	
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
					  			++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
								</td>
							</tr>
				  		</table>
				  		<br />
						<table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
					  			<td>
									Olá,<br />
									Sua compra foi CANCELADA pelo sistema.<br />
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
		
	$assunto = "Cancelamento da compra | ".$n_empresa;
		
	$headers = "MIME-Version: 1.1\n";
    $headers .= "Content-type: text/html; charset=utf-8\n";
    $headers .= "From: ".$n_empresa." <".$n_email.">\n";
    $headers .= "Return-Path: ".$n_email."\n";   
	
	//Envia o email
	//mail("amunes@amunes.org.br", $assunto, $corpo, $headers);
	mail($email, $assunto, $corpo, $headers ,"-r ".$n_email);
	mail($n_email,$assunto, $corpo, $headers);

    redireciona("pedidos.php?ind_msg=3&where=$where");
}

if($_GET['ind_msg'] == 1)
    $msg = '<div class="alert success">Compra confirmada com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
    $msg = '<div class="alert success">Compra enviada com sucesso!</div>';
elseif($_GET['ind_msg'] == 3)
    $msg = '<div class="alert success">Compra cancelada com sucesso!</div>';

include('menu.inc.php');
?>
<script>
function valida()
{
    document.form.cmd.value = "filtrar";
}
</script>
<section id="content">
<div class="g12">
    <h1>Relatório de pedidos feitos no site</h1>
    <p></p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <fieldset>
    	<section>
            <label for="text_field">Chave:</label>
            <div>
            	<input type="text" id="chave" name="chave" value="<?=$chave?>" placeholder="Informar o código do pedido ou nome do cliente.">
            	<br><span>Informar o código do pedido ou nome do cliente.</span>
            </div>
        </section>
    	<section>
            <label for="dropdown">Status do pedido:</label>
            <div>					
                <select name="status" id="status" >
                    <option value="">Selecione um status de pedido abaixo</option>
                    <option value="4" <?=("4" == $status) ? 'selected' : '' ?>>Pendente</option>
                    <option value="1" <?=("1" == $status) ? 'selected' : '' ?>>Confirmado / Pago</option>
                    <option value="2" <?=("2" == $status) ? 'selected' : '' ?>>Enviado / Entregue</option>
                    <option value="3" <?=("3" == $status) ? 'selected' : '' ?>>Cancelado</option>
                </select>
            </div>
        </section>         
        <section>
        	<label for="date">Data de geração do pedido:<br><span>Data de início e término dos registros</span></label>
        	<div><input id="data_inicio" name="data_inicio" type="text" class="date" value="<?=($_POST['data_inicio'] == TRUE) ? ConverteData($_POST['data_inicio']) : "" ?>"><span> Ex: dd/mm/aaaa</span></div>
            <div><input id="data_termino" name="data_termino" type="text" class="date" value="<?=($_POST['data_termino'] == TRUE) ? ConverteData($_POST['data_termino']) : "" ?>"><span> Ex: dd/mm/aaaa</span></div>
    	</section>
        <section>
            <div><button class="i_tick icon" onClick="javascript: valida();" >Buscar</button></div>
        </section>
    </fieldset>
    </form>
    
    <?
    if($_POST['cmd'] == 'filtrar' || !empty($where) || $status > 0)
    {
    	$strWhere = " ";

    	if($_POST['cmd'] == 'filtrar' || $status > 0)
    	{
	        if($status)
			{
				if($status == 4)
					$status = '0';
					
				$strWhere .= "AND A.status = '$status'";
			}

			$chave = anti_injection($_POST['chave']);
	        $data_inicio = anti_injection($_POST['data_inicio']);
	        $data_termino = anti_injection($_POST['data_termino']);

	        if($chave == TRUE)
	            $strWhere .= "AND (A.codigo LIKE '%$chave%' OR B.nome LIKE '%$chave%')";

	        if($data_inicio == TRUE)
	            $strWhere .= "AND A.data_geracao >= '$data_inicio'";

	        if($data_termino == TRUE)
	            $strWhere .= "AND A.data_geracao <= '$data_termino'";

	        $where = base64_encode($strWhere);
	        $strWhere = base64_decode($where);
	    }
    	elseif(!empty($where))
    	{
    		$strWhere = base64_decode($where);
    	}
    }

    $str = "SELECT DISTINCT A.*, B.nome AS str_nome, B.sobrenome AS str_sobrenome 
        FROM pedidos A
        INNER JOIN cadastros B ON A.idcadastro = B.codigo
        WHERE 1 = 1 
        $strWhere 
        ORDER BY A.data_geracao DESC";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

	/*while($row = mysql_fetch_array($rs)){
		echo $row['data_geracao']."<br>";
	}*/

    if($num > 0)
    {        
	?>
    <h1>Retorno da busca</h1>
    <p><?=$num?> registros encontrados</p>
    
    <table >
        <thead>
            <tr>
                <th colspan="4">LEGENDA - Status do pedido</th>
            </tr>
        </thead>
        <tbody>
            <tr >
            	<td style="background-color:#F3F781; width:25%;">Pendente</td>                
                <td style="background-color:#BFDFFF; width:25%;">Confirmado / Pago</td>                
                <td style="background-color:#81F79F; width:25%;">Enviado / Entregue</td>
                <td style="background-color:#f0a8a8; width:25%;">Cancelado</td>
            </tr>
        </tbody>
    </table>

    <fieldset>
    <table class="">
        <thead>
            <tr>
                <th>ID<br>pedido</th>
                <th>Cliente</th>
                <th>Datas</th>
                <th>Forma de entrega</th>
                <th>Valor</th>
                <th>Forma de pagto</th>
                <th class="c">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?
    	$total = 0;
    	$total_f = 0;
        while($vet = mysql_fetch_array($rs))
        {
        	$idpedido = $vet['codigo'];

			if($vet['status'] == 0)
                $class = 'style="background-color:#F3F781;"';
            elseif($vet['status'] == 1)
                $class = 'style="background-color:#BFDFFF;"';
            elseif($vet['status'] == 2)
                $class = 'style="background-color:#81F79F;"';
			elseif($vet['status'] == 3)
                $class = 'style="background-color:#f0a8a8;"';

            $strF = "SELECT * FROM frete WHERE idpedido = '$idpedido'";
		    $rsF  = mysql_query($strF) or die(mysql_error());
		    $vetF = mysql_fetch_array($rsF);
			
			$total = $vet['valor'] + $vetF['valor'];
			$total_f += $total;

			$entrega = 'erro!';
			switch($vet['entrega']){
				case '0':
					$entrega = 'retirar na loja';
				break;
				case '1':
					$entrega = 'PAC';
				break;
				case '2':
					$entrega = 'SEDEX';
				break;
			}
    	?>
            <tr >
                <td <?=$class?>><a href="pedidos_ver.php?idpedido=<?=$vet['codigo']?>" target="_blank">#<?=$vet['idcarrinho']?></a></td>
                <td><a href="clientes_ver.php?idcadastro=<?=$vet['idcadastro']?>" target="_blank"><?=stripslashes($vet['str_nome'].' '.$vet['str_sobrenome'])?></a></td>
                <td style="text-align:justify">
                	Geração: <?=$vet['data_geracao']?><br />

                    <?
                    if($vet['status'] == 1)
                    	echo "Pago: ".$vet['data_baixa']."<br />";
                    elseif($vet['status'] == 2)
                    	echo "Envio: ".$vet['data_envio']."<br />";                    	
					elseif($vet['status'] == 3)
                    	echo "Cancelamento: ".$vet['data_cancelamento']."<br />";
                    ?>
                </td>
                <td><?=$entrega?></td>
                <td>R$ <?=number_format($total, 2, ',', '.')?></td>
                <td><?=($vet['pagamento'] == 1) ? 'site' : 'presencial'?></td>
                <td class="c">
                <?
                if(!$vet['status'])
				{
				?>
                	<a class="btn i_pencil icon small" title="confirmar" href="pedidos.php?cmd=confirmar&codigo=<?=$vet['codigo']?>&where=<?=$where?>">confirmar</a><br>
                <?
				}
				
				if($vet['status'] == 1)
				{
				?>			
                    <a class="btn i_pencil icon small" title="enviar" href="pedidos_enviar.php?codigo=<?=$vet['codigo']?>&where=<?=$where?>" target="_blank">enviar</a><br>
                    <a class="btn i_trashcan icon small" title="cancelar" href="pedidos.php?cmd=cancelar&codigo=<?=$vet['codigo']?>&where=<?=$where?>" onclick="javascript: if(!confirm('Deseja realmente cancelar este pedido?')) { return false }">cancelar</a>
                                     
				<?
				}
				?>
               	</td>
            </tr>
        <?
        }
        ?>
        </tbody>
   	</table>
    </fieldset>
	<?
    }
    else
    {
    ?>
    <fieldset>
        <p>Nenhum registro encontrado</p>
    </fieldset>
    <?
    }
    ?>
</div>
</section>
<?php include('rodape.inc.php'); ?>    
