<?php
include("../s_acessos.php");
include("../funcoes.php");

$idcategoria = anti_injection($_GET['idcategoria']);

$str = "SELECT * FROM produtos_subcategorias WHERE idcategoria = '$idcategoria' AND status = '1' ORDER BY nome";
$rs  = mysql_query($str) or die(mysql_error());
$num = mysql_num_rows($rs);

if($num)
{
?>
<select name="idsubcategoria" id="idsubcategoria" >
    <option value="">Selecione uma subcategoria ...</option>
    <?
    while($vet = mysql_fetch_array($rs))
    {
    ?>
    <option value="<?=$vet['codigo']?>" ><?=stripslashes($vet['nome'])?></option>
    <?
    }
    ?>
</select>
<?
}
else
{
?>
<select name="idsubcategoria" id="idsubcategoria" >
    <option value="">Selecione uma subcategoria ...</option>
</select>
<?  
}
?>