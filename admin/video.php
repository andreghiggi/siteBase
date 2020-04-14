<?php 
include('topo.inc.php'); 

if($_GET['codigo'] == TRUE) 
    $codigo = anti_injection($_GET['codigo']); 
else 
    $codigo = anti_injection($_POST['codigo']);

$url = $_POST['url'];

if($_POST['cmd'] == "add")
{
    $str = "INSERT INTO video (url) VALUES ('$url')";
    $rs  = mysql_query($str) or die(mysql_error());

    redireciona("video.php?ind_msg=1");
}

if($_POST['cmd'] == "edit")
{
    $str = "UPDATE video SET url = '$url' ";
    $rs  = mysql_query($str) or die(mysql_error());

    redireciona("video.php?ind_msg=2");
}

if($_GET['ind_msg'] == 1)
    $msg = '<div class="alert success">Vídeo cadastrado com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
    $msg = '<div class="alert success">Vídeo editado com sucesso!</div>';
    
$str = "SELECT * FROM video ";
$rs  = mysql_query($str) or die(mysql_error());
$num = mysql_num_rows($rs);
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
    <h1>Vídeo</h1>
    <p></p>

    <?=$msg?>
    
    <script>
    	function alterarUrlVideo(self){
    		let url = $(self).val().split('?')[1].split('&');
    		for(let i=0; i < url.length; i++){
    			if(url[i][0] == 'v'){
    				let key = url[i].split('=')[1];
    				$(self).val('https://www.youtube.com/embed/'+key+'?autoplay=1&loop=1');
    				return;
    			}
    		}
    		console.log(url);
    	}	
    </script>
    
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <input type="hidden" name="codigo" value="<?=$vet['codigo']?>">    
    <fieldset>
        <section>
            <label for="text_field">Url (youtube):</label>
            <div><input type="url" id="url" name="url" value="<?=$vet['url']?>" onfocusout="alterarUrlVideo(this)" required></div>
        </section>
        <section>
            <?
            if(!$num)
            {
            ?>
                <div><button class="i_tick icon" id="formsubmitswitcher" onClick="javascript: valida(1);" >Cadastrar</button></div>
            <?
            }
            else
            {
            ?>
                <div><button class="i_refresh_3 icon" id="formsubmitswitcher" onClick="javascript: valida(2);" >Alterar</button></div>
            <?
            }
            ?>
        </section>
    </fieldset>
    </form>

    <?
    if($num)
    {
    ?>
    <iframe id="ytplayer" type="text/html" width="720" height="405" src="<?php echo $vet['url']?>" frameborder="0" allowfullscreen>
    <?
    }
    ?>
</div>
</section>
        
<?php include('rodape.inc.php'); ?>    
