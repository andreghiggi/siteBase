<?

$user = 'siteBase_user';
$senha = '80981b6e15d57d6c3e06f0999789de0b';
$database = 'siteBase_data';

$banco = @mysqli_connect('localhost', $user, $senha, $database) or mysqli_error($conexao);

date_default_timezone_set("America/Sao_Paulo");

?>
