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
//////////////////////////***********************************/////////////////////////////////////

function LlenarComboEstablecimiento(idtipoesta)
{
  	ajax=objetoAjax();
  	opcion=6;
  	ajax.open("POST", "ctr_CitasPorPaciente.php",true);
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
  	ajax.open("POST", "ctr_CitasPorPaciente.php",true);
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
   	ajax.open("POST", "ctr_CitasPorPaciente.php",true);
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


//FUNCION PARA BUSCAR DATOS DE LA SOLICITUD
function BuscarDatoscitas()
{	//alert("llega");
		opcion=1;
		idexpediente=document.getElementById('txtexpediente').value;
		fecha=document.getElementById('txtfecharecep').value;
		primernombre=document.getElementById('PrimerNombre').value;
		segundonombre=document.getElementById('SegundoNombre').value;
		primerapellido=document.getElementById('PrimerApellido').value;
		segundoapellido=document.getElementById('SegundoApellido').value;
		//especialidad=document.getElementById('cmbEspecialidad').value;
		IdEstab=document.getElementById('cmbEstablecimiento').value;
		IdServ=document.getElementById('CmbServicio').value;
		IdSubServ=document.getElementById('cmbSubServ').value;
		
		//idexpediente="";
		idsolicitud="";
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//archivo que realizar� la operacion ->actualizacion.php
		ajax.open("POST", "ctr_CitasPorPaciente.php",true);
		//muy importante este encabezado ya que hacemos uso de un formulario
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		//alert(segundonombre);
		ajax.send("idexpediente="+idexpediente+"&opcion="+opcion+"&idsolicitud="+idsolicitud+"&fecha="+fecha+"&primernombre="+primernombre+"&segundonombre="+segundonombre+"&primerapellido="+primerapellido+"&segundoapellido="+segundoapellido+"&IdEstab="+IdEstab+"&IdServ="+IdServ+"&IdSubServ="+IdSubServ);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				//mostrar los nuevos registros en esta capa
				document.getElementById('divBusqueda').innerHTML = ajax.responseText;
			//alert(ajax.responseText);
			}
	}	
	
	
}

function MostrarDatos(posicion)
{
		idexpediente=document.getElementById('idexpediente['+posicion+']').value;
		idsolicitud=document.getElementById('idsolicitud['+posicion+']').value;
		idexpediente=trim(idexpediente);
		idsolicitud=trim(idsolicitud);
		CargarDatosFormulario(idexpediente,idsolicitud);
		
 }

 
 function CargarDatosFormulario(idexpediente,idsolicitud)
{
	ajax=objetoAjax();
	opcion=2;
	//primernombre="";
	//segundonombre="";
	//primerapellido="";
	//segundoapellido="";
	//especialidad="";
	ajax.open("POST", "ctr_CitasPorPaciente.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	//ajax.send("opcion="+opcion+"&idexpediente="+idexpediente+"&idsolicitud="+idsolicitud);
	ajax.send("opcion="+opcion+"&idexpediente="+idexpediente+"&idsolicitud="+idsolicitud);
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) 
		{	 if (ajax.status == 200)
			{  //mostrar los nuevos registros en esta capa
			  document.getElementById('divSolicitud').innerHTML = ajax.responseText;
			 }
	     }
	}
}

function imprimiretiquetas()
{//cambiar imprimir  etiquetas1.php  por imprimir.php
	idexpediente=document.frmDatos.idexpediente.value;
	idsolicitud=document.frmDatos.idsolicitud.value;
		
	
	//alert idexpediente;
	ventana_secundaria = window.open("etiquetas.php?var1="+idexpediente+
									  "&var2="+idsolicitud,"etiquetas",										"width=400,height=600,menubar=no,location=no,scrollbars=yes") ;
		
}
//FUNCION PARA HABILITAR BOTON Y PROCESAR LA SOLICITUD CAMPBIANDO DE ESTADO
function HabilitarBoton(){
	//verificando que se haya obtenido datos de la consulta
	valor=document.getElementById('txtprecedencia').value;
	if(valor !=" ")
	{	
	    //VERIFICANDO QUE LA SOLICITUD HAYA SIDO PROCESADA
		//Cambia el estado de la solicitud
		CambiarEstadoSolicitud('R');
		CambiarEstadoDetalleSolicitud('TR');
		//Habilita el boton para la impresion
		div = document.getElementById('divoculto');
		div.style.display = "block";
		}
	else {
		alert("No se encontraron datos que procesar...");
	}
}

function Imprimir(){
document.getElementById('divImpresion').style.display="none";
document.getElementById('divInicial').style.display="none";
document.getElementById('divBusqueda').style.display="none";
window.print();
document.getElementById('divImpresion').style.display="block";
document.getElementById('divInicial').style.display="Block";
document.getElementById('divBusqueda').style.display="Block";
}

function Cerrar(){
	//window.opener.location.href = window.opener.location.href;
    window.close();
}

function VistaPrevia()
{

 idexpediente=document.getElementById('txtexpediente').value;
		fecha=document.getElementById('txtfecharecep').value;
		primernombre=document.getElementById('PrimerNombre').value;
		segundonombre=document.getElementById('SegundoNombre').value;
		primerapellido=document.getElementById('PrimerApellido').value;
		segundoapellido=document.getElementById('SegundoApellido').value;
		IdEstab=document.getElementById('cmbEstablecimiento').value;
		IdServ=document.getElementById('CmbServicio').value;
		IdSubServ=document.getElementById('cmbSubServ').value;
		//especialidad=document.getElementById('cmbEspecialidad').value;

		
		ventana_secundaria = window.open("ReporteCitas.php?var1="+idexpediente+
					  "&var2="+fecha+"&var3="+primernombre+
					  "&var4="+segundonombre+"&var5="+primerapellido+"&var6="+segundoapellido+"&var7="+IdEstab+"&var8="+IdServ+"&var9="+IdSubServ,"VistaReport","width=1250,height=575,menubar=no,scrollbars=yes") ;
  
 }
