<?
$str = "SELECT * FROM produtos_categorias WHERE status = '1' ORDER BY nome";
$rs  = mysql_query($str) or die(mysql_error());
$num = mysql_num_rows($rs);
  
if($num > 0)
{
?>
<!-- category-menu-area start-->
<div class="category-menu-area hidden-sm">
	<div class="category-title">
		<h2>Categorias</h2>
	</div>
	<div class="category-menu" id="cate-toggle">
		<ul class="d-flex flex-wrap list-group-flush list-group-horizontal">
			<?
			while($vet = mysql_fetch_array($rs))
			{
				$strS = "SELECT * FROM produtos_subcategorias WHERE idcategoria = '".$vet['codigo']."' AND status = '1' ORDER BY nome";
			    $rsS  = mysql_query($strS) or die(mysql_error());
			    $numS = mysql_num_rows($rsS);

			    if(!$numS)
			    {
			?>
			<li class="list-group-item">
				<a href="loja.php?idcategoria=<?=$vet['codigo']?>"><?=stripslashes($vet['nome'])?></a>
			</li>
			<?
				}
				else
				{
			?>
			<li class="has-sub">
				<a href="#"><?=stripslashes($vet['nome'])?></a>
				<ul class="category-sub">
					<?
					while($vetS = mysql_fetch_array($rsS))
					{
					?>
					<li><a href="loja.php?idsubcategoria=<?=$vetS['codigo']?>"><?=stripslashes($vetS['nome'])?></a></li>
					<?
					}
					?>
				</ul>
			</li>
			<?
				}
			}
			?>
		</ul>
	</div>
</div>
<!-- category-menu-area end-->
<?
}
?>

<?
$strM = "SELECT A.codigo, A.titulo, COUNT(B.codigo) AS t_marcas
	FROM marcas A
	INNER JOIN produtos B ON A.codigo = B.idmarca
	WHERE B.status = '1'
	GROUP BY A.codigo
	ORDER BY A.titulo";
$rsM  = mysql_query($strM) or die(mysql_error());
$numM = mysql_num_rows($rsM);

if($numM)
{
?>
<!-- product-filter-attribute start-->
<div class="attribute-area">	
	<div class="product-filter-attribute">
		<div class="area-title">
			<h2>Marcas</h2>
		</div>
		<div class="list-item">
			<ul>
				<?
				while($vetM = mysql_fetch_array($rsM))
				{
					$_idmarca = $vetM['codigo'];
				?>
				<li><a href="loja.php?idmarca=<?=$_idmarca?>"><?=stripslashes($vetM['titulo'])?> (<?=$vetM['t_marcas']?>)</a></li>
				<?
				}
				?>
			</ul>
		</div>
	</div>
</div>
<!-- product-filter-attribute end-->
<?
}
?>

<?
$str = "SELECT * FROM publicidade WHERE tipo = '2' AND status = '1' ORDER BY RAND() LIMIT 1";
$rs  = mysql_query($str) or die(mysql_error());
$num = mysql_num_rows($rs);

if($num > 0)
{
?>
<!-- add-banner-slider start-->
<div class="add-banner-slider-area">
	<div class="add-banner-carsuol">
		<?
        while($vet = mysql_fetch_array($rs))
        {
        ?>
		<a href="<?=($vet['url']) ? $vet['url'] : 'javascript:;'?>" target="_blank">
			<img src="upload/<?=$vet['imagem']?>" alt="<?=stripslashes($vet['titulo'])?>">
		</a>
		<?
		}
		?>
	</div>
</div>
<!-- add-banner-slider start-->
<?
}
?>

<?
/**********
BOX - TAGS
***********/
box_tags();

/*************************
BOX - PRODUTOS EM DESTQUE
**************************/
//box_destaque();
?>	
