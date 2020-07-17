<?
/*
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
*/

session_start();

include("s_acessos.php");
include("funcoes.php");

error_reporting(0);

$class_header = 'class="home-2"';
if(strstr($_SERVER['REQUEST_URI'], 'blog'))
	$class_header = 'class="blog"';

$config_frete = 0;
$c_idendereco = 0;

$c_codigo = $_SESSION['user_codigo'];

if($c_codigo)
{
	//DADOS PESSOAIS
	$strC = "SELECT A.*, B.codigo AS idendereco, B.endereco, B.numero, B.complemento, B.referencia, B.bairro, B.cidade, B.estado, B.cep
		FROM cadastros A
		LEFT JOIN cadastros_enderecos B ON A.codigo = B.idcadastro
		WHERE A.codigo = '".$_SESSION['user_codigo']."'";
	$rsC  = mysql_query($strC) or die(mysql_error());
	$numC = mysql_num_rows($rsC);
	$vetC = mysql_fetch_array($rsC);

	//DADOS PESSOAIS
	$c_nome = stripslashes($vetC['nome']);
	$c_email = $vetC['email'];
	$c_telefone = $vetC['telefone'];
	$c_telefone_02 = $vetC['telefone_02'];
	$c_data_nascimento = $vetC['data_nascimento'];
	$c_cpf = $vetC['cpf'];
	$c_sobrenome = $vetC['sobrenome'];

	//ENDEREÇO DE ENTREGA
	$c_idendereco = stripslashes($vetC['idendereco']);
	$c_endereco = stripslashes($vetC['endereco']);
	$c_numero = $vetC['numero'];
	$c_complemento = stripslashes($vetC['complemento']);
	$c_referencia = stripslashes($vetC['referencia']);
	$c_bairro = stripslashes($vetC['bairro']);
	$c_cidade = stripslashes($vetC['cidade']);
	$c_estado = stripslashes($vetC['estado']);
	$c_cep = stripslashes($vetC['cep']);

	$servico = anti_injection($_POST['servico']);

	if(!$servico)
		$servico = 41106;

	$_SESSION['c_servico'] = $servico;

	$strF = "SELECT * FROM config_frete ";
	$rsF  = mysql_query($strF) or die(mysql_error());
	$numF = mysql_num_rows($rsF);
	$vetF = mysql_fetch_array($rsF);

	$config_frete = $numF;
}

$strS = "SELECT * FROM config_site ";
$rsS  = mysql_query($strS) or die(mysql_error());
$numS = mysql_num_rows($rsS);
$vetS = mysql_fetch_array($rsS);

//DADOS DA EMPRESA
$facebook = $vetS['facebook'];
$instagram = $vetS['instagram'];
$twitter = $vetS['twitter'];
$pinterest = $vetS['pinterest'];
$google = $vetS['google'];
$whatsapp = $vetS['whatsapp'];

$n_logo = $vetS['logo'];
$n_empresa = stripslashes($vetS['nome']);
$n_email = $vetS['email'];
$n_telefone = $vetS['telefone'];
$n_endereco = nl2br(stripslashes($vetS['endereco']));
$n_url_maps = $vetS['url_maps'];

$n_funcionamento = $vetS['funcionamento'];
$n_frete = $vetS['frete'];
$n_devolucao = $vetS['devolucao'];

$n_pagamento = $vetS['pagamento'];
$n_pac = $vetS['pac'];

if($_GET['chave'] == TRUE)
    $chave = base64_decode(anti_injection($_GET['chave']));
elseif($_POST['chave'] == TRUE)
    $chave = anti_injection($_POST['chave']);
?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if lt IE 7 ]> <html lang="en" class="ie6">    <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7">    <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8">    <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="ie9">    <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?=$n_empresa?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Favicon
		============================================ -->
		<link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">

		<!-- Google fonts
		============================================ -->
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,600" type="text/css" media="all" />
		<link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600,700' rel='stylesheet' type='text/css'>

 		<!-- CSS  -->

		<!-- Bootstrap CSS
		============================================ -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

		<!-- font-awesome.min CSS
		============================================ -->
        <link rel="stylesheet" href="css/font-awesome.min.css">

        <!-- owl.carousel CSS
		============================================ -->
       	<link rel="stylesheet" href="css/owl.carousel.css">
       	<link rel="stylesheet" href="css/owl.theme.css">
       	<link rel="stylesheet" href="css/owl.transitions.css">

 		<!-- animate CSS
		============================================ -->
        <link rel="stylesheet" href="css/animate.css">

 		<!-- meanmenu.min CSS
		============================================ -->
        <link rel="stylesheet" href="css/meanmenu.min.css">

        <!-- jquery-ui.min CSS
		============================================ -->
        <link rel="stylesheet" href="css/jquery-ui.min.css">

        <!-- fancybox CSS
		============================================ -->
        <link rel="stylesheet" href="css/fancybox/jquery.fancybox.css">

 		<!-- normalize CSS
		============================================ -->
        <link rel="stylesheet" href="css/normalize.css">

		<!-- nivo slider CSS
		============================================ -->
		<link rel="stylesheet" href="custom-slider/css/nivo-slider.css" type="text/css" />
		<link rel="stylesheet" href="custom-slider/css/preview.css" type="text/css" media="screen" />

        <!-- main CSS
		============================================ -->
        <link rel="stylesheet" href="css/main.css">

        <!-- style CSS
		============================================ -->
        <link rel="stylesheet" href="style.css">

        <!-- responsive CSS
		============================================ -->
        <link rel="stylesheet" href="css/responsive.css">

        <!-- modernizr js
		============================================ -->
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
    <body <?=$class_header?>>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->

	<header>
		<div class="header-container">
		<!-- header-bottom-area start -->
		<div class="header-bottom-area">
			<div class="container">
				<div class="row">
					<!-- logo start -->
					<div class="col-sm-6 col-xs-12 col-md-3">
						<div class="logo">
							<?
							if($n_logo)
							{
							?>
							<a href="index.php">
								<img src="upload/<?=$n_logo?>" alt="">
							</a>
							<?
							}
							?>
						</div>
					</div>
					<!-- logo end -->
					<!-- search-box start -->
					<div class="col-sm-6 col-xs-12 col-md-5">
						<div class="search-box">
							<form name="form_b" id="form_b" method="post" action="loja.php">
								<div class="search-form">
									<input type="text" name="chave" name="chave" value="<?php echo (isset($_GET['chave']))? base64_decode($_GET['chave']) : $_POST['chave'];?>" onfocus="if (this.value == 'BUSCAR PRODUTOS NO SITE') {this.value = '';}" onblur="if (this.value == '') {this.value = 'BUSCAR PRODUTOS NO SITE';}" >
									<button type="submit">
										<i class="fa fa-search"></i>
									</button>
								</div>
							</form>
						</div>
					</div>
					<!-- search-box end -->
					<?
					$idcarrinho = $_SESSION['idcarrinho'];
					$idcadastro = $c_codigo;;

					$str = "SELECT A.*, B.qtde, B.valor AS valor_pedido, B.idtamanho, B.idcor, C.numero AS tamanho, D.titulo AS cor
						FROM produtos A
						INNER JOIN carrinho B ON A.codigo = B.idproduto
						LEFT JOIN tamanhos C ON B.idtamanho = C.codigo
						LEFT JOIN cores D ON B.idcor = D.codigo
						WHERE idcadastro = '$idcadastro'
						AND idcarrinho = '$idcarrinho'
						ORDER BY A.nome";
					$rs  = mysql_query($str) or die(mysql_error());
					$num = mysql_num_rows($rs);

					if($num > 0)
					{
						$i = 0;
						$total = 0;
						$valor_frete = 0;

						while($vet = mysql_fetch_array($rs))
						{
							$imagem = img_produto_destaque($vet['codigo'], $vet['idcor']);

							$valor = $vet['valor_pedido'] * $vet['qtde'];
							$total += $valor;

							if($valor_frete_02 > $valor_frete)
								$valor_frete = $valor_frete_02;

							$array_carrinho[$i][0] = $vet['codigo'];
							$array_carrinho[$i][1] = $imagem;

							$nome_produto = stripslashes($vet['nome']);

							if($vet['idtamanho'] > 0 && $vet['idcor'] > 0)
								$nome_produto = stripslashes($vet['nome']).'<br>Tamanho: '.$vet['tamanho'].' - Cor: '.stripslashes($vet['cor']);

							$array_carrinho[$i][2] = $nome_produto;
							$array_carrinho[$i][3] = $vet['qtde'];
							$array_carrinho[$i][4] = $valor;

							$i++;
						}
					}
					?>
					<!-- shopping-cart start -->
					<div class="col-sm-12 col-xs-12 col-md-4">
						<div class="shopping-cart">
							<ul>
								<li>
									<a href="#">
										<b>Carrinho de compras</b>
										<span class="item">
											<?=$num?> produtos(s) -
											<span class="total-amu">
												R$ <?=number_format($total, 2, ',', '.')?>
											</span>
										</span>
									</a>
									<?
									if(count($array_carrinho) > 0)
									{
									?>
									<div class="mini-cart-content">
										<?
										for($i = 0; $i < count($array_carrinho); $i++)
										{
										?>
										<div class="cart-img-details">
											<div class="cart-img-photo">
												<a href="produto.php?codigo=<?=$array_carrinho[$i][0]?>"><img src="upload/<?=$array_carrinho[$i][1]?>" alt="<?=$array_carrinho[$i][2]?>"></a>
											</div>
											<div class="cart-img-contaent">
												<a href="produto.php?codigo=<?=$array_carrinho[$i][0]?>"><h4><?=$array_carrinho[$i][2]?></h4></a>
												<span class="quantity"><?=$array_carrinho[$i][3]?>X</span>
												<spanR$ <?=number_format($array_carrinho[$i][4], 2, ',', '.')?></span>
											</div>
											<div class="pro-del"><a href="#"><i class="fa fa-times-circle"></i></a>
											</div>
										</div>
										<div class="clear"></div>
										<?
										}
										?>
										<!--div class="cart-img-details">
											<div class="cart-img-photo">
												<a href="#"><img src="img/product/total-cart2.jpg" alt=""></a>
											</div>
											<div class="cart-img-contaent">
												<a href="#"><h4>Aenean eu tristique</h4></a>
												<span class="quantity">1X</span>
												<span>€70.00</span>
											</div>
											<div class="pro-del"><a href="#"><i class="fa fa-times-circle"></i></a>
											</div>
										</div-->
										<div class="cart-inner-bottom">
											<p class="total">Total: <span class="amount">R$ <?=number_format($total, 2, ',', '.')?></span></p>
											<div class="clear"></div>
											<p class="buttons"><a href="carrinho.php">Carrinho de compras</a></p>
										</div>
									</div>
									<?
									}
									?>
								</li>
							</ul>
						</div>
					</div>
					<!-- shopping-cart end -->
				</div>
			</div>
			<!-- main-menu-area start -->
			<div class="main-menu-area">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<div class="main-menu">
								<nav>
									<ul>
										<li><a href="index.php">Home</a></li>
										<li><a href="#">Produtos</a>
											<ul class="sub-menu">
												<li><a href="loja.php?ind=1">Novos produtos</a></li>
												<li><a href="loja.php?ind=2">Mais populares</a></li>
												<li><a href="loja.php?ind=4">Mais vendidos</a></li>
												<li><a href="loja.php?ind=3">Vendidos recentemente</a></li>
												<!--li><a href="loja.php?ind=5">Destaques da semana</a></li-->
											</ul>
										</li>
										<li><a href="#">Sobre a loja</a>
											<ul class="sub-menu">
												<li><a href="quem_somos.php">Quem somos</a></li>
												<li><a href="como_comprar.php">Como comprar</a></li>
												<li><a href="termos_uso.php">Termos de uso</a></li>
												<li><a href="faq.php">FAQ</a></li>
												<li><a href="politica_troca.php">Política de troca</a></li>
											</ul>
										</li>
										<li><a href="contato.php">Contato</a></li>
										<?php if(!isset($_SESSION["user_codigo"])): ?><li><a href="login.php">Entre / Cadastre-se</a></li><?php endif;?>
											<?php if(isset($_SESSION["user_codigo"])): ?><li><a href="logoff.php">Sair</a></li><?php endif;?>
									</ul>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- main-menu-area end -->
		</div>
		<!-- header-bottom-area end -->
		</div>
		<!-- mobile-menu-area start -->
		<div class="mobile-menu-area">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="mobile-menu">
							<nav id="dropdown">
								<ul>
									<li><a href="index.php">Home</a></li>
									<li><a href="loja.php">Loja Virtual</a></li>
									<li><a href="#">Sobre a loja</a>
										<ul class="sub-menu">
											<li><a href="quem_somos.php">Quem somos</a></li>
											<li><a href="como_comprar.php">Como comprar</a></li>
											<li><a href="politica_troca.php">Política de troca</a></li>
											<li><a href="termos_uso.php">Termos de uso</a></li>
											<li><a href="faq.php">FAQ</a></li>
										</ul>
									</li>
									<li><a href="#">Produtos</a>
										<ul class="sub-menu">
											<li><a href="loja.php?ind=1">Novos produtos</a></li>
											<li><a href="loja.php?ind=2">Mais populares</a></li>
											<li><a href="loja.php?ind=4">Mais vendidos</a></li>
											<li><a href="loja.php?ind=3">Vendidos recentemente</a></li>
											<!--li><a href="loja.php?ind=5">Destaques da semana</a></li-->
										</ul>
									</li>
									<li><a href="blog.php">Blog</a></li>
									<li><a href="contato.php">Contato</a></li>
									<li><a href="login.php">Entre / Cadastre-se</a></li>
								</ul>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- mobile-menu-area end -->
	</header>
	<!-- static-right-social-area start-->
	<div class="static-right-social-area">
		<div class="static-right-social">
			<ul>
				<?
				if($facebook)
				{
				?>
				<li class="liFacebook"><a href="<?=$facebook?>" target="_blank"><i class="fa fa-facebook"></i>Nosso Facebook</a></li>
				<?
				}

				if($instagram)
				{
				?>
				<li class="liInstagram"><a href="<?=$instagram?>" target="_blank"><i class="fa fa-instagram"></i>Nosso Instagram</a></li>
				<?
				}

				if($twitter)
				{
				?>
				<li class="liTwitter"><a href="<?=$twitter?>" target="_blank"><i class="fa fa-twitter"></i>Nosso Twitter</a></li>
				<?
				}

				if($pinterest)
				{
				?>
				<li class="liPinterest"><a href="<?=$pinterest?>" target="_blank"><i class="fa fa-pinterest-p"></i>Nosso Pinterest</a></li>
				<?
				}

				if($google)
				{
				?>
				<li class="liGoogle"><a href="<?=$google?>" target="_blank"><i class="fa fa-google-plus"></i>Nosso Google Plus</a></li>
				<?
				}

				if($whatsapp)
				{
				?>
				<li class="liWhatsapp"><a href="<?=$whatsapp?>" target="_blank"><i class="fa fa-whatsapp"></i>Whatsapp</a></li>
				<?
				}
				?>
			</ul>
		</div>
	</div>
