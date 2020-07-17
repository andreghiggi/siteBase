<?
include("includes/header.php");

$urlPagamento = "pagto.php";

if(mysql_fetch_assoc(mysql_query('select status from pagseguro_configuracao'))['status'])
	$urlPagamento = "pagto-pagseguro.php";


if(!$_SESSION['user_verifica'])
{
	redireciona("login.php?ind_msg=2");
}

?>
<!-- static-right-social-area end-->
<div class="container">
	<!-- page-heading start-->
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="page-heading">
				<h1>Endereço de entrega</h1>
			</div>
		</div>
	</div>
	<!-- page-heading end-->
	<?
	include("includes/menu_pagto.php");
	?>

	<?
	$_SESSION['pagamento'] = 1;
	$_SESSION['finalizar_pagto'] = 1;
	$idcadastro = $c_codigo;		
	?>	
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<?
			if($c_idendereco > 0)
			{
				$vet = mysql_fetch_array($rs);
				
				$endereco = $c_endereco.', '.$c_numero;

				if($c_complemento)
					$endereco .= ', '.$c_complemento;
			?>
            <p>Confira abaixo o local de entrega:</p>
            <p>
                <b>Endereço Cadastrado:</b><br><br>
                Endereço: <?=$endereco?><br>
                <?
                if(!empty($c_referencia))
                {
                ?>
                Ponto de referência: <?=$c_referencia?><br>
                <?
            	}
                ?>
                Bairro: <?=$c_bairro?><br>
                Cidade: <?=$c_cidade?><br>
                Estado: <?=$c_estado?><br>
                CEP: <?=$c_cep?>
            </p>
            <div class="cart-button">
				<a  href="meus_dados.php">
					<i class="fa fa-angle-left"></i>
					Alterar endereço de entrega
				</a>
				<br>
				<a  href="carrinho.php">
					<i class="fa fa-angle-left"></i>
					Voltar para o carrinho de compras
				</a>
				<a class="standard-checkout" href="<?=$urlPagamento?>">
					<span>
						Finalizar compra
						<i class="fa fa-angle-right"></i>
					</span>
				</a>
				<br><br><br>
			</div>
            <?
        	}
        	else
        	{
        	?>
            <p>O endereço de entrega não consta no sistema, favor informar no link abaixo.</p>
            <div class="cart-button">
	            <a class="standard-checkout" href="meus_dados.php" style="float:left;">
					<span>
						Cadastrar endereço de entrega
						<i class="fa fa-angle-right"></i>
					</span>
				</a>
				<br><br><br>
			</div>
        	<?
        	}
        	?>
		</div>
	</div>
</div>
<?
include("includes/footer.php");
?>
