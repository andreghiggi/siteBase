<?
session_start();

include("../s_acessos.php");
include("../funcoes.php");

if($_POST['cmd'] == "logar")
{    
    $login 	= anti_injection($_POST['login']);
    $senha 	= anti_injection($_POST['senha']);	
    $senha_aux 	= md5($senha);

    $str = "SELECT * FROM usuarios WHERE login = '$login' AND senha = '$senha_aux' AND status = '1'";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

    if ($num > 0) 
    {
        $vet = mysql_fetch_array($rs);

        $idusuario = $vet["codigo"];
        $login = $vet["login"];
        $nome = $vet["nome"];

        $ip = $_SERVER['REMOTE_ADDR'];

        $_SESSION["adm_verifica"] = "adm_ecommerce";
        $_SESSION["adm_login"] = $login;
        $_SESSION["adm_nome"] = $nome;
        $_SESSION["adm_codigo"] = $idusuario;

        $str = "INSERT INTO acessos (idusuario, ip, data) VALUE ('$idusuario', '$ip', NOW())";
        $rs  = mysql_query($str) or die(mysql_error());
        
        redireciona("dashboard.php");
    } 
    else 
    {
        msg("Erro: O login e / ou senha fornecidos não estão cadastrados em nosso sistema");
        redireciona("index.php");
    }
}

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
	<meta name="author" content="revaxarts.com">
	
	
	<!-- Apple iOS and Android stuff -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="apple-touch-icon-precomposed" href="img/icon.png">
	<link rel="apple-touch-startup-image" href="img/startup.png">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,maximum-scale=1">
	
	<!-- Google Font and style definitions -->
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans:regular,bold">
	<link rel="stylesheet" href="css/style.css">
	
	<!-- include the skins (change to dark if you like) -->
	<link rel="stylesheet" href="css/light/theme.css" class="theme">
	
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<link rel="stylesheet" href="css/ie.css">
	<![endif]-->
	
	<!-- Use Google CDN for jQuery and jQuery UI -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js"></script>
	
	<!-- Loading JS Files this way is not recommended! Merge them but keep their order -->
	
	<!-- some basic functions -->
	<script src="js/functions.js"></script>
		
	<!-- all Third Party Plugins -->
	<script src="js/plugins.js"></script>
		
	<!-- Whitelabel Plugins -->
	<script src="js/wl_Alert.js"></script>
	<script src="js/wl_Dialog.js"></script>
	<script src="js/wl_Form.js"></script>
		
	<!-- configuration to overwrite settings -->
	<script src="js/config.js"></script>
		
	<!-- the script which handles all the access to plugins etc... -->
	<script src="js/login.js"></script>
    <script language="javascript" src="../scripts.js"></script>
</head>
<body id="login">
<header>
    <div style="margin:12px;">
        <h5><?=$n_empresa?></h5>
        <p>Gerenciador de Conteúdo</p>
    </div>
</header>

<section id="content">
<form name="form" id="form" method="post">
	<input type="hidden" name="cmd" id="cmd" value="logar">
	<fieldset>
    	<section><label for="username">Login</label>
            <div><input type="text" id="login" name="login" required autofocus ></div>
        </section>
        <section><label for="password">Senha <!--<a href="#">lost password?</a>--></label>
            <div><input type="password" id="senha" name="senha" required ></div>
        </section>
        <section>
            <div><button class="fr" >Acessar</button></div>
        </section>
    </fieldset>
</form>
</section>
<footer><?=$n_empresa?> - Gerenciador de Conteúdo</footer>
</body>
</html>