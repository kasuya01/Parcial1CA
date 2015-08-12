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
///////////////////////////////***********************************/////////////////////////////////
//Esta funcion mandan a llamar
/*function calc_edad()
{
  var fecnac1=document.getElementById("suEdad").value;
  var fecnac2=fecnac1.substring(0,10);
  var suEdades=calcular_edad(fecnac2);
  document.getElementById("divsuedad").innerHTML=suEdades;
}*/


//funcion para calculo de edad

function calcular_edad(fecha){
    //calculo la fecha de hoy
    hoy=new Date();

    //calculo la fecha que recibo
    //La descompongo en un array
    var array_fecha = fecha.split("/");
    //si el array no tiene tres partes, la fecha es incorrecta
    if (array_fecha.length!=3){
       return false;
    }
    //compruebo que los ano, mes, dia son correctos
    var ano;
    ano = fecha.substring(6,10);
    if (isNaN(ano)){
       return false;
    }

    var mes;
    mes = fecha.substring(3,5);   
    if (isNaN(mes)){
       return false;
    }

    var dia;
    dia = fecha.substring(0,2);
    if (isNaN(dia)){
       return false;
    }
    //si el aï¿½o de la fecha que recibo solo tiene 2 cifras hay que cambiarlo a 4
    if (ano<=99){
       ano +=1900;       
    }

// alert("dia: "+dia+" mes:"+mes+" anio:"+ano);
//        08       08        2010
    //resto los aï¿½os de las dos fechas
    annios=hoy.getFullYear()- ano;
        edad=hoy.getFullYear()- ano - 1; //-1 porque no se si ha cumplido aï¿½os ya este aï¿½o
    //si resto los meses y me da menor que 0 entonces no ha cumplido aï¿½os. Si da mayor si ha cumplido
   
   
    var meses=hoy.getMonth() + 1 - mes;   

    var dias=hoy.getUTCDate() - dia    ;


 //alert("Dias: "+dias+" Meses:"+meses+" Anios:"+annios+" Edad:"+edad);
//        -3         1        1        0
 var Minimo="0 dias";
 var diasx=0;
    if(dias<0){
        diasx=dias;
        dias=30+dias;
        if(meses==1){
           Minimo=dias+" DIAS";
        }

    }

    //alert(diasx+" dias:"+dias);
   
    if(Minimo=="0 dias" && dias >=0){Minimo=dias+" dias";}
   
    if(diasx<0){
        meses=meses-1;
       
    }

    if(meses==0 && annios==0){return dias+" DIAS";}
    if(annios==0){return meses+" MESES Y "+Minimo;}
    if(meses<0){meses=12+meses;}



    if (hoy.getMonth() + 1 - mes < 0){
       return edad+" a\u00f1os y "+meses+" meses y "+Minimo;       
//       return edad;       
    } //+ 1 porque los meses empiezan en 0
    if (hoy.getMonth() + 1 - mes > 0){
       return (edad+1)+" a\u00f1os y "+meses+" meses y "+Minimo;
//       return edad+1;
    }
    //entonces es que eran iguales. miro los dias
    //si resto los dias y me da menor que 0 entonces no ha cumplido aï¿½os. Si da mayor o igual si ha cumplido
    if (hoy.getUTCDate() - dia >= 0){
       return (edad+1)+" a\u00f1os y "+meses+" meses y "+Minimo;       
//       return edad + 1;
    }
    return edad+" a\u00f1os y "+meses+" meses y "+Minimo;
//    return edad;
}

//FUNCION PARA BUSCAR DATOS DE LA SOLICITUD
function BuscarDatospaciente(pag)
    
{	ajax=objetoAjax();
		opcion=1;
		IdEstab=document.getElementById('cmbEstablecimiento').value;
		IdServ=document.getElementById('CmbServicio').value;
		IdSubServ=document.getElementById('cmbSubServ').value;
		idexpediente=document.getElementById('txtexpediente').value;
		primernombre=document.getElementById('PrimerNombre').value;
		segundonombre=document.getElementById('SegundoNombre').value;
		primerapellido=document.getElementById('PrimerApellido').value;
		segundoapellido=document.getElementById('SegundoApellido').value;
		//especialidad=document.getElementById('cmbEspecialidad').value;
		fechaconsulta=document.getElementById('txtfecharecep').value;
         // alert(IdServ);
         //alert(fechaconsulta);
		
		//archivo que realizar� la operacion ->actualizacion.php
		ajax.open("POST", "ctrLab_SolicitudesPorPaciente.php",true);
		//muy importante este encabezado ya que hacemos uso de un formulario
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idexpediente="+idexpediente+"&opcion="+opcion+"&primernombre="+escape(primernombre)+"&segundonombre="+escape(segundonombre)+"&primerapellido="+escape(primerapellido)+"&segundoapellido="+escape(segundoapellido)+"&fechaconsulta="+fechaconsulta+"&IdEstab="+IdEstab+"&IdServ="+IdServ+"&IdSubServ="+IdSubServ+"&pag="+pag);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				//mostrar los nuevos registros en esta capa
				document.getElementById('divBusqueda').innerHTML = ajax.responseText;
			
			}
	        }	
	
	
}

function CargarDatosFormulario(idexpediente,idsolicitud,idestablecimiento)
{
	ajax=objetoAjax();
	opcion=2;
	
		
		especialidad=0;
		
	
        //alert(idsolicitud);
	ajax.open("POST", "ctrLab_SolicitudesPorPaciente.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	//ajax.send("opcion="+opcion+"&idexpediente="+idexpediente+"&idsolicitud="+idsolicitud);
	ajax.send("opcion="+opcion+"&idexpediente="+idexpediente+"&idsolicitud="+idsolicitud+
	"&especialidad="+especialidad+"&idestablecimiento="+idestablecimiento);
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) 
		{	 if (ajax.status == 200)
			{  //mostrar los nuevos registros en esta capa
			  document.getElementById('divSolicitud').innerHTML = ajax.responseText;
			  //calc_edad();
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
	ajax.open("POST", "ctrLab_SolicitudesPorPaciente.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	//ajax.send("opcion="+opcion+"&idexpediente="+idexpediente+"&idsolicitud="+idsolicitud);
	ajax.send("opcion="+opcion+"&idexpediente="+idexpediente+"&idsolicitud="+idsolicitud+"&primernombre="+escape(primernombre)+"&segundonombre="+escape(segundonombre)+"&primerapellido="+escape(primerapellido)+"&segundoapellido="+escape(segundoapellido)+"&especialidad="+especialidad+"&pag="+pag+"&fechaconsulta="+fechaconsulta);
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) 
		{	 if (ajax.status == 200)
			{  //mostrar los nuevos registros en esta capa
			     document.getElementById('divFormulario').innerHTML = ajax.responseText;
				//calc_edad();
			 }
	     }
	}
}

function LlenarComboExamen(idarea)
{
 	ajax=objetoAjax();
	opcion=5;
   	ajax.open("POST", "ctrLab_SolicitudesPorPaciente.php",true);
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

function LlenarComboEstablecimiento(idtipoesta)
{
  	ajax=objetoAjax();
  	opcion=6;
  	ajax.open("POST", "ctrLab_SolicitudesPorPaciente.php",true);
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
  	ajax.open("POST", "ctrLab_SolicitudesPorPaciente.php",true);
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






function MostrarDatos(posicion)
{
		idexpediente=document.getElementById('idexpediente['+posicion+']').value;
		idsolicitud=document.getElementById('idsolicitud['+posicion+']').value;
		idestablecimiento=document.getElementById('idestablecimiento['+posicion+']').value;
		idexpediente=trim(idexpediente);
		idsolicitud=trim(idsolicitud);
		CargarDatosFormulario(idexpediente,idsolicitud,idestablecimiento);
		//aca
  
 }

 
 

function imprimiretiquetas()
{//cambiar imprimir  etiquetas1.php  por imprimir.php
	idexpediente=document.frmDatos.idexpediente.value;
	idsolicitud=document.frmDatos.idsolicitud.value;
		
	
	//alert idexpediente;
	ventana_secundaria = window.open("etiquetas.php?var1="+idexpediente+
									  "&var2="+idsolicitud,"etiquetas",										"width=500,height=600,menubar=no,location=no,scrollbars=yes") ;
		
}



function Cerrar(){
	//window.opener.location.href = window.opener.location.href;
    window.close();
}

function ImprimirSolicitud(){
idexpediente=document.frmDatos.idexpediente.value;
idsolicitud=document.frmDatos.idsolicitud.value;
		
ventana_secundaria = window.open("SolicitudEstudiosPaciente.php?var1="+idexpediente+
									  "&var2="+idsolicitud,"solicitud",										"width=800,height=700,menubar=no,location=no,scrollbars=yes") 
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