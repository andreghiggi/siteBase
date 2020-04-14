<?
include("includes/header.php");

if(!$_SESSION['user_verifica'])
{
	redireciona("login.php?ind_msg=2");
}

if($_POST['cmd'] == "edit")
{
	$nome = addslashes(anti_injection($_POST['nome']));
    $email = anti_injection($_POST['email']);
    $telefone = anti_injection($_POST['telefone']);
    $telefone_02 = anti_injection($_POST['telefone_02']);
    $data_nascimento = ConverteData(anti_injection($_POST['data_nascimento']));
    $senha = anti_injection($_POST['senha']);
    $senha_aux = md5($senha);
    
    $str = "SELECT * FROM cadastros WHERE email = '$email' AND codigo != '$c_codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

    if($num)
    {
        redireciona("meus_dados.php?ind_msg=1");
    }

    $strSet = "";
    if($senha)
    	$strSet = ", senha = '$senha_aux'";

    $str = "UPDATE cadastros SET nome = '$nome', email = '$email', telefone = '$telefone', telefone_02 = '$telefone_02',
    	data_nascimento = '$data_nascimento', nome = '$nome' $strSet
    	WHERE codigo = '$c_codigo'";
    $rs  = mysql_query($str) or die(mysql_error());

    redireciona("meus_dados.php?ind_msg=2");
}

if($_POST['cmd'] == "address")
{
	$endereco = addslashes(anti_injection($_POST['endereco']));
    $numero = anti_injection($_POST['numero']);
    $complemento = addslashes(anti_injection($_POST['complemento']));
    $referencia = addslashes(anti_injection($_POST['referencia']));
    $bairro = addslashes(anti_injection($_POST['bairro']));
    $cidade = addslashes(anti_injection($_POST['cidade']));
    $estado = anti_injection($_POST['estado']);
    $cep = anti_injection($_POST['cep']);
    
    $str = "SELECT * FROM cadastros_enderecos WHERE idcadastro = '$c_codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

    if(!$num)
    {
    	$str = "INSERT INTO cadastros_enderecos (idcadastro, endereco, numero, complemento, referencia, bairro, cidade, estado, cep)
    		VALUES ('$c_codigo', '$endereco', '$numero', '$complemento', '$referencia', '$bairro', '$cidade', '$estado', '$cep')";
    	$rs  = mysql_query($str) or die(mysql_error());
    }
    else
    {
    	$str = "UPDATE cadastros_enderecos SET endereco = '$endereco', numero = '$numero', complemento = '$complemento', referencia = '$referencia',
    		bairro = '$bairro', cidade = '$cidade', estado = '$estado', cep = '$cep'
	    	WHERE idcadastro = '$c_codigo'";
	    $rs  = mysql_query($str) or die(mysql_error());
    }

    redireciona("meus_dados.php?ind_msg=3");
}
?>
<script>
function valida_c()
{
	if(document.form_c.nome.value == '')
	{
		alert("Informe seu nome");
		document.form_c.nome.focus();
		return false;
	}

	if(document.form_c.email.value == '')
	{
		alert("Informe seu email");
		document.form_c.email.focus();
		return false;
	}

	if(document.form_c.telefone.value == '')
	{
		alert("Informe seu telefone");
		document.form_c.telefone.focus();
		return false;
	}

	if(document.form_c.data_nascimento.value == '')
	{
		alert("Informe a data do seu aniversário");
		document.form_c.data_nascimento.focus();
		return false;
	}

	if(document.form_c.senha.value != document.form_c.confirm_senha.value)
	{
		alert("As senhas informadas não conferem, favor confirmar");
		document.form_c.senha.focus();
		return false;
	}

	document.form_c.submit();
}

function valida_e()
{
	if(document.form_e.endereco.value == '')
	{
		alert("Informe seu endereço");
		document.form_e.endereco.focus();
		return false;
	}

	if(document.form_e.numero.value == '')
	{
		alert("Informe o número");
		document.form_e.numero.focus();
		return false;
	}

	if(document.form_e.bairro.value == '')
	{
		alert("Informe o bairro");
		document.form_e.bairro.focus();
		return false;
	}

	if(document.form_e.cidade.value == '')
	{
		alert("Informe a cidade");
		document.form_e.cidade.focus();
		return false;
	}

	if(document.form_e.estado.value == '')
	{
		alert("Informe o estado");
		document.form_e.estado.focus();
		return false;
	}

	if(document.form_e.cep.value == '')
	{
		alert("Informe o cep");
		document.form_e.cep.focus();
		return false;
	}

	document.form_e.submit();
}
</script>

<!-- static-right-social-area end-->
<div class="container">
	<!-- page-heading start-->
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="page-heading">
				<h1>Meus dados</h1>
			</div>
			<?
			if($_GET['ind_msg'] == 1)
			{
			?>
			<div class="page-heading">
				<p style="color: #DF0101">Email já cadastrado por outro usuário, favor informar outro.</p>
			</div>
			<?
			}
			?>

			<?
			if($_GET['ind_msg'] == 2)
			{
			?>
			<div class="page-heading">
				<p style="color: #04B404">Dados pessoais editados com sucesso!</p>
			</div>
			<?
			}
			?>

			<?
			if($_GET['ind_msg'] == 3)
			{
			?>
			<div class="page-heading">
				<p style="color: #04B404">Endereço de entrega cadastrado / editado com sucesso.</p>
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
				<form name="form_c" id="form_c" method="post">
					<input type="hidden" name="cmd" id="cmd" value="edit">					
					<div class="form-area">
						<h2 class="form-heading">Dados pessoais</h2>
						<div class="form-content">
							<p>Informe abaixo seus dados pessoais.</p>
							<p>
								<label>Nome <em>*</em></label>
								<input type="text" name="nome" id="nome" value="<?=$c_nome?>" >
							</p>
							<p>
								<label>Email <em>*</em></label>
								<input type="text" name="email" id="email" value="<?=$c_email?>" >
							</p>
							<p>
								<label>Telefone <em>*</em></label>
								<input type="text" name="telefone" id="telefone" value="<?=$c_telefone?>" >
							</p>
							<p>
								<label>Telefone (adicional) </label>
								<input type="text" name="telefone_02" id="telefone_02" value="<?=$c_telefone_02?>" >
							</p>
							<p>
								<label>Aniversário <em>*</em></label>
								<input type="text" name="data_nascimento" id="data_nascimento" placeholder="Ex: dd/mm/YYYY" value="<?=($c_data_nascimento) ? ConverteData($c_data_nascimento) : ''?>" maxlength="10" onKeyUp="javascript: auto_data('data_nascimento');" onKeyPress="javascript: return somenteNumeros(event);" >
							</p>
							<p>
								<label>Senha </label>
								<input type="password" name="senha" id="senha" >
							</p>
							<p>
								<label>Confirmar Senha </label>
								<input type="password" name="confirm_senha" id="confirm_senha" >
							</p>
							<button type="submit" class="login" onclick="javascript: return valida_c();">
								<span>
									<i class="fa fa-user"></i>
									Editar dados pessoais
								</span>
							</button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-sm-6 col-lg-6 col-md-6">
				<div class="form-area">
					<form name="form_e" id="form_e" method="post">
						<input type="hidden" name="cmd" id="cmd" value="address">
						<h2 class="form-heading">Endereço de entrega</h2>
						<div class="form-content">
							<p>Informe abaixo os dados do seu endereço de entrega.</p>
							<p>
								<label>CEP <em>*</em></label>
								<input type="text" name="cep" id="cep" value="<?=$c_cep?>" placeholder="Ex: xxxxxxxx"  maxlength="8" onKeyPress="javascript: return somenteNumeros(event);">
							</p>
							<p>
								<label>Endereço <em>*</em></label>
								<input type="text" name="endereco" id="endereco" value="<?=$c_endereco?>" >
							</p>
							<p>
								<label>Número <em>*</em></label>
								<input type="text" name="numero" id="numero" value="<?=$c_numero?>"  onKeyPress="javascript: return somenteNumeros(event);">
							</p>
							<p>
								<label>Complemento</label>
								<input type="text" name="complemento" id="complemento" value="<?=$c_complemento?>">
							</p>
							<p>
								<label>Ponto de referência</label>
								<input type="text" name="referencia" id="referencia" value="<?=$c_referencia?>">
							</p>
							<p>
								<label>Bairro <em>*</em></label>
								<input type="text" name="bairro" id="bairro" value="<?=$c_bairro?>" >
							</p>
							<p>
								<label>Cidade <em>*</em></label>
								<input type="text" name="cidade" id="cidade" value="<?=$c_cidade?>" >
							</p>
							<p>
								<label>Estado <em>*</em></label>
								<input type="text" name="estado" id="estado" value="<?=$c_estado?>" style="width:10%" maxlength="2" placeholder="ES">
							</p>							
							<button type="submit" class="login" onclick="javascript: return valida_e();">
								<span>
									<i class="fa fa-user"></i>
									<?=(!$c_idendereco) ? 'Cadastrar endereço' : 'Editar endereço'?>
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