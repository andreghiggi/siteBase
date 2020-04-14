<?
include("includes/header.php");

$codigo = anti_injection($_GET['codigo']);

$str = "SELECT A.*, DAY(A.data) AS dia_blog, MONTH(A.data) AS mes_blog, B.nome AS categoria, C.nome AS usuario
    FROM blog A
    INNER JOIN blog_categorias B ON A.idcategoria = B.codigo
    INNER JOIN usuarios C ON A.idusuario = C.codigo
    WHERE A.codigo = '$codigo'";
$rs  = mysql_query($str) or die(mysql_error());
$num = mysql_num_rows($rs);
$vet = mysql_fetch_array($rs);

//Caso o usuário tente forçar algum id (produto) q não exista no banco
if(!$num)
	redireciona("index.php");
?>
<!-- static-right-social-area end-->
<section class="blog-page-content">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<!-- breadcrumb-start -->
				<div class="breadcrumb">
					<ul>
						<li>
							<a href="index.php">Home</a> 
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="blog.php">Blog</a> 
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="blog.php?idcategoria=<?=$vet['idcategoria']?>"><?=stripslashes($vet['categoria'])?></a> 
							<i class="fa fa-angle-right"></i>
						</li>
						<li><?=stripslashes($vet['titulo'])?></li>
					</ul>
				</div>
				<!-- breadcrumb-end -->
			</div>
		</div>
		<div class="row">
			<div class="col-sm-9 col-lg-9 col-md-9">
				<div class="blog-content-area">
					<!-- single-blog-start -->
					<div class="single-blog">
						<div class="post-thumbnail">
							<a href="blog_ver.php?codigo=<?=$vet['codigo']?>">
								<img src="upload/<?=$vet['imagem']?>" alt="<?=stripslashes($vet['titulo'])?>">
							</a>
						</div>
						<div class="postinfo-wrapper">
							<div class="date-social">
								<div class="post-date">
									<span class="day"><?=$vet['dia_blog']?></span>
									<span class="month"><?=mes_extenso_abrev($vet['mes_blog'])?></span>
								</div>
								<div class="social-icon">
									<ul>
										<li>
											<a href="">
												<i class="fa fa-facebook"></i>
											</a>
										</li>
										<li>
											<a href="">
												<i class="fa fa-twitter"></i>
											</a>
										</li>
										<li>
											<a href="">
												<i class="fa fa-pinterest"></i>
											</a>
										</li>
										<li>
											<a href="">
												<i class="fa fa-google-plus"></i>
											</a>
										</li>
									</ul>
								</div>
							</div>
							<div class="post-info">
								<div class="hedding">
									<h1 class="blog-hedding">
										<a href="#"><?=stripslashes($vet['titulo'])?></a>
									</h1>
								</div>
								<div class="meta-small">
									/ Escrito por
									<span class="author">
										<a href="#"><?=$vet['usuario']?></a>
									</span>/
									<a href="blog.php?idcategoria=<?=$vet['idcategoria']?>"><?=stripslashes($vet['categoria'])?></a>
								</div>
								<div class="clear"></div>
								<div class="post-decrip">
									<?=stripslashes($vet['texto'])?>
								</div>								
							</div>
						</div>
					</div>				
				</div>				
			</div>
			<?
			include("includes/blog_lateral.php");
			?>	
		</div>
	</div>
</section>
<?
include("includes/footer.php");
?>