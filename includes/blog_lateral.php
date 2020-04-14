<div class="col-sm-3 col-lg-3 col-md-3">
	<div class="left-sidebar right">
		<!-- search-area start-->
		<div class="sidebar-search">
			<div class="area-title">
				<h2>Buscar</h2>
			</div>
			<form name="form_b" id="form_b" method="post" action="blog.php">
				<div class="form-input">
					<input type="text" name="chave" name="chave" value="<?=$chave?>" onblur="if (this.value == '') {this.value = 'Buscar ...';}" onfocus="if (this.value == 'Buscar ...') {this.value = '';}">
					<button type="submit">
						<i class="fa fa-search"></i>
					</button>
				</div>
			</form>
		</div>
		<!-- search-area end-->

		<?
	    $str = "SELECT B.*, COUNT(A.codigo) AS qtde_posts
	    	FROM blog A
	    	INNER JOIN blog_categorias B ON A.idcategoria = B.codigo
	    	GROUP BY B.codigo
	    	ORDER BY B.nome DESC";
	    $rs  = mysql_query($str) or die(mysql_error());
	    $num = mysql_num_rows($rs);
	      
	    if($num)
	    {
	    ?>
		<!-- categories start-->
		<div class="categories">
			<div class="area-title">
				<h2>Categorias</h2>
			</div>
			<ul>
				<?
		        while($vet = mysql_fetch_array($rs))
		        {
		        ?>
				<li><a href="blog.php?idcategoria=<?=$vet['codigo']?>"><?=stripslashes($vet['nome'])?> (<?=$vet['qtde_posts']?>)</a></li>
				<?
				}
				?>
			</ul>
		</div>
		<!-- categories end-->
		<?
		}
		?>
		
		<?
		$str = "SELECT A.*, DATE_FORMAT(A.data, '%d/%m/%Y %H:%i') AS dt_blog, B.nome AS categoria, C.nome AS usuario
		    FROM blog A
		    INNER JOIN blog_categorias B ON A.idcategoria = B.codigo
		    INNER JOIN usuarios C ON A.idusuario = C.codigo
		    ORDER BY A.data DESC LIMIT 5";
		$rs  = mysql_query($str) or die(mysql_error());
		$num = mysql_num_rows($rs);

		if($num)
		{
		?>
		<!-- recent-post-area start-->
		<div class="recent-post-area">
			<div class="area-title">
				<h2>Últimas do blog</h2>
			</div>
			<div class="recent-post">
				<?
				while($vet = mysql_fetch_array($rs))
				{
				?>
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
							 <?=str_replace(" ", " às ", $vet['dt_blog'])?>
						</span>
						<span class="post-author">
							Escrito por  <b><?=$vet['usuario']?></b>
						</span>
					</div>
				</div>
				<?
				}
				?>
			</div>
		</div>
		<!-- recent-post-area end-->
		<?
		}
		?>

		<?
		$str = "SELECT MONTH(A.data) AS mes, YEAR(A.data) AS ano, COUNT(A.codigo) AS qtde_posts
			FROM blog A
			INNER JOIN blog_categorias B ON A.idcategoria = B.codigo
			GROUP BY MONTH(A.data), YEAR(A.data)
			ORDER BY MONTH(A.data), YEAR(A.data) DESC";
		$rs  = mysql_query($str) or die(mysql_error());
	    $num = mysql_num_rows($rs);

		if($num)
		{
		?>
		<!-- archives-area start-->
		<div class="archives-area" style="margin: 30px 0;">
			<div class="area-title">
				<h2>Arquivos do blog</h2>
			</div>
			<ul>
				<?
		        while($vet = mysql_fetch_array($rs))
		        {
		        ?>
				<li><a href="blog.php?mesb=<?=$vet['mes']?>&anob=<?=$vet['ano']?>"><?=mes_extenso($vet['mes'])?> <?=$vet['ano']?> (<?=$vet['qtde_posts']?>)</a></li>
				<?
				}
				?>
			</ul>
		</div>
		<!-- archives-area end-->
		<?
		}
		?>
	</div>
</div>