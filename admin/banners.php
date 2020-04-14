<?php 
include('topo.inc.php'); 

if($_GET['codigo'] == TRUE) 
    $codigo = anti_injection($_GET['codigo']); 
else 
    $codigo = anti_injection($_POST['codigo']);

$vet_imagens = $_POST['imagens'];

if($_POST['cmd'] == "add")
{
    if(is_array($vet_imagens))
    {
        for($i = 0; $i < count($vet_imagens); $i++)
        {
            $imagem = altera_nome_imagem($vet_imagens[$i]);

            $str = "INSERT INTO banners (imagem) VALUES ('".$imagem."')";
            $rs  = mysql_query($str) or die(mysql_error());
        }
    }
    
    redireciona("banners.php?ind_msg=1");
}

if($_GET['cmd'] == "del")
{
    $str = "SELECT * FROM banners WHERE codigo = '$codigo'";
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

    $str = "DELETE FROM banners WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
    
    redireciona("banners.php?ind_msg=2");
}

if($_GET['ind_msg'] == 1)
    $msg = '<div class="alert success">Banner cadastrado com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
    $msg = '<div class="alert success">Banner cancelado com sucesso!</div>';

include('menu.inc.php'); 
?>  
<script language="javascript">
function valida()
{
    document.form.cmd.value = "add";
}

function excluir_imagem(codigo)
{
    if(confirm('Deseja realmente excluir esta imagem?')) {
        document.form.action = "banners.php?cmd=del&codigo="+codigo;
        document.form.submit();
    } else {  
        return false;
    }
}
</script>           
<section id="content">
<div class="g12">
    <h1>Banners</h1>
    <p><a class="btn i_wizard icon small" title="Ordenar banners" href="banners_ordenar.php" >Ordenar banners</a></p>

    <?=$msg?>
    
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <input type="hidden" name="codigo" value="<?=$vet['codigo']?>">
    <fieldset>
        <section>
            <label for="imagens">Upload de fotos:</label>     
            <div><input type="file" id="imagens" name="imagens" multiple required></div>
        </section>
        <?
        $str = "SELECT * FROM banners ORDER BY ordem";
        $rs  = mysql_query($str) or die(mysql_error());
        $num = mysql_num_rows($rs);

        if($num)
        {
        ?>
        <section>
            <label for="file_upload">Galeria de fotos:</label>
            <div>
                <ul class="gallery">
                    <?
                    while($vet = mysql_fetch_array($rs))
                    {
                    ?>
                    <li>
                        <a href="../upload/<?=$vet['imagem']?>" title=""><img src="../upload/<?=$vet['imagem']?>" width="116" height="116"></a>
                        <div id="excluir" style="margin:0 auto; margin-top:5%; text-align: center;"><button class="i_trashcan icon small" onClick="javascript: excluir_imagem('<?=$vet['codigo']?>');">excluir foto</button></div>
                    </li>
                    <?
                    }
                    ?>
                </ul>
            </div>
        </section>
        <?
        }
        ?>
        <section>
            <div><button class="i_tick icon" id="formsubmitswitcher" onClick="javascript: valida();" >Cadastrar</button></div>
        </section>
    </fieldset>
    </form>
</div>
</section>
        
<?php include('rodape.inc.php'); ?>    
