<?
include("includes/header.php");

include("PagSeguroArquivos/pagseguro_integracao.php");

$ModeloPagseguro = new ModeloPagseguro();
$v = $ModeloPagseguro->setsessionPagseguro();

if(!$_SESSION['user_verifica'])
{
	redireciona("login.php?ind_msg=2");
}

if($_GET['fin'] == $_SESSION['finalizar'] && $_GET['fin'] != null)
{
	
	//$_SESSION['finalizar'] = -1;
	$idcadastro = $c_codigo;
	$idcarrinho = $_SESSION['idcarrinho'];

	if($n_pagamento == 1)
		$pagamento = 1;
	elseif($n_pagamento == 2)
		$pagamento = 2;

	if($_SESSION['pagamento'])
		$pagamento = $_SESSION['pagamento'];
	
	/*if($_SESSION['idpedido'])
	{
		$str = "DELETE FROM pedidos WHERE codigo = '".$_SESSION['idpedido']."'";
		$rs  = mysql_query($str) or die(mysql_error());
		
		$str = "DELETE FROM pedidos_detalhe WHERE idpedido = '".$_SESSION['idpedido']."'";
		$rs  = mysql_query($str) or die(mysql_error());
		
		$str = "DELETE FROM frete WHERE idpedido = '".$_SESSION['idpedido']."'";
		$rs  = mysql_query($str) or die(mysql_error());
	}*/
	
	$valor_compra = subtotal_carrinho($c_codigo, $_SESSION['idcarrinho'], $_SESSION['c_servico'], $c_cep, $n_pac);
	
	$str = "INSERT INTO pedidos (idcadastro, idcarrinho, valor, pagamento, data_geracao, `status`) VALUES ('$idcadastro', '$idcarrinho', '$valor_compra', '1', CURDATE(), '1')";
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
			'.$tr_frete.'
			<tr>
				<td><b>Valor total</b></td>
				<td>R$ '.number_format(($total + $valor_frete), 2, ',', '.').'</td>
			</tr>
			<tr>
				<td><b>Prazo de entrega</b></td>
				<td>'.$prazo_entrega.' dia(s)</td>
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
	//mail($c_email, $assunto, $corpo, $headers ,"-r ".$n_email);
	//mail($n_email, $assunto, $corpo, $headers);

	/*$_SESSION['idcarrinho_auxiliar'] = $_SESSION['idcarrinho'];
	$_SESSION['idpedido_auxiliar'] = $_SESSION['idpedido'];
	$_SESSION['pagamento_auxiliar'] = $_SESSION['pagamento'];

	unset($_SESSION['finalizar_pagto']);
	unset($_SESSION['idcarrinho']);
	unset($_SESSION['idpedido']);
	unset($_SESSION['pagamento']);*/
	
	redireciona("index.php");
}
else{
	$_SESSION['finalizar'] = uniqid();
}

$idpedido = $_SESSION['idpedido_auxiliar'];
$valor = subtotal_carrinho($c_codigo, $_SESSION['idcarrinho_auxiliar'], $servico, $c_cep, $n_pac);
$valor = number_format($valor, 2, ',', '.');
?>
<!-- static-right-social-area end-->
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<!-- breadcrumbs start-->
			<div class="breadcrumb">
				<ul>
					<li>
						<a href="index.php">Home</a> 
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="carrinho.php">Carrinho de compras</a> 
						<i class="fa fa-angle-right"></i>
					</li>
					<li>Finalizar compra</li>
				</ul>
			</div>
			<!-- breadcrumbs end-->
		</div>
	</div>
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
			<br><h3>Finalizar o Pagamento</h3>
			<?php

				$resp = mysql_query("select * from carrinho where idcarrinho like '".$_SESSION['idcarrinho']."'");
				$cont = 1;
				
				while($row = mysql_fetch_array($resp)){
					$produto = mysql_fetch_array(mysql_query("select * from produtos where codigo like ".$row['idproduto']));
					$req .= "&itemId".$cont."=".$produto['codigo']."&itemDescription".$cont."=".urlencode($produto["nome"])."&itemAmount".$cont."=".number_format($produto['valor_produto'],2,'.','')."&itemQuantity".$cont."=".$row['qtde']."&itemWeight".$cont."=".($produto['peso']*1000);
					$cont++;
				}
			?>


          
<?php 
	$valor_frete = 0;
	$prazo_entrega = 0;
	
	$altura = 0;
	$largura = 0;
	$comprimento = 0;
	$peso = 0;
	
	
	$str = "SELECT A.*, B.qtde FROM produtos A
		INNER JOIN carrinho B ON A.codigo = B.idproduto
		WHERE idcadastro = '".(isset($c_codigo)? $c_codigo:0)."'
		AND idcarrinho = '".$_SESSION['idcarrinho']."'
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
    $endereco = mysql_fetch_assoc(mysql_query('select * from cadastros_enderecos WHERE idcadastro = ' . $c_codigo));
    
	$args = 'nCdEmpresa='.$frete['empresa'];
	$args .= '&sDsSenha='.$frete['senha'];
	$args .= '&nCdServico='.$frete['SEDEX'];//.$servico;
	$args .= '&sCepOrigem='.$frete['cep_origem'];//.$vetF['cep_origem'];
	$args .= '&sCepDestino='.$endereco['cep'];//.$c_cep;
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

include 'modelo/templates/comprador.php';



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
						$idcarrinho = $_SESSION['idcarrinho'];
	$idcadastro = $c_codigo;
$id_p = 0;
	$str = "SELECT A.*, B.qtde, B.valor AS valor_pedido, B.codigo as Bcodigo, B.idtamanho, B.idcor, C.numero, D.titulo AS cor
		FROM produtos A
		INNER JOIN carrinho B ON A.codigo = B.idproduto
		LEFT JOIN tamanhos C ON B.idtamanho = C.codigo
		LEFT JOIN cores D ON B.idcor = D.codigo
		WHERE (idcadastro = '$idcadastro' or idcadastro = 0)
		AND idcarrinho = '$idcarrinho'
		ORDER BY A.nome";
	$rs  = mysql_query($str) or die(mysql_error());
	$num = mysql_num_rows($rs);
					
					if($num > 0)
					{
						$total = 0;
						while($vet = mysql_fetch_array($rs))
						{
						    $id_p = $vet['Bcodigo'];
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
							<small>Tamanho: <?=($vet['numero']) ? $vet['numero'] : 'Não informado'?> - Cor: <?=($vet['cor']) ? $vet['cor'] : 'Não informado'?></small>
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
						
						</td>
					</tr>
					<?
						}
					}
					?>
					</tbody>
					<tfoot>
						<tr class="<?php echo (isset($sedex))?'':'hidden';?>" id="linhaSubTotal">
							<td colspan="3" class="total"><span>SubTotal</span></td>
							<td colspan="3">R$ <span class="total-price" id="totalValue"><?php echo number_format($total, 2, ',', '.');?></span></td>
						</tr>
						<tr class="<?php echo (isset($sedex))?'':'hidden';?>" id="linhaFrete">
							<td colspan="2"><span>Prazo: <span id="diasFrete" class="total-price"><?php echo $sedexDias;?></span> Dias</span></td>
							<td colspan="1" class="total"><span>Frete</span></td>
							<td colspan="3">R$ <span class="total-price" id="valorFrete"><?php echo $sedex;?></span></td>
						</tr>
						<tr>
							<td colspan="3" class="total"><span>Total</span></td>
							<td colspan="3">R$ <span class="total-price" id="valorFinal"><?php echo number_format($total+$sedex, 2, ',', '.');?></span></td>
						</tr>
						<tr>
						    <td colspan="6">
						        
						    

		<br clear="all"/>
		          <?php include 'modelo/templates/pagamento.php'; ?>
        <br clear="all"/>
						    </td>
						</tr>
						<tr style="display:none;">
							<td colspan="3" style="text-align: left;">
							
							</td>
							<td></td>
							<td colspan="2" class="text-left">
							    <?php include 'modelo/env.php'; ?>   
								<div class="btn btn-primary" >Pagar</div>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="cart-button">
				<a  href="loja.php">
					<i class="fa fa-angle-left"></i>
					Continue comprando
				</a>
				
				<div id="btnProximo" class="btn btn-success standard-checkout <?php echo (isset($sedex))?'':'hidden';?>" style="float:right;height:50px;line-height:30px; display:none;" onclick="avancarCarrinho()">
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
	</div>
</div>

<!--<div class="btn" onclick="$('#myModal').modal();">modal</div>-->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-body text-center">
      	<div id="pagamentoCarregando">
      		<span class="mb-3"><strong>Carregando...</strong></span><br><span class="spinner-border spinner-border-lg text-primary"></span>
      	</div>
      	<div id="pagamentoConcluido" class="hidden">
      		<h2 class="text-success mt-3"><strong>Seu pagamento foi aprovado!</strong></h2><br>
      		<p>Você receberar mais informações sobre o seu pedido no email cadastrado.</p>
      		<a href="index.php" class="btn btn-info float-right">Ok</a>
      	</div>
      </div>

    </div>
  </div>
</div>


<?
include("includes/footer.php");
?>
<?php /* ?>
<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>


<?php */ ?>

<?php if(false): ?>
<script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
<?php else: ?>
<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
<?php endif; ?>



<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="js/jquery.mask.min.js"></script>

<script> //Máscaras dos inputs
  jQuery(function($){
  $("#creditCardHolderBirthDate").mask("99/99/9999");
  $("#senderCPF").mask("999.999.999-99");
  $("#creditCardHolderCPF").mask("999.999.999-99");
  $("#shippingAddressPostalCode").mask("99999-999");
  $("#billingAddressPostalCode").mask("99999-999");
  });

  $(document).ready(function() {
    $.ajax({
      type: 'GET',
      url: 'modelo/getSession.php',
      cache: false,
      success: function(data) {
        PagSeguroDirectPayment.setSessionId(data);
      }
    });
  });
</script>

<script>

$("input[name='changePaymentMethod']").on('click', function(e) {
    if (e.currentTarget.value == 'creditCard') {
      $('#boletoData').css('display', 'none');
      $('#creditCardData').css('display', 'block');
    } else if (e.currentTarget.value == 'boleto') {
      $('#creditCardData').css('display', 'none');
      $('#boletoData').css('display', 'block');
    }
});

$("input[name='holderType']").on('click', function(e) {
    if (e.currentTarget.value == 'sameHolder') {
      $('#dadosOtherPagador').css('display', 'none');
      ReInserir();
    } else if (e.currentTarget.value == 'otherHolder') {
      $('#dadosOtherPagador').css('display', 'block');
    }
});

$("input[type='text']").on('blur', function(e) {
    if ( ( $("#" + e.currentTarget.id).css('border') == '2px solid rgb(255, 0, 0)') || ($("#" + e.currentTarget.id).css('border') == '2px solid red' ) ) {
      $("#" + e.currentTarget.id).css('border', '1px solid #999');
    }
});

  function ReInserir() {
        $("#creditCardHolderName").val($("#senderName").val());
        $("#creditCardHolderCPF").val($("#senderCPF").val());
        $("#creditCardHolderAreaCode").val($("#senderAreaCode").val());
        $("#creditCardHolderPhone").val($("#senderPhone").val());
        $("#billingAddressPostalCode").val($("#shippingAddressPostalCode").val());
        $("#billingAddressStreet").val($("#shippingAddressStreet").val());
        $("#billingAddressNumber").val($("#shippingAddressNumber").val());
        $("#billingAddressComplement").val($("#shippingAddressComplement").val());
        $("#billingAddressDistrict").val($("#shippingAddressDistrict").val());
        $("#billingAddressCity").val($("#shippingAddressCity").val());
        $("#billingAddressState").val($("#shippingAddressState").val());
        $("#billingAddressCountry").val("BRA");
  }
</script>

<script>

  function parcelasDisponiveis() {
      console.log('parcelas_disponiveis');
    PagSeguroDirectPayment.getInstallments({
        
      amount: (($("#totalValue").html()).replace(",", ".")),
      brand: $("#creditCardBrand").val(),
      maxInstallmentNoInterest: 2,

      success: function(response) {
        //console.log(response.installments);
        //$("#installmentsWrapper").css('display', "block");
        
        var installments = response.installments[$("#creditCardBrand").val()];

        var options = '';
        for (var i in installments) {

          var optionItem     = installments[i];
          var optionQuantity = optionItem.quantity;
          var optionAmount   = optionItem.installmentAmount;
          var optionLabel    = (optionQuantity + " x R$ " + (optionAmount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,').replace(".", ',')));

          options += ('<option value="' + optionItem.quantity + '" valorparcela="' + optionAmount +'">'+ optionLabel +'</option>');

        };

        $("#installmentQuantity").html(options);

      },

      error: function(response) {
        console.log('error', response);
      },

      complete: function(response) {
          console.log('response', response);
      }
    });
  }

  $("#installmentQuantity").change(function() {
    var option = $(this).find("option:selected");
    if (option.length) {
      $("#installmentValue").val( option.attr("valorparcela") );
    }
  });

  function brandCard() {
    
    PagSeguroDirectPayment.getBrand({
      cardBin: $("#cardNumber").val(),
      success: function(response) {
          console.log('response', response);
        $("#creditCardBrand").val(response.brand.name);
        //$("#cardNumber").css('border', '1px solid #999');

        /*if (response.brand.expirable) {
          $("#expiraCartao").css('display', 'block');
        } else {
          $("#expiraCartao").css('display', 'none');
        }
        if (response.brand.cvvSize > 0) {
          $("#cvvCartao").css('display', 'block');
        } else {
          $("#cvvCartao").css('display', 'none');
        }*/

        $("#bandeiraCartao").attr('src', 'https://stc.sandbox.pagseguro.uol.com.br/public/img/payment-methods-flags/68x30/' + response.brand.name + '.png');


        parcelasDisponiveis();

      },

      error: function(response) {
        $("#cardNumber").css('border', '2px solid red');
        console.log(response);
        //$("#cardNumber").focus();
      },

      complete: function(response) {
				console.log(response);
      }

    }).catch(err => console.log(err));

  }

  function showModal() {
      $("#modal-title").html("Aguarde");
      $("#modal-body").html("");
      $("#aguarde").modal("show");
  }
<?php 
	$idcadastro = $c_codigo;

	$str = "SELECT A.*, B.qtde, B.valor AS valor_pedido, B.idtamanho, B.idcor, C.numero, D.titulo AS cor
		FROM produtos A
		INNER JOIN carrinho B ON A.codigo = B.idproduto
		LEFT JOIN tamanhos C ON B.idtamanho = C.codigo
		LEFT JOIN cores D ON B.idcor = D.codigo
		WHERE (idcadastro = '$idcadastro' or idcadastro = 0)
		AND idcarrinho = '$idcarrinho'
		ORDER BY A.nome";
	$rs  = mysql_query($str) or die(mysql_error());
	$num = mysql_num_rows($rs);

						$total = 0;
						$_itens = array();
						while($vet = mysql_fetch_array($rs))
						{
						    $dados = array();
						    $dados['id'] = $vet["codigo"];
						    $dados['description'] = $vet["nome"];
						    $dados['amount'] = $vet["valor_pedido"];
						    $dados['quantity'] = $vet["qtde"];
						    
						    array_push($_itens, $dados);
							$valor = $vet['valor_pedido'] * $vet['qtde'];
							$valor_compras += $valor;
							$total += $valor;
						}
						
						$itens = json_encode($_itens);

?>
  function pagarBoleto(senderHash) {
  	showModal();
    $.ajax({
      type: 'POST',
      url: 'modelo/pagamentoBoleto.php',
      cache: false,
      data: {
        id: <?php echo $id_p ?>,
        idcadastro: <?php echo $idcadastro ?>,
        idcarrinho: '<?php echo $idcarrinho ?>',
        valor: '<?php echo $total ?>',
        email: $("#senderEmail").val(),
        nome: $("#senderName").val(),
        cpf: $("#senderCPF").val(),
        ddd: $("#senderAreaCode").val(),
        telefone: $("#senderPhone").val(),
        cep: $("#shippingAddressPostalCode").val(),
        endereco: $("#shippingAddressStreet").val(),
        numero: $("#shippingAddressNumber").val(),
        complemento: $("#shippingAddressComplement").val(),
        bairro: $("#shippingAddressDistrict").val(),
        cidade: $("#shippingAddressCity").val(),
        estado: $("#shippingAddressState").val(),
        pais: "BRA",
        senderHash: senderHash,
      },
      success: function(data) {
				console.log("entrou");
        if (!(data.paymentLink)) {
          //alert(data);
          $("#modal-title").html("<font color='red'>Erro</font>");

          $("#modal-body").html("");
          //console.log(data.error);
          $.each(data.error, function (index, value) {
            if (value.code) {
              //console.log("6 " + value.code);
              tratarError(value.code);
            } else {
              //console.log("7 " + data.error);
              tratarError(data.error.code);
            }

          });
        } else {
          window.location = data.paymentLink;
          setTimeout(function () {
            $("#modal-body").html("");
            $("#modal-title").html("<font color='green'>Sucesso!</font>")

            $("#modal-body").html("Caso você não seja redirecionado para o seu boleto, clique no botão abaixo.<br /><br /><a href='" + data.paymentLink + "'><center><img src='images/boleto.png' /><br /><br /><button class='btn-success btn-block btn-lg'>Ir para o meu boleto</button></center></a>");
          }, 3500);
        }

      },
    error: function(data){console.log(data)}
    });

  }

    function pagarCartao(senderHash) {
      $('#myModal').modal();
      PagSeguroDirectPayment.createCardToken({
        
        cardNumber: $("#cardNumber").val(),
        brand: $("#creditCardBrand").val(),
        cvv: $("#cardCvv").val(),
        expirationMonth: $("#cardExpirationMonth").val(),
        expirationYear: $("#cardExpirationYear").val(),

        success: function (response) {
            console.log('response_s', response);
          $("#creditCardToken").val(response.card.token);
          finPagarCartao(senderHash);
        },
        error: function (response) {
          if (response.error) {
            $("#modal-title").html("<font color='red'>Erro</font>");

            $("#modal-body").html("");
            //console.log("4" + response);
            $.each(response.errors, function (index, value) {
              //console.log(value);
              tratarError(value);
            });
          }
        },
        complete: function (response) {
					console.log('complete_card',response);
        }

      });
      
   }
   
	 function finPagarCartao(senderHash){

			$.ajax({
        type: 'POST',
        url: 'modelo/pagamentoCartao.php',
        cache: false,
        data: {
            id: <?php echo $id_p ?>,
            idcadastro: <?php echo $idcadastro ?>,
            idcarrinho: '<?php echo $idcarrinho ?>',
            valor: '<?php echo $total ?>',
          email: $("#senderEmail").val(),
          nome: $("#senderName").val(),
          cpf: $("#senderCPF").val(),
          ddd: $("#senderAreaCode").val(),
          telefone: $("#senderPhone").val(),
          cep: $("#shippingAddressPostalCode").val(),
          endereco: $("#shippingAddressStreet").val(),
          numero: $("#shippingAddressNumber").val(),
          complemento: $("#shippingAddressComplement").val(),
          bairro: $("#shippingAddressDistrict").val(),
          cidade: $("#shippingAddressCity").val(),
          estado: $("#shippingAddressState").val(),
          itens: '<?php echo $itens ?>',
          pais: "BRA",
          senderHash: senderHash,

          enderecoPagamento: $("#billingAddressStreet").val(),
          numeroPagamento: $("#billingAddressNumber").val(),
          complementoPagamento: $("#billingAddressComplement").val(),
          bairroPagamento: $("#billingAddressDistrict").val(),
          cepPagamento: $("#billingAddressPostalCode").val(),
          cidadePagamento: $("#billingAddressCity").val(),
          estadoPagamento: $("#billingAddressState").val(),
          cardToken: $("#creditCardToken").val(),
          cardNome: $("#creditCardHolderName").val(),
          cardCPF: $("#creditCardHolderCPF").val(),
          cardNasc: $("#creditCardHolderBirthDate").val(),
          cardFoneArea: $("#creditCardHolderAreaCode").val(),
          cardFoneNum: $("#creditCardHolderPhone").val(),

          numParcelas: $("#installmentQuantity").val(),
          valorParcelas: $("#installmentValue").val(),

        },
        success: function(data) {
          console.log('success_data',data);
          if (data.error) {
            if (data.error.code == "53037") {
              //$("#creditCardPaymentButton").click();
            } else {
              $("#modal-title").html("<font color='red'>Erro</font>");

              $("#modal-body").html("");
              $.each(data.error, function (index, value) {
                if (value.code) {
                  tratarError(value.code);

                } else {
                  tratarError(data.error.code)
                }
              })
            }
          } else {


            $.ajax({
              type: 'POST',
              url: 'modelo/getStatus.php',
              cache: false,
              data: {
                id: data.code,
              },
              success: function(status) {

                if (status == "7") {
                  //alert(data);
                  $("#modal-title").html("<font color='red'>Erro</font>");

                  $("#modal-body").html("Erro ao processar o seu pagamento.<br/> Não se preocupe pois esse valor <b>não será debitado de sua conta ou não constará em sua fatura</b><br /><br />Verifique se você possui limite suficiente para efetuar a transação e/ou tente um cartão diferente");

                } else {
                	$('#pagamentoCarregando').addClass('hidden');
                	$('#pagamentoConcluido').removeClass('hidden');
                }

              }
            });


            //console.log("1 " + data);
          }

          },
       error: function(err) {console.log('saida',err);}

      });

    }

function tratarError(id) {
  if (id.charAt(0) == '2') id = id.substr(1);
  if (id == "53020" || id == '53021') {
    $("#modal-body").append("<p>Verifique telefone inserido</p>");
    $("#senderPhone").css('border', '2px solid red');

  } else if (id == "53010" || id == '53011' || id == '53012') {
    $("#modal-body").append("<p>Verifique o e-mail inserido</p>");
    $("#senderEmail").css('border', '2px solid red');

  } else if (id == "53017") {
    $("#modal-body").append("<p>Verifique o CPF inserido</p>");
    $("#senderCPF").css('border', '2px solid red');

  } else if (id == "53018" || id == "53019") {
    $("#modal-body").append("<p>Verifique o DDD inserido</p>");
    $("#senderAreaCode").css('border', '2px solid red');

  } else if (id == "53013" || id == '53014' || id == '53015') {
    $("#modal-body").append("<p>Verifique o nome inserido</p>");
    $("#senderName").css('border', '2px solid red');

  } else if (id == "53029" || id == '53030') {
    $("#modal-body").append("<p>Verifique o bairro inserido</p>");
    $("#shippingAddressDistrict").css('border', '2px solid red');

  } else if (id == "53022" || id == '53023') {
    $("#modal-body").append("<p>Verifique o CEP inserido</p>");
    $("#shippingAddressPostalCode").css('border', '2px solid red');

  } else if (id == "53024" || id == '53025') {
    $("#modal-body").append("<p>Verifique a rua inserido</p>");
    $("#shippingAddressStreet").css('border', '2px solid red');

  } else if (id == "53026" || id == '53027') {
    $("#modal-body").append("<p>Verifique o número inserido</p>");
    $("#shippingAddressNumber").css('border', '2px solid red');

  } else if (id == "53033" || id == '53034') {
    $("#modal-body").append("<p>Verifique o estado inserido</p>");
    $("#shippingAddressState").css('border', '2px solid red');

  } else if (id == "53031" || id == '53032') {
    $("#modal-body").append("<p>Verifique a cidade informada</p>");
    $("#shippingAddressCity").css('border', '2px solid red');

  } else if (id == '10001') {
    $("#modal-body").append("<p>Verifique o número do cartão inserido</p>");
    $("#cardNumber").css('border', '2px solid red');

  } else if (id == '10002' || id == '30405') {
    $("#modal-body").append("<p>Verifique a data de validade do cartão inserido</p>");
    $("#cardExpirationMonth").css('border', '2px solid red');
    $("#cardExpirationYear").css('border', '2px solid red');

  } else if (id == '10004') {
    $("#modal-body").append("<p>É obrigatorio informar o código de segurança, que se encontra no verso, do cartão</p>");
    $("#cardCvv").css('border', '2px solid red');

  } else if (id == '10006' || id == '10003' || id == '53037') {
    $("#modal-body").append("<p>Verifique o código de segurança do cartão informado</p>");
    $("#cardCvv").css('border', '2px solid red');

  } else if (id == '30404') {
    $("#modal-body").append("<p>Ocorreu um erro. Atualize a página e tente novamente!</p>");

  } else if (id == '53047') {
    $("#modal-body").append("<p>Verifique a data de nascimento do titular do cartão informada</p>");
    $("#creditCardHolderBirthDate").css('border', '2px solid red');

  } else if (id == '53053' || id == '53054') {
    $("#modal-body").append("<p>Verifique o CEP inserido</p>");
    $("#billingAddressPostalCode").css('border', '2px solid red');

  } else if (id == '53055' || id == '53056') {
    $("#modal-body").append("<p>Verifique a rua inserido</p>");
    $("#billingAddressStreet").css('border', '2px solid red');

  } else if (id == '53042' || id == '53043' || id == '53044') {
    $("#modal-body").append("<p>Verifique o nome inserido</p>");
    $("#creditCardHolderName").css('border', '2px solid red');

  } else if (id == '53057' || id == '53058') {
    $("#modal-body").append("<p>Verifique o número inserido</p>");
    $("#billingAddressNumber").css('border', '2px solid red');

  } else if (id == '53062' || id == '53063') {
    $("#modal-body").append("<p>Verifique a cidade informada</p>");
    $("#billingAddressCity").css('border', '2px solid red');

  } else if (id == '53045' || id == '53046') {
    $("#modal-body").append("<p>Verifique o CPF inserido</p>");
    $("#creditCardHolderCPF").css('border', '2px solid red');

  } else if (id == '53060' || id == '53061') {
    $("#modal-body").append("<p>Verifique o bairro inserido</p>");
    $("#billingAddressDistrict").css('border', '2px solid red');

  } else if (id == '53064' || id == '53065') {
    $("#modal-body").append("<p>Verifique o estado inserido</p>");
    $("#billingAddressState").css('border', '2px solid red');

  } else if (id == '53051' || id == '53052') {
    $("#modal-body").append("<p>Verifique telefone inserido</p>");
    $("#billingAddressState").css('border', '2px solid red');

  } else if (id == '53049' || id == '53050') {
    $("#modal-body").append("<p>Verifique o código de área informado</p>");
    $("#creditCardHolderAreaCode").css('border', '2px solid red');

  } else if (id == '53122') {
    $("#modal-body").append("<p>Enquanto na sandbox do PagSeguro, o e-mail deve ter o domínio '@sandbox.pagseguro.com.br' (ex.: comprador@sandbox.pagseguro.com.br)</p>");

  }

  // else {
  //   $("#modal-body").append("<p>"+ id + "</p>");
  // }
}

</script>


