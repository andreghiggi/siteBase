<?php 
include('topo.inc.php'); 

if($_GET['codigo'] == TRUE)
	$codigo = anti_injection($_GET['codigo']);
else
	$codigo = anti_injection($_POST['codigo']);

$titulo = addslashes($_POST['titulo']);
$url = $_POST['url'];
$tipo = $_POST['tipo'];
$status = $_POST['status'];
$imagem	= altera_nome_imagem($_POST['imagem'][0]);

if($_POST['cmd'] == "add")
{
    $str = "INSERT INTO publicidade (titulo, url, tipo, status, imagem) VALUES ('$titulo', '$url', '$tipo', '$status', '$imagem')";
    $rs  = mysql_query($str) or die(mysql_error());
    
    redireciona("publicidade.php?ind_msg=1");
}

if($_POST['cmd'] == "edit")
{   
    $str = "UPDATE publicidade SET titulo = '$titulo', url = '$url', tipo = '$tipo', status = '$status' WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
    
    if(!empty($imagem))
    {
        $str = "UPDATE publicidade SET imagem = '$imagem' WHERE codigo = '$codigo'";
        $rs  = mysql_query($str) or die(mysql_error());
    }

    redireciona("publicidade.php?ind_msg=2");
}

if($_GET['cmd'] == "del")
{	
    $str = "SELECT * FROM publicidade WHERE codigo = '$codigo'";
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

	$str = "DELETE FROM publicidade WHERE codigo = '$codigo'";
	$rs  = mysql_query($str) or die(mysql_error());
	
	redireciona("publicidade.php?ind_msg=3");
}

if($_GET['ind_msg'] == 1)
	$msg = '<div class="alert success">Publicidade cadastrada com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
	$msg = '<div class="alert success">Publicidade editada com sucesso!</div>';
elseif($_GET['ind_msg'] == 3)
    $msg = '<div class="alert success">Publicidade excluída com sucesso!</div>';

$str = "SELECT * FROM publicidade WHERE codigo = '$codigo'";
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
    <h1>Publicidade</h1>
    <p>Cadastre aqui as publicidades que aparecerão no topo e na lateral do site.</p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <input type="hidden" name="codigo" value="<?=$vet['codigo']?>">    
    <fieldset>
        <section>
            <label for="file_upload">                
                <?
                if(!empty($vet['imagem']))
                {
                    echo 'Imagem:<br><br><a href="../upload/'.$vet['imagem'].'" class="thickbox"><img src="../upload/thumbnails/'.$vet['imagem'].'" ></a>';
                }
                else
                {
                    echo 'Imagem:';
                }
                ?>
            </label>
            <div>
                <input type="file" id="imagem" name="imagem" <?=($ind == 1) ? 'required' : '' ?>>
            </div>
        </section>
        <section>
            <label for="text_field">Título:</label>
            <div><input type="text" id="titulo" name="titulo" required value="<?=stripslashes($vet['titulo'])?>" ></div>
        </section>
        <section>
            <label for="text_field">Link:</label>
            <div><input type="url" id="url" name="url" placeholder="http://" value="<?=$vet['url']?>" ></div>
        </section>
        <section>
            <label for="textarea_auto">Tipo:</span></label>
            <div>
                <input type="radio" id="tipo_1" name="tipo" <?=($vet['tipo'] == FALSE || $vet['tipo'] == 1) ? "checked" : "" ?> value="1"><label for="tipo_1" class="radio">Topo do site</label>
                <input type="radio" id="tipo_2" name="tipo" <?=($vet['tipo'] == 2) ? "checked" : "" ?> value="2"><label for="tipo_2" class="radio">Lateral do site</label>
            </div>
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
    $str = "SELECT * FROM publicidade ORDER BY titulo";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

	if($num > 0)
	{
	?>
	<h1>Lista de publicidades</h1>
    <p>Todas as publicidades cadastradas no sistema.</p>
    <table >
        <thead>
            <tr>
                <th colspan="3">LEGENDA - Status da publicidade</th>
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
            <th>Título</th>
            <th>Tipo</th>
            <th>Imagem</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
		<?
        while($vet = mysql_fetch_array($rs))
        {
            $titulo = stripslashes($vet['titulo']);
            if($vet['url'])
                $titulo = "<a href='".$vet['url']."' target='_blank'>".stripslashes($vet['titulo'])."</a>";

            if($vet['status'] != 1)
                $class = 'class="gradeA"';
            else
                $class = 'class="gradeU"';
        ?>
        <tr <?=$class?>>
            <td><?=$titulo?></td>
            <td><?=($vet['tipo'] == 1) ? 'Topo' : 'Lateral'?></td>
            <td>
                <?
                if($vet['imagem'])
                {
                ?>
                <a href="../upload/<?=$vet['imagem']?>" class="thickbox"><img src="../upload/thumbnails/<?=$vet['imagem']?>"></a>
                <?
                }
                else
                {
                    echo 'Nenhuma imagem cadastrada';
                }
                ?>
            </td>
            <td class="c">
                <a class="btn i_pencil icon small" title="editar" href="publicidade.php?ind=2&codigo=<?=$vet['codigo']?>" >editar</a>
                <a class="btn i_trashcan icon small" title="excluir" href="publicidade.php?cmd=del&codigo=<?=$vet['codigo']?>" onclick="javascript: if(!confirm('Deseja realmente excluir este registro?')) { return false }">excluir</a>
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
