function objetoAjax(){
	var xmlhttp=false;
	try{
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	}catch(e){
		try{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}catch(E){
			xmlhttp = false;
  		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
//////////////////////////*************FUNCIONES PARA EL MANEJO DE CADENAS ELININACION DE ESPACIOS EN BLANCO **********//////////////////////
function trim( str ) 
{
	var resultstr = "";

	resultstr = trimleft(str);
	resultstr = trimright(resultstr);
	return resultstr;
}

function trimright( str ) {
	var resultStr = "";
	var i = 0;

	// Return immediately if an invalid value was passed in
	if (str+"" == "undefined" || str == null)	
		return null;

	// Make sure the argument is a string
	str += "";
	
	if (str.length == 0) 
		resultStr = "";
	else {
  		// Loop through string starting at the end as long as there
  		// are spaces.
  		i = str.length - 1;
  		while ((i >= 0) && (str.charAt(i) == " "))
 			i--;
 			
 		// When the loop is done, we're sitting at the last non-space char,
 		// so return that char plus all previous chars of the string.
  		resultStr = str.substring(0, i + 1);
  	}
  	
  	return resultStr;  	
}

function trimleft(str) { 
	for(var k = 0; k < str.length && isWhitespace(str.charAt(k)); k++);
	return str.substring(k, str.length);
}

function isWhitespace(charToCheck) {
	var whitespaceChars = " \t\n\r\f";
	return (whitespaceChars.indexOf(charToCheck) != -1);
}
///////////////////////////////////////////////////////////***********************************/////////////////////////////////////////////////////////

function LlenarComboEstablecimiento(idtipoesta)
{
  	ajax=objetoAjax();
  	opcion=6;
  	ajax.open("POST", "ctrConsultaMuestrasRechazadas.php",true);
  	//muy importante este encabezado ya que hacemos uso de un formulario
  	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  	//enviando los valores
	ajax.send("opcion="+opcion+"&idtipoesta="+idtipoesta);	
	ajax.onreadystatechange=function() 
	{
		
		if (ajax.readyState == 4){//4 The request is complete
			if (ajax.status == 200){//200 means no error.
				respuesta = ajax.responseText;	
				// alert (respuesta)
				document.getElementById('divEstablecimiento').innerHTML = respuesta;
			}	  	
		}
   	}
}

function LlenarComboServicio(IdServicio)
{
	ajax=objetoAjax();
	opcion=7;
  	ajax.open("POST", "ctrConsultaMuestrasRechazadas.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("opcion="+opcion+"&IdServicio="+IdServicio);	 
	ajax.onreadystatechange=function() 
	{
		
		if (ajax.readyState == 4){//4 The request is complete
			if (ajax.status == 200){//200 means no error.
	  			respuesta = ajax.responseText;	
				document.getElementById('divsubserv').innerHTML = respuesta;
	 		}	  	
   	 	}
   	}
}

function LlenarComboExamen(idarea)
{
 	ajax=objetoAjax();
	opcion=5;
   	ajax.open("POST", "ctrConsultaMuestrasRechazadas.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	ajax.send("opcion="+opcion+"&idarea="+idarea);	 
	ajax.onreadystatechange=function() 
	{
		
		if (ajax.readyState == 4){//4 The request is complete
			if (ajax.status == 200){//200 means no error.
	  			//respuesta = ajax.responseText;	
	 			// alert (respuesta)
		 document.getElementById('divExamen').innerHTML = ajax.responseText;
			}	  	
			
   		}
   	}
}




//CambiarEstadoDetalleSolicitud1(estado,idexamen)
function Cerrar(){
	//window.opener.location.href = window.opener.location.href;
    window.close();
}
/*function RechazarMuestra()
{
   alert("Voy a cambiar estados");
}*/
//function RechazarMuestra(idexamen)






function MuestrasRechazadas()
{
	ajax=objetoAjax();
	opcion=1;
	idarea=document.getElementById('cmbArea').value;
	idexpediente=document.getElementById('txtexpediente').value;
	fecharecep=document.getElementById('txtfecharecep').value;
	IdEstab=document.getElementById('cmbEstablecimiento').value;
	IdServ=document.getElementById('CmbServicio').value;
	IdSubServ=document.getElementById('cmbSubServ').value;
	idexamen=document.getElementById('cmbExamen').value;
        PNombre=document.getElementById('PrimerNombre').value;
        SNombre=document.getElementById('SegundoNombre').value;
	PApellido=document.getElementById('PrimerApellido').value;
        SApellido=document.getElementById('SegundoApellido').value;

	ajax.open("POST", "ctrConsultaMuestrasRechazadas.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	ajax.send("opcion="+opcion+"&idarea="+idarea+"&idexpediente="+idexpediente+"&idexamen="+idexamen+"&fecharecep="+fecharecep+"&IdEstab="+IdEstab+"&IdServ="+IdServ+"&IdSubServ="+IdSubServ+"&idexamen="+idexamen+"&PNombre="+PNombre+"&SNombre="+SNombre+"&PApellido="+PApellido+"&SApellido="+SApellido);
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) 
		{	 if (ajax.status == 200)
			{  //mostrar los nuevos registros en esta capa
			   document.getElementById('divBusqueda').innerHTML = ajax.responseText;
			   document.getElementById('divResultado').style.display= "none"
			}
	     }
	}
}

 function VistaPrevia()
{

	idarea=document.getElementById('cmbArea').value;
	idexpediente=document.getElementById('txtexpediente').value;
	fecharecep=document.getElementById('txtfecharecep').value
	IdEstab=document.getElementById('cmbEstablecimiento').value;
	IdServ=document.getElementById('CmbServicio').value;
	IdSubServ=document.getElementById('cmbSubServ').value;
	idexamen=document.getElementById('cmbExamen').value;
        PNombre=document.getElementById('PrimerNombre').value;
        SNombre=document.getElementById('SegundoNombre').value;
	PApellido=document.getElementById('PrimerApellido').value;
        SApellido=document.getElementById('SegundoApellido').value;
	ventana_secundaria = window.open("ReporteMuestrasRechazadas.php?var1="+idarea+
				  "&var2="+idexpediente+"&var3="+fecharecep+"&var4="+IdEstab+"&var5="+IdServ+"&var6="+IdSubServ+
			"&var7="+idexamen+"&var8="+PNombre+"&var9="+SNombre+"&var10="+PApellido+
			"&var11="+ SApellido,"VistaRechazadas","width=1250,height=575,menubar=no,scrollbars=yes") ;
  
 }

