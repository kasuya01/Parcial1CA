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
///////////////////////////////////////////////////////////**********************/////////////////////////////////////////////////////////
function LlenarComboEstablecimiento(idtipoesta)
{
  	ajax=objetoAjax();
  	opcion=6;
  	ajax.open("POST", "ctrConsultaMuestrasPendientes.php",true);
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
  	ajax.open("POST", "ctrConsultaMuestrasPendientes.php",true);
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
   	ajax.open("POST", "ctrConsultaMuestrasPendientes.php",true);
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


//FUNCION PARA VERIFICAR SI EXISTEN  DATOS DE LA SOLICITUD
function MostrarDatos(posicion)
{
		idexpediente=document.getElementById('idexpediente['+posicion+']').value;
		idsolicitud=document.getElementById('idsolicitud['+posicion+']').value;
		idarea=document.getElementById('idarea['+posicion+']').value;
		idexamen=document.getElementById('idexamen['+posicion+']').value;
		idexpediente=trim(idexpediente);
		idsolicitud=trim(idsolicitud);
		switch (idarea)
		 { case "URI": 
			ventana_secundaria = window.open("DatosSolicitudesPorArea1.php?var1="+idexpediente+
										  "&var2="+idarea+"&var3="+idsolicitud+"&var4="+idexamen,"Datos1",											"width=850,height=475,menubar=no,scrollbars=yes") ;
			break;
			case "BAT": 
			ventana_secundaria = window.open("DatosSolicitudesPorArea1.php?var1="+idexpediente+
										  "&var2="+idarea+"&var3="+idsolicitud+"&var4="+idexamen,"Datos1",											"width=850,height=475,menubar=no,scrollbars=yes") ;
			break;
			
			default:
			ventana_secundaria = window.open("DatosSolicitudesPorArea.php?var1="+idexpediente+
										  "&var2="+idarea+"&var3="+idsolicitud+"&var4="+idexamen,"Datos",											"width=850,height=475,menubar=no,scrollbars=yes") ;
			break;
		}
 }




//FUNCION PARA CAMBIAR ESTADO DE CADA DETALLE DE LA SOLICITUD
function CambiarEstadoDetalleSolicitud(estado)
{
   		idsolicitud=document.frmDatos.idsolicitud.value;
		idexpediente=document.frmDatos.idexpediente.value;
		fechasolicitud=document.frmDatos.fechasolicitud.value;
		idarea=document.frmDatos.idarea.value;
		fecharecep="";
		idexamen="";
		pag="";
		opcion=3;
		
		//alert(estado);
		idsolicitud=trim(idsolicitud);
		idexpediente=trim(idexpediente);
		fechasolicitud=trim(fechasolicitud);
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//usando del medoto POST
		ajax.open("POST", "ctrMuestrasPendientes.php",true);
		//muy importante este encabezado ya que hacemos uso de un formulario
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idexpediente="+idexpediente+"&idarea="+idarea+"&fechasolicitud="+fechasolicitud+"&idsolicitud="+idsolicitud+"&opcion="+opcion+"&estado="+estado+"&idexamen="+idexamen+"&fecharecep="+fecharecep+"&pag="+pag);	
		ajax.onreadystatechange=function() 
		{
			if (ajax.readyState==4) 
			{
			    if (ajax.status == 200)
				{
					//mostrar los nuevos registros en esta capa
					//document.getElementById('divCambioEstado').innerHTML = ajax.responseText;	
					alert(ajax.responseText);					
				}
			}
	   }
	
}


function CambiarEstadoDetalleSolicitud1(estado,idexamen)
{
   		idsolicitud=document.frmDatos.idsolicitud.value;
		idexpediente=document.frmDatos.idexpediente.value;
		fechasolicitud=document.frmDatos.fechasolicitud.value;
		idarea=document.frmDatos.idarea.value;
		fecharecep="";
		pag="";
		//idexamen=document.frmDatos.// puse esto
		opcion=3;
		//alert(estado);
		idsolicitud=trim(idsolicitud);
		idexpediente=trim(idexpediente);
		fechasolicitud=trim(fechasolicitud);
		//observacion="";
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//usando del medoto POST
		ajax.open("POST", "ctrMuestrasPendientes.php",true);
		//muy importante este encabezado ya que hacemos uso de un formulario
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idexpediente="+idexpediente+"&idarea="+idarea+"&fechasolicitud="+fechasolicitud+"&idsolicitud="+idsolicitud+"&opcion="+opcion+"&estado="+estado+"&idexamen="+idexamen+"&fecharecep="+fecharecep+"&pag="+pag);	
		ajax.onreadystatechange=function() 
		{
			if (ajax.readyState==4) 
			{
			    if (ajax.status == 200)
				{
					//mostrar los nuevos registros en esta capa
					//document.getElementById('divCambioEstado').innerHTML = ajax.responseText;	
					alert(ajax.responseText);					
				}
			}
	   }
	
}

function ProcesarMuestra()
{
   //alert("Voy a cambiar estados");
   estado='PM'; //LOS DETALLES DE LA SOLICITUD SE LES HA PROCESADO LA MUESTRA
   CambiarEstadoDetalleSolicitud(estado);
  // window.close();
}

function ProcesarMuestra1(idexamen)
{
   //alert("Voy a cambiar estados");
 //  alert(idexamen);
   estado='PM'; //LOS DETALLES DE LA SOLICITUD SE LES HA PROCESADO LA MUESTRA
   CambiarEstadoDetalleSolicitud1(estado,idexamen)
  // window.close();
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
function RechazarMuestra()
{
   estado='RM'
   idsolicitud=document.frmDatos.idsolicitud.value;
   idexpediente=document.frmDatos.idexpediente.value;
   fechasolicitud=document.frmDatos.fechasolicitud.value;
	idarea=document.frmDatos.idarea.value;
	observacion=document.frmDatos.txtobservacion.value;
	opcion=4;
	idexamen="";
	fecharecep="";
	pag="";
	idsolicitud=trim(idsolicitud);
	idexpediente=trim(idexpediente);
	fechasolicitud=trim(fechasolicitud);
		//instanciamos el objetoAjax
	ajax=objetoAjax();
		//usando del medoto POST
	ajax.open("POST", "ctrMuestrasPendientes.php",true);
		//muy importante este encabezado ya que hacemos uso de un formulario
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
	//	ajax.send("idexpediente="+idexpediente+"&idarea="+idarea+"&fechasolicitud="+fechasolicitud+"&idsolicitud="+idsolicitud+"&opcion="+opcion+"&estado="+estado+"&idexamen="+idexamen+"&observacion="+observacion);	
	ajax.send("idexpediente="+idexpediente+"&idarea="+idarea+"&fechasolicitud="+fechasolicitud+"&idsolicitud="+idsolicitud+"&opcion="+opcion+"&estado="+estado+"&observacion="+escape(observacion)+"&idexamen="+idexamen+"&fecharecep="+fecharecep+"&pag="+pag)
	
	ajax.onreadystatechange=function() 
		{
			if (ajax.readyState==4) 
			{
			    if (ajax.status == 200)
				{
					//mostrar los nuevos registros en esta capa
					//document.getElementById('divCambioEstado').innerHTML = ajax.responseText;	
					alert(ajax.responseText);					
				}
			}
	   }
}

function RechazarMuestra1(idexamen)
{
   estado='RM'
   idsolicitud=document.frmDatos.idsolicitud.value;
   idexpediente=document.frmDatos.idexpediente.value;
   fechasolicitud=document.frmDatos.fechasolicitud.value;
	idarea=document.frmDatos.idarea.value;
	observacion=document.frmDatos.txtobservacion.value;
	opcion=4;
    fecharecep="";
	pag="";
	idsolicitud=trim(idsolicitud);
	idexpediente=trim(idexpediente);
	fechasolicitud=trim(fechasolicitud);
		//instanciamos el objetoAjax
	ajax=objetoAjax();
		//usando del medoto POST
	ajax.open("POST", "ctrMuestrasPendientes.php",true);
		//muy importante este encabezado ya que hacemos uso de un formulario
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idexpediente="+idexpediente+"&idarea="+idarea+"&fechasolicitud="+fechasolicitud+"&idsolicitud="+idsolicitud+"&opcion="+opcion+"&estado="+estado+"&idexamen="+idexamen+"&observacion="+observacion+"&fecharecep="+fecharecep+"&pag="+pag);	
		ajax.onreadystatechange=function() 
		{
			if (ajax.readyState==4) 
			{
			    if (ajax.status == 200)
				{
					//mostrar los nuevos registros en esta capa
					//document.getElementById('divCambioEstado').innerHTML = ajax.responseText;	
					alert(ajax.responseText);					
				}
			}
	   }
}
function MostrarObservacion(){
   if (document.getElementById('cmbProcesar').value=="N")
   {
		document.getElementById('divObservacion').style.display="block";
		document.getElementById('btnRechazar').disabled=false;
		document.getElementById('btnProcesar').disabled=true;
   }
   if (document.getElementById('cmbProcesar').value=="S")
   {
		document.getElementById('divObservacion').style.display="none";
		document.getElementById('btnRechazar').disabled=true;
		document.getElementById('btnProcesar').disabled=false;
		
   }
}

function CargarDatosFormulario(idexpediente,idsolicitud,idarea)
{
	ajax=objetoAjax();
	opcion=2;
	fechasolicitud="";
	estado="";
	idexamen="";
	fecharecep="";
	pag="";
	ajax.open("POST", "ctrMuestrasPendientes.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	ajax.send("opcion="+opcion+"&estado="+estado+"&idarea="+idarea+"&fechasolicitud="+fechasolicitud+"&idexpediente="+idexpediente+"&idsolicitud="+idsolicitud+"&idexamen="+idexamen+"&fecharecep="+fecharecep+"&pag="+pag);
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) 
		{	 if (ajax.status == 200)
			{  //mostrar los nuevos registros en esta capa
			  document.getElementById('divFormulario').innerHTML = ajax.responseText;
			 }
	     }
	}
}

function CargarDatosFormulario1(idexpediente,idsolicitud,idarea,idexamen)
{
	ajax=objetoAjax();
	opcion=2;
	fechasolicitud="";
	estado="";
	fecharecep="";
	pag="";
	//idexamen="";
	//observacion="";
	ajax.open("POST", "ctrMuestrasPendientes.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	ajax.send("opcion="+opcion+"&estado="+estado+"&idarea="+idarea+"&fechasolicitud="+fechasolicitud+"&idexpediente="+idexpediente+"&idsolicitud="+idsolicitud+"&idexamen="+idexamen+"&fecharecep="+fecharecep+"&pag="+pag);
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) 
		{	 if (ajax.status == 200)
			{  //mostrar los nuevos registros en esta capa
			  document.getElementById('divFormulario').innerHTML = ajax.responseText;
			 }
	     }
	}
}

function MuestrasPendientes()
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
	//idsolicitud="";
	
        
        
	//fechasolicitud="";
	
	ajax.open("POST", "ctrConsultaMuestrasPendientes.php",true);
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
	fecharecep=document.getElementById('txtfecharecep').value;
	IdEstab=document.getElementById('cmbEstablecimiento').value;
	IdServ=document.getElementById('CmbServicio').value;
	IdSubServ=document.getElementById('cmbSubServ').value;
	idexamen=document.getElementById('cmbExamen').value;
        PNombre=document.getElementById('PrimerNombre').value;
        SNombre=document.getElementById('SegundoNombre').value;
	PApellido=document.getElementById('PrimerApellido').value;
        SApellido=document.getElementById('SegundoApellido').value;
	//alert(IdEstab+"-"+idarea);
	ventana_secundaria = window.open("ReporteMuestrasPendientes.php?var1="+idarea+
			"&var2="+idexpediente+"&var3="+fecharecep+"&var4="+IdEstab+"&var5="+IdServ+"&var6="+IdSubServ+
			"&var7="+idexamen+"&var8="+PNombre+"&var9="+SNombre+"&var10="+PApellido+
			"&var11="+ SApellido,"VistaPendientes","width=1250,height=575,menubar=no,scrollbars=yes") ;
  
 }

