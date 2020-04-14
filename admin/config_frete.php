<?php 
include('topo.inc.php'); 

$cep_origem = $_POST['cep_origem'];
$peso = $_POST['peso'];
$comprimento = $_POST['comprimento'];
$altura = $_POST['altura'];
$largura = $_POST['largura'];
$mao_propria = $_POST['mao_propria'];

if($_POST['cmd'] == "add")
{
	$str = "INSERT INTO config_frete (cep_origem, peso, comprimento, altura, largura, mao_propria)
		VALUES ('$cep_origem', '$peso', '$comprimento', '$altura', '$largura', '$mao_propria')";
	$rs  = mysql_query($str) or die(mysql_error());
			
	redireciona("config_frete.php?ind_msg=1");
}

if($_POST['cmd'] == "edit")
{
	$str = "UPDATE config_frete SET cep_origem = '$cep_origem', peso = '$peso', comprimento = '$comprimento', altura = '$altura', largura = '$largura', mao_propria = '$mao_propria'";
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
    <p><a class="btn i_wizard icon small" title="Desfazer configurações de frete" href="config_frete.php?cmd=clean" onclick="javascript: if(!confirm('Deseja realmente desfazer as configurações de frete?')) { return false }">Desfazer configurações de frete</a></p>

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
            <label for="peso">Peso da embalagem:</label>
            <div>
                <input type="text" id="peso" name="peso" value="<?=$vet['peso']?>" style="width:10%;" >
                <br><span>Exemplo: 0.05 (50g) / 0.5 (500g) / 1 (1kg) / 1.5 (1.5kg)</span>
            </div>
        </section>
        <section>
            <label for="comprimento">Comprimento da embalagem:</label>
            <div>
                <input type="text" id="comprimento" name="comprimento" value="<?=$vet['comprimento']?>" style="width:10%;" >
                <br><span>Comprimento da encomenda (incluindo embalagem), em centímetros.</span>
            </div>
        </section>
        <section>
            <label for="altura">Altura da embalagem:</label>
            <div>
                <input type="text" id="altura" name="altura" value="<?=$vet['altura']?>" style="width:10%;" >
                <br><span>Altura da encomenda (incluindo embalagem), em centímetros.</span>
            </div>
        </section>
        <section>
            <label for="largura">Largura da embalagem:</label>
            <div>
                <input type="text" id="largura" name="largura" value="<?=$vet['largura']?>" style="width:10%;" >
                <br><span>Largura da encomenda (incluindo embalagem), em centímetros.</span>
            </div>
        </section>
        <section>
            <label for="textarea_auto">Mão própria?</span></label>
            <div>
                <input type="radio" id="mao_propria_1" name="mao_propria" <?=($vet['mao_propria'] == FALSE || $vet['mao_propria'] == 's') ? "checked" : "" ?> value='s'><label for="mao_propria_1" class="radio">Sim</label>
                <input type="radio" id="mao_propria_2" name="mao_propria" <?=($vet['mao_propria'] == 'n') ? "checked" : "" ?> value='n'><label for="mao_propria_2" class="radio">Não</label>
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
