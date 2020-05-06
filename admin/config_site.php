<?php 

/*ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);*/

include('topo.inc.php'); 

$facebook = $_POST['facebook'];
$instagram = $_POST['instagram'];
$twitter = $_POST['twitter'];
$pinterest = $_POST['pinterest'];
$google = $_POST['google'];
$whatsapp = $_POST['whatsapp'];

$logo = altera_nome_imagem($_POST['logo'][0]);
$nome = addslashes($_POST['nome']);
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$endereco = addslashes($_POST['endereco']);
$url_maps = $_POST['url_maps'];

$funcionamento = $_POST['funcionamento'];
$frete = $_POST['frete'];
$devolucao = $_POST['devolucao'];

$pagamento = $_POST['pagamento'];
$pac = $_POST['pac'];

if($_POST['cmd'] == "add")
{
    $str = "INSERT INTO config_site (whatsapp, facebook, instagram, twitter, pinterest, google, logo, nome, email, telefone, endereco, url_maps, funcionamento, frete, devolucao, pagamento, pac)
        VALUES ('$whatsapp','$facebook', '$instagram', '$twitter', '$pinterest', '$google', '$logo', '$nome', '$email', '$telefone', '$endereco', '$url_maps', '$funcionamento', '$frete', '$devolucao', '$pagamento', '$pac')";
    $rs  = mysql_query($str) or die(mysql_error());
        
    redireciona("config_site.php?ind_msg=1");
}

if($_POST['cmd'] == "edit")
{
    $str = "UPDATE config_site SET whatsapp = '$whatsapp', facebook = '$facebook', instagram = '$instagram', twitter = '$twitter', pinterest = '$pinterest', google = '$google',
        nome = '$nome', email = '$email', telefone = '$telefone', endereco = '$endereco', url_maps = '$url_maps', funcionamento = '$funcionamento',
        frete = '$frete', devolucao = '$devolucao', pagamento = '$pagamento', pac = '$pac'";
    $rs  = mysql_query($str) or die(mysql_error());

    if(!empty($logo))
    {
        $str = "UPDATE config_site SET logo = '$logo'";
        $rs  = mysql_query($str) or die(mysql_error());
    }
        
    redireciona("config_site.php?ind_msg=2");
}

if($_GET['ind_msg'] == 1)
	$msg = '<div class="alert success">Dados cadastrados com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
	$msg = '<div class="alert success">Dados editados com sucesso!</div>';
	
$str = "SELECT * FROM config_site ";
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
	<h1>Configurações gerais do site</h1>
	<p>Preencha os dados abaixo para configuração de alguns dados do site.</p>

	 <?=$msg?>
    
	<form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <label>Opção de cobrança do PAC</label> 
    <fieldset>
        <section>
            <label for="textarea_auto">PAC gratuíto?</span></label>
            <div>
                <input type="radio" id="pac_1" name="pac" <?=($vet['pac'] == FALSE || $vet['pac'] == 1) ? "checked" : "" ?> value="1"><label for="pagamento_1" class="radio">SIM</label>
                <input type="radio" id="pac_2" name="pac" <?=($vet['pac'] == 2) ? "checked" : "" ?> value="2"><label for="pagamento_2" class="radio">NÃO</label>
            </div>
        </section>
    </fieldset>

    <label>Configurar opção de pagamento</label> 
    <fieldset>
        <section>
            <label for="textarea_auto">Tipo:</span></label>
            <div>
                <input type="radio" id="pagamento_1" name="pagamento" <?=($vet['pagamento'] == FALSE || $vet['pagamento'] == 1) ? "checked" : "" ?> value="1"><label for="pagamento_1" class="radio">Apenas módulo de pagamento</label>
                <input type="radio" id="pagamento_2" name="pagamento" <?=($vet['pagamento'] == 2) ? "checked" : "" ?> value="2"><label for="pagamento_2" class="radio">Apenas pagamento presencial</label>
                <input type="radio" id="pagamento_3" name="pagamento" <?=($vet['pagamento'] == 3) ? "checked" : "" ?> value="3"><label for="pagamento_3" class="radio">Ambos</label>
            </div>
        </section>
    </fieldset>

	<label>Redes sociais</label> 
    <fieldset>
        <section>
            <label for="text_field">Facebook:</label>
            <div><input type="url" id="facebook" name="facebook" value="<?=$vet['facebook']?>" placeholder="http://" ></div>
        </section>
        <section>
            <label for="text_field">Instagram:</label>
            <div><input type="url" id="instagram" name="instagram" value="<?=$vet['instagram']?>" placeholder="http://" ></div>
        </section>
        <section>
            <label for="text_field">Twitter:</label>
            <div><input type="url" id="twitter" name="twitter" value="<?=$vet['twitter']?>" placeholder="http://" ></div>
        </section>
        <section>
            <label for="text_field">Pinterest:</label>
            <div><input type="url" id="pinterest" name="pinterest" value="<?=$vet['pinterest']?>" placeholder="http://" ></div>
        </section>
        <section>
            <label for="text_field">Google +:</label>
            <div><input type="url" id="google" name="google" value="<?=$vet['google']?>" placeholder="http://" ></div>
        </section>  
        <section>
            <label for="text_field">Whatsapp:</label>
            <div><input type="url" id="whatsapp" name="whatsapp" value="<?=$vet['whatsapp']?>" placeholder="https://api.whatsapp.com/send?phone="></div>
        </section> 
    </fieldset>

    <label>Dados da empresa</label> 
    <fieldset>
        <section>
            <label for="file_upload">                
                <?
                if(!empty($vet['logo']))
                {
                    echo 'Logo:<br><br><a href="../upload/'.$vet['logo'].'" class="thickbox"><img src="../upload/thumbnails/'.$vet['logo'].'" ></a>';
                }
                else
                {
                    echo 'Logo:';
                }
                ?>
            </label>
            <div>
                <input type="file" id="logo" name="logo" <?=(!$num) ? 'required' : '' ?>>
            </div>
        </section>
        <section>
            <label for="text_field">Nome:</label>
            <div><input type="text" id="nome" name="nome" value="<?=stripslashes($vet['nome'])?>" ></div>
        </section>
        <section>
            <label for="text_field">Email:</label>
            <div><input type="email" id="email" name="email" class="email" value="<?=$vet['email']?>" ></div>
        </section>
        <section>
            <label for="text_field">Telefone(s):</label>
            <div><input type="text" id="telefone" name="telefone" value="<?=$vet['telefone']?>" ></div>
        </section>
        <section>
            <label for="text_field">Endereço:</label>
            <div><textarea id="endereco" name="endereco" ><?=stripslashes($vet['endereco'])?></textarea></div>
        </section>
        <section>
            <label for="text_field">Url (google maps):</label>
            <div><input type="text" id="url_maps" name="url_maps" value="<?=$vet['url_maps']?>" ></div>
        </section>
    </fieldset>

    <label>Dados da loja</label> 
    <fieldset>
        <section>
            <label for="text_field">Funcionamento:</label>
            <div><input type="text" id="funcionamento" name="funcionamento" value="<?=$vet['funcionamento']?>" ></div>
        </section>
        <section>
            <label for="text_field">Frete:</label>
            <div><input type="text" id="frete" name="frete" value="<?=$vet['frete']?>" ></div>
        </section>
        <section>
            <label for="text_field">Devolução:</label>
            <div><input type="text" id="devolucao" name="devolucao" value="<?=$vet['devolucao']?>" ></div>
        </section>
    </fieldset>

    <fieldset>
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
