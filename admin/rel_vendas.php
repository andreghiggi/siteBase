<?php 
include('topo.inc.php'); 

if($_GET['codigo'])
    $codigo = $_GET['codigo'];
else
    $codigo = $_POST['codigo'];
	
if($_GET['status'])
    $status = $_GET['status'];
else
    $status = $_POST['status'];

if($_GET['cmd'] == "cancelar")
{
	$email = email_pedido($codigo);
	
	$str = "SELECT * FROM pedidos_detalhe WHERE idpedido = '$codigo'";
	$rs  = mysql_query($str) or die(mysql_error());
	
	$tr_pedidos = '';
	$tr_total = '';
	$total = 0;

	while($vet = mysql_fetch_array($rs))
	{
		$idproduto 	= $vet['idproduto'];
		$produto 	= $vet['nome'];
		$valor		= $vet['valor'];
		$qtde 		= $vet['qtde'];		
		
		$strP = "SELECT * FROM produtos WHERE codigo = '$idproduto'";
		$rsP  = mysql_query($strP) or die(mysql_error());
		$vetP = mysql_fetch_array($rsP);
		
		$estoque = $vetP['estoque'] + $qtde;
		
		$strP = "UPDATE produtos SET estoque = '$qtde' WHERE codigo = '$idproduto'";
    	$rsP  = mysql_query($strP) or die(mysql_error());
		
		$tr_pedidos .= '
			<tr>
				<td height="20">'.stripslashes($produto).'</td>
				<td>'.number_format($valor, 2, ',', '.').'</td>
				<td>'.$qtde.'</td>
			</tr>';
	}
	
	$tr_total = '
		<tr>
			<td height="20">&nbsp;</td>
			<td><b>'.number_format($total, 2, ',', '.').'</b></td>
			<td>&nbsp;</td>
		</tr>';
	
    $str = "UPDATE pedidos SET status = '2', data_cancelamento = CURDATE() WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
	
	$corpo = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Zorzaneli Baterias</title>
			<style type="text/css">
			td {font-family: Arial, Helvetica, sans-serif;font-size: 12px;color: #494949; padding:5px;}
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
				  		<table width="93%" border="0" align="center" cellpadding="0" cellspacing="0" border="1">
							<tr>
					  			<td height="20"><b>Nome do produto</b></td>
								<td><b>Valor R$</b></td>
								<td><b>Qtde de itens</b></td>
							</tr> 
							'.$tr_pedidos.'
							'.$tr_total.'
				  		</table>
				  		<br />
					</td>
			  	</tr>
			</table>
			</body>
		</html>';
		
	$assunto = "Cancelamento de compra | Zorzaneli Baterias";
		
	$headers = "MIME-Version: 1.1\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n";
	$headers .= "From: programacao@fbrandao.com.br\r\n"; //E-mail do remetente
	$headers .= "Return-Path: programacao@fbrandao.com.br\r\n"; //E-mail do remetente
	
	mail($email, $assunto, $corpo, $headers);

    redireciona("rel_vendas.php?ind_msg=1");
}

if($_GET['cmd'] == "enviar")
{
	$email = email_pedido($codigo);
	
	$str = "SELECT * FROM pedidos_detalhe WHERE idpedido = '$codigo'";
	$rs  = mysql_query($str) or die(mysql_error());
	
	while($vet = mysql_fetch_array($rs))
	{		
		$idproduto 	= $vet['idproduto'];
		$produto 	= $vet['nome'];
		$valor		= $vet['valor'];
		$qtde 		= $vet['qtde'];
		
		$tr_pedidos .= '
			<tr>
				<td height="20">'.stripslashes($produto).'</td>
				<td>'.number_format($valor, 2, ',', '.').'</td>
				<td>'.$qtde.'</td>
			</tr>';
	}
	
	$tr_total = '
		<tr>
			<td height="20">&nbsp;</td>
			<td><b>'.number_format($total, 2, ',', '.').'</b></td>
			<td>&nbsp;</td>
		</tr>';
	
	$str = "UPDATE pedidos SET status = '3', data_envio = CURDATE() WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
	
	$corpo = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Zorzaneli Baterias</title>
			<style type="text/css">
			td {font-family: Arial, Helvetica, sans-serif;font-size: 12px;color: #494949; padding:5px;}
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
									Sua compra foi ENVIADA pelo sistema.<br />
									Entre em contato com o suporte para maiores detalhes.
								</td>
							</tr>
				  		</table>
				  		<br />
				  		<table width="93%" border="0" align="center" cellpadding="0" cellspacing="0" border="1">
							<tr>
					  			<td height="20"><b>Nome do produto</b></td>
								<td><b>Valor R$</b></td>
								<td><b>Qtde de itens</b></td>
							</tr>
							'.$tr_pedidos.'
							'.$tr_total.'
				  		</table>
				  		<br />
					</td>
			  	</tr>
			</table>
			</body>
		</html>';
		
	$assunto = "Compra enviada | Zorzaneli Baterias";
		
	$headers = "MIME-Version: 1.1\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n";
	$headers .= "From: programacao@fbrandao.com.br\r\n"; //E-mail do remetente
	$headers .= "Return-Path: programacao@fbrandao.com.br\r\n"; //E-mail do remetente
	
	mail($email, $assunto, $corpo, $headers);

    redireciona("rel_vendas.php?ind_msg=2");
}

if($_GET['ind_msg'] == 1)
    $msg = '<div class="alert success">Compra cancelada com sucesso!</div>';
if($_GET['ind_msg'] == 2)
    $msg = '<div class="alert success">Compra enviada com sucesso!</div>';

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
    <h1>Pedidos</h1>
    <p></p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
        <fieldset>
            <section>
                <label for="dropdown">Status do pedido:</label>
                <div>					
                    <select name="status" id="status" >
                        <option value="">Selecione um status de pedido abaixo</option>
                        <option value="4" <?=("4" == $status) ? 'selected' : '' ?>>Pendente</option>
                        <option value="1" <?=("1" == $status) ? 'selected' : '' ?>>Pago</option>
                        <option value="2" <?=("2" == $status) ? 'selected' : '' ?>>Cancelado</option>
                        <option value="3" <?=("3" == $status) ? 'selected' : '' ?>>Enviado</option>
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
    if($_POST['cmd'] == 'filtrar' || $idevento > 0)
    {
        $strWhere = "";	

        $data_inicio 	= anti_injection($_POST['data_inicio']);
        $data_termino 	= anti_injection($_POST['data_termino']);
			
		if($status)
		{
			if($status == 4)
				$status = '0';
				
			$strWhere .= " AND A.status = '$status'";
		}

        if($_POST['data_inicio'] == TRUE)
                $strWhere .= " AND A.data_geracao >= '$data_inicio'";

        if($_POST['data_termino'] == TRUE)
                $strWhere .= " AND A.data_geracao <= '$data_termino'";

        $str = "SELECT DISTINCT A.*, CASE WHEN B.sobrenome IS NOT NULL THEN CONCAT(B.nome,' ',B.sobrenome) ELSE B.nome END AS str_nome 
                FROM pedidos A
                INNER JOIN cadastros B ON A.idcadastro = B.codigo
				INNER JOIN pedidos_detalhe C ON A.codigo = C.idpedido
				INNER JOIN produtos D ON C.idproduto = D.codigo
                WHERE 1 = 1 
                $strWhere 
                ORDER BY A.data_geracao ASC";
        $rs  = mysql_query($str) or die(mysql_error());
        $num = mysql_num_rows($rs);

        if($num > 0)
        {
	?>
    <h1>Retorno da busca</h1>
    <p><?=$num?> registros encontrados</p>
    
    <fieldset>
    <table >
        <thead>
            <tr>
                <th>ID<br>Pedido</th>
                <th>Cliente</th>
                <th>Datas</th>
                <th>Detalhes do pedido</th>
                <th>Status</th>
                <td class="c">Ações</td>
            </tr>
        </thead>
        <tbody>
        <?
			$total = 0;
            while($vet = mysql_fetch_array($rs))
            {
				$idpedido = $vet['codigo'];
				$idcarrinho = $vet['idcarrinho'];
                $total += $vet['valor'];
				
				$strD = "SELECT * FROM pedidos_detalhe WHERE idpedido = '$idpedido' ORDER BY descricao";
				$rsD  = mysql_query($strD) or die(mysql_error());
				
				$detalhes = '';
				while($vetD = mysql_fetch_array($rsD))
				{
					$detalhes .= $vetD['descricao'].'<br>R$'.number_format($vetD['valor'], 2, ',', '.').'<br>Qtde de itens: ['.$vetD['qtde'].']<br><br>';
				}
				
				$detalhes .= 'Valor total: R$ '.number_format($vet['valor'], 2, ',', '.').'<br><br>';
				
				$strF = "SELECT * FROM frete WHERE idpedido = '$idpedido'";
				$rsF  = mysql_query($strF) or die(mysql_error());
				$vetF = mysql_fetch_array($rsF);
				
				$detalhes .= 'Dados do frete<br>Cep destino: '.$vetF['cep_destino'].'<br>R$ '.number_format($vetF['valor'], 2, ',', '.').' - '.$vetF['servico'];
        ?>
            <tr>
                <td>#<?=$vet['codigo']?></td>
                <td><?=$vet['str_nome']?></td>
                <td style="text-align:justify">
                	Geração: <?=ConverteData($vet['data_geracao'])?><br />
                    Vencimento: <?=ConverteData($vet['data_vencimento'])?><br />
                    Baixa: <?=($vet['data_baixa']) ? ConverteData($vet['data_baixa']) : " - " ?><br />
                    Cancelamento: <?=($vet['data_cancelamento']) ? ConverteData($vet['data_cancelamento']) : " - " ?><br />
                    Envio: <?=($vet['data_envio']) ? ConverteData($vet['data_envio']) : " - " ?>
                </td>
                <td style="text-align:justify"><?=$detalhes?></td>
                <td>
                <?
                if($vet['status'] == 0)
                        echo 'Pendente';
                elseif($vet['status'] == 1)
                        echo 'Pago';
                elseif($vet['status'] == 2)
                        echo 'Cancelado';
				elseif($vet['status'] == 3)
                        echo 'Enviado';
                ?>
                </td>
                <td class="c">
                <?
                if($vet['status'] == 1)
				{
				?>
                	<a class="btn i_trashcan icon small" title="cancelar" href="rel_vendas.php?cmd=cancelar&codigo=<?=$vet['codigo']?>">cancelar</a>
                    <a class="btn i_pencil icon small" title="cancelar" href="rel_vendas.php?cmd=enviar&codigo=<?=$vet['codigo']?>">enviar</a>
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
    
    <h4>Valor total dos pedidos encontrados na busca: <b>R$ <?=number_format($total, 2, ',', '.')?></b></h4>
	<?
        }
        else
        {
            echo 'Nenhum registro encontrado';
        }
    }
    ?>
</div>
</section>
<?php include('rodape.inc.php'); ?>    
