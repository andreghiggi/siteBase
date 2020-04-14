<?php 
include('topo.inc.php'); 

if($_GET['codigo'] == TRUE)
    $codigo = anti_injection($_GET['codigo']);
else
    $codigo = anti_injection($_POST['codigo']);

$idcategoria = $_POST['idcategoria'];
$titulo = addslashes($_POST['titulo']);
$texto = addslashes($_POST['texto']);
$imagem = altera_nome_imagem($_POST['imagem'][0]);

if($_POST['cmd'] == "add")
{
    $str = "INSERT INTO blog (idusuario, idcategoria, titulo, texto, data) VALUES ('$idusuario', '$idcategoria', '$titulo', '$texto', NOW())";
    $rs  = mysql_query($str) or die(mysql_error());
    $codigo = mysql_insert_id();

    if(!empty($imagem))
    {
        $str = "UPDATE blog SET imagem = '$imagem' WHERE codigo = '$codigo'";
        $rs  = mysql_query($str) or die(mysql_error());
    }

    redireciona("blog.php?ind_msg=1");
}

if($_POST['cmd'] == "edit")
{   
    $str = "UPDATE blog SET idcategoria = '$idcategoria', titulo = '$titulo', texto = '$texto' WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());

    if(!empty($imagem))
    {
        $str = "UPDATE blog SET imagem = '$imagem' WHERE codigo = '$codigo'";
        $rs  = mysql_query($str) or die(mysql_error());
    }

    redireciona("blog.php?ind_msg=2");
}

if($_GET['cmd'] == "del")
{
    $str = "SELECT * FROM blog WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
    $vet = mysql_fetch_array($rs);

    $imagem = $vet['imagem'];
        
    $dir = substr(getcwd(), 0, -6);
    $dir_upload = $dir . "/upload/";
    $dir_thumbnails = $dir . "/upload/thumbnails/";

    if($imagem)
    {
        @unlink($dir_upload.$imagem);
        @unlink($dir_thumbnails.$imagem);
    }

    $str = "DELETE FROM blog WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
    
    redireciona("blog.php?ind_msg=3");
}

if($_GET['cmd'] == "img_del")
{
    $str = "SELECT * FROM blog WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
    $vet = mysql_fetch_array($rs);

    $arquivo = $vet['imagem'];
        
    $dir = substr(getcwd(), 0, -6);
    $dir_upload = $dir . "/upload/";
    
    $ponteiro = opendir($dir_upload);

    while ($pArquivos = readdir($ponteiro))
    {
        if (strstr($pArquivos, $arquivo))
        {
            unlink($dir_upload.$pArquivos);
        }
    }

    $str = "UPDATE blog SET imagem = NULL WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());

    redireciona("blog.php?ind_msg=4&ind=2&codigo=$codigo");
}

if($_GET['ind_msg'] == 1)
    $msg = '<div class="alert success">Post cadastrado com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
    $msg = '<div class="alert success">Post editado com sucesso!</div>';
elseif($_GET['ind_msg'] == 3)
    $msg = '<div class="alert success">Post excluído com sucesso!</div>';
elseif($_GET['ind_msg'] == 4)
    $msg = '<div class="alert success">Imagem / foto do topo excluída com sucesso!</div>';

$str = "SELECT * FROM blog WHERE codigo = '$codigo'";
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

function excluir_imagem(codigo)
{
    if(confirm('Deseja realmente excluir este registro?')) {
        document.form.action = "blog.php?cmd=img_del&codigo="+codigo;
        document.form.submit();
    } else {  
        return false;
    }
}
</script>    
            
<section id="content">
<div class="g12">
    <h1>Blog</h1>
    <p></p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <input type="hidden" name="codigo" value="<?=$vet['codigo']?>">
    <fieldset>
        <section>
            <label for="idcategoria">Categoria</label>
            <div>
                <select name="idcategoria" id="idcategoria" required>
                    <option value="">Selecione uma categoria ...</option>
                    <?
                    $strE = "SELECT * FROM blog_categorias WHERE status = '1' ORDER BY nome";
                    $rsE  = mysql_query($strE) or die(mysql_error());

                    while($vetE = mysql_fetch_array($rsE))
                    {
                    ?>
                    <option value="<?=$vetE['codigo']?>" <?=($vetE['codigo'] == $vet['idcategoria']) ? 'selected' : ''?>><?=stripslashes($vetE['nome'])?></option>
                    <?
                    }
                    ?>
                </select>
            </div>
        </section>
        <section>
            <label for="text_field">Título:</label>
            <div><input type="text" id="titulo" name="titulo" value="<?=stripslashes($vet['titulo'])?>" required></div>
        </section>
        <section>
            <label for="textarea_auto">Texto:</label>
            <div>
                <textarea name="texto" id="texto" rows="10" cols="80" ><?=stripslashes($vet['texto'])?></textarea>
                <script>
                    // Replace the <textarea id="editor1"> with a CKEditor
                    // instance, using default configuration.
                    CKEDITOR.replace( 'texto' );
                </script>
            </div>
        </section>
        <section>
            <label for="imagem">                
                <?
                if(!empty($vet['imagem']))
                {
                    echo 'Imagem:<br><br><a href="../upload/'.$vet['imagem'].'" class="thickbox"><img src="../upload/'.$vet['imagem'].'" width="100" height="50" ></a>';
                    echo '<br />';
                    echo '<a class="btn i_trashcan icon small" title="excluir" href="javascript:;" onclick="excluir_imagem('.$vet['codigo'].')">excluir</a>';
                }
                else
                {
                    echo 'Imagem:';
                }
                ?>
            </label>
            <div>
                <input type="file" id="imagem" name="imagem" >
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
    $str = "SELECT A.*, B.nome AS categoria
        FROM blog A
        INNER JOIN blog_categorias B ON A.idcategoria = B.codigo
        ORDER BY A.titulo";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

    if($num > 0)
    {
    ?>  
    <h1>Lista de posts</h1>
    <p>Todos os posts cadastrados no sistema</p>
    <table class="datatable">
    <thead>
        <tr>
            <th>Título</th>
            <th>Categoria</th>
            <th>Imagem</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?
        while($vet = mysql_fetch_array($rs))
        {
        ?>
        <tr >
            <td><?=stripslashes($vet['titulo'])?></td>
            <td><?=stripslashes($vet['categoria'])?></td>
            <td>
                <?
                if($vet['imagem'])
                {
                    echo '<a href="../upload/'.$vet['imagem'].'" class="thickbox"><img src="../upload/'.$vet['imagem'].'" width="100" height="50" ></a>';
                }
                else
                {
                    echo 'Nenhuma imagem cadastrada!';
                }
                ?>
               
            </td>
            <td class="c">
                <a class="btn i_pencil icon small" title="editar" href="blog.php?ind=2&codigo=<?=$vet['codigo']?>" >editar</a>
                <a class="btn i_trashcan icon small" title="excluir" href="blog.php?cmd=del&codigo=<?=$vet['codigo']?>" onclick="javascript: if(!confirm('Deseja realmente excluir este registro?')) { return false }">excluir</a>
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
