<?php 
include('topo.inc.php'); 

if($_GET['codigo'] == TRUE)
	$codigo = anti_injection($_GET['codigo']);
else
	$codigo = anti_injection($_POST['codigo']);

$titulo = addslashes($_POST['titulo']);

if($_POST['cmd'] == "add")
{
    $str = "INSERT INTO marcas (titulo) VALUES ('$titulo')";
    $rs  = mysql_query($str) or die(mysql_error());
    
    redireciona("marcas.php?ind_msg=1");
}

if($_POST['cmd'] == "edit")
{   
    $str = "UPDATE marcas SET titulo = '$titulo' WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());

    redireciona("marcas.php?ind_msg=2");
}

if($_GET['cmd'] == "del")
{
	$str = "DELETE FROM marcas WHERE codigo = '$codigo'";
	$rs  = mysql_query($str) or die(mysql_error());
	
	redireciona("marcas.php?ind_msg=3");
}

if($_GET['ind_msg'] == 1)
	$msg = '<div class="alert success">Marca cadastrada com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
	$msg = '<div class="alert success">Marca editada com sucesso!</div>';
elseif($_GET['ind_msg'] == 3)
    $msg = '<div class="alert success">Marca excluída com sucesso!</div>';

$str = "SELECT * FROM marcas WHERE codigo = '$codigo'";
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
    <h1>Marcas</h1>
    <p></p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <input type="hidden" name="codigo" value="<?=$vet['codigo']?>">    
    <fieldset>
        <section>
            <label for="text_field">Título:</label>
            <div><input type="text" id="titulo" name="titulo" value="<?=stripslashes($vet['titulo'])?>" ></div>
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

	<?
    $str = "SELECT * FROM marcas ORDER BY titulo";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

	if($num > 0)
	{
	?>
	<h1>Lista de marcas</h1>
    <p>Todas as marcas cadastradas no sistema.</p>
    <table class="datatable">
    <thead>
        <tr>
            <th>Título</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
		<?
        while($vet = mysql_fetch_array($rs))
        {
        ?>
        <tr>
            <td><?=stripslashes($vet['titulo'])?></td>
            <td class="c">
                <a class="btn i_pencil icon small" title="editar" href="marcas.php?ind=2&codigo=<?=$vet['codigo']?>" >editar</a>
                <a class="btn i_trashcan icon small" title="excluir" href="marcas.php?cmd=del&codigo=<?=$vet['codigo']?>" onclick="javascript: if(!confirm('Deseja realmente excluir este registro?')) { return false }">excluir</a>
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
</div>
</section>
<!-- end div #content -->
        
        
<?php include('rodape.inc.php'); ?>    
