<?
include("includes/header.php");
?>
<!-- static-right-social-area end-->
<div class="container">
	<!-- page-heading start-->
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="page-heading">
				<h1>Faq</h1>
			</div>
		</div>
	</div>
	<!-- page-heading end-->
	<div class="row">
		<div class="col-sm-12 col-lg-12 col-md-12">
			<div class="our-company">
				<?
			    $str = "SELECT * FROM faq ORDER BY pergunta";
			    $rs  = mysql_query($str) or die(mysql_error());
			    $num = mysql_num_rows($rs);

				if($num > 0)
				{
				?>	
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<?
					$i = 0;
			        while($vet = mysql_fetch_array($rs))
			        {
			        	$class = 'class="panel-collapse collapse"';
			        	$class_link = '';

			        	if(!$i)
			        	{
			        		$class = 'class="panel-collapse collapse in"';
			        		$class_link = 'class="collapsed"';
			        	}
			        ?>
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading<?=$vet['codigo']?>">
							<h4 class="panel-title">
								<a <?=$class_link?> role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$vet['codigo']?>" aria-expanded="true" aria-controls="collapse<?=$vet['codigo']?>">
									<?=stripslashes($vet['pergunta'])?>
								</a>
							</h4>
						</div>
						<div id="collapse<?=$vet['codigo']?>" <?=$class?> role="tabpanel" aria-labelledby="heading<?=$vet['codigo']?>">
							<div class="panel-body">
								<?=stripslashes($vet['resposta'])?>
							</div>
						</div>
					</div>
					<?
						$i++;
					}
					?>					
				</div>
				<?
				}
				?>
			</div>
		</div>		
	</div>
</div>
<?
include("includes/footer.php");
?>