<?
session_start();

include("../s_acessos.php");
include("../funcoes.php");
include("verifica.php");

$strS = "SELECT * FROM config_site ";
$rsS  = mysql_query($strS) or die(mysql_error());
$numS = mysql_num_rows($rsS);
$vetS = mysql_fetch_array($rsS);

$n_empresa = stripslashes($vetS['nome']);
$n_email = $vetS['email'];
?>
<!doctype html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    
    <title><?=$n_empresa?> - Gerenciador de Conteúdo</title>
    
    <meta name="description" content="">
    
    <meta name="description" content="">    
    
    <!-- Google Font and style definitions -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans:regular,bold">
    <link rel="stylesheet" href="css/style.css">

    <!-- include the skins (change to dark if you like) -->
    <link rel="stylesheet" href="css/light/theme.css" id="themestyle">
    <!-- <link rel="stylesheet" href="css/dark/theme.css" class="theme"> -->

    <!-- Apple iOS and Android stuff -->
    <meta name="apple-mobile-web-app-capable" content="no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-icon-precomposed" href="apple-touch-icon-precomposed.png">

    <!-- Apple iOS and Android stuff - don't remove! -->
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,maximum-scale=1">
    
    <!-- Use Google CDN for jQuery and jQuery UI -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js"></script>
    
    <!-- Loading JS Files this way is not recommended! Merge them but keep their order -->
    
    <!-- some basic functions -->
    <script src="js/functions.js"></script>
        
    <!-- all Third Party Plugins -->
    <script src="js/plugins.js"></script>
    <script src="js/editor.js"></script>
    <script src="js/calendar.js"></script>
    <script src="js/flot.js"></script>
    <script src="js/elfinder.js"></script>
    <script src="js/datatables.js"></script>
    
    <!-- all Whitelabel Plugins -->
    <script src="js/wl_Alert.js"></script>
    <script src="js/wl_Autocomplete.js"></script>
    <script src="js/wl_Breadcrumb.js"></script>
    <script src="js/wl_Calendar.js"></script>
    <script src="js/wl_Chart.js"></script>
    <script src="js/wl_Color.js"></script>
    <script src="js/wl_Date.js"></script>
    <script src="js/wl_Editor.js"></script>
    <script src="js/wl_File.js"></script>
    <script src="js/wl_Dialog.js"></script>
    <script src="js/wl_Fileexplorer.js"></script>
    <script src="js/wl_Form.js"></script>
    <script src="js/wl_Gallery.js"></script>
    <script src="js/wl_Number.js"></script>
    <script src="js/wl_Password.js"></script>
    <script src="js/wl_Slider.js"></script>
    <script src="js/wl_Store.js"></script>
    <script src="js/wl_Time.js"></script>
    <script src="js/wl_Valid.js"></script>
    <script src="js/wl_Widget.js"></script>
    
    <!-- configuration to overwrite settings -->
    <script src="js/config.js"></script>
        
    <!-- the script which handles all the access to plugins etc... -->
    <script src="js/script.js"></script>

    <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
</head>
<body>
    <div id="pageoptions">
        <ul>
            <li><a href="#" id="wl_config">Precisando de ajuda ou detectou alguma falha?</a></li>
        </ul>
        <div>
            <p>
                <b>Telefone:</b> +55   27 3227-9084<br>
                <b>Suporte:</b> +55   27 3026-0298<br>
                <b>Celular:</b> +55   27 99291-1345 <b>(TIM)</b><br><br>
                Há mais de 10 anos com sede própria<br>
                Avenida Nossa Senhora da Penha, 367<br>
                Ed Monte Sernio, sala 3030 - Santa Lúcia<br>
                Vitória - Esoírito Santo - CEP: 29055-131<br>
                (Ao lado do Bradesco, em frente ao Shopping Boulevard)
            </p>
        </div>
    </div>

    <header>
    <div style="margin:15px;" class="imprimir">
        <h2><?=$n_empresa?> - Gerenciador de Conteúdo</h2>
    </div>
    <div id="header">
        <ul id="headernav">
            <li>
                <ul>
                    <li><a href="index.php">Sair</a></li>
                </ul>
            </li>
        </ul>       
    </div>   
</header>
<script type="text/javascript">
$(document).ready(function(){ 
                           
    $(function() {
        $("#contentLeft ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
            var order = $(this).sortable("serialize") + '&action=updateRecordsListings'; 
            $.post("banners_ordenar_update.php", order, function(theResponse){
                $("#contentRight").html(theResponse);
            });                                                              
        }                                 
        });
    });

}); 
</script>
<style>
ul {
    margin: 0;
}

#contentLeft {
    float: left;
    width: 100%;
}

#contentLeft li {
    list-style: none;
    margin: 0 0 4px 0;
    padding: 10px;
    background-color:#fff;
    border: #CCCCCC solid 1px;
    color:#000;
}
</style>

<?
include('menu.inc.php');
?>

<section id="content">
<div class="g12">
    <h1><?=$n_empresa?> - Ordenar banners</h1>
    <p><button class="i_bended_arrow_left icon" onClick="javascript: history.go(-1);" >Voltar</button></p>

    <h4>Os banners abaixo estão dispostos em ordem crescente, basta arrastá-los para a posição que desejar e o sistema atualizará a ordem automaticamente.</h4>
    
    <?
    $str = "SELECT * FROM banners ORDER BY ordem";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

    if($num > 0)
    {
    ?>    	
    <fieldset>
    <div class="alert info" id="contentRight">
        Clique sobre o site que deseja reposicionar e arraste até a posição desejada.
    </div>
    <div id="contentLeft">            
        <ul>                
        <?
        $i = 0;
        $ind = -1;
        while($vet = mysql_fetch_array($rs))
        {
            $i++;

            $id = $vet['codigo'];
            $dados = '<img src="../upload/'.$vet['imagem'].'" style="width:100%; height:150px;">';
        ?>
            <li id="recordsArray_<?=$id?>"><?=$dados?></li>
        <?
        }
        ?>
        </ul>
    </div>    
    </fieldset>
    </form>
    
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
