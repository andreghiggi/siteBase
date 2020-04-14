<?
include("includes/header.php");

if($_POST['cmd'] == "send")
{
    $tipo = $_POST['tipo'];
    $numero = $_POST['numero'];
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
                            <tr><td height="30" valign="top">Nome: '.$c_nome.'</td></tr>
                            <tr><td height="30" valign="top">Email: '.$c_email.'</td></tr>
                            <tr><td height="30" valign="top">Telefone: '.$c_telefone.'</td></tr>
                            <tr><td height="30" valign="top">Tipo: '.$tipo.'</td></tr>
                            <tr><td height="30" valign="top">Número do pedido: '.$numero.'</td></tr>
                            <tr><td height="30" valign="top">Mensagem: '.nl2br($mensagem).'</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
            </body>
        </html>';

    $assunto = "Política de troca | ".$n_empresa;
    
    $headers = "MIME-Version: 1.1\n";
    $headers .= "Content-type: text/html; charset=utf-8\n";
    $headers .= "From: ".$n_empresa." <".$n_email.">\n";
    $headers .= "Return-Path: ".$n_email."\n";
    
     //Envia o email
    mail($n_email, $assunto, $corpo, $headers ,"-r ".$n_email);
    
    msg("Email enviado com sucesso");
    redireciona("politica_troca.php");
}
?>
	<!-- static-right-social-area end-->
    <section class="contuct-us-form-area">
    	<div class="container">
    		<div class="row">
    			<div class="col-lg-12 col-md-12">
    				<div class="area-title">
						<h2>Política de troca</h2>
					</div>
					<div class="fieldset col-sm-7">
						<h2 class="legend">Política de troca</h2>
						<form name="form" id="form" method="post">
                        <input type="hidden" name="cmd" value="send">
    						<div class="form-content">
    							<span>Nos envie um email com o que deseja trocar ou devolver.<br>Informe abaixo também o número do pedido e mensagem.<br>Todos os campos com asterísco (*) são obrigatórios.</span>
    							<div class="form-element-top">
    								<label>Tipo <em>*</em></label>
    								&nbsp;
									<select name="tipo" id="tipo" required>
										<option value="">Selecione ...</option>
										<option value="Devolução">Devolução</option>
										<option value="Troca">Troca</option>
									</select>
    							</div>
    							<div class="form-element">
    								<label>Número do pedido <em>*</em></label>
    								<input type="text" name="numero" id="numero" required>
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