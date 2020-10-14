<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once("../s_acessos.php");
require_once("../funcoes.php");

$data = json_decode($_POST['data']);

$error = 0;
foreach($data as $item){
    mysql_query('update produtos_imagens set ordem = "'.$item->posicao.'" where imagem = "'.$item->img.'"');
    if(mysql_error() != '')$error = 1;
}
echo $error;