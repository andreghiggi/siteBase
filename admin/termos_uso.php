<?php 
include('topo.inc.php'); 

if($_GET['codigo'] == TRUE) 
	$codigo = anti_injection($_GET['codigo']); 
else 
	$codigo = anti_injection($_POST['codigo']);

$texto = addslashes($_POST['texto']);

if($_POST['cmd'] == "add")
{
	$str = "INSERT INTO termos_uso (texto) VALUES ('$texto')";
	$rs  = mysql_query($str) or die(mysql_error());
			
	redireciona("termos_uso.php?ind_msg=1");
}

if($_POST['cmd'] == "edit")
{
	$str = "UPDATE termos_uso SET texto = '$texto' WHERE codigo = '$codigo'";
	$rs  = mysql_query($str) or die(mysql_error());
	
	redireciona("termos_uso.php?ind_msg=2");
}

if($_GET['ind_msg'] == 1)
	$msg = '<div class="alert success">Texto cadastrado com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
	$msg = '<div class="alert success">Texto editado com sucesso!</div>';
	
$str = "SELECT * FROM termos_uso ";
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
	<h1>Termos de uso</h1>
	<p></p>
    
	<form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <input type="hidden" name="codigo" value="<?=$vet['codigo']?>">
    <?=$msg?>

    <fieldset>
    	<section>
        	<label for="textarea_auto">Texto:</label>
            <div>
                <textarea name="texto" id="texto" rows="10" cols="80"><?=stripslashes($vet['texto'])?></textarea>
                <script>
                    // Replace the <textarea id="editor1"> with a CKEditor
                    // instance, using default configuration.
                    CKEDITOR.replace( 'texto' );
                </script>
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
