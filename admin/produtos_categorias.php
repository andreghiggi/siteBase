<?php 
include('topo.inc.php');

if($_GET['codigo'] == TRUE) 
    $codigo = anti_injection($_GET['codigo']); 
else 
    $codigo = anti_injection($_POST['codigo']);

$nome = addslashes($_POST['nome']);
$status = $_POST['status'];

if($_POST['cmd'] == "add")
{
    $str = "INSERT INTO produtos_categorias (nome, status) VALUES ('$nome', '$status')";
    $rs  = mysql_query($str) or die(mysql_error());
    
    redireciona("produtos_categorias.php?ind_msg=1");
}

if($_POST['cmd'] == "edit")
{  
    $str = "UPDATE produtos_categorias SET nome = '$nome', status = '$status' WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
    
    redireciona("produtos_categorias.php?ind_msg=2");
}

if($_GET['cmd'] == "del")
{   
    $strC = "SELECT A.*
        FROM produtos A
        INNER JOIN produtos_categorias B ON A.idcategoria = B.codigo
        INNER JOIN produtos_subcategorias C ON B.codigo = C.idcategoria
        WHERE A.idcategoria = '$codigo'";
    $rsC  = mysql_query($strC) or die(mysql_error());
    $numC = mysql_num_rows($rsC);

    if(!$numC)
    {
        $str = "DELETE FROM produtos_categorias WHERE codigo = '$codigo'";
        $rs  = mysql_query($str) or die(mysql_error());

        $str = "DELETE FROM produtos_subcategorias WHERE idcategoria = '$codigo'";
        $rs  = mysql_query($str) or die(mysql_error());

        redireciona("produtos_categorias.php?ind_msg=3");
    }
    
    redireciona("produtos_categorias.php?ind_msg=4");
}

if($_GET['ind_msg'] == 1)
    $msg = '<div class="alert success">Categoria cadastrada com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
    $msg = '<div class="alert success">Categoria editada com sucesso!</div>';
elseif($_GET['ind_msg'] == 3)
    $msg = '<div class="alert success">Categoria excluída com sucesso!</div>';
elseif($_GET['ind_msg'] == 4)
    $msg = '<div class="alert warning">Não foi possível excluir a categoria!<br>Existem produtos associados a ela ou a subcategoria da mesma!</div>';

$str = "SELECT * FROM produtos_categorias WHERE codigo = '$codigo'";
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
    <h1>Produtos > Categorias</h1>
    <p></p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <input type="hidden" name="codigo" value="<?=$vet['codigo']?>">
    <fieldset>
        <section>
            <label for="text_field">Nome:</label>
            <div><input type="text" id="nome" name="nome" value="<?=stripslashes($vet['nome'])?>" required></div>
        </section>
        <section>
            <label for="textarea_auto">Status:</span></label>
            <div>
                <select name="status">
                    <option value="1" <?=($vet['status'] == FALSE || $vet['status'] == 1) ? "selected" : "" ?> >Ativo</option>
                    <option <?=($vet['status'] == 2) ? "checked" : "" ?> value="2">Inativo</option>
                </select>
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
    $str = "SELECT * FROM produtos_categorias ORDER BY nome";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);
      
    if($num > 0)
    {
    ?>
    <h1>Lista de categorias</h1>
    <p>Todas as categorias cadastradas no sistema</p>
    <table >
        <thead>
            <tr>
                <th colspan="3">LEGENDA - Status da categoria</th>
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
                <th>Nome</th>              
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
                <td class="c">
                    <a class="btn i_pencil icon small" title="editar" href="produtos_categorias.php?ind=2&codigo=<?=$vet['codigo']?>" >editar</a>
                    <a class="btn i_trashcan icon small" title="excluir" href="produtos_categorias.php?cmd=del&codigo=<?=$vet['codigo']?>" onclick="javascript: if(!confirm('Deseja realmente excluir este registro?')) { return false }">excluir</a>
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
