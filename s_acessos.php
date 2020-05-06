<?

$user = 'siteBase_user';
$senha = '80981b6e15d57d6c3e06f0999789de0b';
$database = 'siteBase_data';

/* PHP 5.6+ */
//$banco = @mysqli_connect('localhost', $user, $senha, $database) or mysqli_error($conexao);

/* PHP 5.6- */
$banco = mysql_connect('localhost', $user, $senha) or mysql_error($conexao);
mysql_select_db($database);

date_default_timezone_set("America/Sao_Paulo");

?>
