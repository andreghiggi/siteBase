<?
include("includes/header.php");

if($_POST['cmd'] == "send")
{
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $assunto = $_POST['assunto'];
    $mensagem = $_POST['mensagem'];   
    
    ini_set ('mail_filter', '0');
    
    $corpo = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>'.$n_empresa.'</title>
            <style type="text/css">
            td {font-family: Arial, Helvetica, sans-serif;font-size: 12px;color: #494949; padding:5px;}
            .bordasimples {border-collapse: collapse;}
            .bordasimples {border:1px solid #d0d0d0;}
            </style>
            </head>
            <body>
            <table width="651" border="0" align="left" cellpadding="0" cellspacing="0" class="bordasimples">
                <tr>
                    <td>
                        <table width="93%" border="0" align="left" cellpadding="0" cellspacing="0">
                            <tr><td height="30" valign="top">Nome: '.$nome.'</td></tr>
                            <tr><td height="30" valign="top">Email: '.$email.'</td></tr>
                            <tr><td height="30" valign="top">Mensagem: '.nl2br($mensagem).'</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
            </body>
        </html>';

    $assunto = $assunto." | ".$n_empresa;
    
    $headers = "MIME-Version: 1.1\n";
    $headers .= "Content-type: text/html; charset=utf-8\n";
    $headers .= "From: ".$n_empresa." <".$n_email.">\n";
    $headers .= "Return-Path: ".$n_email."\n";
    
     //Envia o email
    mail($n_email, $assunto, $corpo, $headers ,"-r ".$n_email);
    
    msg("Contato enviado com sucesso");
    redireciona("contato.php");
}
?>
	<!-- static-right-social-area end-->
    <section class="contuct-us-form-area">
    	<div class="container">
    		<div class="row">
    			<div class="col-lg-12 col-md-12">
    				<div class="area-title">
						<h2>Contato</h2>
					</div>
					<div class="fieldset col-sm-7">
						<h2 class="legend">Contato</h2>
						<form name="form" id="form" method="post">
                        <input type="hidden" name="cmd" value="send">
    						<div class="form-content">
    							<span>Nos envie um email com sugestões, comentários, etc.<br>Todos os campos com asterísco (*) são obrigatórios.</span>
    							<div class="form-element-top">
    								<div class="form-element">
    									<label>Nome <em>*</em></label>
    									<input type="text" name="nome" id="nome" required>
    								</div>
    								<div class="form-element">
    									<label>Email <em>*</em></label>
    									<input type="text" name="email" id="email" required>
    								</div>
    							</div>
    							<div class="form-element">
    								<label>Assunto <em>*</em></label>
    								<input type="text" name="assunto" id="assunto" required>
    							</div>
    							<div class="form-element text-area">
    								<label>Mensagem <em>*</em></label>
    								<textarea name="mensagem" id="mensagem" required></textarea>
    							</div>
    							<div class="input-element">
    								<p>
    									<input type="submit" value="Enviar">
    								</p>
    							</div>
    						</div>
						</form>

                        <?
                        if($n_url_maps)
                        {
                        ?>
						<!-- map-area start-->
						<div class="map-area col-sm-5">                            
							<div id="googleMap" style="width:100%;height:460px;">
                                <iframe src="<?=$n_url_maps?>" width="100%" height="460" frameborder="0" style="border:0" allowfullscreen></iframe>
                            </div>
						</div>
						<!-- map-area end-->
                        <?
                        }
                        ?>
					</div>
    			</div>
    		</div>
    	</div>
    </section>
<?
include("includes/footer.php");
?>