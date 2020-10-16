<?php
session_start();

include("funcoes.php");
include("s_acessos.php");

$idproduto = anti_injection($_GET['idproduto']);
$idtamanho = anti_injection($_GET['idtamanho']);
$idcor = 0;

$strE = "SELECT * FROM produtos_estoque WHERE idproduto = '$idproduto' AND idtamanho = '$idtamanho'";
$rsE  = mysql_query($strE) or die(mysql_error());
$vetE = mysql_fetch_array($rsE);

$vet = mysql_fetch_assoc(mysql_query('select tempProd from produtos where codigo = '.$idproduto));

?>

<br>
<p class="pquantityavailable">
<?
if($vetE['estoque'] <= 0)
{
?>
<span class="stock-fail">
	Tempo de produção <?=$vet['tempProd'];?> dias
</span>
<?
}
?>
</p>

<br>
<?
if($vetE['estoque'] > 0)
{
?>
<!--<a href="carrinho.php?cmd=add&idproduto=<?=$idproduto?>&idtamanho=<?=$idtamanho?>&idcor=<?=$idcor?>" class="btn btn-dark mt-2 mb-3">
	<span>Adicionar ao carrinho</span>
</a>-->
<?
}
?>