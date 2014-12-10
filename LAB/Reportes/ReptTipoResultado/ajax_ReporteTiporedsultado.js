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

//FUNCION QUE VERIFICA QUE LOS PARAMETROS DE BUSQUEDA ESTEN COMPLETOS
function DatosCompletos()
{
  var resp = true;
  // if (document.getElementById('cboSubEspecialidades').value == "0")
	// {
		//resp= false;		
	 //}
 if (document.getElementById('txtfechainicio').value == "")
	 {
		resp= false;		
	 }
 if (document.getElementById('txtfechafin').value == "")
	 {
		resp= false;		
	 }
  return resp;
}


//FUNCION PARA BUSCAR DATOS DE LA SOLICITUD
function BuscarDatos(pag)
{	
	//alert (pag);
	//if (DatosCompletos())
	//{	
		opcion=1;
		//procedencia=document.getElementById('cmbProcedencia').value;
		//subservicio=document.getElementById('cmbSubServicio').value;
		fechainicio=document.getElementById('txtfechainicio').value;
		fechafin=document.getElementById('txtfechafin').value;
                
                idarea = document.getElementById('cmbArea').value;
                idexamen = document.getElementById('cmbExamen').value;
                
                //alert(idexamen);
		//alert(procedencia);
		//medico=document.getElementById('cboMedicos').value;
		//idexpediente="";
		//idsolicitud="";
		//idsubespecialidad="";
		//instanciamos el objetoAjax
                //alert(subservicio);
		ajax=objetoAjax();
		//archivo que realizar� la operacion ->actualizacion.php
		ajax.open("POST", "ctrLab_ReporteTiporedsultado.php",true);
		//muy importante este encabezado ya que hacemos uso de un formulario
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send(/*"procedencia="+procedencia+*/"&opcion="+opcion+"&fechainicio="+fechainicio+"&fechafin="+fechafin+/*"&subservicio="+subservicio+*/"&pag="+pag+ "&idarea=" + idarea + "&idexamen=" + idexamen );
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				//mostrar los nuevos registros en esta capa
				document.getElementById('divBusqueda').innerHTML = ajax.responseText;
				//alert(ajax.responseText);
			}
	}	
	//}
	//else{
		//alert("Complete los datos para la busqueda");
	//}
}


function MostrarDatos(posicion)
{
		idexpediente=document.getElementById('idexpediente['+posicion+']').value;
		idsolicitud=document.getElementById('idsolicitud['+posicion+']').value;
		idexpediente=trim(idexpediente);
		idsolicitud=trim(idsolicitud);
		ventana_secundaria = window.open("SolicitudEstudiosPaciente.php?var1="+idexpediente+
										  "&var2="+idsolicitud,"Datos","width=850,height=475,menubar=no,scrollbars=yes") ;
  
 }

 function VistaPrevia()
{
		especialidad=document.getElementById('cboSubEspecialidades').value;
		fechainicio=document.getElementById('txtfechainicio').value;
		fechafin=document.getElementById('txtfechafin').value;
		medico=document.getElementById('cboMedicos').value;
		ventana_secundaria = window.open("ReporteEspecialidades.php?var1="+especialidad+
										  "&var2="+fechainicio+"&var3="+fechafin+"&var4="+medico,"Vista","width=1250,height=575,menubar=no,scrollbars=yes") ;
  
 }
 
 function CargarDatosFormulario(especialidad,fechainicio,fechafin,medico)
{
	ajax=objetoAjax();
	opcion=4;
	//especialidad="";
	//fechainicio="";
	idexpediente="";
	idsubespecialidad="";
	//medico="";
	pag="";
	idsolicitud="";
	//estado="";
	ajax.open("POST", "ctrLab_SolicitudesPorServicioPeriodo.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	//ajax.send("opcion="+opcion+"&idexpediente="+idexpediente+"&idsolicitud="+idsolicitud);
	ajax.send("opcion="+opcion+"&especialidad="+especialidad+"&idsolicitud="+idsolicitud+"&especialidad="+especialidad+"&fechainicio="+fechainicio+"&fechafin="+fechafin+"&idsubespecialidad="+idsubespecialidad+"&medico="+medico+"&idsolicitud"+idsolicitud+"&idexpediente="+idexpediente+"&pag="+pag);
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


function LlenarComboSubServicio(proce)
{
  // alert(idsubespecialidad);
	ajax=objetoAjax();
    idexpediente="";
    idsolicitud="";
	especialidad="";
	fechainicio="";
	fechafin="";
	medico="";
	pag="";
    opcion=3;
   ajax.open("POST", "ctrLab_ReporteTiporedsultado.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	//ajax.send("opcion="+opcion+"&idarea="+area+"&idexpediente="+idexpediente+"&idsolicitud="+idsolicitud);
	
ajax.send("opcion="+opcion+"&idexpediente="+idexpediente+
             "&idsolicitud="+idsolicitud+"&especialidad="+especialidad+"&fechainicio="+fechainicio+"&fechafin="+fechafin+"&medico="+medico+"&pag="+pag+"&proce="+proce);
			 
	ajax.onreadystatechange=function() 
	{
		
	 if (ajax.readyState == 4){//4 The request is complete
		if (ajax.status == 200){//200 means no error.
	 // respuesta = ajax.responseText;	
	 // alert (respuesta)
	 
	}	  	document.getElementById('divSubServ').innerHTML = ajax.responseText;
   }
   }
}

function LlenarComboExamen(idarea)
{
    // alert(idarea);
    ajax = objetoAjax();
    
    
    opcion = 5;

      ajax.open("POST", "ctrLab_ReporteTiporedsultado.php",true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("opcion="+opcion+"&idarea="+idarea);
    ajax.onreadystatechange = function()
    {

        if (ajax.readyState == 4) {//4 The request is complete
            if (ajax.status == 200) {//200 means no error.
                respuesta = ajax.responseText;
                // alert (respuesta)

            }
            document.getElementById('divExamen').innerHTML = ajax.responseText;
        }
    }
}


function Cerrar(){
	//window.opener.location.href = window.opener.location.href;
    window.close();
}

function Imprimir(){

document.getElementById('divFormulario').style.display="block";
document.getElementById('Botones').style.visibility="hidden";

 window.print();
 document.getElementById('divFormulario').style.display="block";
document.getElementById('Botones').style.visibility="block";

}
	