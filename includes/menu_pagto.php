<div class="row">
	<div class="col-lg-12 col-md-12 hidden-xs">
		<div class="order-step">
			<ul>
				<li <?=(strstr($_SERVER['REQUEST_URI'], "carrinho")) ? 'class="current-step"' : ''?> style="width: 33.33%;"><span>01. Lista de produtos</span></li>
				<li <?=(strstr($_SERVER['REQUEST_URI'], "endereco")) ? 'class="current-step"' : ''?> style="width: 33.33%;"><span>02. Endereço de entrega</span></li>
				<li <?=(strstr($_SERVER['REQUEST_URI'], "pagto")) ? 'class="current-step"' : ''?> style="width: 33.33%;"><span>03. Finalizar compra</span></li>
			</ul>
		</div>
	</div>
</div>