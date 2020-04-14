<?
include("includes/header.php");
?>
	<!-- static-right-social-area end-->
	<div class="container">
		<!-- page-heading start-->
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="page-heading">
					<h1>Vídeo</h1>
				</div>
			</div>
		</div>
		<!-- page-heading end-->
		<div class="row">
			<?
			$str = "SELECT * FROM video ";
			$rs  = mysql_query($str) or die(mysql_error());
			$vet = mysql_fetch_array($rs);

			$strT = "SELECT * FROM testemunhos ORDER BY RAND() LIMIT 2";
		    $rsT  = mysql_query($strT) or die(mysql_error());
		    $numT = mysql_num_rows($rsT);

			if($numT > 0)
			{
			?>
			<div class="col-sm-8 col-lg-8 col-md-8">
				<div class="our-company">
					<iframe src="<?=$vet['url']?>" width="100%" height="400" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				</div>
			</div>
			<div class="col-sm-4 col-lg-4 col-md-4">
				<h2 class="sub-section-title">
					Testemunhos
				</h2>
				<div class="testimonials-area">
					<?
					while($vetT = mysql_fetch_array($rsT))
					{
					?>
					<div class="single-testimonial">
						<span class="before">“</span>
						<?=stripslashes($vetT['mensagem'])?>
						<span class="after">”</span>
					</div>
					<h3 class="dark"><?=stripslashes($vetT['nome'])?></h3>
					<?
					}
					?>					
				</div>
			</div>
			<?
			}
			else
			{
			?>
			<div class="col-sm-12 col-lg-12 col-md-12">
				<div class="our-company">
					<iframe src="<?=$vet['url']?>" width="100%" height="400" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				</div>
			</div>
			<?
			}
			?>
		</div>
	</div>
	<br>
<?
include("includes/footer.php");
?>