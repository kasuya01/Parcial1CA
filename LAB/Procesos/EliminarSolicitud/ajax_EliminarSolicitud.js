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
/////////////////////////////////////***********************************//////////////////////////
function LlenarComboEstablecimiento(idtipoesta)
{
  	ajax=objetoAjax();
  	opcion=6;
  	ajax.open("POST", "ctrEliminarSolicitud.php",true);
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
                                 $("#cmbEstablecimiento").select2({
                                 allowClear: true,
                                 dropdownAutoWidth: true
                });
			}	  	
		}
   	}
}

function LlenarComboServicio(IdServicio)
{
	ajax=objetoAjax();
	opcion=7;
  	ajax.open("POST", "ctrEliminarSolicitud.php",true);
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
   	ajax.open("POST", "ctrEliminarSolicitud.php",true);
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
function BuscarDatos()
{	
		opcion=1;
		idexpediente=document.getElementById('txtexpediente').value;
		primernombre=document.getElementById('PrimerNombre').value;
		segundonombre=document.getElementById('SegundoNombre').value;
		primerapellido=document.getElementById('PrimerApellido').value;
		segundoapellido=document.getElementById('SegundoApellido').value;
		//especialidad=document.getElementById('cmbEspecialidad').value;
		fecharecep=document.getElementById('txtfechaRecep').value;
		IdEstab=document.getElementById('cmbEstablecimiento').value;
		IdServ=document.getElementById('CmbServicio').value;
		IdSubServ=document.getElementById('cmbSubServ').value;
		//idexamen=document.getElementById('cmbExamen').value;
		
		//idsolicitud="";
		//instanciamos el objetoAjax
		ajax=objetoAjax();
		//archivo que realizar� la operacion ->actualizacion.php
		ajax.open("POST", "ctrEliminarSolicitud.php",true);
		//muy importante este encabezado ya que hacemos uso de un formulario
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idexpediente="+idexpediente+"&opcion="+opcion+"&primernombre="+escape(primernombre)+"&segundonombre="+escape(segundonombre)+"&primerapellido="+escape(primerapellido)+"&segundoapellido="+escape(segundoapellido)+"&fecharecep="+fecharecep+"&IdEstab="+IdEstab+"&IdServ="+IdServ+"&IdSubServ="+IdSubServ);
//+"&idexamen="+idexamen
//+"&especialidad="+especialidad
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
		//alert(idexpediente+"-"+idsolicitud);
		idsolicitud=trim(idsolicitud);
                
               // ventana_secundaria = window.open("DatosSolicitudesPorArea1.php?var1=" + idexpediente +
            //"&var2=" + idarea + "&var3=" + idsolicitud  +  "&var4=" + idexamen, "Datos1", "width=850,height=475,menubar=no,scrollbars=yes");
		CargarDatosFormulario(idexpediente,idsolicitud);  
 }

 function CargarDatosFormulario(idexpediente,idsolicitud)
{
	ajax=objetoAjax();
	opcion=3;
	
	ajax.open("POST", "ctrEliminarSolicitud.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	
	ajax.send("opcion="+opcion+"&idexpediente="+idexpediente+"&idsolicitud="+idsolicitud);
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) 
		{	 if (ajax.status == 200)
			{  //mostrar los nuevos registros en esta capa
			  document.getElementById('divSolicitud').innerHTML = ajax.responseText;
                          document.getElementById('divSolicitud').scrollIntoView()
			}
	        }
	}
}

function EliminarDatos(iddetalle,idsolicitud,idexpediente,idplantilla)
{
	ajax=objetoAjax();
	opcion=4;
		pag="";
               // alert(iddetalle+"-"+idsolicitud+"-"+idexpediente+"-"+idplantilla);
		// especialidad=0;
		// fechaconsulta="";
	//alert(idplantilla);
        var eliminar = confirm("De verdad desea eliminar este dato?");
	if ( eliminar ) {
            ajax=objetoAjax();
            ajax.open("POST", "ctrEliminarSolicitud.php",true);
                      //muy importante este encabezado ya que hacemos uso de un formulario
            ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                      //enviando los valores
            //alert(iddetalle+"-"+idsolicitud+"-"+idplantilla);
            ajax.send("opcion="+opcion+"&iddetalle="+iddetalle+"&idsolicitud="+idsolicitud+"&idexpediente="+idexpediente+"&pag="+pag+"&idplantilla="+idplantilla);
            ajax.onreadystatechange=function() 
            {
                    if (ajax.readyState==4) 
                    {	if (ajax.status == 200)
                            {  //mostrar los nuevos registros en esta capa
                              alert(ajax.responseText);
                              CargarDatosFormulario(idexpediente,idsolicitud);
                            }
                    }
            }
        }
}


function CargarDatosFormularioSolicitud(idexpediente,idsolicitud)
{
	ajax=objetoAjax();
	opcion=3;
	primernombre="";
		segundonombre="";
		primerapellido="";
		segundoapellido="";
		pag="";
		especialidad=0;
		fechaconsulta="";
	//	ALERT(idsolicitud);
	ajax.open("POST", "ctrEliminarSolicitud.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	
	ajax.send("opcion="+opcion+"&idexpediente="+idexpediente+"&idsolicitud="+idsolicitud+"&pag="+pag+"&fechaconsulta="+fechaconsulta);
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) 
		{	 if (ajax.status == 200)
			{  //mostrar los nuevos registros en esta capa
			  //document.getElementById('divFormulario'.innerHTML = ajax.responseText;
			   document.getElementById('divFormulario').innerHTML = ajax.responseText;
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
					  "&var2="+idsolicitud,"etiquetas",						"width=500,height=600,menubar=no,location=no,scrollbars=yes") ;
		
}



function Cerrar(){
	//window.opener.location.href = window.opener.location.href;
    window.close();
}

function ImprimirSolicitud(){
    idexpediente=document.frmDatos.idexpediente.value;
    idsolicitud=document.frmDatos.idsolicitud.value;

    ventana_secundaria = window.open("SolicitudEstudiosPaciente.php?var1="+idexpediente+
                                     "&var2="+idsolicitud,"solicitud",							"width=800,height=700,menubar=no,location=no,scrollbars=yes") 
}

function Imprimir1(){

//document.getElementById('divSolicitud').style.display="block";
document.getElementById('divImpresion').style.display="none";
document.getElementById('btnCerrar').style.visibility="hidden";
document.getElementById('btnImprimir').style.visibility="hidden";
 window.print();
//document.getElementById('divSolicitud').style.display="block";
document.getElementById('divImpresion').style.display="block";
document.getElementById('btnCerrar').style.visibility="visible";
document.getElementById('btnImprimir').style.visibility="visible";
}

function Imprimir(){

document.getElementById('divFormulario').style.display="block";
document.getElementById('btnCerrar').style.visibility="hidden";
document.getElementById('btnImprimir').style.visibility="hidden";
 window.print();
 document.getElementById('divFormulario').style.display="block";
document.getElementById('btnCerrar').style.visibility="visible";
document.getElementById('btnImprimir').style.visibility="visible";
}