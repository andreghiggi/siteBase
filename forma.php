<?
include("includes/header.php");

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
				<h1>Forma de pagamento</h1>
			</div>
		</div>
	</div>
	<!-- page-heading end-->
	<?
	include("includes/menu_pagto.php");
	?>

	<div class="row">
		<div class="col-lg-12 col-md-12">			
            <p>Escolha entre as opções abaixo:</p>
            <br>
            <p>
	            <form name="form_c" id="form_c" method="post">
	                <input type="radio" id="pagamento_1" name="pagamento" <?=($_SESSION['pagamento'] == FALSE || $_SESSION['pagamento'] == 1) ? "checked" : "" ?> value="1" onclick="javascript: forma_pagto(1);">&nbsp;&nbsp;&nbsp;<strong>Pagar pelo site</strong>
	                <br>
	                <br>
	                <?if($_SESSION['retLoja'] == 2):?>
	                	<input type="radio" id="pagamento_2" name="pagamento" <?=($_SESSION['pagamento'] == 2) ? "checked" : "" ?> value="2" onclick="javascript: forma_pagto(2);">&nbsp;&nbsp;&nbsp;<strong>Pagamento presencial</strong>
	            		<?endif;?>
	            </form>
            </p>
            <br>
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
				
				<a class="standard-checkout" href="pagto.php">
					<span>
						Finalizar compra
						<i class="fa fa-angle-right"></i>
					</span>
				</a>				
				<br><br><br>
			</div>
		</div>
	</div>
</div>
<?
include("includes/footer.php");
?>
