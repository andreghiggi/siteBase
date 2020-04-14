/*------------------------------------------------------*/
/*	VARIAVEIS GLOBAIS DO SISTEMA DE VALIDACAO DE DADOS	*/
/*------------------------------------------------------*/
var _perm = new Array();
var _mess = new Array();
var _conf = new Array();
var _gMsg = "";
var _isIE = false, _isNav = false;

/*------------------------------------------*/
/*	IDENTIFICACAO DO NAVEGADOR DO USUARIO		*/
/*------------------------------------------*/
if (navigator.appName.indexOf("Netscape") != -1) _isNav = true;
if (navigator.appName.indexOf("Microsoft") != -1 ) _isIE = true;

function MM_openBrWindow(theURL,winName,features) 
{
  	window.open(theURL,winName,features);
}

function MM_reloadPage(init) 
{ 
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}

function timedRefresh(timeoutPeriod) 
{
	setTimeout("location.reload(true);",timeoutPeriod);
}

function getgoing()
{ 
    document.form.location.href = "chat.php"; 
    setTimeout('getgoing()',1000); 
}

MM_reloadPage(true);

var isNav, isIE, indVisivel
var browser = navigator.appName

// Descobre o browser
if (browser == "Microsoft Internet Explorer") 
{ 
  isIE = true;
}
else {
  isNav = true;
}

function MM_showHideLayers() { //v9.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) 
  with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
   if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}

}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_displayStatusMsg(msgStr) { //v1.0
  status=msgStr;
  document.MM_returnValue = true;
}

function check_email (emailStr)
{
	var emailPat = /^(.+)@(.+)$/
	var specialChars = "\\(\\)<>@,;:\\\\\\\"\\.\\[\\]"
	var validChars = "\[^\\s" + specialChars + "\]"
	var quotedUser = "(\"[^\"]*\")"
	var ipDomainPat = /^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/
	var atom = validChars + '+'
	var word = "(" + atom + "|" + quotedUser + ")"
	var userPat = new RegExp("^" + word + "(\\." + word + ")*$")
	var domainPat = new RegExp("^" + atom + "(\\." + atom +")*$")

	var matchArray = emailStr.match(emailPat)
	if (matchArray == null)
	{
		return false;
	}

	var user = matchArray[1]
	var domain = matchArray[2]

	if (user.match(userPat) == null)
	{
		return false;
	}

	var IPArray = domain.match(ipDomainPat)

	if (IPArray != null)
	{
		for (var i=1;i<=4;i++)
		{
			if (IPArray[i]>255)
			{
			return false;
			}
		}
		return true;
	}

	var domainArray = domain.match(domainPat)

	if (domainArray == null)
	{
		return false;
	}
	
	var atomPat = new RegExp(atom,"g")
	var domArr = domain.match(atomPat)
	var len = domArr.length
	var pos_arroba = emailStr.indexOf("@");
	var str_pos_arroba = emailStr.substr(pos_arroba+1);
	var ponto = str_pos_arroba.indexOf(".");
	var str_dominio = emailStr.substr(pos_arroba, ponto);
	
	if((str_dominio.length) <= 1)
	{
		return false;
	}
	
	if (domArr[domArr.length-1].length < 2 || domArr[domArr.length-1].length > 3)
	{
  		return false;
	}

	if (len < 2)
	{
  		return false;
	}
	return true;
}

function somenteLetrasMinusculasNumeros(evt)
{
	var key;
	if (isNav || isIE)
	{
		key = (isNav)? evt.which: evt.keyCode;
		if((key >= 97 && key <= 122) || (key > 47 && key < 58) || key == 0 || key == 8)
		{
			return true;
		}
		else
		{
			alert("Permitido somente letras min\u00FAsculas e números");
			return false;
		}
	}
	return true;
}

function somenteLetrasNumeros(evt)
{
	var key;
	if (isNav || isIE)
	{
		key = (isNav)? evt.which: evt.keyCode;
		if((key >= 64 && key <= 122) || (key > 47 && key < 58) || key == 0 || key == 8)
		{
			return true;
		}
		else
		{
			alert("Permitido somente letras e n\u00FAmeros");
			return false;
		}
	}
	return true;
}

function somenteNumeros(evt)
{
	var key;
	if (isNav || isIE)
	{
		key = (isNav)? evt.which: evt.keyCode;
		if((key > 47 && key < 58) || key == 0 || key == 8)
		{
			return true;
		}
		else
		{
			//alert("Permitido somente n\u00FAmeros");
			return false;
		}
	}
	return true;
}

function somenteNumerosPonto(evt)
{
	var key;
	if (isNav || isIE)
	{
		key = (isNav)? evt.which: evt.keyCode;
		if((key > 47 && key < 58) || key == 0 || key == 8 || key == 46)
		{
			return true;
		}
		else
		{
			//alert("Permitido somente n\u00FAmeros");
			return false;
		}
	}
	return true;
}

function auto_data(evt)
{
	obj = document.getElementById(evt);
	vl  = obj.value;
	l   = vl.toString().length;
	
	switch(l)
	{
		case 2:
			obj.value = vl + "/";
		break;
		case 5:
			obj.value = vl + "/";
		break;
	}
}

function auto_tel(evt)
{
	obj = document.getElementById(evt);
	vl  = obj.value;
	l   = vl.toString().length;
	
	switch(l)
	{		
		case 2:
			obj.value = "(" + vl + ") ";
		break;
		case 9:
			obj.value = vl + "-";
		break;
	}
}

function auto_cel(evt)
{
	obj = document.getElementById(evt);
	vl  = obj.value;
	l   = vl.toString().length;
	
	switch(l)
	{		
		case 2:
			obj.value = "(" + vl + ") ";
		break;
		case 10:
			obj.value = vl + "-";
		break;
	}
}

function auto_cep(evt)
{
	obj = document.getElementById(evt);
	vl  = obj.value;
	l   = vl.toString().length;
	
	switch(l)
	{
		case 5:
			obj.value = vl + "-";
		break;
	}
}

function auto_cpf(evt)
{
	obj = document.getElementById(evt);
	vl  = obj.value;
	l   = vl.toString().length;
	
	switch(l)
	{
		case 3:
			obj.value = vl + ".";
		break;
		case 7:
			obj.value = vl + ".";
		break;
		case 11:
			obj.value = vl + "-";
		break;
	}
}

function auto_cnpj(evt)
{
	obj = document.getElementById(evt);
	vl  = obj.value;
	l   = vl.toString().length;
	
	switch(l)
	{
		case 2:
			obj.value = vl + ".";
		break;
		case 6:
			obj.value = vl + ".";
		break;
		case 10:
			obj.value = vl + "/";
		break;
		case 15:
			obj.value = vl + "-";
		break;
	}
}

function auto_horario(evt)
{
	obj = document.getElementById(evt);
	vl  = obj.value;
	l   = vl.toString().length;
	
	switch(l)
	{
		case 2:
			obj.value = vl + ":";
		break;
	}
}

function auto_valor(evt)
{
	obj = document.getElementById(evt);
	vl  = obj.value;
	
	v = vl.replace(/\D/g,""); //Remove tudo o que não é dígito
	v = v.replace(/(\d{2})$/,",$1"); //Coloca a virgula
	v = v.replace(/(\d+)(\d{3},\d{2})$/g,"$1.$2"); //Coloca o primeiro ponto
	var qtdLoop = (v.length-3) / 3;
	var count = 0;
	
	while (qtdLoop > count)
	{
		count++;
		v = v.replace(/(\d+)(\d{3}.*)/,"$1.$2"); //Coloca o resto dos pontos
	}
	
	v = v.replace(/^(0)(\d)/g,"$2"); //Coloca hífen entre o quarto e o quinto dígitos
	
	obj.value = v;
}

function formatar_moeda(campo, separador_milhar, separador_decimal, tecla) 
{
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? tecla.which : tecla.keyCode;

	if (whichCode == 13) 
		return true; // Tecla Enter
		
	if (whichCode == 8) 
		return true; // Tecla Delete
	
	key = String.fromCharCode(whichCode); // Pegando o valor digitado
	
	if (strCheck.indexOf(key) == -1) 
		return false; // Valor inválido (não inteiro)
	
	len = campo.value.length;
	
	for(i = 0; i < len; i++)
		if ((campo.value.charAt(i) != '0') && (campo.value.charAt(i) != separador_decimal)) 
			break;
	
	aux = '';
	
	for(; i < len; i++)
		if (strCheck.indexOf(campo.value.charAt(i))!=-1) 
			aux += campo.value.charAt(i);
			
		aux += key;
		len = aux.length;
	
		if (len == 0) 
			campo.value = '';
			
		if (len == 1) 
			campo.value = '0'+ separador_decimal + '0' + aux;
	
		if (len == 2) 
			campo.value = '0'+ separador_decimal + aux;

		if (len > 2) 
		{
			aux2 = '';

			for (j = 0, i = len - 3; i >= 0; i--) 
			{
				if (j == 3) 
				{
					aux2 += separador_milhar;
					j = 0;
				}
				
				aux2 += aux.charAt(i);
				j++;
			}

			campo.value = '';
			len2 = aux2.length;
		
			for (i = len2 - 1; i >= 0; i--)
				campo.value += aux2.charAt(i);
				
			campo.value += separador_decimal + aux.substr(len - 2, len);
		}

		return false;
}

function valida_cpf(StrCPF)
{
	x 		= 0;
	soma 	= 0;
	dig1 	= 0;
	dig2 	= 0;
	texto 	= "";
	StrCPF1	="";
	len 	= StrCPF.length; 
	x 		= len -1;
	
	for (var i=0; i <= len - 3; i++)
	{
		y 		= StrCPF.substring(i,i+1);
		soma 	= soma + ( y * x);
		x 		= x - 1;
		texto 	= texto + y;
	}
	
	dig1 = 11 - (soma % 11);
	
	if (dig1 == 10) 
		dig1 = 0;
	if (dig1 == 11) 
		dig1 = 0;
		
	StrCPF1 = StrCPF.substring(0,len - 2) + dig1 ;
	x 		= 11; 
	soma	= 0;
	
	for (var i=0; i <= len - 2; i++)
	{
		soma 	= soma + (StrCPF1.substring(i,i+1) * x);
		x 		= x - 1;
	}
	
	dig2 = 11 - (soma % 11);
	
	if (dig2 == 10) 
		dig2 = 0;
		
	if (dig2 == 11) 
		dig2 = 0;
		
	if ((dig1 + "" + dig2) == StrCPF.substring(len,len-2))
	{
		if (StrCPF != "00000000000" && StrCPF != "11111111111" && StrCPF != "22222222222" && StrCPF != "33333333333" && StrCPF != "44444444444" && StrCPF != "55555555555" && StrCPF != "66666666666" && StrCPF != "77777777777" && StrCPF != "88888888888" && StrCPF != "99999999999")
		{
			return true;
		}
	}
	return false;
}


function valida_cnpj(cnpj)
{
	var i = 0;
  	var l = 0;
  	var strNum = "";
  	var strMul = "6543298765432";
  	var character = "";
  	var iValido = 1;
  	var iSoma = 0;
  	var strNum_base = "";
  	var iLenNum_base = 0;
 	var iLenMul = 0;
  	var iSoma = 0;
  	var strNum_base = 0;
  	var iLenNum_base = 0;

  	if (cnpj == "")
    	return false;

  	l = cnpj.length;
  	for (i = 0; i < l; i++) 
	{
		caracter = cnpj.substring(i,i+1)
		if ((caracter >= '0') && (caracter <= '9'))
			strNum = strNum + caracter;
  	}

  	if(strNum.length != 14)
    	return false;

  	strNum_base = strNum.substring(0,12);
  	iLenNum_base = strNum_base.length - 1;
  	iLenMul = strMul.length - 1;
  	for(i = 0;i < 12; i++)
    	iSoma = iSoma + parseInt(strNum_base.substring((iLenNum_base-i),(iLenNum_base-i)+1),10) * parseInt(strMul.substring((iLenMul-i),(iLenMul-i)+1),10);

  	iSoma = 11 - (iSoma - Math.floor(iSoma/11) * 11);
  	if(iSoma == 11 || iSoma == 10)
        iSoma = 0;

  	strNum_base = strNum_base + iSoma;
  	iSoma = 0;
  	iLenNum_base = strNum_base.length - 1
  	for(i = 0; i < 13; i++)
        iSoma = iSoma + parseInt(strNum_base.substring((iLenNum_base-i),(iLenNum_base-i)+1),10) * parseInt(strMul.substring((iLenMul-i),(iLenMul-i)+1),10)

  	iSoma = 11 - (iSoma - Math.floor(iSoma/11) * 11);
  	if(iSoma == 11 || iSoma == 10)
        iSoma = 0;
  
  	strNum_base = strNum_base + iSoma;
  
  	if(strNum != strNum_base)
        return false;

  	return true;
}

function cronometro(segundos, idcurso, idavaliacao, ind) 
{
	if(segundos == 0)
		finalizar_avaliacao(idcurso, idavaliacao, segundos);
		
	horas 	 = parseInt(segundos / (60 * 60));
	minutos  = parseInt((segundos - (horas * 60 * 60)) / 60);
	seg 	 = parseInt(segundos - (horas * 60 * 60) - (minutos * 60));
	
	str_segundo = new String (seg)
    if (str_segundo.length == 1)
       seg = "0" + seg

    str_minuto = new String (minutos)
    if (str_minuto.length == 1)
       minutos = "0" + minutos

    str_hora = new String (horas)
    if (str_hora.length == 1)
       horas = "0" + horas
	
	//ind = 1 -> Testar avaliação
	//ind = 2 -> Ver avaliação
	if(ind == 1 && segundos >= 0)
	{
		segundos--;
		
		exibir_cronometro = horas + ":" + minutos + ":" + seg;
		document.getElementById("relogio").innerHTML = exibir_cronometro;
	
		setTimeout(function(){cronometro(segundos, idcurso, idavaliacao, ind)}, 1000);
	}
}