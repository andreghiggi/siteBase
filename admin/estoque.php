<?php 
include('topo.inc.php'); 
include('menu.inc.php');
?>
<section id="content">
<div class="g12">
    <h1>Relatório de estoque</h1>
    <p>Lista de produtos ATIVOS x qtde em estoque</p>

    <?=$msg?>
    
    <?
    $str = "SELECT * FROM produtos WHERE status = '1' ORDER BY nome ASC";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

    if($num > 0)
    {
    ?>
    <p><?=$num?> registros encontrados</p>
    
    <fieldset>
    <table class="datatable">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Qtde em estoque</th>
                <th>Imagem destaque</th>
                <th class="c">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?
            while($vet = mysql_fetch_array($rs))
            {
                $idproduto = $vet['codigo'];

                if($vet['ind_cores'] == 1)
                {
                    $strE = "SELECT A.*, B.numero, C.titulo AS cor 
                        FROM produtos_estoque A
                        LEFT JOIN tamanhos B ON A.idtamanho = B.codigo
                        LEFT JOIN cores C ON A.idcor = C.codigo
                        WHERE A.idproduto = '$idproduto'
                        ORDER BY B.numero, cor";
                    $rsE  = mysql_query($strE) or die(mysql_error());

                    while($vetE = mysql_fetch_array($rsE))
                    {
                        $imagem = img_produto_destaque($idproduto, $vetE['idcor']);
                        $numero = ($vetE['numero']) ? $vetE['numero'] : 'Não informado';
                        $cor = ($vetE['cor']) ? $vetE['cor'] : 'Não informado';
            ?>
            <tr >
                <td><?=stripslashes($vet['nome']).'<br>Tamanho: '.$numero.' - Cor: '.$cor?></td>
                <td><?=$vetE['estoque']?></td>
                <td>
                    <?
                    if($imagem)
                    {
                    ?>
                    <a href="../upload/<?=$imagem?>" class="thickbox"><img src="../upload/thumbnails/<?=$imagem?>"></a>
                    <?
                    }
                    else
                    {
                        echo 'Nenhuma imagem como destaque';
                    }
                    ?>
                </td>
                <td class="c">
                    <a class="btn i_pencil icon small" title="editar" href="produtos.php?ind=2&codigo=<?=$vet['codigo']?>" >editar</a>
                </td>
            </tr>
            <?
                    }
                }
                else
                {
                    $imagem = img_produto_destaque($vet['codigo']);
            ?>
            <tr >
                <td><?=stripslashes($vet['nome'])?></td>
                <td><?=$vet['estoque']?></td>
                <td>
                    <?
                    if($imagem)
                    {
                    ?>
                    <a href="../upload/<?=$imagem?>" class="thickbox"><img src="../upload/thumbnails/<?=$imagem?>"></a>
                    <?
                    }
                    else
                    {
                        echo 'Nenhuma imagem como destaque';
                    }
                    ?>
                </td>
                <td class="c">
                    <a class="btn i_pencil icon small" title="editar" href="produtos.php?ind=2&codigo=<?=$vet['codigo']?>" >editar</a>
                </td>
            </tr>
            <?
                }
            }
            ?>
        </tbody>
    </table>
    </fieldset>
    <?
    }
    else
    {
    ?>
    <fieldset>
        <p>Nenhum registro encontrado</p>
    </fieldset>
    <?
    }
    ?>
</div>
</section>
<?php include('rodape.inc.php'); ?>    
