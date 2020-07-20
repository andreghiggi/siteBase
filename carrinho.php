<?
include("includes/header.php");

if(isset($_GET['frete'])){
	$valor_frete = 0;
	$prazo_entrega = 0;
	
	$altura = 0;
	$largura = 0;
	$comprimento = 0;
	$peso = 0;
	
	$str = "SELECT A.*, B.qtde FROM produtos A
		INNER JOIN carrinho B ON A.codigo = B.idproduto
		WHERE idcarrinho = '".$_SESSION['idcarrinho']."'
		ORDER BY A.nome";
		
	$resp = mysql_query($str);
	while($row = mysql_fetch_assoc($resp)){
		$altura += $row['altura'];
		$largura += $row['largura'];
		$comprimento += $row['comprimento'];
		$peso += $row['peso']*$row['qtde'];
	}
	
	if($altura < 2) $altura = 2;
	if($largura < 11) $largura = 11;
	if($comprimento < 16) $comprimento = 16;

	$frete = mysql_fetch_assoc(mysql_query('select * from config_frete'));

	$args = 'nCdEmpresa='.$frete['empresa'];
	$args .= '&sDsSenha='.$frete['senha'];
	$args .= '&nCdServico='.$frete['SEDEX'];//.$servico;
	$args .= '&sCepOrigem='.$frete['cep_origem'];//.$vetF['cep_origem'];
	$args .= '&sCepDestino='.$_GET['frete'];//.$c_cep;
	$args .= '&nVlPeso='.$peso;//.$vetF['peso'];
	$args .= '&nCdFormato=1';
	$args .= '&nVlComprimento='.$comprimento;//.$vetF['comprimento'];
	$args .= '&nVlAltura='.$altura;//.$vetF['altura'];
	$args .= '&nVlLargura='.$largura;//.$vetF['largura'];
	$args .= '&nVlDiametro=0';
	$args .= '&sCdMaoPropria='.strtoupper($frete['mao_propria']);
	$args .= '&nVlValorDeclarado=0.00';
	$args .= '&sCdAvisoRecebimento=N';	
	$ret = file_get_contents('http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo?'.$args);
	$resp = new SimpleXMLElement($ret);
	$sedexDias = $resp->Servicos->cServico->PrazoEntrega;
	$sedex = doubleval(str_replace(',','.',$resp->Servicos->cServico->Valor[0]));

	$args = 'nCdEmpresa='.$frete['empresa'];
	$args .= '&sDsSenha='.$frete['senha'];
	$args .= '&nCdServico='.$frete['PAC'];//.$servico;
	$args .= '&sCepOrigem='.$frete['cep_origem'];//.$vetF['cep_origem'];
	$args .= '&sCepDestino='.$_GET['frete'];//.$c_cep;
	$args .= '&nVlPeso='.$peso;//.$vetF['peso'];
	$args .= '&nCdFormato=1';
	$args .= '&nVlComprimento='.$comprimento;//.$vetF['comprimento'];
	$args .= '&nVlAltura='.$altura;//.$vetF['altura'];
	$args .= '&nVlLargura='.$largura;//.$vetF['largura'];
	$args .= '&nVlDiametro=0';
	$args .= '&sCdMaoPropria='.strtoupper($frete['mao_propria']);
	$args .= '&nVlValorDeclarado=0.00';
	$args .= '&sCdAvisoRecebimento=N';	
	$ret = file_get_contents('http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo?'.$args);
	$resp = new SimpleXMLElement($ret);
	$pacDias = $resp->Servicos->cServico->PrazoEntrega;
	$pac = doubleval(str_replace(',','.',$resp->Servicos->cServico->Valor[0]));
}

if($_GET['cmd'] == "add")
{
	if(!$_SESSION['idcarrinho'])
	{
		$_SESSION["idcarrinho"] = uniqid();
	}

	$idcadastro = /*$c_codigo*/ 0;
	$idcarrinho = $_SESSION['idcarrinho'];
	$idproduto = anti_injection($_GET['idproduto']);
	$idtamanho = anti_injection($_GET['idtamanho']);
	$idcor = anti_injection($_GET['idcor']);

	if(!$idproduto)
	{
		redireciona("loja.php?ind_msg=2");
	}

	if(!$idtamanho && !$idcor)
	{
		$str = "SELECT qtde FROM carrinho WHERE idcarrinho = '$idcarrinho' AND idproduto = '$idproduto'";
		$rs  = mysql_query($str) or die(mysql_error());
		$num = mysql_num_rows($rs);

		$vet = mysql_fetch_array($rs);
		$qtde = $vet['qtde'] + 1;

		$strP = "SELECT * FROM produtos WHERE codigo = '$idproduto'";
		$rsP  = mysql_query($strP) or die(mysql_error());
		$vetP = mysql_fetch_array($rsP);

		if($num > 0)
		{
			if($vetP['estoque'] < $qtde)
			{
				//msg("O estoque do produto é inferior à quantidade selecionada, entre em contato com o suporte.");
				redireciona("loja.php?ind_msg=1");
			}

			$str = "UPDATE carrinho SET qtde = '$qtde' WHERE idcarrinho = '$idcarrinho' AND idproduto = '$idproduto'";
			$rs  = mysql_query($str) or die(mysql_error());
		}
		else
		{
			if($vetP['valor_desconto'])
				$valor = $vetP['valor_desconto'];
			else
				$valor = $vetP['valor_produto'];

			$str = "INSERT INTO carrinho (idcarrinho, idcadastro, idproduto, valor) VALUES ('$idcarrinho', '$idcadastro', '$idproduto', '$valor')";
			$rs  = mysql_query($str) or die(mysql_error());
		}
	}
	else
	{
		$str = "SELECT qtde FROM carrinho WHERE idcarrinho = '$idcarrinho' AND idproduto = '$idproduto' AND idtamanho = '$idtamanho' AND idcor = '$idcor'";
		$rs  = mysql_query($str) or die(mysql_error());
		$num = mysql_num_rows($rs);

		$vet = mysql_fetch_array($rs);
		$qtde = $vet['qtde'] + 1;

		$strP = "SELECT * FROM produtos WHERE codigo = '$idproduto'";
		$rsP  = mysql_query($strP) or die(mysql_error());
		$vetP = mysql_fetch_array($rsP);

		if($vetP['valor_desconto'])
			$valor = $vetP['valor_desconto'];
		else
			$valor = $vetP['valor_produto'];

		$strP = "SELECT * FROM produtos_estoque WHERE idproduto = '$idproduto' AND idtamanho = '$idtamanho' AND idcor = '$idcor'";
		$rsP  = mysql_query($strP) or die(mysql_error());
		$vetP = mysql_fetch_array($rsP);

		if($num > 0)
		{
			if($vetP['estoque'] < $qtde)
			{
				//msg("O estoque do produto é inferior à quantidade selecionada, entre em contato com o suporte.");
				redireciona("loja.php?ind_msg=1");
			}

			$str = "UPDATE carrinho SET qtde = '$qtde' WHERE idcarrinho = '$idcarrinho' AND idproduto = '$idproduto' AND idtamanho = '$idtamanho' AND idcor = '$idcor'";
			$rs  = mysql_query($str) or die(mysql_error());
		}
		else
		{
			$str = "INSERT INTO carrinho (idcarrinho, idcadastro, idproduto, idtamanho, idcor, valor)
				VALUES ('$idcarrinho', '$idcadastro', '$idproduto', '$idtamanho', '$idcor', '$valor')";
			$rs  = mysql_query($str) or die(mysql_error());
		}
	}

	//$_SESSION['ind_frete'] = verifica_ind_frete($idcarrinho);
	redireciona("carrinho.php");
}

if($_GET['cmd'] == 'del')
{
	$idcarrinho = $_SESSION['idcarrinho'];
	$idproduto = anti_injection($_GET['idproduto']);

	$str = "DELETE FROM carrinho WHERE idcarrinho = '$idcarrinho' AND idproduto = '$idproduto'";
	$rs  = mysql_query($str) or die(mysql_error());

	redireciona("carrinho.php?ind_msg=1");
}

if($_GET['cmd'] == 'edit_qtde')
{
	$idcarrinho = $_SESSION['idcarrinho'];
	$idproduto = anti_injection($_GET['idproduto']);
	$idtamanho = anti_injection($_GET['idtamanho']);
	$idcor = anti_injection($_GET['idcor']);
	$qtde = anti_injection($_GET['qtde']);

	$strWhere = '';
	$str = "SELECT * FROM produtos WHERE codigo = '$idproduto'";

	if($idtamanho > 0 || $idcor > 0)
	{
		$strWhere = " AND idtamanho = '$idtamanho' AND idcor = '$idcor'";
		$str = "SELECT * FROM produtos_estoque WHERE idproduto = '$idproduto' $strWhere";
	}

	//$str = "SELECT * FROM produtos WHERE codigo = '$idproduto'";
	$rs  = mysql_query($str) or die(mysql_error());
	$vet = mysql_fetch_array($rs);

	if($vet['estoque'] < $qtde)
	{
		redireciona("carrinho.php?ind_msg=2");
	}

	$str = "UPDATE carrinho SET qtde = '$qtde' WHERE idcarrinho = '$idcarrinho' AND idproduto = '$idproduto' $strWhere";
	$rs  = mysql_query($str) or die(mysql_error());
	
	if(isset($_GET['frete']))
		redireciona("carrinho.php?frete");
	else
		redireciona("carrinho.php");
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
function qtde_prod(idproduto, idtamanho, idcor)
{
	if(idproduto <= 0)
	{
		alert("Erro ao calcular quantidade");
		return false;
	}

	var idqtde = 'qtde_'+idproduto+'_'+idtamanho+'_'+idcor;

	if(document.getElementById(idqtde).value > 0)
	{
		qtde = document.getElementById(idqtde).value;
		
		if($("#frete").val() == 1){
			document.form_c.action = "carrinho.php?cmd=edit_qtde&idproduto="+idproduto+"&idtamanho="+idtamanho+"&idcor="+idcor+"&qtde="+qtde+"&frete";
		}
		else{
			document.form_c.action = "carrinho.php?cmd=edit_qtde&idproduto="+idproduto+"&idtamanho="+idtamanho+"&idcor="+idcor+"&qtde="+qtde;
		}
		document.form_c.submit();
	}
	else
	{
		alert("A quantidade informada é inválida para cálculo");

		document.form_c.action = "carrinho.php";
		document.form_c.submit();
	}
}

function calcFrete(self){
	$('#linhaSubTotal').removeClass('hidden');
	$('#linhaFrete').removeClass('hidden');
	$('#btnProximo').removeClass('hidden');
	$('#valorFrete').text(parseFloat($(self).val().split(':')[0]).toFixed(2).toString().replace('.',','));
	$('#diasFrete').text($(self).val().split(':')[1]);
	let valorfinal = parseFloat($('#subTotal').text().replace('.','').replace(',','.')) + parseFloat($(self).val().split(':')[0]);
	$('#valorFinal').text(valorfinal.toFixed(2).toString().replace('.',','));
}

function avancarCarrinho(){
	window.location.href = "endereco.php?frete="+$('#selectFrete').val()+"&destino=<?=$_GET['frete']?>";
}

$(document).ready(() => {
	$('#cep-destino').mask('99999-999');
});

</script>
<?php
?>
<!-- static-right-social-area end-->
<div class="container">
	<?
	$idcarrinho = $_SESSION['idcarrinho'];
	$idcadastro = $c_codigo;

	$str = "SELECT A.*, B.qtde, B.valor AS valor_pedido, B.idtamanho, B.idcor, C.numero, D.titulo AS cor
		FROM produtos A
		INNER JOIN carrinho B ON A.codigo = B.idproduto
		LEFT JOIN tamanhos C ON B.idtamanho = C.codigo
		LEFT JOIN cores D ON B.idcor = D.codigo
		WHERE idcarrinho = '$idcarrinho'
		ORDER BY A.nome";
	$rs  = mysql_query($str) or die(mysql_error());
	$num = mysql_num_rows($rs);

	?>
	<!-- page-heading start-->
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="page-heading">
				<h1>Lista de produtos</h1>
				<span>Seu carrinho de compras contêm: <?=$num?> produto(s)</span>
			</div>
			<?
			if($_GET['ind_msg'] == 1)
			{
			?>
			<div class="page-heading">
				<p style="color: #04B404">Produto excluído com sucesso.</p>
			</div>
			<?
			}
			?>

			<?
			if($_GET['ind_msg'] == 2)
			{
			?>
			<div class="page-heading">
				<p style="color: #DF0101">O estoque do produto é inferior à quantidade selecionada, entre em contato com o suporte.</p>
			</div>
			<?
			}
			?>
		</div>
	</div>
	<!-- page-heading end-->
	<?
	include("includes/menu_pagto.php");
	?>

	<?
	if($idcarrinho)
	{
	?>
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="cart table-responsive">
				<form name="form_c" id="form_c" method="post">
				<table>
					<thead>
						<tr>
							<th>Produto</th>
							<th>Descrição</th>
							<th>Valor</th>
							<th>Qtde</th>
							<th>Total</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?
					if($num > 0)
					{
						$total = 0;
						while($vet = mysql_fetch_array($rs))
						{
							$imagem = img_produto_destaque($vet['codigo'], $vet['idcor']);

							$valor = $vet['valor_pedido'] * $vet['qtde'];
							$valor_compras += $valor;
							$total += $valor;
					?>
					<tr>
						<td class="product-img"><a href="produto.php?codigo=<?=$vet['codigo']?>"><img src="upload/<?=$imagem?>" alt="<?=stripslashes($vet['nome'])?>"></a></td>
						<td class="cart-description">
							<p><a href="produto.php?codigo=<?=$vet['codigo']?>"><?=stripslashes($vet['nome'])?></a></p>
							<small><?=stripslashes($vet['resumo'])?></small>
							<?
							if($vet['ind_cores'] == 1)
							{
							?>
								<small>
								<?if($vet['numero']):?>
									Tamanho: <?=$vet['numero']?>
								<?endif;?>
								<?if($vet['numero'] && $vet['cor']):?> 
									- 
								<?endif;?>
								<?if($vet['cor']):?>
									Cor: <?=$vet['cor']?>
								<?endif;?>
								</small>
							<?
							}
							?>
						</td>
						<td>
							<span class="price">R$ <?=number_format($vet['valor_pedido'], 2, ',', '.')?></span>
						</td>
						<td>
							<input type="number" name="qtde_<?=$vet['codigo']?>_<?=$vet['idtamanho']?>_<?=$vet['idcor']?>" id="qtde_<?=$vet['codigo']?>_<?=$vet['idtamanho']?>_<?=$vet['idcor']?>" value="<?=$vet['qtde']?>" placeholder="1" onchange="javascript: qtde_prod(<?=$vet['codigo']?>, <?=$vet['idtamanho']?>, <?=$vet['idcor']?>);">
						</td>
						<td>
							<span class="price">R$ <?=number_format($valor, 2, ',', '.')?></span>
						</td>
						<td>
							<a href="carrinho.php?cmd=del&idproduto=<?=$vet['codigo']?>" onclick="return confirm('Deseja realmente excluir este produto do carrinho?');"><i class="fa fa-trash-o"></i></a>
						</td>
					</tr>
					<?
						}
					}
					?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3" style="text-align: left;">
								<?if(isset($_GET['frete'])):?>
									<select class="form-control" style="width:50%" onchange="calcFrete(this)" id="selectFrete">
										<option value="<?=$pac.':'.$pacDias.':1'?>">PAC</option>
										<option value="<?=$sedex.':'.$sedexDias.':2'?>" selected>SEDEX</option>
									</select>
									<br>
								<?endif;?>
							</td>
							<td><label>CEP</label></td>
							<td colspan="2" class="text-left">
								<script>
									function carregarFrete(){
										window.location.href = "carrinho.php?frete="+$('#cep-destino').val();
									}
								</script>
								<b>Informe o CEP para continuar</b>
								<br><br>
								<input id="cep-destino" type="text" class="form-control" placeholder="00000-000" value="<?if(isset($_GET['frete'])) echo $_GET['frete']?>">
								<br>
								<div class="btn btn-primary" onclick="carregarFrete()">Calcular</div>
							</td>
						</tr>
						<tr class="<?php echo (isset($sedex))?'':'hidden';?>" id="linhaSubTotal">
							<td colspan="3" class="total"><span>SubTotal</span></td>
							<td colspan="3">R$ <span class="total-price" id="subTotal"><?php echo number_format($total, 2, ',', '.');?></span></td>
						</tr>
						<tr class="<?php echo (isset($sedex))?'':'hidden';?>" id="linhaFrete">
							<td colspan="2"><span>Prazo: <span id="diasFrete" class="total-price"><?php echo $sedexDias;?></span> Dias</span></td>
							<td colspan="1" class="total"><span>Frete</span></td>
							<td colspan="3">R$ <span class="total-price" id="valorFrete"><?php echo number_format($sedex, 2, ',', '.');?></span></td>
						</tr>
						<tr>
							<td colspan="3" class="total"><span>Total</span></td>
							<td colspan="3">R$ <span class="total-price" id="valorFinal"><?php echo number_format($total+$sedex, 2, ',', '.');?></span></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="cart-button">
				<a  href="loja.php">
					<i class="fa fa-angle-left"></i>
					Continue comprando
				</a>
				
				<div id="btnProximo" class="btn btn-success standard-checkout <?php echo (isset($sedex))?'':'hidden';?>" style="float:right;width:20%;height:50px;line-height:30px;" onclick="avancarCarrinho()">
					<span style="font-size:20px;"><strong>
						Próximo
					</strong></span>
				</div>
				
				<br><br><br>
			</div>
		</div>
	</div>
	<?
	}
	else
	{
	?>
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<p>Nenhum item selecionado para o carrinho de compras</p>

            <div class="cart-button">
				<a  href="loja.php">
					<i class="fa fa-angle-left"></i>
					Acessar nossa loja
				</a>
				<br><br><br>
			</div>
		</div>
	</div>
	<?
	}
	?>
</div>
<?
include("includes/footer.php");
?>
