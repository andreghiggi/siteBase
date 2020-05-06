<?php
session_start();

include("s_acessos.php");
include("funcoes.php");

$idproduto = anti_injection($_GET['idproduto']);
$idcor = anti_injection($_GET['idcor']);
$idtamanho = anti_injection($_GET['idtamanho']);

$strI = "SELECT * FROM produtos_imagens WHERE idproduto = '$idproduto' ORDER BY status DESC";
$rsI  = mysql_query($strI) or die(mysql_error());
$numI = mysql_num_rows($rsI);

if($numI > 0)
{
    $array_imagens = array();
    while($vetI = mysql_fetch_array($rsI))
    {
        $array_imagens[] = $vetI['imagem'];
    }
}
?>
<div class="larg-img">
	<div class="tab-content">
		<?
        for($i = 0; $i < count($array_imagens); $i++)
        {
        	$class = 'class="tab-pane fade"';
        	if(!$i)
        		$class = 'class="tab-pane active"';
        ?>
		<div id="image<?=$i?>" <?=$class?>>
			<a href="">
				<img alt="<?=stripslashes($vet['nome'])?>" src="upload/<?=$array_imagens[$i]?>">
			</a>
			<a class="fancybox" data-fancybox-group="group" href="upload/<?=$array_imagens[$i]?>">
				Ampliar
				<i class="fa fa-search-plus"></i>
			</a>
		</div>
		<?
		}
		?>
	</div>
</div>
<div class="thumnail-image">
	<ul class="tab-menu">
		<?
        for($i = 0; $i < count($array_imagens); $i++)
        {
        	$class = '';
        	if(!$i)
        		$class = 'class="active"';
        ?>
		<li <?=$class?>><a data-toggle="tab" href="#image<?=$i?>"><img alt="" src="upload/thumbnails/<?=$array_imagens[$i]?>"></a></li>
		<?
		}
		?>
	</ul>
</div>
<div class="print-mail">
	<span>
		<i class="fa fa-print"></i>
		<a href="javascript:;" onclick="javascript: window.print();">Imprimir</a>
	</span>
</div>

<?
$strE = "SELECT * FROM produtos_estoque WHERE idproduto = '$idproduto' AND idcor = '$idcor' AND idtamanho = '$idtamanho'";
$rsE  = mysql_query($strE) or die(mysql_error());
$vetE = mysql_fetch_array($rsE);
?>

<br>
<p class="pquantityavailable">
<?
if($vetE['estoque'] > 0)
{
?>
<span><?=$vetE['estoque']?> Itens</span>
<span class="stock-success">
	Em estoque
</span>
<?
}
else
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
<a href="carrinho.php?cmd=add&idproduto=<?=$idproduto?>&idtamanho=<?=$idtamanho?>&idcor=<?=$idcor?>" class="add-to-cart">
	<span>+ Adicionar ao carrinho</span>
</a>
<?
}
?>

<br>
<? echo number_format($vetE['valor'], 2, ',', '.');?>