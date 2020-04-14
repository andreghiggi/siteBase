<?php 
include('topo.inc.php');

$idpedido = anti_injection($_GET['idpedido']);

if(isset($_POST['var_rastreio'])){
	mysql_query('update pedidos set codRastreio = "'.$_POST['var_rastreio'].'" where codigo = '.$idpedido);
	echo mysql_error();
	//header('Location: pedidos_ver.php?idpedido='.$idpedido);
}

$str = "SELECT DISTINCT * FROM pedidos WHERE codigo = '$idpedido'";
$rs  = mysql_query($str) or die(mysql_error());
$num = mysql_num_rows($rs);
$vet = mysql_fetch_array($rs);

$tel = mysql_fetch_array(mysql_query("select telefone from cadastros where codigo = ".$vet['idcadastro']))['telefone'];
//var_dump(mysql_error());

if($vet['entrega'] == 1)
	$frete = true;

include('menu.inc.php'); 
?>
<section id="content">
<div class="g12">
    <h1>Pedido #<?=$vet['idcarrinho']?></h1>
    <p></p>
    <p>Nesta seção você consulta o status de seu pedido e a data estimada para o envio.</p>         
    
    <?
    if($vet['status'] == 0)
        $status = 'Pendente';
    elseif($vet['status'] == 1)
        $status = 'Confirmado / Pago';
    elseif($vet['status'] == 2)
        $status = 'Enviado / Entregue';
    elseif($vet['status'] == 3)
        $status = 'Cancelado';

    $strF = "SELECT * FROM frete WHERE idpedido = '$idpedido'";
    $rsF  = mysql_query($strF) or die(mysql_error());
    $numF = mysql_num_rows($rsF);
    $vetF = mysql_fetch_array($rsF);

    $data_entrega = date("d/m/Y", mktime(0, 0, 0, substr($vet['data_geracao'], 5, 2), substr($vet['data_geracao'], 8, 2) + $vetF['prazo'], substr($vet['data_geracao'], 0, 4)));
    $total = $vet['valor'] + $vetF['valor'];
    ?>

    <b>Pedido #<?=$vet['idcarrinho']?></b><br>
    <b>Data do pedido:</b> <?=ConverteData($vet['data_geracao'])?><br><br>
    <b>Endereço de entrega</b><br>

    <?
    $strE = "SELECT * FROM pedidos_enderecos WHERE idpedido = '$idpedido'";
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
    CEP: <?=$vetE['cep']?><br>
    Telefone: <?= $tel;?><br><br>
    
    <b>Subtotal:</b> R$ <?php echo ($frete)? number_format($vet['valor']-25.00, 2, ',', '.') : number_format($vet['valor'], 2, ',', '.');?><br>
    

    <b>Frete:</b> R$ <?php echo ($frete)? '25,00':'0,00';?><br>

    <b>TOTAL:</b> R$ <?=number_format($total, 2, ',', '.')?><br><br>
    
    <b>Status:</b> <?=$status?><br>
    
    <br><br>
    <form method="POST" style="width:20%;">
    	<label>Codigo de Rastreio</label>
    	<input type="text" class="form-control" name="var_rastreio" maxlength="13" value="<?php echo $vet['codRastreio']?>">
    	<br><br>
    	<input type="submit" value="Salvar">
    </form>

    <?
    if($vet['status'] == 2 && $numF > 0)
        echo '<b>Data do envio:</b> '.ConverteData($vet['data_envio']).'<br>';
    ?>

    <?
    $strP = "SELECT DISTINCT A.*, B.numero, C.titulo AS cor 
        FROM pedidos_detalhe A
        LEFT JOIN tamanhos B ON A.idtamanho = B.codigo
        LEFT JOIN cores C ON A.idcor = C.codigo
        WHERE A.idpedido = '$idpedido'
        ORDER BY A.descricao";
    $rsP  = mysql_query($strP) or die(mysql_error());
$numP = mysql_num_rows($rsP);

    if($numP)
    {
    ?>
    <br><br>
    <p>Abaixo a lista de produtos selecionados na compra.</p>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Qtde</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        <?
        while($vetP = mysql_fetch_array($rsP))
        {
            $imagem = img_produto_destaque($vetP['idproduto'], $vetP['idcor']);
            $valor += $vetP['valor'];
        ?>
            <tr>
                <td><a href="../upload/<?=$imagem?>" class="thickbox"><img src="../upload/thumbnails/<?=$imagem?>"></a></td>
                <td>
                    <?=stripslashes($vetP['descricao'])?>
                    <?
                    $numero = ($vetP['numero']) ? $vetP['numero'] : 'Não informado';
                    $cor = ($vetP['cor']) ? $vetP['cor'] : 'Não informado';

                    if($vetP['idtamanho'] > 0 || $vetP['idcor'] > 0)
                        echo '<br>Tamanho: '.$numero.' - Cor: '.$cor.'<br><br>';
                    ?>
                </td>
                <td>R$ <?=number_format(($vetP['valor'] / $vetP['qtde']), 2, ',', '.')?></td>
                <td><?=$vetP['qtde']?></td>
                <td>R$ <?=number_format($vetP['valor'], 2, ',', '.')?></td>                
            </tr>                                   
        <?
        }
        ?>
        </tbody>
    </table>
    <?
    }
    ?>              
</div>
</section>
<!-- end div #content -->
        
        
<?php include('rodape.inc.php'); ?>    
