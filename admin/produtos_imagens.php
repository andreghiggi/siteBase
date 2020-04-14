<?php 
include('topo.inc.php'); 

if($_GET['idproduto'] == TRUE)
    $idproduto = anti_injection($_GET['idproduto']);
else
    $idproduto = anti_injection($_POST['idproduto']);

if($_GET['idcor'] == TRUE)
    $idcor = anti_injection($_GET['idcor']);
else
    $idcor = anti_injection($_POST['idcor']);

if($_GET['idimagem'] == TRUE)
    $idimagem = anti_injection($_GET['idimagem']);
else
    $idimagem = anti_injection($_POST['idimagem']);

$vet_imagem = $_POST['imagens'];

if($_POST['cmd'] == "add")
{
    if(is_array($vet_imagem))
    {
        for($i = 0; $i < count($vet_imagem); $i++)
        {
            $imagem = altera_nome_imagem($vet_imagem[$i]);

            $status = 0;
            if(!$i)
                $status = 1;

            $str = "INSERT INTO produtos_imagens (idproduto, idcor, imagem, status) VALUES ('$idproduto', '$idcor', '$imagem', '$status')";
            $rs  = mysql_query($str) or die(mysql_error());
        }
    }
    
    redireciona("produtos_imagens.php?ind_msg=1&idproduto=$idproduto&idcor=$idcor");
}

if($_POST['cmd'] == "edit")
{  
    if(is_array($vet_imagem))
    {
        $strI = "SELECT * FROM produtos_imagens WHERE idproduto = '$codigo' AND idcor = '$idcor' AND status = '1'";
        $rsI  = mysql_query($strI) or die(mysql_error());
        $numI = mysql_num_rows($rsI);

        for($i = 0; $i < count($vet_imagem); $i++)
        {
            $imagem = altera_nome_imagem($vet_imagem[$i]);

            $status = 0;
            if(!$i && !$numI)
                $status = 1;

            $str = "INSERT INTO produtos_imagens (idproduto, idcor, imagem, status) VALUES ('$idproduto', '$idcor', '$imagem', '$status')";
            $rs  = mysql_query($str) or die(mysql_error());
        }
    }
    
    redireciona("produtos_imagens.php?ind_msg=1&idproduto=$idproduto&idcor=$idcor");
}

if($_GET['cmd'] == "img_destaque")
{   
    $str = "UPDATE produtos_imagens SET status = '0' WHERE idproduto = '$idproduto' AND idcor = '$idcor'";
    $rs  = mysql_query($str) or die(mysql_error());

    $str = "UPDATE produtos_imagens SET status = '1' WHERE codigo = '$idimagem'";
    $rs  = mysql_query($str) or die(mysql_error());

    redireciona("produtos_imagens.php?ind_msg=3&idproduto=$idproduto&idcor=$idcor");
}

if($_GET['cmd'] == "img_del")
{
    $str = "SELECT * FROM produtos_imagens WHERE codigo = '$idimagem'";
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

    $str = "DELETE FROM produtos_imagens WHERE codigo = '$idimagem'";
    $rs  = mysql_query($str) or die(mysql_error());

    redireciona("produtos_imagens.php?ind_msg=4&idproduto=$idproduto&idcor=$idcor");
}

if($_GET['ind_msg'] == 1)
	$msg = '<div class="alert success">Imagens cadastradas com sucesso!</div>';
elseif($_GET['ind_msg'] == 3)
    $msg = '<div class="alert success">Imagem / foto marcada como destaque!</div>';
elseif($_GET['ind_msg'] == 4)
    $msg = '<div class="alert success">Imagem / foto excluída com sucesso!</div>';

$strP = "SELECT * FROM produtos WHERE codigo = '$idproduto'";
$rsP  = mysql_query($strP) or die(mysql_error());
$vetP = mysql_fetch_array($rsP);

$strC = "SELECT * FROM cores WHERE codigo = '$idcor'";
$rsC  = mysql_query($strC) or die(mysql_error());
$vetC = mysql_fetch_array($rsC);
   
include('menu.inc.php');
?>   
<script language="javascript">
function valida()
{   
    document.form.cmd.value = "add";
}

function excluir_imagem(idproduto, idcor, idimagem)
{
    if(confirm('Deseja realmente excluir este registro?')) {
        document.form.action = "produtos_imagens.php?cmd=img_del&idproduto="+idproduto+"&idcor="+idcor+"&idimagem="+idimagem;
        document.form.submit();
    } else {  
        return false;
    }
}

function imagem_destaque(idproduto, idcor, idimagem)
{
    if(confirm('Deseja realmente tornar esta imagem destaque?')) {
        document.form.action = "produtos_imagens.php?cmd=img_destaque&idproduto="+idproduto+"&idcor="+idcor+"&idimagem="+idimagem;
        document.form.submit();
    } else {  
        return false;
    }
}
</script>	    
			
<section id="content">
<div class="g12">
    <h1>Upload de imagens > <?=stripslashes($vetP['nome'])?> > <?=stripslashes($vetC['titulo'])?></h1>
    <p></p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <input type="hidden" name="idproduto" value="<?=$vet['idproduto']?>">
    <input type="hidden" name="idcor" value="<?=$vet['idcor']?>"> 
    <fieldset>
        <section>
            <label for="file_upload">Upload de fotos:</label>
            <div><input type="file" id="imagens" name="imagens" multiple ></div>
        </section>        
        <?
        $strF = "SELECT * FROM produtos_imagens WHERE idproduto = '$idproduto' AND idcor = '$idcor'";
        $rsF  = mysql_query($strF) or die(mysql_error());
        $numF = mysql_num_rows($rsF);

        if($numF)
        {
        ?>
        <section>
            <label for="file_upload">Galeria de fotos:</label>
            <div>
                <ul class="gallery">
                    <?
                    while($vetF = mysql_fetch_array($rsF))
                    {
                        $class = '';
                        if($vetF['status'] == 1) {
                            $class = 'style="border: 1px solid red;"';
                            $str_imagem = 'imagem destaque';
                            $icon_imagem = 'i_tick';
                        } else {
                            $str_imagem = 'tornar destaque';
                            $icon_imagem = 'i_flag';
                        }
                    ?>
                    <li>
                        <a href="../upload/<?=$vetF['imagem']?>" title=""><img src="../upload/<?=$vetF['imagem']?>" width="116" height="116" <?=$class?>></a>
                        <div id="<?=$str_imagem?>" style="margin:0 auto; margin-top:5%; text-align: center;"><button class="<?=$icon_imagem?> icon small" onClick="javascript: imagem_destaque('<?=$idproduto?>', '<?=$idcor?>', '<?=$vetF['codigo']?>');"><?=$str_imagem?></button></div>
                        <div id="excluir" style="margin:0 auto; margin-top:5%; text-align: center;"><button class="i_trashcan icon small" onClick="javascript: excluir_imagem('<?=$idproduto?>', '<?=$idcor?>', '<?=$vetF['codigo']?>');">excluir foto</button></div>
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
            <div><button class="i_tick icon" onClick="javascript: valida();">Cadastrar</button></div>            
        </section>
    </fieldset>
	</form>
   	<!-- end form -->
</div>
</section>
<!-- end div #content -->
        
        
<?php include('rodape.inc.php'); ?>    
