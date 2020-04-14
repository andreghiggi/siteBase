<?
include("includes/header.php");

if(!$_SESSION['user_verifica'])
{
	redireciona("login.php?ind_msg=2");
}

$idpedido = anti_injection($_GET['idpedido']);

$str = "SELECT DISTINCT * FROM pedidos WHERE codigo = '$idpedido' AND idcadastro = '$c_codigo'";
$rs  = mysql_query($str) or die(mysql_error());
$num = mysql_num_rows($rs);
$vet = mysql_fetch_array($rs);
?>
<section class="slider-category-area">
	<div class="container">
		<div class="row">			
			<div class="col-sm-12 col-lg-12 col-md-12">

				<div class="row">
					<div class="col-lg-12 col-md-12">
						<div class="wishlist">
							<h2 class="form-heading">Meus pedidos</h2>
							
							<?
							if($num > 0)
							{	
							?>
							<!-- wishlist-table-start -->
							<div class="cart table-responsive">
								<p>Nesta seção você consulta o status de seu pedido e a data estimada para o envio.</p>							
								<table>
									<tbody>
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
										$vetF = mysql_fetch_array($rsF);

										$data_entrega = date("d/m/Y", mktime(0, 0, 0, substr($vet['data_geracao'], 5, 2), substr($vet['data_geracao'], 8, 2) + $vetF['prazo'], substr($vet['data_geracao'], 0, 4)));
										$total = $vet['valor'] + $vetF['valor'];
										?>
										<tr>
											<td>
												<a name="pagto"></a>
												<b>Pedido #<?=$vet['codigo']?></b><br>
												<b>Data do pedido:</b> <?=ConverteData($vet['data_geracao'])?><br><br>
												<b>Endereço de entrega</b><br>

												<?
												$strE = "SELECT * FROM pedidos_enderecos WHERE idpedido = '$idpedido' AND idcadastro = '$c_codigo'";
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
												
								                <b>Subtotal:</b> R$ <?=number_format($vet['valor'], 2, ',', '.')?><br>
								                <?
												if($config_frete > 0)
												{
												?>
												<b>Frete:</b> R$ <?=number_format($vetF['valor'], 2, ',', '.')?><br>												
												<b>Prazo de entrega:</b> <?=$data_entrega?> (<?=$vetF['servico']?>)<br>
												<?
												}
												?>
												<b>TOTAL:</b> R$ <?=number_format($total, 2, ',', '.')?><br><br>
												
												<b>Código de Rastreio:</b> <?php echo $vet['codRastreio'];?><br><br>
												

												<b>Status:</b> <?=$status?><br>
								                
								                <?
								                if(!$vet['status'] && $vet['pagamento'] == 1)
								                {
								                ?>
								                <br>
								                <p>
								                	Clique no botão abaixo para efetuar o pagamento deste pedido:								                	
									                <form name="pagseguro_form" action="https://pagseguro.uol.com.br/checkout/checkout.jhtml" method="post">
									                    <input type="hidden" name="email_cobranca" value="opion@opioncalcados.com.br" />
									                    <input type="hidden" name="tipo" value="CP" />
									                    <input type="hidden" name="moeda" value="BRL" />
									                    <input type="hidden" name="item_id_1" value="<?=$vet['codigo']?>" />
									                    <input type="hidden" name="item_descr_1" value="<?=$n_empresa?>" />
									                    <input type="hidden" name="item_quant_1" value="1" />
									                    <input type="hidden" name="item_valor_1" value="<?=number_format($total, 2, ',', '.')?>" />
									                    <input type="hidden" name="item_frete_1" value="0" />
									                    <input type="hidden" name="cliente_nome" value="<?=$c_nome?>" />
									                    <input type="hidden" name="cliente_email" value="<?=$c_email?>" />
									                    <input type="hidden" name="cliente_cep" value="<?=$c_cep?>" />
									                    <input name="ref_transacao" type="hidden" id="ref_transacao" value="<?=$vet['codigo']?>" />
									                    <input type="image" src="img/botao_pagar.png" name="submit" alt="PagSeguro" class="standard-checkout"/>
									                </form>
									            </p>
								                <?
								                }

								                if($vet['status'] == 2 && $config_frete > 0)
								                	echo '<b>Data do envio:</b> '.ConverteData($vet['data_envio']).'<br>';
								                ?>
											</td>
										</tr>
									</tbody>
								</table>
								
								<?
								$strP = "SELECT DISTINCT A.*, B.numero, C.titulo AS cor
									FROM pedidos_detalhe A
									LEFT JOIN tamanhos B ON A.idtamanho = B.codigo
									LEFT JOIN cores C ON A.idcor = C.codigo
									WHERE idpedido = '$idpedido'
									ORDER BY descricao";
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
											<td>
												<a href="produto.php?codigo=<?=$vet['codigo']?>"><img src="upload/thumbnails/<?=$imagem?>" ></a>
											</td>
											<td>
												<?=stripslashes($vetP['descricao'])?>
												<?
												if($vetP['idtamanho'] > 0 || $vetP['idcor'] > 0)
												{
												?>
												<br>
												<small>Tamanho: <?=($vetP['numero']) ? $vetP['numero'] : 'Não informado'?> - Cor: <?=($vetP['cor']) ? $vetP['cor'] : 'Não informado'?></small>
												<?
												}
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
							<!-- wishlist-table-end -->
							

							<!-- block-button-start -->
							<div class="block-button">
								<ul>
									<li>
										<a href="meus_pedidos.php">
											<span>
												<i class="fa fa-angle-left"></i>
												Voltar para os meus pedidos
											</span>
										</a>
									</li>
									<li>
										<a href="index.php">
											<span>
												<i class="fa fa-angle-left"></i>
												Ir para o site
											</span>
										</a>
									</li>
									<br><br><br>
								</ul>
							</div>
							<!-- block-button-end -->
						</div>
					</div>
				</div>
				<?
				}
				?>
			</div>
		</div>
	</div>
</section>
<?
include("includes/footer.php");
?>
