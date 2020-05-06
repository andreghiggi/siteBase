<?include("includes/header.php");?>
	
	<!-- static-right-social-area end-->
    <section class="contuct-us-form-area">
    	<div class="container">
    		
    		<div class="row">
					<div class="col-sm-12 col-lg-12 col-md-12">
						<div class="area-title">
						<h2>FAQ</h2>
						</div>
						<br>
						<div class="">
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
									
									<div onclick="mostrarFaq(this)">
										
										<div class="panel-heading" role="tab" id="heading<?=$vet['codigo']?>">
											<h4 class="panel-title">
												<a>
													<?=stripslashes($vet['pergunta'])?>
												</a>
											</h4>
										</div>
										
										<div id="collapse<?=$vet['codigo']?>" class="hidden">
											<div class="panel-body">
												<?=stripslashes($vet['resposta'])?>
											</div>
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
				
				<script>
					function mostrarFaq(self){
						let filho = $(self).children()[1];
						if($(filho).hasClass('hidden')){
							$(filho).removeClass('hidden');
						}
						else{
							$(filho).addClass('hidden');
						}
					}
				</script>
    		
    	</div>
    </section>
<?
include("includes/footer.php");
?>
