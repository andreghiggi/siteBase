<?php 
include('topo.inc.php'); 

if($_GET['codigo'])
    $codigo = anti_injection($_GET['codigo']);
else
    $codigo = anti_injection($_POST['codigo']);
	
if($_GET['chave'])
    $chave = anti_injection($_GET['chave']);
else
    $chave = anti_injection($_POST['chave']);

include('menu.inc.php');
?>
<script>
function valida()
{
    document.form.cmd.value = "filtrar";
}
</script>
<section id="content">
<div class="g12">
    <h1>Relatório de clientes cadastrados no site</h1>
    <p></p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <fieldset>
    	<section>
            <label for="text_field">Chave:</label>
            <div>
            	<input type="text" id="chave" name="chave" value="<?=$chave?>" placeholder="Informar o código ou nome do cliente.">
            	<br><span>Informar o código ou nome do cliente.</span>
            </div>
        </section> 
        <section>
        	<label for="date">Data de cadastro:<br><span>Data de início e término dos registros</span></label>
        	<div><input id="data_inicio" name="data_inicio" type="text" class="date" value="<?=($_POST['data_inicio'] == TRUE) ? ConverteData($_POST['data_inicio']) : "" ?>"><span> Ex: dd/mm/aaaa</span></div>
            <div><input id="data_termino" name="data_termino" type="text" class="date" value="<?=($_POST['data_termino'] == TRUE) ? ConverteData($_POST['data_termino']) : "" ?>"><span> Ex: dd/mm/aaaa</span></div>
    	</section>
        <section>
            <div><button class="i_tick icon" onClick="javascript: valida();" >Buscar</button></div>
        </section>
    </fieldset>
    </form>
    
    <?
    if($_POST['cmd'] == 'filtrar' || !empty($where))
    {
    	$strWhere = " ";

    	if($_POST['cmd'] == 'filtrar')
    	{
			$chave = anti_injection($_POST['chave']);
	        $data_inicio = anti_injection($_POST['data_inicio']);
	        $data_termino = anti_injection($_POST['data_termino']);

	        if($chave == TRUE)
                $strWhere .= "AND (codigo = '$chave' OR nome LIKE '%$chave%')";

            if($data_inicio == TRUE)
                $strWhere .= "AND data_cadastro >= '$data_inicio'";

            if($data_termino == TRUE)
                $strWhere .= "AND data_cadastro <= '$data_termino'";
        }

        $str = "SELECT * FROM cadastros WHERE 1 = 1 $strWhere ORDER BY nome ASC";
        $rs  = mysql_query($str) or die(mysql_error());
        $num = mysql_num_rows($rs);

        if($num > 0)
        {
	?>
    <h1>Retorno da busca</h1>
    <p><?=$num?> registros encontrados</p>
    
    <fieldset>
    <table class="datatable">
        <thead>
            <tr>
                <th>ID<br>Cliente</th>
                <th>Cliente</th>
                <th>Telefone</th>
                <th>Email</th>
                <th class="c">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?
            while($vet = mysql_fetch_array($rs))
            {
        ?>
            <tr >
                <td><a href="clientes_ver.php?idcadastro=<?=$vet['codigo']?>" target="_blank">#<?=$vet['codigo']?></a></td>
                <td><?=stripslashes($vet['nome'].' '.$vet['sobrenome'])?></td>                
                <td><?=$vet['telefone']?></td>
                <td><?=$vet['email']?></td>
                <td><a class="btn i_magnifying_glass icon small" title="ver" href="clientes_ver.php?idcadastro=<?=$vet['codigo']?>" target="_blank">ver</a></td>
            </tr>
            <?
            }
            ?>            
        </tbody>
   	</table>
    </fieldset>
	<?
        }
        else
        {
    ?>
    <fieldset>
        <p>Nenhum registro encontrado</p>
    </fieldset>
    <?
        }
    }
    ?>
</div>
</section>
<?php include('rodape.inc.php'); ?>    
