<?
include("includes/header.php");

if($_POST['cmd'] == "rec"){
	$email = anti_injection($_POST['email']);

	$str = "SELECT * FROM cadastros WHERE email = '$email'";
	$rs  = mysql_query($str) or die(mysql_error());
	$num = mysql_num_rows($rs);
	


    if(!$num)
        redireciona("lembrar_senha.php?ind_msg=1");

	$vet = mysql_fetch_array($rs);
	
	$codigo = $vet['codigo'];
	$c = uniqid();

	$_SESSION['codigo'] = $codigo;
	$_SESSION['c'] = $c;
	
	$corpo = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>'.$n_empresa.'</title>
            <style type="text/css">
            td {font-family: Arial, Helvetica, sans-serif;font-size: 12px;color: #494949;}
            .bordasimples {border-collapse: collapse;}
            .bordasimples {border:1px solid #d0d0d0;}
            </style>
            </head>
            <body>
            <table width="651" border="0" align="left" cellpadding="0" cellspacing="0" class="bordasimples">
                <tr>
                    <td>
                        <table width="93%" border="0" align="left" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="30" valign="top">
                                    Olá, '.$vet['nome'].'<br><br>
                                    Acesse <a href="'.$_SERVER["HTTP_REFERER"].'?c='.$c.'">aqui</a> para alterar a senha</b>.<br>
                                    Boas compras!
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </body>
        </html>';

    $assunto = "Nova senha de acesso - ".$n_empresa;

    $headers = "MIME-Version: 1.1\n";
    $headers .= "Content-type: text/html; charset=utf-8\n";
    $headers .= "From: ".$n_empresa." <".$n_email.">\n";
    $headers .= "Return-Path: ".$n_email."\n";
    
    //Envia o email
	mail($vet['email'], $assunto, $corpo, $headers , "-r ".$n_email);
	echo '<script>alert("Email enviado");location.href = "index.php"</script>';
}

if($_POST['cmd'] == "nova")
{
	if($_SESSION['c'] != $_POST['c'] || $_POST['senha'] != $_POST['confirm_senha']) return;

	mysql_query('update cadastros set senha = "'.md5($_POST['senha']).'" where codigo = '.$_SESSION['codigo']);

	//var_dump($_SESSION['c'], $_SESSION['codigo']);

   redireciona("login.php?ind_msg=4");
}
?>
<script>
function valida()
{
	document.form.submit();
}
</script>

<!-- static-right-social-area end-->
<div class="container">
	<!-- page-heading start-->
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="page-heading">
				<h1>Esqueceu a senha?</h1>
			</div>
			
			<?
			if($_GET['ind_msg'] == 1)
			{
			?>
			<div class="page-heading">
				<p style="color: #DF0101">Email não cadastrado!</p>
			</div>
			<?
			}
			?>
		</div>
	</div>
</div>
<div class="login-register-form">
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-lg-6 col-md-6">
				<form name="form" id="form" method="post" >
					<div class="form-area">
						<h2 class="form-heading">Lembrar senha</h2>
						<div class="form-content">
							
							<?php if(!isset($_GET['c'])): ?>
							<input type="hidden" name="cmd" id="cmd" value="rec">
							<p>
								<label>Email <em>*</em></label>
								<input type="text" name="email" id="email" required>
							</p>

							<?php else: ?>
							<input type="hidden" name="cmd" id="cmd" value="nova">
							<input type="hidden" name="c" id="c" value="<?php echo $_GET['c'];?>">
							<p>
								<label>Senha <em>*</em></label>
								<input type="password" name="senha" id="senha" required>
							</p>
							<p>
								<label>Confirmar Senha <em>*</em></label>
								<input type="password" name="confirm_senha" id="confirm_senha" required>
							</p>
							<p>
								<a href="login.php">Voltar para a tela de login / cadastro</a>
							</p>
							<?php endif;?>

							<button type="button" onclick="javascript: return valida();">
								<span>
									<i class="fa fa-lock"></i>
									Enviar
								</span>
							</button>
						</div>
					</div>
				</form>
			</div>
			<?
			$strT = "SELECT * FROM testemunhos ORDER BY RAND() LIMIT 2";
		    $rsT  = mysql_query($strT) or die(mysql_error());
		    $numT = mysql_num_rows($rsT);

			if($numT > 0)
			{
			?>
			<div class="col-sm-6 col-lg-6 col-md-6">
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
			?>
		</div>
	</div>
</div>
<?
include("includes/footer.php");
?>