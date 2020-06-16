<?
include("includes/header.php");

if($_POST['cmd'] == "add")
{
	$nome = addslashes(anti_injection($_POST['nome']));
    $email = anti_injection($_POST['email']);
    $senha = anti_injection($_POST['senha']);
    $senha_aux = md5($senha);
    $cpf = anti_injection($_POST['cpf']);
	$telefone = anti_injection($_POST['telefone']);
	$sobrenome = anti_injection($_POST['sobrenome']);
    
    $str = "SELECT * FROM cadastros WHERE email = '$email'";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

    if($num)
    {
    	//msg("Erro: O email informado já foi cadastrado por outro usuário!");
        redireciona("login.php?ind_msg=1");
    }

    $str = "INSERT INTO cadastros (nome, sobrenome, email, senha, data_cadastro, cpf, telefone) VALUES ('$nome', '$sobrenome', '$email', '$senha_aux', NOW(), '$cpf','$telefone')";
    $rs  = mysql_query($str) or die(mysql_error());
    $codigo = mysql_insert_id();

    ini_set ('mail_filter', '0');

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
                                    Olá, '.$_POST['nome'].'<br><br>
                                    Obrigado por se cadastrar em nosso site, sua senha de acesso é <b>"'.$_POST['senha'].'"</b>.<br>
                                    Boas compras!
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </body>
        </html>';

    $assunto = "Seja bem vindo - ".$n_empresa;

    $headers = "MIME-Version: 1.1\n";
    $headers .= "Content-type: text/html; charset=utf-8\n";
    $headers .= "From: ".$n_empresa." <".$n_email.">\n";
    $headers .= "Return-Path: ".$n_email."\n";
    
    //Envia o email
    mail($email, $assunto, $corpo, $headers ,"-r ".$n_email);

    $_SESSION["user_verifica"] = "st_ecommerce";
    $_SESSION["user_codigo"] = $codigo;

    if($_SESSION["idcarrinho"])
	{
		echo "<script>location.href='carrinho.php'</script>";
	}

    redireciona("index.php");
}

if($_POST['cmd'] == "login")
{
    $email = anti_injection($_POST['email']);
    $senha = anti_injection($_POST['senha']);
    
    $senha_aux = md5($senha);
    
    $str = "SELECT * FROM cadastros WHERE email = '$email' AND senha = '$senha_aux'";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);
	$vet = mysql_fetch_array($rs);

    
    if ($num > 0) 
    {
        $_SESSION["user_verifica"] = "st_ecommerce";
    	$_SESSION["user_codigo"] = $vet["codigo"];

    	if($_SESSION["idcarrinho"])
	    {
	    	echo "<script>location.href='carrinho.php'</script>";
	    }
        
		redireciona("index.php");
    } 
    
	redireciona("login.php?ind_msg=3");
}
?>
<script>
function valida_l()
{
	if(document.form_l.email.value == '')
	{
		alert("Informe seu email");
		document.form_l.email.focus();
		return false;
	}

	if(document.form_l.senha.value == '')
	{
		alert("Informe sua senha");
		document.form_l.senha.focus();
		return false;
	}

	document.form_l.submit();
}

function valida_c()
{
	if(document.form_c.nome.value == '')
	{
		alert("Informe seu nome");
		document.form_c.nome.focus();
		return false;
	}

	if(document.form_c.sobrenome.value == '')
	{
		alert("Informe seu sobrenome");
		document.form_c.sobrenome.focus();
		return false;
	}
	
	if(document.form_c.cpf.value == '')
	{
		alert("Informe o CPF");
		document.form_c.cpf.focus();
		return false;
	}
	
	if(document.form_c.telefone.value == '')
	{
		alert("Informe o telefone");
		document.form_c.telefone.focus();
		return false;
	}

	if(document.form_c.email.value == '')
	{
		alert("Informe seu email");
		document.form_c.email.focus();
		return false;
	}

	if(document.form_c.senha.value == '')
	{
		alert("Informe a senha");
		document.form_c.senha.focus();
		return false;
	}
	

	document.form_c.submit();
}
</script>

<!-- static-right-social-area end-->
<div class="container">
	<!-- page-heading start-->
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="page-heading">
				<h1>Entre / Cadastre-se</h1>
			</div>
			<?
			if($_GET['ind_msg'] == 1)
			{
			?>
			<div class="page-heading">
				<p style="color: #DF0101">O email informado já foi cadastrado por outro usuário!</p>
			</div>
			<?
			}
			?>

			<?
			if($_GET['ind_msg'] == 2)
			{
			?>
			<div class="page-heading">
				<p style="color: #DF0101">Efetue seu login para poder fazer suas compras.</p>
			</div>
			<?
			}
			?>

			<?
			if($_GET['ind_msg'] == 3)
			{
			?>
			<div class="page-heading">
				<p style="color: #DF0101">Erro: O email e / ou senha informados não estão cadastrados em nosso site!</p>
			</div>
			<?
			}
			?>

			<?
			if($_GET['ind_msg'] == 4)
			{
			?>
			<div class="page-heading">
				<p style="color: #04B404">Senha alterada com sucesso, ela também foi enviada para o seu email de cadastro.</p>
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
				<form name="form_l" id="form_l" method="post">
					<input type="hidden" name="cmd" id="cmd" value="login">
					<div class="form-area">
						<h2 class="form-heading">Entre</h2>
						<div class="form-content">
							<p>Informe login e senha para acessar a ára do cliente no menu superior do site.</p>
							<p>
								<label>Email <em>*</em></label>
								<input type="text" name="email" id="email" >
							</p>
							<p>
								<label>Senha <em>*</em></label>
								<input type="password" name="senha" id="senha" >
							</p>
							<p>
								<a href="lembrar_senha.php">Esqueceu a senha?</a>
							</p>
							<button type="submit" onclick="javascript: return valida_l();">
								<span>
									<i class="fa fa-lock"></i>
									Entrar
								</span>
							</button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-sm-6 col-lg-6 col-md-6">
				<div class="form-area">
					<form name="form_c" id="form_c" method="post">
						<input type="hidden" name="cmd" id="cmd" value="add">
						<h2 class="form-heading">Cadastre-se</h2>
						<div class="form-content">
							<p>Informe os dados básicos de cadastro para iniciar suas compras.</p>
							<p>
								<label>Nome <em>*</em></label>
								<input type="text" name="nome" id="nome" >
							</p>
							<p>
								<label>Sobrenome <em>*</em></label>
								<input type="text" name="sobrenome" id="sobrenome" >
							</p>
							<p>
								<label>CPF <em>*</em></label>
								<input type="text" name="cpf" id="cpf" >
							</p>
							<p>
								<label>Telefone <em>*</em></label>
								<input type="text" name="telefone" id="telefone">
							</p>
							<p>
								<label>Email <em>*</em></label>
								<input type="text" name="email" id="email" >
							</p>
							<p>
								<label>Senha <em>*</em></label>
								<input type="password" name="senha" id="senha" >
							</p>
							<button id="botaoEnviar" type="submit" class="login" onclick="javascript: return valida_c();">
								<span>
									<i class="fa fa-user"></i>
									Cadastrar
								</span>
							</button>
						</div>
					</form>
				</div>
			</div>			
		</div>
	</div>
</div>
<?
include("includes/footer.php");
?>
