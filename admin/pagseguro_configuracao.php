<?php 
include('topo.inc.php'); 

$email = $_POST['email'];
$token = $_POST['token'];
$urlnotificacao = $_POST['urlnotificacao'];
$status = $_POST['status'];
$sandbox = $_POST['sandbox'];

if($_POST['cmd'] == "add")
{
	$str = "INSERT INTO pagseguro_configuracao (email, token, urlnotificacao,status,sandbox)
		VALUES ('$email', '$token', '$urlnotificacao', '$status', '$sandbox')";
	$rs  = mysql_query($str) or die(mysql_error());

	redireciona("pagseguro_configuracao.php?ind_msg=1");
}

if($_POST['cmd'] == "edit")
{
	$str = "UPDATE pagseguro_configuracao SET email = '$email', token = '$token', urlnotificacao = '$urlnotificacao', status='$status', sandbox = '$sandbox'";
	$rs  = mysql_query($str) or die(mysql_error());
	
	redireciona("pagseguro_configuracao.php?ind_msg=2");
}

if($_GET['cmd'] == "clean")
{
    $str = "TRUNCATE TABLE pagseguro_configuracao";
    $rs  = mysql_query($str) or die(mysql_error());
    
    redireciona("pagseguro_configuracao.php?ind_msg=3");
}

if($_GET['ind_msg'] == 1)
	$msg = '<div class="alert success">Dados cadastrados com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
	$msg = '<div class="alert success">Dados editados com sucesso!</div>';
elseif($_GET['ind_msg'] == 3)
    $msg = '<div class="alert success">Configuração de frete desfeita!</div>';
	
$str = "SELECT * FROM pagseguro_configuracao ";
$rs  = mysql_query($str) or die(mysql_error());
$num = mysql_num_rows($rs);
$vet = mysql_fetch_array($rs);

include('menu.inc.php'); 
?>  
<script language="javascript">
function valida(ind)
{	
	if(ind == 1)
		document.form.cmd.value = "add";
	else
		document.form.cmd.value = "edit";
		
	//document.form.submit();
}
</script>			
<section id="content">
<div class="g12">
	<h1>Configurações do frete</h1>
	<p>Preencha os dados abaixo para configuração do cálculo de frete no site.</p>
   <?=$msg?>
    
	<form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <fieldset>
    	<section>
            <label for="email">E-mail:</label>
            <div>
                <input type="text" id="email" name="email" value="<?=$vet['email']?>" style="width:40%;">
                <br><span>Ex: email@email.com.br</span>
            </div>
        </section>

        <section>
            <label for="peso">Token:</label>
            <div>
                <input type="text" id="token" name="token" value="<?=$vet['token']?>" style="width:40%;" >
                <br><span>Usuário do contrato.</span>
            </div>
        </section>

        <section>
            <label for="email">URL de notificação:</label>
            <div>
                <input type="text" id="urlnotificacao" name="urlnotificacao" value="<?=$vet['urlnotificacao']?>" style="width:40%;">
                <br><span>Ex: https://www.seu-site.com.br/pagseguro_notificacoes.php</span>
            </div>
        </section>

		<section>
			<label>Status</label>
			<div>
				<select id="status" name="status">
					<option value="0" <?=$vet['status'] == 0?'Selected':'';?>>Inativo</option>
					<option value="1" <?=$vet['status'] == 1?'Selected':'';?>>Ativo</option>
				</select>
			</div>
		</section>

		<section>
			<label>Sandbox</label>
			<div>
				<select id="sandbox" name="sandbox">
					<option value="0" <?=$vet['sandbox'] == 0?'Selected':'';?>>Inativo</option>
					<option value="1" <?=$vet['sandbox'] == 1?'Selected':'';?>>Ativo</option>
				</select>
			</div>
		</section>
        
        <section>
            <?
			if(!$num)
			{
			?>
				<div><button class="i_tick icon" id="formsubmitswitcher" onClick="javascript: valida(1);" >Cadastrar</button></div>
			<?
			}
			else
			{
			?>
				<div><button class="i_refresh_3 icon" id="formsubmitswitcher" onClick="javascript: valida(2);" >Alterar</button></div>
			<?
			}
			?>
    	</section>

    </fieldset>
   	</form>
</div>
</section>
        
<?php include('rodape.inc.php'); ?>    
