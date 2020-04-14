    <!-- footer-area-start -->
     <footer>
    	<section class="footer-area">
    		<div class="container">
    			<div class="row">
    				<div class="footer">
	    				<div class="col-sm-3 col-lg-3 col-md-3">
	    					<?
	    					$strT = "SELECT * FROM testemunhos ORDER BY RAND() LIMIT 1";
						    $rsT  = mysql_query($strT) or die(mysql_error());
						    $numT = mysql_num_rows($rsT);
						    $vetT = mysql_fetch_array($rsT);

							if($numT > 0)
							{
	    					?>
	    					<div class="static-book">
	    						<div class="footer-title">
	    							<h2>Testemunhos</h2>
	    						</div>
	    						<div class="footer-content">
	    							“<?=stripslashes($vetT['mensagem'])?>”
	    							<span class="author">- <?=stripslashes($vetT['nome'])?> -</span>
	    						</div>
	    					</div>
	    					<?
	    					}
	    					?>
	    				</div>
	    				<div class="col-sm-3 col-lg-2 col-md-2">
	    					<div class="my-account">
	    						<div class="footer-title">
	    							<h2>Área do cliente</h2>
	    						</div>
	    						<div class="footer-menu">
	    							<ul>
	    								<li><a href="carrinho.php">Carinho de compras</a></li>
	    								<li><a href="meus_dados.php">Meus dados</a></li>
	    								<li><a href="meus_pedidos.php">Meus pedidos</a></li>
	    							</ul>
	    						</div>
	    					</div>
	    				</div>
	    				<div class="col-lg-2 col-md-2 hidden-sm">
	    					<div class="information-area">
	    						<div class="footer-title">
	    							<h2>Sobre a loja</h2>
	    						</div>
	    						<div class="footer-menu">
	    							<ul>
	    								<li><a href="quem_somos.php">Quem somos</a></li>
										<li><a href="termos_uso.php">Termos de uso</a></li>
										<li><a href="como_comprar.php">Como comprar</a></li>
	    								<li><a href="faq.php">FAQ</a></li>
	    								<li><a href="contato.php">Contato</a></li>
	    								<li><a href="login.php">Cadastre-se</a></li>
	    							</ul>
	    						</div>
	    					</div>
	    				</div>
	    				<div class="col-sm-3 col-lg-2 col-md-2">
	    					<div class="footer-menu-area">
	    						<div class="footer-title">
	    							<h2>Loja</h2>
	    						</div>
	    						<div class="footer-menu">
	    							<ul>
										<li><a href="loja.php?ind=1">Novos produtos</a></li>
										<li><a href="loja.php?ind=2">Mais populares</a></li>										
										<li><a href="loja.php?ind=4">Mais vendidos</a></li>
										<li><a href="loja.php?ind=3">Vendidos recentemente</a></li>
										<!--li><a href="loja.php?ind=5">Destaques da semana</a></li-->
    								</ul>
	    						</div>
	    					</div>
	    				</div>
	    				<div class="col-sm-3 col-lg-3 col-md-3">
	    					<div class="store-information-area">
	    						<div class="footer-title">
	    							<h2>Informações da loja</h2>
	    						</div>
	    						<div class="store-content">
	    							<ul>
	    								<li><?=$n_endereco?></li>
	    								<li>Telefone de contato: <span><?=$n_telefone?></span></li>
	    								<li>Email:<a href="<?=$n_email?>"><?=$n_email?></a></li>
	    							</ul>
	    						</div>
	    						<div class="footer-payment">
	    							<img alt="" src="img/payment.png">
	    						</div>
	    					</div>
	    				</div>
	    			</div>
    			</div>
    		</div>
    	</section>
    	<div class="copyright-area">
    		<div class="container">
    			<div class="row">
    				<div class="col-lg-12 col-md-12">
    					<div class="copyright">
    						<p>Copyright &copy; 2016 <a href="http://www.i9tecinfo.com.br/">I9 informática e sistemas</a>. Todos os direitos reservados.</p>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </footer>
    <!-- footer-area-end -->

        <!-- JS -->
        
 		<!-- jquery-1.11.3.min js
		============================================ -->         
        <script src="js/vendor/jquery-1.11.3.min.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-72519931-1', 'auto');
  ga('send', 'pageview');

</script>
[15:10, 8/8/2016] Sabrina Dallagnase: <?php include_once("analyticstracking.php") ?>
        
 		<!-- bootstrap js
		============================================ -->         
        <script src="js/bootstrap.min.js"></script>
        
   		<!-- owl.carousel.min js
		============================================ -->       
		<script src="js/owl.carousel.min.js"></script>
		
   		<!-- jquery.meanmenu js
		============================================ -->       
		<script src="js/jquery.meanmenu.js"></script>

   		<!-- jquery-ui.min js
		============================================ -->       
		<script src="js/jquery-ui.min.js"></script>

		<!-- fancybox js -->
		<script src="js/jquery.fancybox.pack.js"></script>	

   		<!-- jquery.scrollUp.min js
		============================================ -->       
		<script src="js/jquery.scrollUp.min.js"></script>
		
   		<!-- wow js
		============================================ -->       
        <script src="js/wow.js"></script>
		<script>
			new WOW().init();
		</script>
		
		<!-- Nivo slider js
		============================================ --> 		
		<script src="custom-slider/js/jquery.nivo.slider.js" type="text/javascript"></script>
		<script src="custom-slider/home.js" type="text/javascript"></script>
        
   		<!-- Google Map js -->
        <script src="https://maps.googleapis.com/maps/api/js"></script>

   		<!-- plugins js
		============================================ -->         
        <script src="js/plugins.js"></script>
        
   		<!-- main js
		============================================ -->           
        <script src="js/main.js"></script>

        <!-- scripts
		============================================ -->          
        <script src="js/scripts.js"></script>
        <script src="js/ajax_funcoes.js"></script>

        <!-- Adicionando Javascript -->
    	<script type="text/javascript" >
	        $(document).ready(function() {

	            function limpa_formulario_cep() {
	                // Limpa valores do formulário de cep.
	                $("#endereco").val("");
	                $("#bairro").val("");
	                $("#cidade").val("");
	                $("#estado").val("");
	            }
	            
	            //Quando o campo cep perde o foco.
	            $("#cep").blur(function() {

	                //Nova variável "cep" somente com dígitos.
	                var cep = $(this).val().replace(/\D/g, '');

	                //Verifica se campo cep possui valor informado.
	                if (cep != "") {

	                    //Expressão regular para validar o CEP.
	                    var validacep = /^[0-9]{8}$/;

	                    //Valida o formato do CEP.
	                    if(validacep.test(cep)) {

	                        //Preenche os campos com "..." enquanto consulta webservice.
	                        $("#endereco").val("")
	                        $("#bairro").val("")
	                        $("#cidade").val("")
	                        $("#estado").val("")

	                        //Consulta o webservice viacep.com.br/
	                        $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

	                            if (!("erro" in dados)) {
	                                //Atualiza os campos com os valores da consulta.
	                                $("#endereco").val(dados.logradouro);
	                                $("#bairro").val(dados.bairro);
	                                $("#cidade").val(dados.localidade);
	                                $("#estado").val(dados.uf);
	                            } //end if.
	                            else {
	                                //CEP pesquisado não foi encontrado.
	                                limpa_formulario_cep();
	                                alert("CEP não encontrado.");
	                            }
	                        });
	                    } //end if.
	                    else {
	                        //cep é inválido.
	                        limpa_formulario_cep();
	                        alert("Formato de CEP inválido.");
	                    }
	                } //end if.
	                else {
	                    //cep sem valor, limpa formulário.
	                    limpa_formulario_cep();
	                }
	            });
	        });
    	</script>
    </body>
</html>
