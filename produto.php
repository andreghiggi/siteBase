<?
include("includes/header.php");

$codigo = anti_injection($_GET['codigo']);
$idtamanho = anti_injection($_GET['idtamanho']);

/********************************
Registra visualização do produto
*********************************/
produtos_mais_procurados($codigo);

$str = "SELECT A.*, B.nome AS categoria, C.nome AS subcategoria, D.titulo AS marca
    FROM produtos A
    INNER JOIN produtos_categorias B ON A.idcategoria = B.codigo
    LEFT JOIN produtos_subcategorias C ON A.idsubcategoria = C.codigo
    LEFT JOIN marcas D ON A.idmarca = D.codigo
    WHERE A.codigo = '$codigo'";
$rs  = mysql_query($str) or die(mysql_error());
$num = mysql_num_rows($rs);
$vet = mysql_fetch_array($rs);

//Caso o usuário tente forçar algum id (produto) q não exista no banco
if(!$num)
	redireciona("index.php");
?>

<section class="slider-category-area mt-3">
	<div class="container">
		<div class="row">
			<div class="col-sm-9 col-lg-9 col-md-9">
				<!-- breadcrumb-start -->
				<div class="product-description-area mb-3">
					<div class="row">
						<div id="imagens" class="col-sm-5 col-lg-5 col-md-5">
							<?
							$strI = "SELECT * FROM produtos_imagens WHERE idproduto = '$codigo' ORDER BY ordem";
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
							        	$class = 'class="tab-pane"';
							        	if(!$i)
							        		$class = 'class="tab-pane in active"';
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
									<li <?=$class?>><a data-toggle="tab" href="#image<?=$i?>"><img alt="" class="thumnail-img" src="upload/<?=$array_imagens[$i]?>"></a></li>
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
						</div>
						<div class="col-sm-7 col-lg-7 col-md-7">
							<div class="product-description">
								<h2>
									<?=stripslashes($vet['nome'])?>
								</h2>
								<?php if(isset($vet['ref']) && $vet['ref'] > 0):?>
									<p class="model-condi">
										<label>Ref.:</label>
										<span><?=$vet['ref'];?></span>
									</p>
								<?php endif;?>
								<p class="model-condi">
									<label>Categoria:</label>
									<span><?=stripslashes($vet['categoria'])?></span>
								</p>
								<?
								if($vet['subcategoria'])
								{
								?>
								<p class="model-condi">
									<label>Subcategoria:</label>
									<span><?=stripslashes($vet['subcategoria'])?></span>
								</p>
								<?
								}
								?>

								<?
								if($vet['marca'])
								{
								?>
								<p class="model-condi">
									<label>Marca:</label>
									<span><?=stripslashes($vet['marca'])?></span>
								</p>
								<?
								}
								?>

								

								<div class="price-box-area">
									<span class="new-price">
										<?
										if($vet['valor_desconto']){
											$valor = $vet['valor_desconto'];
											echo '
											<small id="prodValorSemDesconto"><strike>De: R$ '.(number_format($vet['valor_produto'], 2, ',', '.')).'</strike></small><br>
											<big><strong >Por: R$ <span id="prodValor">'.(number_format($valor, 2, ',', '.')).'</span></strong></big>';
										}
										else{
											$valor = $vet['valor_produto'];
											echo 'R$ <span id="prodValor">'.number_format($valor, 2, ',', '.').'</span>';
										}
										?>
										<span id="estoqueValor" class="d-none"></span>
									</span>
								</div>

								<?
								$variacao = verifica_varicacao_cores_tamanho($codigo);
								?>
								<br>
								
								<div class="product-attributes">
									<?
									if($variacao == 1)
									{
									?>
									<div class="tamanho">
										<label>Tamanho :</label>
										<select name="idtamanho" id="idtamanho" onchange="javascript: exibe_cores(<?=$codigo?>, this.value);">
											<option value="" >Selecione um tamanho</option>
											<?
											$strT = "SELECT DISTINCT B.*
												FROM produtos_estoque A
												INNER JOIN tamanhos B ON A.idtamanho = B.codigo
												WHERE idproduto = '$codigo'
												GROUP BY idtamanho
												ORDER BY B.numero";
											$rsT  = mysql_query($strT) or die(mysql_error());

											while($vetT = mysql_fetch_array($rsT))
											{
											?>
											<option value="<?=$vetT['codigo']?>" ><?=stripslashes($vetT['numero'])?></option>
											<?
											}
											?>
										</select>
									</div>

									<div id="cores" ></div>
									<br>
									<?
									}
									?>

									<?
									if($variacao == 2)
									{
									?>
									<div class="tamanho">
										<label>Tamanho :</label>
										<select name="idtamanho" id="idtamanho"  onchange="javascript: exibe_estoque(<?=$codigo?>, this.value);">
											<option value="" >Selecione um tamanho</option>
											<?
											$strT = "SELECT DISTINCT B.*
												FROM produtos_estoque A
												INNER JOIN tamanhos B ON A.idtamanho = B.codigo
												WHERE idproduto = '$codigo'
												GROUP BY idtamanho
												ORDER BY B.numero";
											$rsT  = mysql_query($strT) or die(mysql_error());

											while($vetT = mysql_fetch_array($rsT))
											{
											?>
											<option value="<?=$vetT['codigo']?>" ><?=stripslashes($vetT['numero'])?></option>
											<?
											}
											?>
										</select>
									</div>
									<br>
									<?
									}
									?>

									<?
									if($variacao == 3)
									{
									?>
									<div class="color">
										<label>Cor :</label>
										<select name="idcor" id="idcor" >
											<option value="" >Selecione uma cor</option>
											<?
											$strT = "SELECT DISTINCT B.*
												FROM produtos_estoque A
												INNER JOIN cores B ON A.idcor = B.codigo
												WHERE idproduto = '$codigo'
												GROUP BY idcor";
											$rsT  = mysql_query($strT) or die(mysql_error());

											while($vetT = mysql_fetch_array($rsT))
											{
											?>
											<option value="<?=$vetT['codigo']?>"><?=stripslashes($vetT['titulo'])?></option>
											<?
											}
											?>
										</select>
									</div>

									<div id="cores" ></div>
									<br>
									<?
									}
									?>

									<div id="estoque" >
										<?
										if($vet['ind_cores'] == 2)
										{
										?>
										<br>
										<p class="pquantityavailable">
											<?
											if($vet['estoque'] <= 0)
											{
											?>
											<span class="stock-fail">
												Tempo de produção <?=$vet['tempProd'];?> dias
											</span>
											<?
											}
											?>
										</p>
										<?
										}
										?>
									</div>
								</div>

								<div id="carrinho" class="">
								<?
								/*if($vet['ind_cores'] == 2)
								{*/
								?>
									<a href="carrinho.php?cmd=add&idproduto=<?=$vet['codigo']?>" class="btn btn-dark mt-2 mb-3">
										<span>Adicionar ao carrinho</span>
									</a>
								<?
								#}
								?>
								</div>

								<p><?php echo nl2br($vet['descricao']);?></p>
							</div>
						</div>
					</div>
				</div>



				<?
				if($vet['informacoes'])
				{
				?>
				<!-- product-overview-start -->
				<div class="product-overview">
    				<div class="product-overview-tab-menu">
	    				<ul>
							<li class="active"><a href="#moreinfo" data-toggle="tab">Mais informações</a></li>
						</ul>
					</div>
					<div class="tab-content">
						<div id ="moreinfo" class="tab-pane in active">
							<div class="rte"><?=stripslashes($vet['informacoes'])?></div>
						</div>
					</div>
    			</div>
    			<!-- product-overview-end -->
    			<?
    			}
    			?>

    			<?
			    $str = "SELECT * FROM produtos WHERE status = '1' AND codigo != '$codigo' ORDER BY RAND() LIMIT 30";
			    $rs  = mysql_query($str) or die(mysql_error());
			    $num = mysql_num_rows($rs);

				if($num > 0)
				{
				?>
					<br><br><br>
    			<!-- other-product-start-->
    			<div class="other-product-area mt-4">
    				<div class="area-title">
						<h2>Outros produtos</h2>
					</div>
    				<div class="row">
    					<div class="new-product-carosul">
    						<?
							while($vet = mysql_fetch_array($rs))
							{
								$imagem = img_produto_destaque($vet['codigo']);
							?>
    						<!-- single-product-start -->
    						<div class="col-lg col-md">
								<div class="single-product">
									<div class="image-area">
										<a href="produto.php?codigo=<?=$vet['codigo']?>">
											<img alt="<?=stripslashes($vet['nome'])?>" src="upload/<?=$imagem?>">
										</a>
									</div>
									<div class="product-info">
										<h2 class="product-name">
											<a href="produto.php?codigo=<?=$vet['codigo']?>"><?=stripslashes($vet['nome'])?></a>
										</h2>
										<div class="price-ratting">
											<div class="price-box-area">
												<?
												if(!$vet['valor_desconto'])
												{
												?>
												<div class="new-price">
													R$ <?=number_format($vet['valor_produto'], 2, ',', '.')?>
												</div>
												<?
												}
												else
												{
												?>
												<div class="new-price">
													R$ <?=number_format($vet['valor_desconto'], 2, ',', '.')?>
												</div>
												<span class="old-price">
													R$ <?=number_format($vet['valor_produto'], 2, ',', '.')?>
												</span>
												<?
												}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- single-product-end -->
							<?
							}
							?>
						</div>
    				</div>
    			</div>
    			<!-- other-product-end-->
    			<?
    			}
    			?>
			</div>
			<div class="col-sm-3 col-lg-3 col-md-3">
				<div class="left-sidebar">
					<?
					/**********
					TAGS
					***********/
					box_tags();

					/*************************
					BOX - PRODUTOS EM DESTQUE
					**************************/
					//box_destaque($codigo);
					?>
				</div>
			</div>
		</div>
	</div>
</section>
<?
include("includes/footer.php");
?>
