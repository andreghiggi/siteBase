<?php 
include('topo.inc.php'); 

if($_GET['codigo'] == TRUE)
	$codigo = anti_injection($_GET['codigo']);
else
	$codigo = anti_injection($_POST['codigo']);

if($_GET['idimagem'] == TRUE)
    $idimagem = anti_injection($_GET['idimagem']);
else
    $idimagem = anti_injection($_POST['idimagem']);

$idcategoria = $_POST['idcategoria'];
$idsubcategoria = $_POST['idsubcategoria'];
$idmarca = $_POST['idmarca'];
$nome = addslashes($_POST['nome']);
//$valor_produto = str_replace(",", ".", str_replace(".", "", $_POST['valor_produto']));
//$valor_desconto = str_replace(",", ".", str_replace(".", "", $_POST['valor_desconto']));
$peso = addslashes($_POST['peso']);
$tags = $_POST['tags'];
$descricao = addslashes($_POST['descricao']);
$informacoes = addslashes($_POST['informacoes']);
$estoque = $_POST['estoque'];
$destaque = $_POST['destaque'];
$ind_cores = $_POST['ind_cores'];
$status = $_POST['status'];
$vet_imagem = $_POST['imagens'];
$referencia = $_POST['referencia'];

$qTabela = explode('|',$_POST['q_tabelas']);

if($_POST['cmd'] == "add")
{
    if($ind_cores == 1)
        $estoque = 0;

    $str = "INSERT INTO produtos (valor_produto, idcategoria, idsubcategoria, idmarca, nome, descricao, informacoes,  peso, tags, estoque, destaque, ind_cores, status, referencia)
        VALUES (0, '$idcategoria', '$idsubcategoria', '$idmarca', '$nome', '$descricao', '$informacoes', '$peso', '$tags', '$estoque', '$destaque', '$ind_cores', '$status', '$referencia')";
    $rs  = mysql_query($str);
    $codigo = mysql_insert_id();
		
    if(is_array($vet_imagem))
    {
        for($i = 0; $i < count($vet_imagem); $i++)
        {
            $imagem = altera_nome_imagem($vet_imagem[$i]);

            $status = 0;
            if(!$i)
                $status = 1;

            $str = "INSERT INTO produtos_imagens (idproduto, imagem, status) VALUES ('$codigo', '$imagem', '$status')";
            $rs  = mysql_query($str) or die(mysql_error());
        }
    }
    
    for($i=0; $i < sizeof($qTabela); $i++){
			mysql_query('insert into valores(valor_produto,valor_desconto,produto,tabela) values ('.$_POST['valor_produto|'.$qTabela[$i]].','.$_POST['valor_desconto|'.$qTabela[$i]].','.$codigo.','.$qTabela[$i].')');
		}
		
    redireciona("produtos.php?ind_msg=1");
}

if($_POST['cmd'] == "edit")
{   
    if($ind_cores == 1)
        $estoque = 0;
        
   $str = "UPDATE produtos SET idcategoria = '$idcategoria', idsubcategoria = '$idsubcategoria', idmarca = '$idmarca', nome = '$nome', descricao = '$descricao',
        informacoes = '$informacoes', valor_produto = '$valor_produto', valor_desconto = '$valor_desconto', peso = '$peso', tags = '$tags', estoque = '$estoque',
        destaque = '$destaque', ind_cores = '$ind_cores', status = '$status', id_tabela='$vTabela', referencia='$referencia' where codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());

    
    if(is_array($vet_imagem))
    {
        $strI = "SELECT * FROM produtos_imagens WHERE idproduto = '$codigo' AND status = '1'";
        $rsI  = mysql_query($strI) or die(mysql_error());
        $numI = mysql_num_rows($rsI);

        for($i = 0; $i < count($vet_imagem); $i++)
        {
            $imagem = altera_nome_imagem($vet_imagem[$i]);

            $status = 0;
            if(!$i && !$numI)
                $status = 1;

            $str = "INSERT INTO produtos_imagens (idproduto, imagem, status) VALUES ('$codigo', '$imagem', '$status')";
            $rs  = mysql_query($str) or die(mysql_error());
        }
    }
    
    for($i=0; $i < sizeof($qTabela); $i++){
			mysql_query('update valores set valor_produto="'.$_POST['valor_produto|'.$qTabela[$i]].'", valor_desconto="'.$_POST['valor_desconto|'.$qTabela[$i]].'" where produto='.$codigo.' and tabela='.$qTabela[$i]);
		}
    
    redireciona("produtos.php?ind_msg=2");
}

if($_GET['cmd'] == "del")
{
    $str = "SELECT * FROM produtos_imagens WHERE idproduto = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

    if($num)
    {
        while($vet = mysql_fetch_array($rs))
        {
            $idimagem = $vet['codigo'];
            $imagem = $vet['imagem'];
        
            $dir = substr(getcwd(), 0, -6);
            $dir_upload = $dir . "/upload/";
            $dir_thumbnails = $dir . "/upload/thumbnails/";

            if($imagem)
            {
                @unlink($dir_upload.$imagem);
                @unlink($dir_thumbnails.$imagem);
            }

            $strD = "DELETE FROM produtos_imagens WHERE codigo = '$idimagem'";
            $rsD  = mysql_query($strD) or die(mysql_error());
        }
    }

	$str = "DELETE FROM produtos WHERE codigo = '$codigo'";
	$rs  = mysql_query($str) or die(mysql_error());
	
	mysql_query("delete from valores where produto=".$codigo);
	
	redireciona("produtos.php?ind_msg=3");
}

if($_GET['cmd'] == "img_destaque")
{   
    $str = "UPDATE produtos_imagens SET status = '0' WHERE idproduto = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());

    $str = "UPDATE produtos_imagens SET status = '1' WHERE codigo = '$idimagem'";
    $rs  = mysql_query($str) or die(mysql_error());

    redireciona("produtos.php?ind_msg=4&ind=2&codigo=$codigo");
}

if($_GET['cmd'] == "img_del")
{
    $str = "SELECT * FROM produtos_imagens WHERE codigo = '$idimagem'";
    $rs  = mysql_query($str) or die(mysql_error());
    $vet = mysql_fetch_array($rs);

    $imagem = $vet['imagem'];
        
    $dir = substr(getcwd(), 0, -6);
    $dir_upload = $dir . "/upload/";
    $dir_thumbnails = $dir . "/upload/thumbnails/";

    if($imagem)
    {
        @unlink($dir_upload.$imagem);
        @unlink($dir_thumbnails.$imagem);
    }

    $str = "DELETE FROM produtos_imagens WHERE codigo = '$idimagem'";
    $rs  = mysql_query($str) or die(mysql_error());

    redireciona("produtos.php?ind_msg=5&ind=2&codigo=$codigo");
}

if($_GET['ind_msg'] == 1)
	$msg = '<div class="alert success">Produto cadastrado com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
	$msg = '<div class="alert success">Produto editado com sucesso!</div>';
elseif($_GET['ind_msg'] == 3)
    $msg = '<div class="alert success">Produto excluído com sucesso!</div>';
elseif($_GET['ind_msg'] == 4)
    $msg = '<div class="alert success">Imagem / foto marcada como destaque!</div>';
elseif($_GET['ind_msg'] == 5)
    $msg = '<div class="alert success">Imagem / foto excluída com sucesso!</div>';

$str = "SELECT * FROM produtos WHERE codigo = '$codigo'";
$rs  = mysql_query($str) or die(mysql_error());
$vet = mysql_fetch_array($rs);
   
include('menu.inc.php');
?>   
<script language="javascript">
function valida(ind)
{   
    if(ind == 1)
        document.form.cmd.value = "add";
    else
        document.form.cmd.value = "edit";
}

function excluir_imagem(codigo, idimagem)
{
    if(confirm('Deseja realmente excluir este registro?')) {
        document.form.action = "produtos.php?cmd=img_del&codigo="+codigo+"&idimagem="+idimagem;
        document.form.submit();
    } else {  
        return false;
    }
}

function imagem_destaque(codigo, idimagem)
{
    if(confirm('Deseja realmente tornar esta imagem destaque?')) {
        document.form.action = "produtos.php?cmd=img_destaque&codigo="+codigo+"&idimagem="+idimagem;
        document.form.submit();
    } else {  
        return false;
    }
}
</script>	    
			
<section id="content">
<div class="g12">
    <h1>Produtos</h1>
    <p></p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <input type="hidden" name="codigo" value="<?=$vet['codigo']?>">    
    <fieldset>
        <label>Informações sobre o produto</label>
        <section>
            <label for="idcategoria">Categoria</label>
            <div>
                <select name="idcategoria" id="idcategoria" required onchange="javascript: lista_subcategorias(this.value);">
                    <option value="">Selecione uma categoria ...</option>
                    <?
                    $strE = "SELECT * FROM produtos_categorias WHERE status = '1' ORDER BY nome";
                    $rsE  = mysql_query($strE) or die(mysql_error());

                    while($vetE = mysql_fetch_array($rsE))
                    {
                    ?>
                    <option value="<?=$vetE['codigo']?>" <?=($vetE['codigo'] == $vet['idcategoria']) ? 'selected' : ''?>><?=stripslashes($vetE['nome'])?></option>
                    <?
                    }
                    ?>
                </select>
            </div>
        </section>
        <section>
            <label for="idsubcategoria">Subcategoria</label>
            <div id="subcategorias">
                <?
                if(empty($vet['idcategoria']))
                {
                ?>
                <select name="idsubcategoria" id="idsubcategoria" >
                    <option value="">Selecione uma subcategoria ...</option>
                </select>
                <?
                }
                else
                {
                    $idcategoria = $vet['idcategoria'];
                    $strC = "SELECT * FROM produtos_subcategorias WHERE idcategoria = '$idcategoria' AND status = '1' ORDER BY nome";
                    $rsC  = mysql_query($strC) or die(mysql_error());
                ?>
                <select name="idsubcategoria" id="idsubcategoria" >
                    <option value="">Selecione uma subcategoria ...</option>
                    <?
                    while($vetC = mysql_fetch_array($rsC))
                    {
                    ?>
                    <option value="<?=$vetC['codigo']?>" <?=($vetC['codigo'] == $vet['idsubcategoria']) ? 'selected' : ''?>><?=stripslashes($vetC['nome'])?></option>
                    <?
                    }
                    ?>
                </select>
                <?
                }
                ?>
            </div>
        </section>
        <section>
            <label for="idmarca">Marca</label>
            <div>
                <select name="idmarca" id="idmarca" >
                    <option value="">Selecione uma marca ...</option>
                    <?
                    $strE = "SELECT * FROM marcas ORDER BY titulo";
                    $rsE  = mysql_query($strE) or die(mysql_error());

                    while($vetE = mysql_fetch_array($rsE))
                    {
                    ?>
                    <option value="<?=$vetE['codigo']?>" <?=($vetE['codigo'] == $vet['idmarca']) ? 'selected' : ''?>><?=stripslashes($vetE['titulo'])?></option>
                    <?
                    }
                    ?>
                </select>
            </div>
        </section>
        <section>
            <label for="text_field">Nome:</label>
            <div><input type="text" id="nome" name="nome" value="<?=stripslashes($vet['nome'])?>" required></div>
        </section>
        
        <?php
        	$vResp = mysql_query("select * from tabela");
        	
        	$log = "";
        	while($row = mysql_fetch_array($vResp)){
        		$resp = mysql_query("select * from valores where produto=".$codigo." and tabela=".$row['id']);
						$vVet = mysql_fetch_array($resp);
        		echo'
		      		<section>
		      			<label for="title">Valor do produto<br>('.$row['nome'].')<br></label>
		      			<div>
		      				<input type="number" id="valor_produto|'.$row['id'].'" name="valor_produto|'.$row['id'].'" value="'.$vVet['valor_produto'].'" required>
		      			</div>
		      		</section>
		      		<section>
		      			<label for="title">Valor com Desconto<br>('.$row['nome'].')<br></label>
		      			<div>
		      				<input type="number" id="valor_desconto|'.$row['id'].'" name="valor_desconto|'.$row['id'].'" value="'.$vVet['valor_desconto'].'">
		      			</div>
		      		</section>
		      	';
		      	$log .= $row['id'].'|';
        	}
        	echo '<input type="hidden" name="q_tabelas" value="'.substr($log,0,strlen($log)-1).'">';
        ?>
        <section>
            <label for="estoque">Peso do produto:</label>
            <div>
                <input type="text" id="peso" name="peso" value="<?=$vet['peso']?>" style="width:10%;" >
                <br><span>Informe o peso para o cálculo do frete. Exemplo: 0.05 (50g) / 0.5 (500g) / 1 (1kg) / 1.5 (1.5kg)</span>
            </div>
        </section>
        <section>
            <label for="referencia">Referência:</label>
            <div>
            		<div><input type="text" id="referencia" name="referencia" value="<?=$vet['referencia']?>" style="width:10%;"></div>
            </div>
        </section>
        <section>
            <label for="title">Tags:<br><span>Separar as tags com ";" sem espaços</span></label>
            <div><input type="text" id="tags" name="tags" value="<?=$vet['tags']?>" ></div>
        </section>
        <section>
            <label for="textarea_auto">Descrição:</label>
            <div><textarea name="descricao" id="descricao" required><?=stripslashes($vet['descricao'])?></textarea></div>
        </section>
        <section>
            <label for="textarea_auto">Mais informações:</label>
            <div><textarea name="informacoes" id="informacoes"><?=stripslashes($vet['informacoes'])?></textarea></div>
        </section>
        <section>
            <label for="file_upload">Upload de fotos:</label>
            <div><input type="file" id="imagens" name="imagens" multiple ></div>
        </section>
        <?
        $strF = "SELECT * FROM produtos_imagens WHERE idproduto = '$codigo' AND idcor = '0'";
        $rsF  = mysql_query($strF) or die(mysql_error());
        $numF = mysql_num_rows($rsF);

        if($numF)
        {
        ?>
        <section>
            <label for="file_upload">Galeria de fotos:</label>
            <div>
                <ul class="gallery">
                    <?
                    while($vetF = mysql_fetch_array($rsF))
                    {
                        $class = '';
                        if($vetF['status'] == 1) {
                            $class = 'style="border: 1px solid red;"';
                            $str_imagem = 'imagem destaque';
                            $icon_imagem = 'i_tick';
                        } else {
                            $str_imagem = 'tornar destaque';
                            $icon_imagem = 'i_flag';
                        }
                    ?>
                    <li>
                        <a href="../upload/<?=$vetF['imagem']?>" title=""><img src="../upload/<?=$vetF['imagem']?>" width="116" height="116" <?=$class?>></a>
                        <div id="<?=$str_imagem?>" style="margin:0 auto; margin-top:5%; text-align: center;"><button class="<?=$icon_imagem?> icon small" onClick="javascript: imagem_destaque('<?=$vet['codigo']?>', '<?=$vetF['codigo']?>');"><?=$str_imagem?></button></div>
                        <div id="excluir" style="margin:0 auto; margin-top:5%; text-align: center;"><button class="i_trashcan icon small" onClick="javascript: excluir_imagem('<?=$vet['codigo']?>', '<?=$vetF['codigo']?>');">excluir foto</button></div>
                    </li>
                    <?
                    }
                    ?>
                </ul>
            </div>
        </section>
        <?
        }
        ?>
        <section>
            <label for="estoque">Estoque:</label>
            <div>
                <input type="number" id="estoque" name="estoque" value="<?=$vet['estoque']?>" required class="integer">
                <br><span>Para produtos com variedade de cores e tamanhos, este campo será ignorado, pois o estoque será informado numa próxima etapa.</span>
            </div>
        </section>
        <section>
            <label for="textarea_auto">Produto em DESTAQUE no site?</span></label>
            <div>
                <input type="radio" id="destaque_1" name="destaque" <?=($vet['destaque'] == 1) ? "checked" : "" ?> value="1"><label for="destaque_1" class="radio">Sim</label>
                <input type="radio" id="destaque_2" name="destaque" <?=($vet['destaque'] == FALSE || $vet['destaque'] == 2) ? "checked" : "" ?> value="2"><label for="destaque_2" class="radio">Não</label>
            </div>
        </section>
        <section>
            <label for="textarea_auto">Este produto possui variação de cores ou tamanhos?</span></label>
            <div>
                <input type="radio" id="ind_cores_1" name="ind_cores" <?=($vet['ind_cores'] == 1) ? "checked" : "" ?> value="1"><label for="ind_cores_1" class="radio">Sim</label>
                <input type="radio" id="ind_cores_2" name="ind_cores" <?=($vet['ind_cores'] == FALSE || $vet['ind_cores'] == 2) ? "checked" : "" ?> value="2"><label for="ind_cores_2" class="radio">Não</label>
            </div>
        </section>
        <section>
            <label for="textarea_auto">Status:</span></label>
            <div>
                <input type="radio" id="status_1" name="status" <?=($vet['status'] == FALSE || $vet['status'] == 1) ? "checked" : "" ?> value="1"><label for="status_1" class="radio">Ativo</label>
                <input type="radio" id="status_2" name="status" <?=($vet['status'] == 2) ? "checked" : "" ?> value="2"><label for="status_2" class="radio">Inativo</label>
            </div>
        </section>
        <section>
			<?
            if($ind == 1)
            {
            ?>
            <div><button class="i_tick icon" onClick="javascript: valida(1);">Cadastrar</button></div>
            <?
            }
            else
            {
            ?>
            <div><button class="i_refresh_3 icon" onClick="javascript: valida(2);">Alterar</button></div>
            <?
            }
            ?>
        </section>
    </fieldset>

    
	</form>
   	<!-- end form -->

	<?
    $str = "SELECT A.*, B.nome AS categoria, C.nome AS subcategoria
        FROM produtos A
        INNER JOIN produtos_categorias B ON A.idcategoria = B.codigo
        LEFT JOIN produtos_subcategorias C ON A.idsubcategoria = C.codigo
        ORDER BY A.nome";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

	if($num > 0)
	{
	?>
	<h1>Lista de produtos</h1>
    <p>Todos os produtos cadastrados no sistema.</p>
    <table >
        <thead>
            <tr>
                <th colspan="3">LEGENDA - Status do produto</th>
            </tr>
        </thead>
        <tbody>
            <tr >
                <td style="background-color:#f0a8a8; width:50%;">Inativo</td>
                <td style="background-color:#BFDFFF">Ativo</td>
            </tr>
        </tbody>
    </table>

    <table class="datatable">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Categoria</th>
            <th>Subcategoria</th>
            <th>Imagem destaque</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
		<?
        while($vet = mysql_fetch_array($rs))
        {
            $imagem = img_produto_destaque($vet['codigo']);

            if($vet['status'] != 1)
                $class = 'class="gradeA"';
            else
                $class = 'class="gradeU"';
        ?>
        <tr <?=$class?>>
            <td><?=stripslashes($vet['nome'])?></td>
            <td><?=stripslashes($vet['categoria'])?></td>
            <td><?=($vet['subcategoria']) ? stripslashes($vet['subcategoria']) : 'Não possui'?></td>
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
                <?
                if($vet['ind_cores'] == 1)
                {
                ?>
                <a class="btn i_incomming icon small" title="estoque" href="produtos_estoque.php?idproduto=<?=$vet['codigo']?>" >estoque</a>
                <?
                }
                ?>
                <a class="btn i_pencil icon small" title="editar" href="produtos.php?ind=2&codigo=<?=$vet['codigo']?>" >editar</a>
                <a class="btn i_trashcan icon small" title="excluir" href="produtos.php?cmd=del&codigo=<?=$vet['codigo']?>" onclick="javascript: if(!confirm('Deseja realmente excluir este registro?')) { return false }">excluir</a>
            </td>
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
