<?php 
include('topo.inc.php');
include('menu.inc.php');

if($_GET['codigo'] == TRUE)
	$codigo = anti_injection($_GET['codigo']);
else
	$codigo = anti_injection($_POST['codigo']);

if($_GET['cmd'] == "del")
{
    $str = "DELETE FROM newsletter WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());

	redireciona("newsletter.php?ind_msg=1");
}

if($_GET['ind_msg'] == 1)
	$msg = '<div class="alert success">Email excluído com sucesso!</div>';
?> 
<section id="content">
<div class="g12">
	<h1>Newsletter</h1>
    <p><a class="btn i_wizard icon small" title="Exportar emails" href="newsletter.php?cmd=news" >Exportar emails</a></p>

    <?=$msg?>
    
    <?
	$str = "SELECT * FROM newsletter ORDER BY email";
	$rs  = mysql_query($str) or die(mysql_error());
	$num = mysql_num_rows($rs);

	if($num > 0)
	{
	?>
    <h1>Todos os emails</h1>
    <p>Lista de emails cadastrados no site</p>
    
    <table class="datatable">
    <thead>
        <tr>
            <th>Email</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
		<?
        while($vet = mysql_fetch_array($rs))
        {
        ?>
        <tr >        	
            <td><?=$vet['email']?></td>
            <td class="c">            	
                <a class="btn i_trashcan icon small" title="excluir" href="newsletter.php?cmd=del&codigo=<?=$vet['codigo']?>" onclick="javascript: if(!confirm('Deseja realmente excluir este registro?')) { return false }">excluir</a>
            </td>
        </tr>
        <?
        }
        ?>
    </tbody>
    </table>
    <!-- end form -->
    <?
	}
	else
	{
	?>
    <p>Nenhum registro encontrado no sistema</p>
    <?
	}
	?>
</div>
</section>
<!-- end div #content -->
        
<?php include('rodape.inc.php'); ?>    