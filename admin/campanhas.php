<?php 
include('topo.inc.php');



include('menu.inc.php'); 
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<link href="select2/select2.min.css" rel="stylesheet" />
<script src="select2/select2.min.js"></script>


<section id="content">

<?php

if(isset($_POST['var_nome'])){
	if($_POST['var_categoria'] > -1){
		mysql_query("insert into campanha(descricao,categoria,desconto) values('".$_POST['var_nome']."',".$_POST['var_categoria'].",".$_POST['var_desconto'].")");
		mysql_query("update produtos set valor_desconto = valor_produto - ((valor_produto / 100) * ".$_POST['var_desconto'].") where idcategoria like ".$_POST['var_categoria']);
	}
	else if($_POST['var_subCategoria'] > -1){
		mysql_query("insert into campanha(descricao,subCategoria,desconto) values('".$_POST['var_nome']."',".$_POST['var_subCategoria'].",".$_POST['var_desconto'].")");
		mysql_query("update produtos set valor_desconto = valor_produto - ((valor_produto / 100) * ".$_POST['var_desconto'].") where idsubcategoria like ".$_POST['var_subCategoria']);
	}
	else if($_POST['var_produto'] > -1){
		mysql_query("insert into campanha(descricao,produto,desconto) values('".$_POST['var_nome']."',".$_POST['var_produto'].",".$_POST['var_desconto'].")");
		mysql_query("update produtos set valor_desconto = valor_produto - ((valor_produto / 100) * ".$_POST['var_desconto'].") where codigo like ".$_POST['var_produto']);
	}
	echo "<script>window.location.href='campanhas.php'</script>";
}

else if(isset($_GET['finalizar'])){
	$campanha = mysql_fetch_array(mysql_query("select * from campanha where id like ".$_GET['finalizar']));
	
	if($campanha['produto'] != null)
		mysql_query("update produtos set valor_desconto = null where codigo like ".$campanha['produto']);
		
	else if($campanha['categoria'] != null)
		mysql_query("update produtos set valor_desconto = null where idcategoria like ".$campanha['categoria']);
		
	else if($campanha['subCategoria'] != null)
		mysql_query("update produtos set valor_desconto = null where idsubcategoria like ".$campanha['subCategoria']);
	
	mysql_query("update campanha set ativa = 0 where id like ".$_GET['finalizar']);
	echo "<script>window.location.href='campanhas.php'</script>";
}

?>

<form id="form" name="form" method="post">
	<fieldset>
		
		<section>
			<label>Descrição:</label>
			<input type="text" id="var_nome" name="var_nome" value="<?=$vet['desconto']?>" style="width:50%;margin-top:1%;" maxlength="80" required>
		</section>
		
		<section>
			<label>Categoria:</label>
			<select id="var_categoria" name="var_categoria" style="width:25%;margin-top:1%;">
				<option value="-1" selected>Selecione a categoria</option>
				<?php
					$resp = mysql_query("select codigo,nome from produtos_categorias",$banco);
					while($row = mysql_fetch_array($resp)){
						echo '<option value="'.$row['codigo'].'">'.$row['nome'].'</option>';
					}				
				?>
			</select>
		</section>
		
		<section>
			<label>Subcategoria:</label>
			<select id="var_categoria" name="var_subCategoria" style="width:25%;margin-top:1%;">
				<option value="-1" selected>Selecione a subcategoria</option>
				<?php
					$resp = mysql_query("select codigo,nome from produtos_subcategorias",$banco);
					while($row = mysql_fetch_array($resp)){
						echo '<option value="'.$row['codigo'].'">'.$row['nome'].'</option>';
					}				
				?>
			</select>
		</section>
		
		<section>
			<label>Produto:</label>
			<select id="var_produto" name="var_produto" style="width:25%;margin-top:1%;">
				<option value="-1" selected>Selecione o produto</option>
				<?php
					$resp = mysql_query("select codigo,nome from produtos",$banco);
					while($row = mysql_fetch_array($resp)){
						echo '<option value="'.$row['codigo'].'">'.$row['nome'].'</option>';
					}				
				?>
			</select>
			<script>
				$('#var_produto').select2();
			</script>
		</section>
	
		<section>
			<label>Desconto(%):</label>
			<input type="number" id="var_desconto" name="var_desconto" value="<?=$vet['desconto']?>" style="width:10%;margin-top:1%;" step="0.01" required>
		</section>
		
		<section>
			<button class="i_tick icon">Salvar</button>
		</section>		
		
	</fieldset>
</form>
<script>
	function aceitarCadastro(self){
		let tr = $(self).parent().parent();
		$('#tipo').val('a');
		$('#id').val($($(tr).find('td')[0]).text());
		$('#tabela').val($(tr).find('select').val());
		$('#form_cadastro').submit();
	}
	function finalizar(id){
		window.location.href = "campanhas.php?finalizar="+id;
	}
</script>


<table class="table">
	<thead>
		<th style="width: 10%">Código</th>
		<th style="width: 30%">Produto/Categoria/Subcategoria</th>
		<th>Descrição</th>
		<th style="width: 10%">Desconto</th>
		<th style="width: 10%"></th>
	</thead>
	<tbody>
		<?php
			$resp = mysql_query("select * from campanha");
			while($row = mysql_fetch_array($resp)){
				$botao = ($row['ativa'] == 1)? '<button onclick="finalizar('.$row['id'].')">Finalizar</button>':'';
				
				if($row['produto'] != NULL)
					$campanha = mysql_fetch_array(mysql_query("select nome from produtos where codigo like ".$row['produto']))['nome'];
				else if($row['categoria'] != NULL)
					$campanha = mysql_fetch_array(mysql_query("select nome from produtos_categorias where codigo like ".$row['categoria']))['nome'];
				else if($row['subCategoria'] != NULL)
					$campanha = mysql_fetch_array(mysql_query("select nome from produtos_subcategorias where codigo like ".$row['subCategoria']))['nome'];
				
				echo '
					<tr>
						<td>'.$row['id'].'</td>
						<td>'.$campanha.'</td>
						<td>'.$row['descricao'].'</td>
						<td>'.$row['desconto'].'%</td>
						<td>'.$botao.'</td>
					</tr>
				';
			}
		?>
	</tbody>
</table>


</section>


<?php include('rodape.inc.php'); ?>    
