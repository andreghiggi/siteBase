<?php
session_start();

include("s_acessos.php");
include("funcoes.php");

$idproduto = anti_injection($_GET['idproduto']);
$idtamanho = anti_injection($_GET['idtamanho']);
$idcor = 0;

$strE = "SELECT * FROM produtos_estoque WHERE idproduto = '$idproduto' AND idtamanho = '$idtamanho'";
$rsE  = mysql_query($strE) or die(mysql_error());
$vetE = mysql_fetch_array($rsE);
?>

<br>
<p class="pquantityavailable">
<?
if($vetE['estoque'] <= 0)
{
?>
<span class="stock-fail">
	Esgotado
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
<a href="carrinho.php?cmd=add&idproduto=<?=$idproduto?>&idtamanho=<?=$idtamanho?>&idcor=<?=$idcor?>" class="btn btn-dark mt-2 mb-3">
	<span>Adicionar ao carrinho</span>
</a>
<?
}
?>