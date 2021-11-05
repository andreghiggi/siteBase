<?
include("includes/header.php");

$pagina = anti_injection($_GET['pagina']);

if($_GET['idcategoria'] == TRUE)
    $idcategoria = anti_injection($_GET['idcategoria']);
elseif($_POST['idcategoria'] == TRUE)
    $idcategoria = anti_injection($_POST['idcategoria']);

if($_GET['idsubcategoria'] == TRUE)
    $idsubcategoria = anti_injection($_GET['idsubcategoria']);
elseif($_POST['idsubcategoria'] == TRUE)
    $idsubcategoria = anti_injection($_POST['idsubcategoria']);

if($_GET['idmarca'] == TRUE)
    $idmarca = anti_injection($_GET['idmarca']);
elseif($_POST['idmarca'] == TRUE)
    $idmarca = anti_injection($_POST['idmarca']);

if($_GET['tag'] == TRUE)
    $tag = base64_decode(anti_injection($_GET['tag']));
elseif($_POST['tag'] == TRUE)
    $tag = base64_decode(anti_injection($_POST['tag']));

if($_GET['order'] == TRUE)
    $order = anti_injection($_GET['order']);
elseif($_POST['order'] == TRUE)
    $order = anti_injection($_POST['order']);

if($_GET['ind'] == TRUE)
    $ind = anti_injection($_GET['ind']);
elseif($_POST['ind'] == TRUE)
    $ind = anti_injection($_POST['ind']);

if($_GET['var_marca'] == TRUE)
	$marca = $_GET['var_marca'];
elseif($_POST['var_marca'] == TRUE)
	$marca = $_POST['var_marca'];

$strWhere = "";
$strOrder = "";

if($idcategoria)
	$strWhere .= " AND idcategoria = '$idcategoria'";

if($idsubcategoria)
	$strWhere .= " AND idsubcategoria = '$idsubcategoria'";

if($idmarca)
	$strWhere .= " AND idmarca = '$idmarca'";

if($tag)
	$strWhere .= " AND tags LIKE '%$tag%'";
	
if($marca)
	$strWhere .= 'AND idMarca = '.$marca;

if($chave){
	$strWhere .= " AND (soundex(nome) LIKE soundex('$chave') OR nome LIKE '%$chave%' OR lower(nome) LIKE lower('%$chave%') OR idcategoria IN (SELECT codigo FROM produtos_categorias WHERE soundex(nome) LIKE soundex('$chave') OR nome LIKE '%$chave%') OR idsubcategoria IN (SELECT codigo FROM produtos_subcategorias WHERE soundex(nome) LIKE soundex('$chave') OR nome LIKE '%$chave%') OR idmarca IN (SELECT codigo FROM marcas WHERE soundex(titulo) LIKE soundex('$chave')) OR LOWER(nome) LIKE LOWER('%".implode('%',explode(' ',$chave))."%'))";
}

if($pagina)
    $pag++;

$num_lista = 30;

if($pagina == FALSE || $pagina == 1)
    $pag = 0;
else
    $pag = $pagina - 1;

$pag = $pag * $num_lista;

if(!$order || $order == 1 || $ind == 1)
	$strOrder .= " ORDER BY codigo DESC LIMIT $pag, $num_lista";
elseif($order == 2)
	$strOrder .= " ORDER BY valor_produto ASC LIMIT $pag, $num_lista";
elseif($order == 3)
	$strOrder .= " ORDER BY valor_produto DESC LIMIT $pag, $num_lista";
elseif($order == 4)
	$strOrder .= " ORDER BY nome ASC LIMIT $pag, $num_lista";
?>
<!-- static-right-social-area end-->
<section class="slider-category-area">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="left-sidebar">
					<?
					include("includes/menu.php");
					?>
				</div>
			</div>
			<div class="col-12">
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<?
						if($_GET['ind_msg'] == 1)
						{
						?>
						<div class="page-heading">
							<p style="color: #DF0101">O estoque do produto é inferior à quantidade selecionada, entre em contato com o suporte.</p>
						</div>
						<?
						}
						?>

						<?
						if($_GET['ind_msg'] == 2)
						{
						?>
						<div class="page-heading">
							<p style="color: #DF0101">Erro ao adicionar produto.</p>
						</div>
						<?
						}
						?>

						<?
					    $str = "SELECT * FROM publicidade WHERE tipo = '1' ORDER BY RAND() LIMIT 1";
					    $rs  = mysql_query($str) or die(mysql_error());
					    $num = mysql_num_rows($rs);

						if($num > 0)
						{
							$vet = mysql_fetch_array($rs);
						?>
						<div class="category-image">
							<a href="<?=($vet['url']) ? $vet['url'] : 'javascript:;'?>" target="_blank">
								<img src="upload/<?=$vet['imagem']?>" alt="<?=stripslashes($vet['titulo'])?>">
							</a>
						</div>
						<?
						}
						?>
						<div class="toolbar">
							<!--<div class="view-mode">
								<ul>
									<li class="active">
										<a data-toggle="tab" href="#grid">
											<i class="fa fa-th-large"></i>
										</a>
									</li>
									<li>
										<a data-toggle="tab" href="#list">
											<i class="fa fa-th-list"></i>
										</a>
									</li>
								 </ul>
							</div>-->
							<form name="form_l" id="form_l" method="post" action="loja.php?pagina=<?=$pagina?>" style="width:100%">
								<input type="hidden" name="cmd" id="cmd" value="list">
								<input type="hidden" name="idcategoria" id="idcategoria" value="<?=$idcategoria?>">
								<input type="hidden" name="idsubcategoria" id="idsubcategoria" value="<?=$idsubcategoria?>">
								<input type="hidden" name="idmarca" id="idmarca" value="<?=$idmarca?>">
								<input type="hidden" name="tag" id="tag" value="<?=base64_encode($tag)?>">
								<input type="hidden" name="chave" id="chave" value="<?=(isset($_POST['chave']))? base64_encode($chave) : $chave?>">
								
								<div class="row">
									<div class="col-5">
										<div class="short-by">
											<label>Ordenar por:</label>
											<select name="order" id="order" onchange="javascript: document.form_l.submit();" style="height:100%">
												<option value="1" <?=(!$order || $order == 1) ? 'selected' : ''?>>Últimos cadastrados</option>
												<option value="2" <?=($order == 2) ? 'selected' : ''?>>Menor preço</option>
												<option value="3" <?=($order == 3) ? 'selected' : ''?>>Maior preço</option>
												<option value="4" <?=($order == 4) ? 'selected' : ''?>>Ordem alfabética</option>
											</select>
										</div>
									</div>
									<div class="col">
										<div class="short-by">
											<label>Marca:</label>
											<select name="var_marca" id="var_marca" onchange="javascript: document.form_l.submit();"  style="height:100%">
												<option value=""></option>
												<?php
													$resp = mysql_query('select codigo,titulo from marcas where codigo in (SELECT idmarca FROM produtos WHERE status = "1" '.$strWhere.' group by idmarca)');
													while($row = mysql_fetch_array($resp)){
														
														echo '<option value="'.$row['codigo'].'" '.(($marca == $row['codigo'])?'SELECTED' : '').' >'.$row['titulo'].'</option>';
													}
												?>
											</select>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<?				
			  	$str = "SELECT * FROM produtos WHERE status = '1' $strWhere $strOrder";

			    $rs  = mysql_query($str) or die(mysql_error());
			    $num = mysql_num_rows($rs);

				if($num > 0)
				{
					$i = 0;
				    $array_produtos = array();
				    while($vet = mysql_fetch_array($rs))
				    {
				        $array_produtos[$i][0] = $vet['codigo'];
				        $array_produtos[$i][1] = stripslashes($vet['nome']);
				        $array_produtos[$i][2] = $vet['valor_produto'];
				        $array_produtos[$i][3] = $vet['valor_desconto'];
				        $array_produtos[$i][4] = stripslashes($vet['descricao']);
				        $array_produtos[$i][5] = img_produto_destaque($vet['codigo']);
				        $i++;
				    }
				?>
				<div class="row">
					<div class="tab-content" style="width:100%">
						<?
						if(count($array_produtos) > 0)
						{
						?>
						<!-- grid-product-tab-start -->
						<div id="grid" class="tab-pane active in">
							<?
							for($i = 0; $i < count($array_produtos); $i++)
				        	{
							?>
							<!-- single-grid-product-start -->
							<div class="col-sm-3 col-lg-3 col-md-3" style="max-width:160px;height:300px;">
								<div class="single-product">
									<div class="image-area">
										<a href="produto.php?codigo=<?=$array_produtos[$i][0]?>">
											<img src="upload/<?=$array_produtos[$i][5]?>" alt="<?=$array_produtos[$i][1]?>" style="max-height:140px">
										</a>
										<!--span class="new-box">
											<span class="new-label">New</span>
										</span-->
										<!--<div class="price-box">
											<?
											valor_produto($array_produtos[$i][2], $array_produtos[$i][3]);
											?>
										</div>-->
									</div>
									<div class="product-info">
										<h2 class="product-name">
											<a href="#"><?=$array_produtos[$i][1]?></a>
										</h2>
										<div class="price-ratting">
											<div class="price-box-area">
												<?
												valor_produto($array_produtos[$i][2], $array_produtos[$i][3]);
												?>
											</div>
										</div>
										<div class="action-button-area">
											<br>
											<a class="add-to-cart" href="produto.php?codigo=<?=$array_produtos[$i][0]?>">
												<span>+ Ver mais</span>
											</a>
										</div>
									</div>
								</div>
							</div>
							<!-- single-grid-product-end -->
							<?
							}
							?>
						</div>
						<!-- grid-product-tab-end -->
						<?
						}
						?>

						<?
						if(count($array_produtos) > 0)
						{
						?>
						<!-- list-product-tab-start -->
						<div id="list" class="tab-pane fade">
							<div class="col-lg-12 col-md-12">
								<?
								for($i = 0; $i < count($array_produtos); $i++)
					        	{
									$imagem = img_produto_destaque($array_produtos[$i][5]);
								?>
								<!-- single-grid-product-start -->
								<div class="row">
									<div class="single-product">
										<div class="col-lg-3 col-md-3">
											<div class="image-area">
												<a href="produto.php?codigo=<?=$array_produtos[$i][0]?>">
													<img src="upload/<?=$array_produtos[$i][5]?>" alt="<?=$array_produtos[$i][1]?>">
												</a>
											</div>
										</div>
										<div class="col-lg-6 col-md-6">
											<div class="product-description">
												<h2 class="product-name">
													<a href="#"><?=$array_produtos[$i][1]?></a>
												</h2>
												<div class="price-box-area">
													<span class="new-price">
														R$ <?=number_format($array_produtos[$i][3], 2, ',', '.')?>
													</span>
													<?
													if($array_produtos[$i][2])
													{
													?>
													<span class="old-price">
														R$ <?=number_format($array_produtos[$i][2], 2, ',', '.')?>
													</span>
													<?
													}
													?>
												</div>
												<p class="product-desc"><?=substr($array_produtos[$i][4], 0, 150)." ..."?></p>
											</div>
										</div>
										<div class="col-lg-3 col-md-3">
											<div class="action-button">
												<a class="add-to-cart" href="produto.php?codigo=<?=$array_produtos[$i][0]?>">
													<span>+ Ver mais</span>
												</a>
											</div>
										</div>
									</div>
								</div>
								<!-- single-grid-product-end -->
								<?
								}
								?>
							</div>
						</div>
						<!-- list-product-tab-end -->
						<?
						}
						?>
					</div>
				</div>
        <div class="row" style="display:flex;margin-left: auto; margin-right: auto;">
          <?
              $strP = "SELECT * FROM produtos WHERE status = '1' $strWhere ORDER BY codigo DESC";
              $rsP  = mysql_query($strP) or die(mysql_error());
              $numP = mysql_num_rows($rsP);

              if($numP)
              {
              ?>
          <div class="pagination-area">
            <ul>
              <?
                        $max = $num_lista;

                        if (!$pagina)
                            $pagina = 1;

                        $inicio = $pagina - 1;
                        $inicio = $max * $inicio;

                        //calculando pagina anterior
                        $menos = $pagina - 1;

                        //calculando pagina posterior
                        $mais = $pagina + 1;

                        $pgs = ceil($numP / $max);

                        if($pgs > 1)
                        {
                            if($menos > 0)
                            {
                                echo "<li><a href='loja.php?pagina=$menos&idcategoria=$idcategoria&idsubcategoria=$idsubcategoria&idmarca=$idmarca&tag=$tag&order=$order&chave=".base64_encode($chave)."'>&laquo;</a></li> ";
                            }

                            if (($pagina - 4) < 1)
                                $anterior = 1;
                            else
                                $anterior = $pagina - 4;

                            if (($pagina + 4) > $pgs)
                                $posterior = $pgs;
                            else
                                $posterior = $pagina + 4;

                            for($i = $anterior; $i <= $posterior; $i++)
                            {
                                if($i != $pagina)
                                    echo "<li><a href='loja.php?pagina=$i&var_marca=$marca&idcategoria=$idcategoria&idsubcategoria=$idsubcategoria&idmarca=$idmarca&tag=$tag&order=$order&chave=".base64_encode($chave)."'>$i</a></li> ";
                                else
                                    echo "<li class='current-pag'><a href='javascript:;'>$i</a></li> ";
                            }

                            if($mais <= $pgs)
                                echo "<li><a href='loja.php?pagina=$mais&var_marca=$marca&idcategoria=$idcategoria&idsubcategoria=$idsubcategoria&idmarca=$idmarca&tag=$tag&order=$order&chave=".base64_encode($chave)."'>&raquo;</a></li> ";
                        }
                        ?>
            </ul>
          </div>
          <?
          }
          ?>
        </div>
				<?
				}
				else
				{
				?>
				<div class="row">
					<div class="tab-content" align="center">
						<br><br>
						<p><i>Nenhum produto encontrado.</i></p>
					</div>
				</div>
				<?
				}
				?>
				<br><br>
			</div>
		</div>
	</div>
</section>
<?
include("includes/footer.php");
?>
