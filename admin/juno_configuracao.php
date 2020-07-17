<?php 
include('topo.inc.php');

include('../juno/juno.class.php');

$email = $_POST['email'];
$token = $_POST['token'];
$urlnotificacao = $_POST['urlnotificacao'];
$status = $_POST['status'];

if($_POST['cmd'] == "add")
{
	var_dump($_POST);

	//$rs  = mysql_query($str) or die(mysql_error());
	
	//redireciona("juno_configuracao.php?ind_msg=2");
}

if($_POST['cmd'] == "edit")
{
	$str = 'update juno_configuracao set
		sandbox = "'.$_POST['sandbox'].'",
		status = "'.$_POST['status'].'",
		name = "'.$_POST['name'].'",
		document = "'.$_POST['document'].'",
		email = "'.$_POST['email'].'",
		birthDate = "'.$_POST['birthDate'].'",
		phone = "'.$_POST['phone'].'",
		bussinesArea = "'.$_POST['bussinesArea'].'",
		linesOfBussines = "'.$_POST['linesOfBussines'].'",
		companyType = "'.$_POST['companyType'].'",
		street = "'.$_POST['street'].'",
		number = "'.$_POST['number'].'",
		complement = "'.$_POST['complement'].'",
		neighborhood = "'.$_POST['neighborhood'].'",
		city = "'.$_POST['city'].'",
		state = "'.$_POST['state'].'",
		postCode = "'.$_POST['postCode'].'",
		bankNumber = "'.$_POST['bankNumber'].'",
		agencyNumber = "'.$_POST['agencyNumber'].'",
		accountNumber = "'.$_POST['accountNumber'].'"
		accountComplementNumber = "'.$_POST['accountComplementNumber'].'",
		accountType = "'.$_POST['accountType'].'",
		accountHolder_name = "'.$_POST['accountHolder_name'].'",
		accountHolder_document = "'.$_POST['accountHolder_document'].'",
		type = "'.$_POST['type'].'"
	';
	#mysql_query($str);
	
	#if(mysql_error() != '')
	#	redireciona('juno_configuracao.php?ind_msg=3');
	
	$account = array(
		"type" => $_POST['type'],
		"name" => $_POST['name'],
		"document" => $_POST['document'],
		"email" => $_POST['email'],
		"birthDate" => $_POST['birthDate'],
		"phone" => $_POST['phone'],
		"businessArea" => $_POST['bussinesArea'],
		"linesOfBusiness" => $_POST['linesOfBussines'],
		"companyType" => $_POST['companyType'],
		"address" => array(
			"street" => $_POST['street'],
			"number" => $_POST['number'],
			"complement" => $_POST['complement'],
			"neighborhood" => $_POST['neighborhood'],
			"city" => $_POST['city'],
			"state" => $_POST['state'],
			"postCode" => $_POST['postCode']
		),
		"bankAccount" => array(
			"bankNumber" => $_POST['bankNumber'],
			"agencyNumber" => $_POST['agencyNumber'],
			"accountNumber" => $_POST['accountNumber'],
			"accountComplementNumber" => $_POST['accountComplementNumber'],
			"accountType" => $_POST['accountType'],
			"accountHolder" => array(
				"name" => $_POST['accountHolder_name'],
				"document" => $_POST['accountHolder_document']
			)
		)
	);

	var_dump($_POST['identificacao'][0],$_POST['self'][0]);
	$fotoId = explode('.',$_POST['identificacao'][0])[0].'_'.date('Ymd').'.'.explode('.',$_POST['identificacao'][0])[1];
	$fotoSelf = explode('.',$_POST['self'][0])[0].'_'.date('Ymd').'.'.explode('.',$_POST['self'][0])[1];
	echo '<img src="../upload/'.$fotoSelf.'">';

	/*if(!isset($_POST['recipient_token']) || $_POST['recipient_token'] == ''){
		$token = $juno->account($account)->resourceToken;
		mysql_query('update juno_configuracao set recipient_token');
		$juno->setRecipientToken($token);

		
	}*/


	#redireciona("juno_configuracao.php?ind_msg=2");
}

if($_GET['ind_msg'] == 1)
	$msg = '<div class="alert success">Dados cadastrados com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
	$msg = '<div class="alert success">Dados editados com sucesso!</div>';
elseif($_GET['ind_msg'] == 3)
    $msg = '<div class="alert success">Erro! os valores não foram salvos</div>';
	
$str = "SELECT * FROM juno_configuracao where id = 1";
$rs  = mysql_query($str) or die(mysql_error());
$num = mysql_num_rows($rs);
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
		
	//document.form.submit();
}
</script>			
<section id="content">
<div class="g12">
	<h1>Configurações Juno</h1>
	<p>Preencha os dados abaixo para configuração do módulo da Juno.</p>
   <?=$msg?>
    
	<form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <fieldset>
    	<input type="hidden" name="type" value="PAYMENT">

		<label>Informações sobre a empresa</label>

		<section>
            <label for="name">Nome Fantasia:</label>
            <div>
                <input type="text" id="name" name="name" value="<?php echo $config['nome'];?>" style="width:40%;" maxlength="80" required>
            </div>
        </section>

		<section>
            <label for="document">CPF/CNPJ:</label>
            <div>
                <input type="text" id="document" name="document" value="" style="width:40%;" maxlength="16" required>
				<br><span>Apenas números</span>
            </div>
        </section>

		<section>
            <label for="email">Email:</label>
            <div>
                <input type="text" id="email" name="email" value="" style="width:40%;" maxlength="80" required>
            </div>
        </section>

		<section>
            <label for="birthDate">Data de aniversário:</label>
            <div>
                <input type="date" id="birthDate" name="birthDate" value="" style="width:40%;" required>
            </div>
        </section>

		<section>
            <label for="phone">Telefone:</label>
            <div>
                <input type="text" id="phone" name="phone" value="" style="width:40%;" maxlength="16" required>
            </div>
        </section>

		<section>
            <label for="bussinesArea">Área de atuação:</label>
            <div>
                <select id="bussinesArea" name="bussinesArea" required>
					<option value="1000">Acessorios automotivos</option>
					<option value="1001">Agronomia e agricultura</option>
					<option value="1002">Agropecuária e venda de animais</option>
					<option value="1003">Alimentos e Bebidas</option>
					<option value="1004">Antiguidades e colecionaveis</option>
					<option value="1005">Arte e artesanato</option>
					<option value="1006">Artigos religiosos</option>
					<option value="1007">Beleza e cuidados pessoais</option>
					<option value="1008">Brinquedos e Jogos</option>
					<option value="1009">Construção civil e alvenaria</option>
					<option value="1010">Eletrodomesticos</option>
					<option value="1011">Esporte e lazer</option>
					<option value="1012">Gestante e bebê</option>
					<option value="1013">Joias e relojoaria</option>
					<option value="1014">Livros, apostilas, Cds e DVDs</option>
					<option value="1015">Maquinário industrial</option>
					<option value="1016">Material de limpeza</option>
					<option value="1017">Moda e vestuário</option>
					<option value="1018">Móveis e decoração</option>
					<option value="1019">Instrumentos musicais e equipamentos sonoros</option>
					<option value="1020">Papelaria e escritório</option>
					<option value="1021">Pet Shop</option>
					<option value="1022">Saúde e suplementos</option>
					<option value="1023">Sex Shop e artigos adultos</option>
					<option value="1024">Tecnologia, eletronicos e informatica</option>
					<option value="1025">Utensílios e objetos em geral</option>
					<option value="1026">Outros</option>
					<option value="1027">Drop Shipping</option>
					<option value="2000">Academia e esportes</option>
					<option value="2001">Advocacia</option>
					<option value="2002">Agronomia e agricultura</option>
					<option value="2003">Aluguel e condominio</option>
					<option value="2004">Clube de descontos e benefícios</option>
					<option value="2005">Veterinária e pet shop</option>
					<option value="2006">Arquitetura e decoração</option>
					<option value="2007">Assistencia tecnica</option>
					<option value="2008">Aulas Particulares</option>
					<option value="2009">Jardim e Botânica</option>
					<option value="2010">Cobranças e Dividas</option>
					<option value="2011">Construção civil e alvenaria</option>
					<option value="2012">Consultoria</option>
					<option value="2013">Contabilidade</option>
					<option value="2014">Cursos e treinamentos</option>
					<option value="2015">Desenvolvimento de sites aplicativos e afins</option>
					<option value="2016">Eventos, festas e entretenimento</option>
					<option value="2017">Hidráulica e elétrica</option>
					<option value="2018">Instituições de ensino</option>
					<option value="2019">Internet, hospedagem e dominio</option>
					<option value="2020">Limpeza e manutenção</option>
					<option value="2021">Manutençao de veículos</option>
					<option value="2022">Design, Marketing, fotografia e audiovisual</option>
					<option value="2023">Profissionais da saúde</option>
					<option value="2024">Representação comercial</option>
					<option value="2025">Registro de marcas e patentes</option>
					<option value="2026">Saúde e Beleza</option>
					<option value="2027">Segurança e sistema de controle</option>
					<option value="2028">Seguros, Planos e corretagem</option>
					<option value="2029">Serralheria e vidraçaria</option>
					<option value="2030">Transporte e logística</option>
					<option value="2031">Turismo e viagens</option>
					<option value="2032">TVs e sinais de antena</option>
					<option value="2033">Outros</option>
					<option value="3000">ONGs, Associações e Afins</option>
				</select>
            </div>
        </section>

		<section>
            <label for="linesOfBussines">Descrição da empresa:</label>
            <div>
                <input type="text" id="linesOfBussines" name="linesOfBussines" value="" style="width:40%;" maxlength="100" required>
            </div>
        </section>

		<section>
            <label for="companyType">Tipo da empresa:</label>
            <div>
                <select id="companyType" name="companyType" required>
					<option value="MEI" selected>MEI</option>
					<option value="EI">EI</option>
					<option value="EIRELI">EIRELI</option>
					<option value="LTDA">LTDA</option>
					<option value="SA">SA</option>
					<option value="INSTITUITION_NGO_ASSOCIATION">Instituição/ONG/Associação</option>
				</select>
            </div>
        </section>

		<label>Endereço</label>

		<section>
            <label for="street">Rua:</label>
            <div>
                <input type="text" id="street" name="street" value="" style="width:40%;" maxlength="30" required>
            </div>
        </section>

		<section>
            <label for="number">Número:</label>
            <div>
                <input type="text" id="number" name="number" value="" style="width:40%;" maxlength="10" required>
            </div>
        </section>

		<section>
            <label for="complement">Complemento:</label>
            <div>
                <input type="text" id="complement" name="complement" value="" style="width:40%;" maxlength="80">
            </div>
        </section>

		<section>
            <label for="neighborhood">Bairro:</label>
            <div>
                <input type="text" id="neighborhood" name="neighborhood" value="" style="width:40%;" maxlength="80" required>
            </div>
        </section>

		<section>
            <label for="city">Cidade:</label>
            <div>
                <input type="text" id="city" name="city" value="" style="width:40%;" maxlength="80" required>
            </div>
        </section>

		<section>
            <label for="state">Estado:</label>
            <div>
                <input type="text" id="state" name="state" value="" style="width:40%;" maxlength="2" required>
            </div>
        </section>

		<section>
            <label for="postCode">CEP:</label>
            <div>
                <input type="text" id="postCode" name="postCode" value="" style="width:40%;" maxlength="9" required>
            </div>
        </section>

		<label>Informações bancárias</label>

		<section>
            <label for="bankNumber">Número do banco:</label>
            <div>
                <input type="text" id="bankNumber" name="bankNumber" value="" style="width:40%;" maxlength="10" required>
            </div>
        </section>

		<section>
            <label for="agencyNumber">Número da agência:</label>
            <div>
                <input type="text" id="agencyNumber" name="agencyNumber" value="" style="width:40%;" maxlength="10" required>
            </div>
        </section>

		<section>
            <label for="accountNumber">Número da conta:</label>
            <div>
                <input type="text" id="accountNumber" name="accountNumber" value="" style="width:40%;" maxlength="30" required>
            </div>
        </section>

		<section>
            <label for="accountComplementNumber">Complemento:</label>
            <div>
                <select id="accountComplementNumber" name="accountComplementNumber" required>
					<option value="001">001</option>
					<option value="002">002</option>
					<option value="003">003</option>
					<option value="006">006</option>
					<option value="007">007</option>
					<option value="013">013</option>
					<option value="022">022</option>
					<option value="023">023</option>
					<option value="028">028</option>
					<option value="043">043</option>
					<option value="031">031</option>
				</select>
				<br><span>Complemento da conta a ser criada. Exclusivo e obrigatório apenas para contas Caixa.</span>
            </div>
        </section>

		<section>
            <label for="accountType">Tipo da conta:</label>
            <div>
                <select id="accountType" name="accountType" required>
					<option value="CHECKING">Conta corrente</option>
					<option value="SAVINGS">Conta poupança</option>
				</select>
            </div>
        </section>

		<section>
            <label for="accountHolder_name">Responsável:</label>
            <div>
                <input type="text" id="accountHolder_name" name="accountHolder_name" value="" style="width:40%;" maxlength="80" required>
            </div>
        </section>

		<section>
            <label for="accountHolder_document">CPF/CNPJ do responsável:</label>
            <div>
                <input type="text" id="accountHolder_document" name="accountHolder_document" value="" style="width:40%;" maxlength="16" required>
            </div>
        </section>

		<label>Documentos</label>

		<section>
            <label for="identificacao">Documento de identificação (RG ou CNH):</label>
            <div>
                <input type="file" id="identificacao" name="identificacao" value="" style="width:40%;" maxlength="16" required>
            </div>
        </section>

		<section>
            <label for="self">Selfie com documento de identificação:</label>
            <div>
                <input type="file" id="self" name="self" value="" style="width:40%;" maxlength="16" required>
            </div>
        </section>

		<label>Informações módulo</label>

		<section>
            <label for="recipient_token">Token:</label>
            <div>
                <input type="text" id="recipient_token" name="recipient_token" value="<?=$vet['recipient_token']?>" style="width:40%;" readonly>
				<br><span>Valor gerado automaticamente</span>
            </div>
        </section>

        <section>
            <label for="sandbox">Sandbox:</label>
            <div>
                <select type="text" id="sandbox" name="sandbox">
					<option value="1" <?= $vet['sandbox'] == 1?'selected':'';?>>Ativo</option>
					<option value="0" <?= $vet['sandbox'] == 0?'selected':'';?>>Inativo</option>
				</select>
            </div>
        </section>

		<section>
			<label>Status</label>
			<div>
				<select id="status" name="status">
					<option value="0" selected>Inativo</option>
					<option value="1" >Ativo</option>
				</select>
			</div>
		</section>
        
        <section>
            <?
			if(!$num)
			{
			?>
				<div><button class="i_tick icon" id="formsubmitswitcher" onClick="javascript: valida(1);" >Cadastrar</button></div>
			<?
			}
			else
			{
			?>
				<div><button class="i_refresh_3 icon" id="formsubmitswitcher" onClick="javascript: valida(2);" >Alterar</button></div>
			<?
			}
			?>
    	</section>

    </fieldset>
   	</form>
</div>
</section>
        
<?php include('rodape.inc.php'); ?>    
