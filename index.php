<?php
include("includes/header.php");

if($_POST['cmd'] == "add_news")
{
	$email = anti_injection($_POST['email']);

	$str = "SELECT * FROM newsletter WHERE email = '$email'";
	$rs  = mysql_query($str) or die(mysql_error());
	$num = mysql_num_rows($rs);

	if($num > 0)
	{
		msg("Email já cadastrado anteriormente!");
		redireciona("index.php");
	}

	$str = "INSERT INTO newsletter (email) VALUES ('$email')";
	$rs  = mysql_query($str) or die(mysql_error());

	msg("Email cadastrado com sucesso!");
	redireciona("index.php");
}

$str = "SELECT *, CASE WHEN ordem < 10 THEN CONCAT('0', ordem) ELSE ordem END AS n_ordem FROM banners ORDER BY ordem";
$rs  = mysql_query($str) or die(mysql_error());
$num = mysql_num_rows($rs);

if($num > 0)
{
?>
<!-- static-right-social-area end-->
<div class="slider-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="bend niceties preview-2">
					<div id="ensign-nivoslider-2" class="slides">
						<?
					    while($vet = mysql_fetch_array($rs))
					    {
				        ?>
						<img src="upload/<?=$vet['imagem']?>" alt="" />
						<?
				        }
				        ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?
}
?>

<section class="slider-category-area">
	<div class="container">
		<div class="row">
			<div class="col-sm-3 col-lg-3 col-md-3">
				<div class="left-sidebar">
					<?
					include("includes/menu.php");
					?>
				</div>
			</div>
			<div class="col-sm-9 col-lg-9 col-md-9">
				<?
			    $str = "SELECT * FROM publicidade WHERE tipo = '1' AND status = '1' ORDER BY RAND() LIMIT 1";
			    $rs  = mysql_query($str) or die(mysql_error());
			    $num = mysql_num_rows($rs);

				if($num > 0)
				{
					$vet = mysql_fetch_array($rs);
				?>
				<!-- banner-area start-->
				<div class="banner-area">
    				<div class="row">
    					<div class="col-lg-12 col-md-12">
    						<div class="single-banner">
								<a href="<?=($vet['url']) ? $vet['url'] : 'javascript:;'?>" target="_blank">
									<img src="upload/<?=$vet['imagem']?>" alt="<?=stripslashes($vet['titulo'])?>">
								</a>
    						</div>
    					</div>
    				</div>
				</div>
				<!-- banner-area end-->
				<?
				}
				?>

				<!-- new products-area start-->
				<div class="newproducts-area">
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<div class="product-header">
								<div class="area-title">
									<h2>Novos Produtos</h2>
								</div>
							</div>
						</div>
					</div>

					<?
					/******************
					NOVOS PRODUTOS
					*******************/
				    $str = "SELECT * FROM produtos WHERE status = '1' ORDER BY codigo DESC LIMIT 20";
				    $rs  = mysql_query($str) or die(mysql_error());
				    $num = mysql_num_rows($rs);

					if($num > 0)
					{
					?>
					<div class="row">
						<div class="new-product-carosul">
							<?
							while($vet = mysql_fetch_array($rs))
							{
								$imagem = img_produto_destaque($vet['codigo']);
							?>
							<!-- single-product-start -->
							<div class="container">
								<div class="single-product">
									<div class="image-area" style="background-image:url('upload/<?=$imagem?>')">
										<a href="produto.php?codigo=<?=$vet['codigo']?>" style="display:none">
											<img src="upload/<?=$imagem?>" alt="<?=stripslashes($vet['nome'])?>">
										</a>
									</div>
									<div class="product-info">
										<h2 class="product-name">
											<a href="produto.php?codigo=<?=$vet['codigo']?>"><?=stripslashes($vet['nome'])?></a>
										</h2>
										<div class="price-ratting">
											<div class="price-box-area">
												<?
												valor_produto($vet['valor_produto'], $vet['valor_desconto']);
												?>
											</div>
										</div>
										<div class="action-button-area">
											<br>
											<a href="produto.php?codigo=<?=$vet['codigo']?>" class="add-to-cart">
												<span>+ Ver mais</span>
											</a>
										</div>
									</div>
								</div>
							</div>
							<!-- single-product-end -->
							<?
							}
							?>
						</div>
					</div>
				</div>
				<!-- new products-area start-->
				<?
				}
				?>

				<?
				/******************
				MAIS POPULARES
				*******************/
				$str = "SELECT DISTINCT idcategoria FROM produtos WHERE status = '1' ORDER BY visualizacoes DESC LIMIT 5";
			    $rs  = mysql_query($str) or die(mysql_error());
			    $num = mysql_num_rows($rs);

				if($num > 0)
				{
					$array_categorias = array();

					while($vet = mysql_fetch_array($rs))
					{
						$array_categorias[] = $vet['idcategoria'];
					}

					//shuffle($array_categorias);
				?>
				<!-- new-arrival-tab-area start-->
				<div class="product-tab-area">
    				<div class="row">
    					<div class="col-lg-12 col-md-12">
							<div class="product-header">
								<div class="area-title">
									<h2>Mais populares</h2>
								</div>
								<div class="tab-menu">
								  	<ul>
								  		<?
								  		for($i = 0; $i < count($array_categorias); $i++)
								  		{
								  			$class = '';
								  			if(!$i)
								  				$class = 'class="active"';
								  		?>
										<li <?=$class?>><a href="#a<?=categoria_to_id($array_categorias[$i])?>" data-toggle="tab"><?=nome_categoria($array_categorias[$i])?></a></li>
										<?
										}
										?>
								  	</ul>
								</div>
							</div>
						</div>
    				</div>
    				<div class="product-area">
    					<div class="row">
							<div class="tab-content">
								<?
								for($i = 0; $i < count($array_categorias); $i++)
								{
									$idcategoria = $array_categorias[$i];

									$class = 'class="tab-pane fade"';
						  			if(!$i)
						  				$class = 'class="tab-pane fade in active"';
								?>
								<!-- shoes-tab-start -->
								<div <?=$class?> id="a<?=categoria_to_id($array_categorias[$i])?>">
									<div class="product-carusul-pagination">
										<?
										$str = "SELECT * FROM produtos WHERE idcategoria = '$idcategoria' AND status = '1' ORDER BY visualizacoes DESC LIMIT 20";
								    	$rs  = mysql_query($str) or die(mysql_error());

									    while($vet = mysql_fetch_array($rs))
										{
											$imagem = img_produto_destaque($vet['codigo']);
										?>
										<!-- single-product-start -->
										<div class="col-lg-3 col-md-3">
											<div class="single-product">
												<div class="img-area">
													<a href="produto.php?codigo=<?=$vet['codigo']?>">
														<img src="upload/<?=$imagem?>" alt="<?=stripslashes($vet['nome'])?>">
													</a>
													<span class="new-box">
														<span class="new-label">New</span>
													</span>
													<div class="price-box">
														<?
														valor_produto($vet['valor_produto'], $vet['valor_desconto']);
														?>
													</div>
												</div>
												<div class="product-details">
													<h2 class="product-name">
														<a href="produto.php?codigo=<?=$vet['codigo']?>"><?=stripslashes($vet['nome'])?></a>
													</h2>
													<div class="button-area">
														<a href="produto.php?codigo=<?=$vet['codigo']?>">
															<span>+ Ver mais</span>
														</a>
													</div>
												</div>
											</div>
										</div>
										<!-- single-product-end -->
										<?
										}
										?>
									</div>
								</div>
								<!-- shoes-tab-end -->
								<?
								}
								?>
							</div>
						</div>
    				</div>
				</div>
				<!-- new-arrival-tab-area end-->
				<?
				}
				?>

				<?
				/*********************
				MAIS VENDIDOS
				**********************/
				$str = "SELECT DISTINCT C.idcategoria, COUNT(C.idcategoria) AS total
					FROM pedidos A
					INNER JOIN pedidos_detalhe B ON A.codigo = B.idpedido
					INNER JOIN produtos C ON B.idproduto = C.codigo
					WHERE A.status IN ('1', '2')
					AND C.status = '1'
					GROUP BY C.idcategoria
					ORDER BY total DESC LIMIT 5";
			    $rs  = mysql_query($str) or die(mysql_error());
			    $num = mysql_num_rows($rs);

				if($num > 0)
				{
					$array_categorias = array();

					while($vet = mysql_fetch_array($rs))
					{
						$array_categorias[] = $vet['idcategoria'];
					}

					//shuffle($array_categorias);
				?>
				<!-- popular-product-area-tab-area start-->
				<div class="product-tab-area popular-product">
    				<div class="row">
    					<div class="col-lg-12 col-md-12">
							<div class="product-header">
								<div class="area-title">
									<h2>Mais vendidos</h2>
								</div>
								<div class="tab-menu">
									<ul>
								  		<?
								  		for($i = 0; $i < count($array_categorias); $i++)
								  		{
								  			$class = '';
								  			if(!$i)
								  				$class = 'class="active"';
								  		?>
										<li <?=$class?>><a href="#c<?=categoria_to_id($array_categorias[$i])?>" data-toggle="tab"><?=nome_categoria($array_categorias[$i])?></a></li>
										<?
										}
										?>
								  	</ul>
								</div>
							</div>
						</div>
    				</div>
    				<div class="product-area">
    					<div class="row">
							<div class="tab-content">
								<?
								for($i = 0; $i < count($array_categorias); $i++)
								{
									$idcategoria = $array_categorias[$i];

									$class = 'class="tab-pane fade"';
						  			if(!$i)
						  				$class = 'class="tab-pane fade in active"';
								?>
								<!-- shoes-tab-start -->
								<div <?=$class?> id="c<?=categoria_to_id($array_categorias[$i])?>">
									<div class="product-carusul-pagination">
										<?
										$str = "SELECT DISTINCT C.*, COUNT(C.codigo) AS total
											FROM pedidos A
											INNER JOIN pedidos_detalhe B ON A.codigo = B.idpedido
											INNER JOIN produtos C ON B.idproduto = C.codigo
											WHERE A.status IN ('1', '2')
											AND C.status = '1'
											AND C.idcategoria = '$idcategoria'
											GROUP BY C.codigo
											ORDER BY total DESC LIMIT 20";
									    $rs  = mysql_query($str) or die(mysql_error());

									    while($vet = mysql_fetch_array($rs))
										{
											$imagem = img_produto_destaque($vet['codigo']);
										?>
										<!-- single-product-start -->
										<div class="col-lg-3 col-md-3">
											<div class="single-product">
												<div class="img-area">
													<a href="produto.php?codigo=<?=$vet['codigo']?>">
														<img src="upload/<?=$imagem?>" alt="<?=stripslashes($vet['nome'])?>">
													</a>
													<span class="new-box">
														<span class="new-label">Novo</span>
													</span>
													<div class="price-box">
														<?
														valor_produto($vet['valor_produto'], $vet['valor_desconto']);
														?>
													</div>
												</div>
												<div class="product-details">
													<h2 class="product-name">
														<a href="produto.php?codigo=<?=$vet['codigo']?>"><?=stripslashes($vet['nome'])?></a>
													</h2>
													<div class="button-area">
														<a href="produto.php?codigo=<?=$vet['codigo']?>">
															<span>+ Ver mais</span>
														</a>
													</div>
												</div>
											</div>
										</div>
										<!-- single-product-end -->
										<?
										}
										?>
									</div>
								</div>
								<!-- shoes-tab-end -->
								<?
								}
								?>
							</div>
						</div>
    				</div>
				</div>
				<!-- popular-products-tab-area end-->
				<?
				}
				?>

				<script>
				function valida_newsletter()
				{
					if(!check_email(document.form_news.email.value))
					{
						alert("Informe um email válido!");
						document.form_news.email.focus();
						return false;
					}

					document.form_news.cmd.value = "add_news";
				}
				</script>
			</div>
		</div>
	</div>
</section>

<?
$str = "SELECT A.*, DATE_FORMAT(A.data, '%d/%m/%Y %H:%i') AS dt_blog, B.nome AS usuario
    FROM blog A
    INNER JOIN usuarios B ON A.idusuario = B.codigo
    ORDER BY A.data DESC LIMIT 9";
$rs  = mysql_query($str) or die(mysql_error());
$num = mysql_num_rows($rs);

if($num > 0)
{
?>
<section class="blog-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="area-title">
					<h2>Últimas do blog</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="blog-carosul">
				<?
		        while($vet = mysql_fetch_array($rs))
		        {
		        ?>
				<!-- single-blog-start -->
				<div class="col-lg-4 col-md-4">
					<div class="single-recent-post">
						<div class="post-image">
							<a href="blog_ver.php?codigo=<?=$vet['codigo']?>">
								<img src="upload/<?=$vet['imagem']?>" alt="<?=stripslashes($vet['titulo'])?>">
							</a>
						</div>
						<div class="post-info">
							<h2 class="post-title">
								<a href="blog_ver.php?codigo=<?=$vet['codigo']?>"><?=stripslashes($vet['titulo'])?></a>
							</h2>
							<?=substr(stripslashes($vet['texto']), 0, 150)?> ...
						</div>
						<div class="post-additional-info">
							<span class="post-date">
								 <?=$vet['dt_blog']?>
							</span>
							<span class="post-author">
								Escrito por  <b><?=$vet['usuario']?></b>
							</span>
						</div>
					</div>
				</div>
				<!-- single-blog-end -->
				<?
				}
				?>
			</div>
		</div>
	</div>
</section>
<?
}

include("includes/footer.php");
?>
