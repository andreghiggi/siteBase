<?php 
include('topo.inc.php'); 

$cep_origem = $_POST['cep_origem'];
$mao_propria = $_POST['mao_propria'];
$empresa = $_POST['empresa'];
$senha = $_POST['senha'];
$pac = $_POST['pac'];
$sedex = $_POST['sedex'];

if($_POST['cmd'] == "add")
{
	$str = "INSERT INTO config_frete (cep_origem, mao_propria, empresa, senha, pac, sedex)
		VALUES ('$cep_origem', '$mao_propria', '$empresa', '$senha', '$pac', '$sedex')";
	$rs  = mysql_query($str) or die(mysql_error());

	redireciona("config_frete.php?ind_msg=1");
}

if($_POST['cmd'] == "edit")
{
	$str = "UPDATE config_frete SET cep_origem = '$cep_origem', mao_propria = '$mao_propria', empresa = '$empresa', senha = '$senha', pac = '$pac', sedex = '$sedex'";
	$rs  = mysql_query($str) or die(mysql_error());
	
	redireciona("config_frete.php?ind_msg=2");
}

if($_GET['cmd'] == "clean")
{
    $str = "TRUNCATE TABLE config_frete";
    $rs  = mysql_query($str) or die(mysql_error());
    
    redireciona("config_frete.php?ind_msg=3");
}

if($_GET['ind_msg'] == 1)
	$msg = '<div class="alert success">Dados cadastrados com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
	$msg = '<div class="alert success">Dados editados com sucesso!</div>';
elseif($_GET['ind_msg'] == 3)
    $msg = '<div class="alert success">Configuração de frete desfeita!</div>';
	
$str = "SELECT * FROM config_frete ";
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
            <label for="cep_origem">CEP de origem:</label>
            <div>
                <input type="text" id="cep_origem" name="cep_origem" value="<?=$vet['cep_origem']?>" style="width:20%;" onKeyPress="javascript: return somenteNumeros(event);">
                <br><span>Ex: 96010140</span>
            </div>
        </section>
    	<section>
            <label for="peso">Contrato:</label>
            <div>
                <input type="text" id="empresa" name="empresa" value="<?=$vet['empresa']?>" style="width:40%;" >
                <br><span>Usuário do contrato.</span>
            </div>
        </section>
        <section>
            <label for="comprimento">Senha:</label>
            <div>
                <input type="text" id="senha" name="senha" value="<?=$vet['senha']?>" style="width:40%;" >
                <br><span>Senha do contrato.</span>
            </div>
        </section>
        <section>
            <label for="altura">PAC:</label>
            <div>
                <input type="text" id="pac" name="pac" placeholder="04510" value="<?=$vet['PAC']?>" style="width:16%;" >
                <br><span>Código para PAC (informado no contrato, valor padrão sem contrato 04510).</span>
            </div>
        </section>
        <section>
            <label for="largura">SEDEX:</label>
            <div>
                <input type="text" id="sedex" name="sedex" placeholder="04014" value="<?=$vet['SEDEX']?>" style="width:16%;" >
                <br><span>Código para SEDEX (informado no contrato, valor padrão sem contrato 04014).</span>
            </div>
        </section>
        <section>
            <label for="textarea_auto">Mão própria?</span></label>
            <div>
                <select id="mao_propria_1" name="mao_propria">
                    <option value="s" <?=($vet['mao_propria'] == 's') ? "selected" : "" ?> >Sim</option>
                    <option value="n" <?=(!isset($vet['mao_propria']) || $vet['mao_propria'] == 'n') ? "selected" : "" ?> >Não</option>
                </select>
                <br><span>Aqui você informa se quer que a encomenda deva ser entregue somente para uma determinada pessoa após confirmação por RG.</span>
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
