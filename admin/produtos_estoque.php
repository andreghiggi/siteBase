<?php 
include('topo.inc.php'); 

if($_GET['codigo'] == TRUE)
	$codigo = anti_injection($_GET['codigo']);
else
	$codigo = anti_injection($_POST['codigo']);

if($_GET['idproduto'] == TRUE)
    $idproduto = anti_injection($_GET['idproduto']);
else
    $idproduto = anti_injection($_POST['idproduto']);

$idcor = $_POST['idcor'];
$idtamanho = $_POST['idtamanho'];
$estoque = $_POST['estoque'];

if($_POST['cmd'] == "add")
{
    $str = "SELECT * FROM produtos_estoque WHERE idproduto = '$idproduto' AND idcor = '$idcor' AND idtamanho = '$idtamanho'";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

    if($num)
        redireciona("produtos_estoque.php?ind_msg=4&idproduto=$idproduto");

    $str = "INSERT INTO produtos_estoque (idproduto, idcor, idtamanho, estoque) VALUES ('$idproduto', '$idcor', '$idtamanho', '$estoque')";
    $rs  = mysql_query($str) or die(mysql_error());
    
    redireciona("produtos_estoque.php?ind_msg=1&idproduto=$idproduto");
}

if($_POST['cmd'] == "edit")
{   
    $str = "SELECT * FROM produtos_estoque WHERE codigo != '$codigo' AND idproduto = '$idproduto' AND idcor = '$idcor' AND idtamanho = '$idtamanho'";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

    if($num)
        redireciona("produtos_estoque.php?ind_msg=4&idproduto=$idproduto");

    $str = "UPDATE produtos_estoque SET idproduto = '$idproduto', idcor = '$idcor', idtamanho = '$idtamanho', estoque = '$estoque' WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
    
    redireciona("produtos_estoque.php?ind_msg=2&idproduto=$idproduto");
}

if($_GET['cmd'] == "del")
{
	$str = "DELETE FROM produtos_estoque WHERE codigo = '$codigo'";
	$rs  = mysql_query($str) or die(mysql_error());
	
	redireciona("produtos_estoque.php?ind_msg=3&idproduto=$idproduto");
}

if($_GET['ind_msg'] == 1)
	$msg = '<div class="alert success">Estoque cadastrado com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
	$msg = '<div class="alert success">Estoque editado com sucesso!</div>';
elseif($_GET['ind_msg'] == 3)
    $msg = '<div class="alert success">Estoque excluído com sucesso!</div>';
elseif($_GET['ind_msg'] == 4)
    $msg = '<div class="alert warning">Cor e tamanho já cadastrados para este produto!</div>';

$strP = "SELECT * FROM produtos WHERE codigo = '$idproduto'";
$rsP  = mysql_query($strP) or die(mysql_error());
$vetP = mysql_fetch_array($rsP);

$str = "SELECT * FROM produtos_estoque WHERE codigo = '$codigo'";
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
    <h1>Estoque > <?=stripslashes($vetP['nome'])?></h1>
    <p></p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <input type="hidden" name="codigo" value="<?=$vet['codigo']?>">
    <input type="hidden" name="idproduto" value="<?=$vet['idproduto']?>"> 
    <fieldset>
        <section>
            <label for="idcor">Cor:</label>
            <div>
                <select name="idcor" id="idcor" >
                    <option value="">Selecione uma cor ...</option>
                    <?
                    $strE = "SELECT * FROM cores ORDER BY titulo";
                    $rsE  = mysql_query($strE) or die(mysql_error());

                    while($vetE = mysql_fetch_array($rsE))
                    {
                    ?>
                    <option value="<?=$vetE['codigo']?>" <?=($vetE['codigo'] == $vet['idcor']) ? 'selected' : ''?>><?=stripslashes($vetE['titulo'])?></option>
                    <?
                    }
                    ?>
                </select>
            </div>
        </section>
        <section>
            <label for="idtamanho">Tamanho:</label>
            <div>
                <select name="idtamanho" id="idtamanho" >
                    <option value="">Selecione um tamanho ...</option>
                    <?
                    $strE = "SELECT * FROM tamanhos ORDER BY numero";
                    $rsE  = mysql_query($strE) or die(mysql_error());

                    while($vetE = mysql_fetch_array($rsE))
                    {
                    ?>
                    <option value="<?=$vetE['codigo']?>" <?=($vetE['codigo'] == $vet['idtamanho']) ? 'selected' : ''?>><?=stripslashes($vetE['numero'])?></option>
                    <?
                    }
                    ?>
                </select>
            </div>
        </section>        
        <section>
            <label for="estoque">Estoque:</label>
            <div>
                <input type="number" id="estoque" name="estoque" value="<?=$vet['estoque']?>" required class="integer">
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

	<?
    $str = "SELECT A.*, B.titulo AS cor, C.numero AS tamanho 
        FROM produtos_estoque A
        LEFT JOIN cores B ON A.idcor = B.codigo
        LEFT JOIN tamanhos C ON A.idtamanho = C.codigo
        WHERE A.idproduto = '$idproduto'
        ORDER BY C.numero, cor";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

	if($num > 0)
	{
	?>
	<h1>Lista de registros para o produto <b><?=stripslashes($vetP['nome'])?></b></h1>
    <p>Todos os registros de estoque cadastrados.</p>

    <?
    $strC = "SELECT DISTINCT A.idcor, B.titulo AS cor
        FROM produtos_estoque A
        INNER JOIN cores B ON A.idcor = B.codigo
        WHERE A.idproduto = '$idproduto'
        ORDER BY A.idcor";
    $rsC  = mysql_query($strC) or die(mysql_error());
    $numC = mysql_num_rows($rsC);

    if($numC > 0)
    {
        while($vetC = mysql_fetch_array($rsC))
        {
        ?>
            <a class="btn i_image icon" title="imagens" href="produtos_imagens.php?idproduto=<?=$idproduto?>&idcor=<?=$vetC['idcor']?>" >Upload de imagens de cor "<?=stripslashes($vetC['cor'])?>"</a>
        <?
        }
    }

    echo '<br><br>';
    ?>
    
    <table class="datatable">
    <thead>
        <tr>
            <th>Cor</th>
            <th>Tamanho</th>
            <th>Estoque</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
		<?
        while($vet = mysql_fetch_array($rs))
        {
        ?>
        <tr>
            <td><?=($vet['cor']) ? stripslashes($vet['cor']) : 'Não informado'?></td>
            <td><?=($vet['tamanho']) ? $vet['tamanho'] : 'Não informado'?></td>
            <td><?=$vet['estoque']?></td>
            <td class="c">
                <a class="btn i_pencil icon small" title="editar" href="produtos_estoque.php?ind=2&idproduto=<?=$idproduto?>&codigo=<?=$vet['codigo']?>" >editar</a>
                <a class="btn i_trashcan icon small" title="excluir" href="produtos_estoque.php?cmd=del&idproduto=<?=$idproduto?>&codigo=<?=$vet['codigo']?>" onclick="javascript: if(!confirm('Deseja realmente excluir este registro?')) { return false }">excluir</a>
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
