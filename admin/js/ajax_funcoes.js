try
{
	// tenta criar o objeto XMLHttpRequest (Firefox e navedores do projeto Mozilla, Safari, Opera)
	// Nota : Safari e Opera tem as implementaes incompletas, nos deixando usar apenas GET e POST
	xmlhttp = new XMLHttpRequest();
}
catch(ee)
{
	try
	{
		// caso ele no consiga
		// tenta criar o ActiveX Msxml2.XMLHTTP, referente aos IE de verso mais antigas (IE 5)
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			// caso no consiga novamente
			/// tenta criar o ActiveX Msxml2.XMLHTTP, referente aos IE de verso mais novas				
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E)
		{
			// caso no consiga novamente
			// o navevador no d? suporte ao objeto XMLHttpRequest
			// e por isso o objeto recebe false
			xmlhttp = false;
		}
	}
}

function lista_subcategorias() 
{
	idcategoria = document.getElementById('idcategoria').value;
	//alert(idsubcategoria);

	if (xmlhttp) 
	{	
		var hack = new Date(); 
		var dummy = hack.getTime();
		xmlhttp.open("post", "ajax_subcategorias.php?idcategoria=" + idcategoria + "&hack=" + dummy ,true);
		xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; iso-8859-1");
		xmlhttp.setRequestHeader("Cache-Control", "no-store, no-cache, must-revalidate");
		xmlhttp.setRequestHeader("Cache-Control", "post-check=0, pre-check=0");
		xmlhttp.setRequestHeader("Pragma", "no-cache");

		xmlhttp.onreadystatechange=function() 
		{
			if (xmlhttp.readyState == 4)
			{
				//alert(xmlhttp.responseText);
				if(xmlhttp.responseText != "Erro")
				{
					document.getElementById('subcategorias').innerHTML = xmlhttp.responseText;
				}
				else
				{
					document.getElementById('subcategorias').innerHTML = "";
				}
			}
		}
		
		xmlhttp.send(null);		
	}	
}