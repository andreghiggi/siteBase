<?php 
include('topo.inc.php'); 

$idcadastro = anti_injection($_GET['idcadastro']);

$str = "SELECT DISTINCT * FROM cadastros WHERE codigo = '$idcadastro'";
$rs  = mysql_query($str) or die(mysql_error());
$num = mysql_num_rows($rs);
$vet = mysql_fetch_array($rs);

include('menu.inc.php'); 
?>
<section id="content">
<div class="g12">
    <h1>Dados pessoais</h1>
    <p></p>        
    
    <b>Nome: <?=stripslashes($vet['nome'].' '.$vet['sobrenome'])?></b><br>
    <b>Email:</b> <?=$vet['email']?><br>
    <b>Telefone:</b> <?=$vet['telefone']?><br>
    <b>Telefone (adicional):</b> <?=$vet['telefone_02']?><br>
    <b>Data de nascimento:</b> <?=ConverteData($vet['data_nascimento'])?><br>
    <b>Data de cadastro:</b> <?=ConverteData($vet['data_cadastro'])?><br><br>
    
    <h1>Endereço de entrega</h1>
    <p></p>

    <?
    $strE = "SELECT * FROM cadastros_enderecos WHERE idcadastro = '$idcadastro'";
    $rsE  = mysql_query($strE) or die(mysql_error());
    $vetE = mysql_fetch_array($rsE);

    $endereco = $vetE['endereco'].', '.$vetE['numero'];

    if($vetE['complemento'])
        $endereco .= ', '.$vetE['complemento'];
    ?>
    Endereço: <?=$endereco?><br>
    <?
    if(!empty($vetE['referencia']))
    {
    ?>
    Ponto de referência: <?=$vetE['referencia']?><br>
    <?
    }
    ?>
    Bairro: <?=$vetE['bairro']?><br>
    Cidade: <?=$vetE['cidade']?><br>
    Estado: <?=$vetE['estado']?><br>
    CEP: <?=$vetE['cep']?><br><br>           
</div>
</section>
<!-- end div #content -->
        
        
<?php include('rodape.inc.php'); ?>    
