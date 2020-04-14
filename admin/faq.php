<?php 
include('topo.inc.php'); 

if($_GET['codigo'] == TRUE)
	$codigo = anti_injection($_GET['codigo']);
else
	$codigo = anti_injection($_POST['codigo']);

$pergunta = addslashes($_POST['pergunta']);
$resposta = addslashes($_POST['resposta']);

if($_POST['cmd'] == "add")
{
    $str = "INSERT INTO faq (pergunta, resposta) VALUES ('$pergunta', '$resposta')";
    $rs  = mysql_query($str) or die(mysql_error());

    redireciona("faq.php?ind_msg=1");
}

if($_POST['cmd'] == "edit")
{   
    $str = "UPDATE faq SET pergunta = '$pergunta', resposta = '$resposta' WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());

    redireciona("faq.php?ind_msg=2");
}

if($_GET['cmd'] == "del")
{
    $str = "DELETE FROM faq WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
	
	redireciona("faq.php?ind_msg=3");
}

if($_GET['ind_msg'] == 1)
	$msg = '<div class="alert success">FAQ cadastrado com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
	$msg = '<div class="alert success">FAQ editado com sucesso!</div>';
elseif($_GET['ind_msg'] == 3)
    $msg = '<div class="alert success">FAQ excluído com sucesso!</div>';

$str = "SELECT * FROM faq WHERE codigo = '$codigo'";
$rs  = mysql_query($str) or die(mysql_error());
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
}
</script>  
			
<section id="content">
<div class="g12">
    <h1>FAQ</h1>
    <p></p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <input type="hidden" name="codigo" value="<?=$vet['codigo']?>">    
    <fieldset>
        <section>
            <label for="text_field">Pergunta:</label>
            <div><input type="text" id="pergunta" name="pergunta" value="<?=$vet['pergunta']?>" required></div>
        </section>
        <section>
            <label for="textarea_auto">Resposta:</label>
            <div>
                <textarea name="resposta" id="resposta" rows="10" cols="80" ><?=stripslashes($vet['resposta'])?></textarea>
                <script>
                    // Replace the <textarea id="editor1"> with a CKEditor
                    // instance, using default configuration.
                    CKEDITOR.replace( 'resposta' );
                </script>
            </div>
        </section>
        <section>
			<?
            if($ind == 1)
            {
            ?>
            <div><button class="i_tick icon" onClick="javascript: valida(1);">Cadastrar</button></div>
            <?
            }
            else
            {
            ?>
            <div><button class="i_refresh_3 icon" onClick="javascript: valida(2);">Alterar</button></div>
            <?
            }
            ?>
        </section>
    </fieldset>
	</form>
   	<!-- end form -->

	<fieldset>
	<?
    $str = "SELECT * FROM faq ORDER BY pergunta";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

	if($num > 0)
	{
	?>	
	<h1>Lista de FAQs</h1>
    <p>Todos os FAQs cadastrados no sistema</p>
    <table class="datatable">
    <thead>
        <tr>
            <th>Pergunta</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
		<?
        while($vet = mysql_fetch_array($rs))
        {
        ?>
        <tr >
            <td><?=stripslashes($vet['pergunta'])?></td>
            <td class="c">
                <a class="btn i_pencil icon small" title="editar" href="faq.php?ind=2&codigo=<?=$vet['codigo']?>" >editar</a>
                <a class="btn i_trashcan icon small" title="excluir" href="faq.php?cmd=del&codigo=<?=$vet['codigo']?>" onclick="javascript: if(!confirm('Deseja realmente excluir este registro?')) { return false }">excluir</a>
            </td>
        </tr>
        <?
        }
        ?>
    </tbody>
    </table>
    <?
	}
	?>
    </fieldset>
</div>
</section>
<!-- end div #content -->
        
        
<?php include('rodape.inc.php'); ?>    
