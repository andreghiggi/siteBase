<?
include("includes/header.php");

$pagina = anti_injection($_GET['pagina']);
$idcategoria = anti_injection($_GET['idcategoria']);
$mesb = anti_injection($_GET['mesb']);
$anob = anti_injection($_GET['anob']);

$strWhere = "";

if($idcategoria)
	$strWhere .= " AND A.idcategoria = '$idcategoria'";

if($mesb)
	$strWhere .= " AND MONTH(A.data) = '$mesb'";

if($anob)
	$strWhere .= " AND YEAR(A.data) = '$anob'";

if($chave)
	$strWhere .= " AND (titulo LIKE '%$chave%' OR texto LIKE '%$chave%')";

if($pagina)
    $pag++;

$num_lista = 5;

if($pagina == FALSE || $pagina == 1)
    $pag = 0;
else
    $pag = $pagina - 1;

$pag = $pag * $num_lista;
?>
<!-- static-right-social-area end-->
<section class="blog-page-content">
	<div class="container">
		<div class="row">
			<div class="col-sm-9 col-lg-9 col-md-9">
				<div class="blog-content-area">
					<?
					$str = "SELECT A.*, DAY(A.data) AS dia_blog, MONTH(A.data) AS mes_blog, B.nome AS categoria, C.nome AS usuario
					    FROM blog A
					    INNER JOIN blog_categorias B ON A.idcategoria = B.codigo
					    INNER JOIN usuarios C ON A.idusuario = C.codigo
					    WHERE 1 = 1
					    $strWhere
					    ORDER BY A.data LIMIT $pag, $num_lista";
					$rs  = mysql_query($str) or die(mysql_error());
					$num = mysql_num_rows($rs);

					if($num > 0)
					{
				        while($vet = mysql_fetch_array($rs))
				        {
					?>
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
										<a href="blog_ver.php?codigo=<?=$vet['codigo']?>"><?=stripslashes($vet['titulo'])?></a>
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
									<?=substr(stripslashes($vet['texto']), 0, 150)?> ...
								</div>
								<div class="button-exclusive">
									<a href="blog_ver.php?codigo=<?=$vet['codigo']?>">
										<span>Leia mais</span>
										<i class="fa fa-arrow-right"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<!-- single-blog-end -->
					<?
						}
					}
					?> 

					<?
			        $strP = "SELECT * FROM blog ORDER BY codigo DESC";
			        $rsP  = mysql_query($strP) or die(mysql_error());
			        $numP = mysql_num_rows($rsP);

			        if($numP)
			        {
			        ?>  					
					<div class="paginations">
						<ul>
							<?
		                    $max = $num_lista;
		                
		                    if (!$pagina)
		                        $pagina = 1;
		                     
		                    $inicio = $pagina - 1;
		                    $inicio = $max * $inicio;
		                    
		                    //calculando pagina anterior
		                    $menos = $pagina - 1;
		                    
		                    //calculando pagina posterior
		                    $mais = $pagina + 1;
		                    
		                    $pgs = ceil($numP / $max);   
            
		                    if($pgs > 1)
		                    {   
		                        if($menos > 0)
		                        {
		                            echo "<li><a href='blog.php?pagina=$menos'>&laquo;</a></li> ";
		                        }
		                        
		                        if (($pagina - 4) < 1)
		                            $anterior = 1;
		                        else
		                            $anterior = $pagina - 4;
		                             
		                        if (($pagina + 4) > $pgs)
		                            $posterior = $pgs;
		                        else
		                            $posterior = $pagina + 4;           
		                    
		                        for($i = $anterior; $i <= $posterior; $i++)
		                        {
		                            if($i != $pagina)
		                                echo "<li><a href='blog.php?pagina=$i'>$i</a></li> ";
		                            else
		                                echo "<li class='current'><a href='javascript:;'>$i</a></li> ";
		                        }
		                                
		                        if($mais <= $pgs)
		                            echo "<li><a href='blog.php?pagina=$mais'>&raquo;</a></li> ";
		                    }
            				?>
						</ul>
					</div>
					<?
					}
					?>					
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