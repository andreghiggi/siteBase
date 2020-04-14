<?php 
include('topo.inc.php');

if(isset($_POST['nome'])){
	$str = "insert into tabela(nome) values ('".$_POST['nome']."');";
	$rs  = mysql_query($str);
}
else if(isset($_POST['remover'])){
	$str = "delete from tabela where id like ".$_POST['remover'];
	$rs = mysql_query($str);
}

include('menu.inc.php'); 
?>
<section id="content">
<form method="POST">
	Nome: <input type="text" maxlength="60" name="nome" style="width:40%;">
	<input type="submit" value="Salvar">
</form>

<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Nome</th>
			<th style="width:10%;"></th>
		</tr>
	</thead>
	<tbody>
		<?php
			$resp = mysql_query('select * from tabela;');	
			while($row = mysql_fetch_array($resp)){
				echo '
					<tr>
						<td>'.$row['id'].'</td>
						<td>'.$row['nome'].'</td>
						<td>
							<form method="POST">
								<fieldset>
									<input type="hidden" value="'.$row['id'].'" name="remover">
									<input type="submit" value="Remover">
								</fieldset>
							</form>
						</td>
					</tr>
				';
			}
		?>
	</tbody>
</table>

</section>
<?php include('rodape.inc.php'); ?> 
