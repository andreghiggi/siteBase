<?
session_start();

include("../s_acessos.php");
include("../funcoes.php");
include("verifica.php");

error_reporting(0);

if ($_GET['arquivo'] == TRUE)
{
    $file = 'upload/'.$_GET['arquivo'];
    header("Content-type: application/save");
    header('Content-Disposition: attachment; filename="' . $file . '"');
    header('Expires: 0');
    header('Pragma: no-cache');

    readfile($file);
    die;
}

if($_GET['ind'] == 2)
	$ind = 2;
else
	$ind = 1;

$idusuario = $_SESSION["adm_codigo"];
$adm_nome = $_SESSION["adm_nome"];

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


    <!-- Google Font and style definitions -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=PT+Sans:regular,bold">
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js"></script>

    <!-- Loading JS Files this way is not recommended! Merge them but keep their order -->

    <script type="text/javascript" src="thickbox/thickbox.js"></script>
    <link rel="stylesheet" href="thickbox/thickbox.css" type="text/css" media="screen" />

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

    <script src="ckeditor/ckeditor.js"></script>
    
    <script src="js/scripts.js"></script>
    <script src="js/ajax_funcoes.js"></script> 
    
    
</head>
<body>
	<br>
    <header>
    <div style="margin:15px;" class="imprimir">
        <h2><?=$n_empresa?></h2>
    </div>
    <div id="header">
        <!--ul id="headernav">
            <li>
            	<ul>
                    <li><a href="index.php">Sair</a></li>
            	</ul>
            </li>
        </ul-->
        <ul id="headernav">
            <li>
                <ul>
                    <li><a href="logoff.php">Sair</a></li>
                </ul>
            </li>
        </ul>
</header>
