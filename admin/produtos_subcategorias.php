<?php 
include('topo.inc.php');

if($_GET['codigo'] == TRUE) 
    $codigo = anti_injection($_GET['codigo']); 
else 
    $codigo = anti_injection($_POST['codigo']);

$idcategoria = $_POST['idcategoria'];
$nome = addslashes($_POST['nome']);
$status = $_POST['status'];

if($_POST['cmd'] == "add")
{
    $str = "INSERT INTO produtos_subcategorias (idcategoria, nome, status) VALUES ('$idcategoria', '$nome', '$status')";
    $rs  = mysql_query($str) or die(mysql_error());
    
    redireciona("produtos_subcategorias.php?ind_msg=1");
}

if($_POST['cmd'] == "edit")
{    
    $str = "UPDATE produtos_subcategorias SET idcategoria = '$idcategoria', nome = '$nome', status = '$status' WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
    
    redireciona("produtos_subcategorias.php?ind_msg=2");
}

if($_GET['cmd'] == "del")
{
    $strC = "SELECT A.*
        FROM produtos A
        INNER JOIN produtos_subcategorias B ON A.idsubcategoria = B.codigo
        WHERE A.idsubcategoria = '$codigo'";
    $rsC  = mysql_query($strC) or die(mysql_error());
    $numC = mysql_num_rows($rsC);

    if(!$numC)
    {
        $str = "DELETE FROM produtos_subcategorias WHERE idcategoria = '$codigo'";
        $rs  = mysql_query($str) or die(mysql_error());

        redireciona("produtos_subcategorias.php?ind_msg=3");
    }
    
    redireciona("produtos_subcategorias.php?ind_msg=4");
}

if($_GET['ind_msg'] == 1)
    $msg = '<div class="alert success">SubCategoria cadastrada com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
    $msg = '<div class="alert success">SubCategoria editada com sucesso!</div>';
elseif($_GET['ind_msg'] == 3)
    $msg = '<div class="alert success">SubCategoria excluída com sucesso!</div>';
elseif($_GET['ind_msg'] == 4)
    $msg = '<div class="alert warning">Não foi possível excluir a subcategoria!<br>Existem produtos associados a ela!</div>';

$str = "SELECT * FROM produtos_subcategorias WHERE codigo = '$codigo'";
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
    <h1>Produtos > Subcategorias</h1>
    <p></p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <input type="hidden" name="codigo" value="<?=$vet['codigo']?>">
    <fieldset>
        <section>
            <label for="text_field">Categorias:</label>
            <div>
                <select name="idcategoria" id="idcategoria" required>
                    <option value="">Selecione um categoria</option>
                    <?
                    $strC = "SELECT * FROM produtos_categorias WHERE status = '1' ORDER BY nome";
                    $rsC  = mysql_query($strC) or die(mysql_error());
                    
                    while($vetC = mysql_fetch_array($rsC))
                    {
                    ?>
                    <option value="<?=$vetC['codigo']?>" <?=($vetC['codigo'] == $vet['idcategoria']) ? 'selected' : '' ?>><?=stripslashes($vetC['nome'])?></option>
                    <?
                    }
                    ?>
                </select>
            </div>
        </section>
        <section>
            <label for="text_field">Nome:</label>
            <div><input type="text" id="nome" name="nome" value="<?=stripslashes($vet['nome'])?>" required></div>
        </section>
        <section>
            <label for="textarea_auto">Status:</span></label>
            <div>
                <input type="radio" id="status_1" name="status" <?=($vet['status'] == FALSE || $vet['status'] == 1) ? "checked" : "" ?> value="1"><label for="status_1" class="radio">Ativo</label>
                <input type="radio" id="status_2" name="status" <?=($vet['status'] == 2) ? "checked" : "" ?> value="2"><label for="status_2" class="radio">Inativo</label>
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
    $str = "SELECT A.*, B.nome AS categoria
        FROM produtos_subcategorias A
        INNER JOIN produtos_categorias B ON A.idcategoria = B.codigo
        ORDER BY nome";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);
      
    if($num > 0)
    {
    ?>
    <h1>Lista de subcategorias</h1>
    <p>Todas as subcategorias cadastradas no sistema</p>
    <table >
        <thead>
            <tr>
                <th colspan="3">LEGENDA - Status da subcategoria</th>
            </tr>
        </thead>
        <tbody>
            <tr >
                <td style="background-color:#f0a8a8; width:50%;">Inativo</td>
                <td style="background-color:#BFDFFF">Ativo</td>
            </tr>
        </tbody>
    </table>
    
    <table class="datatable">
        <thead>
            <tr>
                <th>Subcategoria</th>     
                <th>Categoria</th>              
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?
        while($vet = mysql_fetch_array($rs))
        {
            if($vet['status'] != 1)
                $class = 'class="gradeA"';
            else
                $class = 'class="gradeU"';
        ?>
            <tr <?=$class?>>
                <td><?=stripslashes($vet['nome'])?></td>
                <td><?=stripslashes($vet['categoria'])?></td>
                <td class="c">
                    <a class="btn i_pencil icon small" title="editar" href="produtos_subcategorias.php?ind=2&codigo=<?=$vet['codigo']?>" >editar</a>
                    <a class="btn i_trashcan icon small" title="excluir" href="produtos_subcategorias.php?cmd=del&codigo=<?=$vet['codigo']?>" onclick="javascript: if(!confirm('Deseja realmente excluir este registro?')) { return false }">excluir</a>
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
    ?>
</div>
</section>
<!-- end div #content -->

<?php include('rodape.inc.php'); ?>    
