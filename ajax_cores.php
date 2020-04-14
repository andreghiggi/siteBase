<?php
session_start();

include("s_acessos.php");
include("funcoes.php");

$idproduto = anti_injection($_GET['idproduto']);
$idtamanho = anti_injection($_GET['idtamanho']);
?>
<div class="color">
	<label>Cor :</label>
	<select name="idcor" id="idcor" onchange="javascript: exibe_galerias(<?=$idproduto?>, <?=$idtamanho?>, this.value);">	
		<option value="" >Selecione uma cor</option>
		<?	
		if($idtamanho)
		{
			$strT = "SELECT DISTINCT B.*
				FROM produtos_estoque A
				INNER JOIN cores B ON A.idcor = B.codigo
				WHERE idproduto = '$idproduto'
				AND idtamanho = '$idtamanho'
				GROUP BY idcor";
			$rsT  = mysql_query($strT) or die(mysql_error());

			while($vetT = mysql_fetch_array($rsT))
			{
		?>
		<option value="<?=$vetT['codigo']?>"><?=stripslashes($vetT['titulo'])?></option>
		<?
			}
		}
		?>
	</select>
</div>
