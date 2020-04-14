<?
include("includes/header.php");

if(!$_SESSION['user_verifica'])
{
	redireciona("login.php?ind_msg=2");
}
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
							$str = "SELECT DISTINCT * FROM pedidos WHERE idcadastro = '$c_codigo' ORDER BY codigo DESC";
						    $rs  = mysql_query($str) or die(mysql_error());
						    $num = mysql_num_rows($rs);

							if($num > 0)
							{
							?>					
							<!-- wishlist-table-start -->
							<div class="cart table-responsive">
								<table>
									<thead>
										<tr>
											<th>Pedido</th>
											<th>Data do pedido</th>
											<th>Valor</th>
											<th>Status</th>
											<th>Ações</th>
										</tr>
									</thead>
									<tbody>
										<?
										while($vet = mysql_fetch_array($rs))
										{
											$idpedido = $vet['codigo'];
											$valor += $vet['valor'];

											$strF = "SELECT * FROM frete WHERE idpedido = '$idpedido'";
											$rsF  = mysql_query($strF) or die(mysql_error());
											$vetF = mysql_fetch_array($rsF);

											$valor += $vetF['valor'];
										?>
										<tr>
											<td><a href="pedido.php?idpedido=<?=$vet['codigo']?>">#<?=$vet['codigo']?></a></td>
											<td><?=ConverteData($vet['data_geracao'])?></td>
											<td>R$ <?=number_format($vet['valor'], 2, ',', '.')?></td>
											<td>
												<?
												if($vet['status'] == 0)
								                    echo 'Aguardando confirmação';
								                elseif($vet['status'] == 1)
								                    echo 'Confirmado / Pago';
								                elseif($vet['status'] == 2)
								                    echo 'Enviado / Entregue';
												elseif($vet['status'] == 3)
								                    echo 'Cancelado';
												?>
											</td>
											<td>
												<a href="pedido.php?idpedido=<?=$vet['codigo']?>"><i class="fa fa-search"></i>&nbsp;ver</a>
											</td>
										</tr>
										<?
										}
										?>
									</tbody>
								</table>
							</div>
							<!-- wishlist-table-end -->
							<?
							}
							else
							{
							?>
							<p>Nenhum pedido encontrado.</p>
							<?
							}
							?>

							<div class="cart-button">
								<a  href="loja.php">
									<i class="fa fa-angle-left"></i>
									Acessar nossa loja
								</a>
								<br><br><br>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?
include("includes/footer.php");
?>
