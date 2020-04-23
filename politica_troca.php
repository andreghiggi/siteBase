<?include("includes/header.php");?>
	
	<!-- static-right-social-area end-->
    <section class="contuct-us-form-area">
    	<div class="container">
    		<div class="row">
    			<div class="col-lg-12 col-md-12">
    				<div class="area-title">
						<h2>Política de troca</h2>
					</div>
					<div class="col-sm">
						<?php
							ini_set('display_errors',1);
							ini_set('display_startup_erros',1);
							error_reporting(E_ALL);
						
							$rs  = mysql_query('select * from politica_troca');
							$vet = mysql_fetch_array($rs);
							
							echo $vet['texto'];
						?>
					</div>
    			</div>
    		</div>
    	</div>
    </section>
<?
include("includes/footer.php");
?>
