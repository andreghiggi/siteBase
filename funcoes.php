<?
// Reporta erros simples
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', '1');

$datahj 	= date("Y-m-d");
$datahora 	= date("Y-m-d H:i:s");

$diab = date("d");
$mesb = date("m");
$anob = date("Y");

$ip = getenv("REMOTE_ADDR");

/* PHP 5.6+ */

function mysql_query($string){return mysqli_query($GLOBALS['banco'],$string);}
function mysql_error(){return mysqli_error($GLOBALS['banco']);}
function mysql_num_rows($result){return mysqli_num_rows($result);}
function mysql_fetch_array($result){return mysqli_fetch_array($result);}
function mysql_insert_id(){return mysqli_insert_id($GLOBALS['banco']);}
function mysql_fetch_assoc($resul){return mysqli_fetch_assoc($result);}



function msg($string)
{
	print("<script>alert(unescape(\"$string\"));</script>");
}

function redireciona($string)
{
	print ("<script language='JavaScript'>self.location.href=\"$string\";</script>");
	die;
}

function window_open($url)
{
	print ("<script language='JavaScript'>window.open('".$url."', 'Impressao', 'width=1280, height=1024, left=100, top=100, scrollbars=yes');</script>");
	die;
}

function voltar()
{
	print("<script>javascript:history.go(-1)</script>");
}

function fechar()
{
	print("<script>window.close();</script>");
}

function reload()
{
	print("<script>window.opener.location.reload();</script>");
}

function ConverteData($Data)
{
	//verifica se tem a barra
	if(strstr($Data, "/") == TRUE)
	{
		$d = explode ("/", $Data);
		$rstData = "$d[2]-$d[1]-$d[0]";
		return $rstData;
	} 
	elseif(strstr($Data, "-") == TRUE)
	{
		$d = explode ("-", $Data);
		$rstData = "$d[2]/$d[1]/$d[0]"; 
		return $rstData;
	}
	else
	{
		return "Data invalida";
	}
}

function anti_injection($sql)
{
	// remove palavras que contenham sintaxe sql
	$sql = preg_replace(preg_quote("/(from|select|insert|delete|where|drop table|show tables|#|*|--|\\)/"),"",$sql);
	$sql = trim($sql);//limpa espa�os vazio
	$sql = strip_tags($sql);//tira tags html e php
	$sql = addslashes($sql);//Adiciona barras invertidas a uma string
	return $sql;
}

function ultimo_dia($ano, $mes)
{
	if (((fmod($ano, 4) == 0) and (fmod($ano, 100) != 0)) or (fmod($ano, 400) == 0)) 
	{
		$dias_fevereiro = 29;
	} 
	else 
	{
		$dias_fevereiro = 28;
	}
	
	switch($mes) 
	{
       case "01": return 31; break;
       case "02": return $dias_fevereiro; break;
       case "03": return 31; break;
       case "04": return 30; break;
       case "05": return 31; break;
       case "06": return 30; break;
       case "07": return 31; break;
       case "08": return 31; break;
       case "09": return 30; break;
       case "10": return 31; break;
       case "11": return 30; break;
       case "12": return 31; break;
	}
}

function mes_extenso($numMes)
{
	/* guardando o nome do mes */
	switch ($numMes)
	{
		case "1":
			$strMes = 'Janeiro';
			break;
		case "2":
			$strMes = 'Fevereiro';
			break;
		case "3":
			$strMes = 'Mar&ccedil;o';
			break;
		case "4":
			$strMes = 'Abril';
			break;
		case "5":
			$strMes = 'Maio';
			break;
		case "6":
			$strMes = 'Junho';
			break;
		case "7":
			$strMes = 'Julho';
			break;
		case "8":
			$strMes = 'Agosto';
			break;
		case "9":
			$strMes = 'Setembro';
			break;
		case "10":
			$strMes = 'Outubro';
			break;
		case "11":
			$strMes = 'Novembro';
			break;
		case "12":
			$strMes = 'Dezembro';
			break;
		default:
		
			$strMes = '';
			break;
	}
	
	return $strMes;
}

function mes_extenso_abrev($numMes)
{
	/* guardando o nome do mes */
	switch ($numMes)
	{
		case "01":
			$strMes = 'Jan';
			break;
		case "02":
			$strMes = 'Fev';
			break;
		case "03":
			$strMes = 'Mar';
			break;
		case "04":
			$strMes = 'Abr';
			break;
		case "05":
			$strMes = 'Mai';
			break;
		case "06":
			$strMes = 'Jun';
			break;
		case "07":
			$strMes = 'Jul';
			break;
		case "08":
			$strMes = 'Ago';
			break;
		case "09":
			$strMes = 'Set';
			break;
		case "10":
			$strMes = 'Out';
			break;
		case "11":
			$strMes = 'Nov';
			break;
		case "12":
			$strMes = 'Dez';
			break;
		default:
		
			$strMes = '';
			break;
	}
	
	return $strMes;
}

function data_extenso($data)
{
	$vet_data = explode("-", $data);

	$ano = $vet_data[0];
	$mes = $vet_data[1];
	$dia = $vet_data[2];

	return $dia.' de '.mes_extenso($mes).' de '.$ano;
}

function altera_nome_imagem($imagem)
{
	if($imagem != 'n')
	{
		$imagem = preg_replace('/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//IGNORE', $imagem));
		$imagem = str_replace(' ', '_', $imagem);

		$strpos = strpos($imagem, ".");
	    $nome_imagem = substr($imagem, 0, $strpos);
	    $ext = substr($imagem, $strpos);            
	    $imagem = $nome_imagem."_".date("Ymd").$ext;
	}
	else
	{
		$imagem = NULL;
	}

    return $imagem;
}

function calcula_frete($servico, $cep_origem, $cep_destino, $peso, $altura, $largura, $comprimento, $mao_propria)
{
    ////////////////////////////////////////////////
    // C�digo dos Servi�os dos Correios
    // 41106 PAC
    // 40010 SEDEX
    // 40045 SEDEX a Cobrar
    // 40215 SEDEX 10
    ////////////////////////////////////////////////
    // URL do WebService
    $correios = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=".$cep_origem."&sCepDestino=".$cep_destino."&nVlPeso=".$peso."&nCdFormato=1&nVlComprimento=".$comprimento."&nVlAltura=".$altura."&nVlLargura=".$largura."&sCdMaoPropria=".$mao_propria."&nVlValorDeclarado=100&sCdAvisoRecebimento=n&nCdServico=".$servico."&nVlDiametro=0&StrRetorno=xml";
    
    // Carrega o XML de Retorno
    $xml = simplexml_load_file($correios);
    // Verifica se n�o h� erros
    if($xml->cServico->Erro == '0')
	{
        return $xml->cServico->Valor.';'.$xml->cServico->PrazoEntrega;
    }
	else
	{
        return false;
    }
}

function img_produto_destaque($idproduto)
{
	$str = "SELECT * FROM produtos_imagens WHERE idproduto = '$idproduto' AND status = '1'";
    $rs  = mysql_query($str) or die(mysql_error());
    $vet = mysql_fetch_array($rs);

    return $vet['imagem'];
}

function categoria_to_id($idcategoria)
{
	$str = "SELECT * FROM produtos_categorias WHERE codigo = '$idcategoria' AND status = '1'";
    $rs  = mysql_query($str) or die(mysql_error());
    $vet = mysql_fetch_array($rs);

    $categoria = preg_replace('/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', stripslashes($vet['nome'])));
    $categoria = str_replace(' ', '_', $categoria);

    return $categoria;
}

function nome_categoria($idcategoria)
{
	$str = "SELECT * FROM produtos_categorias WHERE codigo = '$idcategoria' AND status = '1'";
    $rs  = mysql_query($str) or die(mysql_error());
    $vet = mysql_fetch_array($rs);

    return stripslashes($vet['nome']);
}

function box_tags()
{
	$str = "SELECT * FROM produtos WHERE status = '1' AND tags IS NOT NULL ORDER BY RAND() LIMIT 5";
    $rs  = mysql_query($str) or die(mysql_error());
    
    $array_tags = array();

    while($vet = mysql_fetch_array($rs))
    {
    	if(!strstr($vet['tags'], ";"))
    	{
    		$array_tags[] = $vet['tags'];
    	}
    	else
    	{
    		$array = explode(";", $vet['tags']);

    		for($i = 0; $i < count($array); $i++)
    			$array_tags[] = $array[$i];

    	}
    }

    $result = array_unique($array_tags);
    sort($result);

    if(count($result))
	{
	?>
	<!-- tag-area start-->
	<div class="tag-area">
		<div class="area-title">
			<h2>Tags</h2>
		</div>
		<div class="tag">
			<ul>
				<?
				for($i = 0; $i < count($result); $i++)
				{
					if($result[$i])
					{
				?>
				<li><a href="loja.php?tag=<?=base64_encode($result[$i])?>"><?=$result[$i]?></a></li>
				<?
					}
				}
				?>
			</ul>
		</div>
	</div>
	<!-- tag-area end-->
	<?
	}
}

function box_destaque($idproduto = false)
{
	$strWhere = "";
	if($idproduto)
		$strWhere = " AND codigo != '$idproduto'";

	$str = "SELECT * FROM produtos WHERE destaque = '1' AND status = '1' $strWhere ORDER BY codigo DESC LIMIT 10";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);

	if($num > 0)
	{
	?>
	<!-- special-product-area start-->
	<div class="special-product-area">
		<div class="area-title">
			<h2>Produtos em destaque</h2>
		</div>
		<div class="special-product-carosul">
			<?
			while($vet = mysql_fetch_array($rs))
			{
				$imagem = img_produto_destaque($vet['codigo']);
			?>
			<div class="special-product">
				<div class="single-product">
					<div class="image-area">
						<a href="produto.php?codigo=<?=$vet['codigo']?>">
							<img src="upload/<?=$imagem?>" alt="<?=stripslashes($vet['nome'])?>">
						</a>
						<!--div class="reduction">
							<span>-5%</span>
						</div-->
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
								<span class="new-price">
									R$ <?=number_format($vet['valor_produto'], 2, ',', '.')?>
								</span>
								<?
								}
								else
								{
								?>
								<span class="new-price">
									R$ <?=number_format($vet['valor_desconto'], 2, ',', '.')?>
								</span>
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
			<?
			}
			?>
		</div>
	</div>
	<!-- special-product-area end-->
	<?
	}
}

function produtos_mais_procurados($idproduto)
{
	$str = "UPDATE produtos SET visualizacoes = visualizacoes + '1' WHERE codigo = '$idproduto'";
	$rs  = mysql_query($str) or die(mysql_error());
}

function subtotal_carrinho($idcadastro, $idcarrinho, $servico = false, $c_cep, $n_pac)
{	
	//echo $idcadastro.', '.$idcarrinho.', '.$servico;

	$strF = "SELECT * FROM config_frete ";
	$rsF  = mysql_query($strF) or die(mysql_error());
	$numF = mysql_num_rows($rsF);
	$vetF = mysql_fetch_array($rsF);

    $str = "SELECT A.*, B.qtde, B.valor AS valor_pedido
		FROM produtos A
		INNER JOIN carrinho B ON A.codigo = B.idproduto
		WHERE idcadastro = '$idcadastro' 
		AND idcarrinho = '$idcarrinho'
		ORDER BY A.nome";
	$rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);
	
	$valor_compras = 0;
	$valor_frete = 0;
	$total = 0;
	
	if($num > 0)
	{
		while($vet = mysql_fetch_array($rs))
		{    
			$valor_compras += $vet['valor_pedido'] * $vet['qtde'];			
		}

		$valor_frete = 0;
		$prazo_entrega = 0;
		
		if($numF > 0 && !empty($servico) && $n_pac == 2)
		{
			$frete = calcula_frete($servico, $vetF['cep_origem'], $c_cep, $vetF['peso'], $vetF['altura'], $vetF['largura'], $vetF['comprimento'], $vetF['mao_propria']);
			$array_frete = explode(";", $frete);

			$valor_frete = str_replace(",", ".", $array_frete[0]);
			$prazo_entrega = $array_frete[1];
		}
		
		$total = $valor_compras + $valor_frete;
	}
				
    return $total;
}

function email_pedido($codigo)
{
    $str = "SELECT A.email 
		FROM cadastros A
		INNER JOIN pedidos B ON A.codigo = B.idcadastro 
		WHERE B.codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
    $vet = mysql_fetch_array($rs);
    
    return $vet['email'];
}

function valor_produto($valor_produto, $valor_desconto)
{
	if(!$valor_desconto)
	{
	?>
	<span class="new-price">R$ <?=number_format($valor_produto, 2, ',', '.')?></span>
	<?
	}
	else
	{
	?>
	<span class="new-price">R$ <?=number_format($valor_desconto, 2, ',', '.')?></span>									
	<span class="old-price">R$ <?=number_format($valor_produto, 2, ',', '.')?></span>
	<?	
	}
}

function total_pedidos()
{
	$str = "SELECT * FROM pedidos ORDER BY codigo";
	$rs  = mysql_query($str) or die(mysql_error());

	return mysql_num_rows($rs);
}

function total_pedidos_valor()
{
	$str = "SELECT SUM(A.valor + B.valor) AS total FROM pedidos A INNER JOIN frete B ON A.codigo = B.idpedido ORDER BY B.codigo";
	$rs  = mysql_query($str) or die(mysql_error());
	$vet = mysql_fetch_array($rs);

	return number_format($vet['total'], 2, ',', '.');
}

function total_vendas($status)
{
	$str = "SELECT * FROM pedidos WHERE status = '$status' ORDER BY codigo";
	$rs  = mysql_query($str) or die(mysql_error());

	return mysql_num_rows($rs);
}

function total_vendas_valor($status)
{
	$str = "SELECT SUM(A.valor + B.valor) AS total FROM pedidos A INNER JOIN frete B ON A.codigo = B.idpedido WHERE A.status = '$status' ORDER BY B.codigo";
	$rs  = mysql_query($str) or die(mysql_error());
	$vet = mysql_fetch_array($rs);

	return number_format($vet['total'], 2, ',', '.');
}

function total_produtos_ativos()
{
	$str = "SELECT * FROM produtos WHERE status = '1' ORDER BY codigo";
	$rs  = mysql_query($str) or die(mysql_error());

	return mysql_num_rows($rs);
}

function total_clientes()
{
	$str = "SELECT * FROM cadastros ORDER BY codigo";
	$rs  = mysql_query($str) or die(mysql_error());

	return mysql_num_rows($rs);
}

function verifica_varicacao_cores_tamanho($idproduto)
{
	$return = 0;

	//varia��o de cor e tamanho
	$strE = "SELECT * FROM produtos_estoque WHERE idproduto = '$idproduto' AND idtamanho > '0' AND idcor > '0'";
	$rsE  = mysql_query($strE) or die(mysql_error());
	$numE = mysql_num_rows($rsE);

	if($numE)
		$return = 1;

	//varia��o de tamanho
	$strE = "SELECT * FROM produtos_estoque WHERE idproduto = '$idproduto' AND idtamanho > '0' AND idcor = '0'";
	$rsE  = mysql_query($strE) or die(mysql_error());
	$numE = mysql_num_rows($rsE);

	if($numE)
		$return = 2;

	//varia��o de cor
	$strE = "SELECT * FROM produtos_estoque WHERE idproduto = '$idproduto' AND idtamanho = '0' AND idcor > '0'";
	$rsE  = mysql_query($strE) or die(mysql_error());
	$numE = mysql_num_rows($rsE);

	if($numE)
		$return = 3;

	return $return;
}
?>
