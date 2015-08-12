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
//Esta funcion mandan a llamar

//funcion para calcular la edad
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




//FUNCION PARA VERIFICAR SI EXISTEN  DATOS DE LA SOLICITUD
function MostrarDatos(posicion)
{
		idexpediente=document.getElementById('idexpediente['+posicion+']').value;
		idsolicitud=document.getElementById('idsolicitud['+posicion+']').value;
		idarea=document.getElementById('idarea['+posicion+']').value;
		idexamen=document.getElementById('idexamen['+posicion+']').value;
		idtipo=document.getElementById('idtipo['+posicion+']').value;
		idexpediente=trim(idexpediente);
		idsolicitud=(idsolicitud);
		//alert(idsolicitud+" - "+idarea+" - "+idexamen+" - "+idtipo+" - "+idexpediente);
		ventana_secundaria = window.open("DatosSolicitudesPorArea.php?var1="+idexpediente+
		"&var2="+idarea+"&var3="+idsolicitud+"&var4="+idexamen+"&var5="+idtipo,"Datos","width=850,height=475,menubar=no,scrollbars=yes") ;
 }





function CambiarEstadoDetalleSolicitud1(estado,idexamen)
{
   		idsolicitud=document.frmDatos.idsolicitud.value;
		idexpediente=document.frmDatos.idexpediente.value;
		fechasolicitud=document.frmDatos.fechasolicitud.value;
		idarea=document.frmDatos.idarea.value;
		
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
		ajax.open("POST", "DatosSolicitudesPorArea.php",true);
		//muy importante este encabezado ya que hacemos uso de un formulario
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idexpediente="+idexpediente+"&idarea="+idarea+"&fechasolicitud="+fechasolicitud+
		"&idsolicitud="+idsolicitud+"&opcion="+opcion+"&estado="+estado+"&idexamen="+idexamen+"&pag="+pag);	
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





//CambiarEstadoDetalleSolicitud1(estado,idexamen)
function Cerrar(){
	//window.opener.location.href = window.opener.location.href;
    window.close();
}

function Imprimir(){
	//window.opener.location.href = window.opener.location.href;
	document.getElementById('divBotones').style.visibility="hidden";
	document.getElementById('divimpresion').style.visibility="hidden";
	
    window.print();
	document.getElementById('divBotones').style.visibility="visible";
	document.getElementById('divimpresion').style.visibility="visible";
}

function ProcesarMuestra(idtipo,idexpediente,idarea,idsolicitud,fechasolicitud)
{ 
   estado='PM'; //LOS DETALLES DE LA SOLICITUD SE LES HA PROCESADO LA MUESTRA
   CambiarEstadoDetalleSolicitud(estado,idtipo,idexpediente,idarea,idsolicitud,fechasolicitud);

}
//FUNCION PARA CAMBIAR ESTADO DE CADA DETALLE DE LA SOLICITUD
function CambiarEstadoDetalleSolicitud(estado,idtipo,idexpediente,idarea,idsolicitud,fechasolicitud)
{  	idexamen="";
	opcion=3;
	idsolicitud=trim(idsolicitud);
	idexpediente=trim(idexpediente);
	fechasolicitud=trim(fechasolicitud);
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	ajax.open("POST", "ctrSolicitudesPorArea.php",true);
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idexpediente="+idexpediente+"&idarea="+idarea+"&fechasolicitud="+fechasolicitud+
	"&idsolicitud="+idsolicitud+"&opcion="+opcion+"&estado="+estado+"&idexamen="+idexamen+"&idtipo="+idtipo);	
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

//function RechazarMuestra(idexamen)
function RechazarMuestra(idtipo,idexpediente,idarea,idsolicitud,fechasolicitud,observacion,idexamen)
{
        estado='RM'
  	opcion=4;
	//idexamen="";
        idsolicitud=trim(idsolicitud);
	idexpediente=trim(idexpediente);
	fechasolicitud=trim(fechasolicitud);
		//instanciamos el objetoAjax
        //alert()        
	ajax=objetoAjax();
		//usando del medoto POST
	ajax.open("POST", "ctrSolicitudesPorArea.php",true);
		//muy importante este encabezado ya que hacemos uso de un formulario
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
	
	ajax.send("idexpediente="+idexpediente+"&idarea="+idarea+"&fechasolicitud="+fechasolicitud+
	"&idsolicitud="+idsolicitud+"&opcion="+opcion+"&estado="+estado+"&observacion="+escape(observacion)+
        "&idexamen="+idexamen+"&idtipo="+idtipo);	

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
   idtipo="";
   pag="";
	opcion=4;
		
	idsolicitud=trim(idsolicitud);
	idexpediente=trim(idexpediente);
	fechasolicitud=trim(fechasolicitud);
		//instanciamos el objetoAjax
	ajax=objetoAjax();
		//usando del medoto POST
	ajax.open("POST", "ctrSolicitudesPorArea.php",true);
		//muy importante este encabezado ya que hacemos uso de un formulario
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
		ajax.send("idexpediente="+idexpediente+"&idarea="+idarea+"&fechasolicitud="+fechasolicitud+"&idsolicitud="+idsolicitud+"&opcion="+opcion+"&estado="+estado+"&idexamen="+idexamen+"&idtipo="+idtipo+"&observacion="+observacion+"&pag="+pag);	
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

function CargarDatosFormulario(idexpediente,idsolicitud,idarea,idtipo)
{
	ajax=objetoAjax();
	opcion=2;
	fechasolicitud="";
	estado="";
	idexamen="";
	pag="";
	ajax.open("POST", "ctrSolicitudesPorArea.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	ajax.send("opcion="+opcion+"&estado="+estado+"&idarea="+idarea+"&fechasolicitud="+fechasolicitud+"&idexpediente="+idexpediente+"&idsolicitud="+idsolicitud+"&idexamen="+idexamen+"&idtipo="+idtipo+"&pag="+pag);
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
	pag="";
	//idexamen="";
	//observacion="";
	ajax.open("POST", "ctrSolicitudesPorArea.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	ajax.send("opcion="+opcion+"&estado="+estado+"&idarea="+idarea+"&fechasolicitud="+fechasolicitud+"&idexpediente="+idexpediente+"&idsolicitud="+idsolicitud+"&idexamen="+idexamen+"&pag="+pag);
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

function SolicitudesPorArea()
{
	ajax=objetoAjax();
	opcion=1;
        IdEstab=document.getElementById('cmbEstablecimiento').value;
        IdServ=document.getElementById('CmbServicio').value;
        IdSubServ=document.getElementById('cmbSubServ').value;
	idarea=document.getElementById('cmbArea').value;
	idexpediente=document.getElementById('txtexpediente').value;
        idexamen=document.getElementById('cmbExamen').value;
	fechasolicitud=document.getElementById('txtfechasolicitud').value;
	PNombre=document.getElementById('PrimerNombre').value;
        SNombre=document.getElementById('SegundoNombre').value;
	PApellido=document.getElementById('PrimerApellido').value;
        SApellido=document.getElementById('SegundoApellido').value;
	TipoSolic=document.getElementById('cmbTipoSolic').value;
	//alert(TipoSolic);
		//alert(IdEstab+'*'+IdServ+'*'+IdSubServ+'*'+idarea+'*'+idexpediente+'*'+idexamen); 
	ajax.open("POST", "ctrSolicitudesPorArea.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	ajax.send("opcion="+opcion+"&IdEstab="+IdEstab+"&IdServ="+IdServ+"&IdSubServ="+IdSubServ+"&idarea="+idarea+
	"&idexpediente="+idexpediente+"&idexamen="+idexamen+"&PNombre="+PNombre+"&SNombre="+SNombre+"&PApellido="+PApellido+
	"&SApellido="+SApellido+"&fechasolicitud="+fechasolicitud+"&TipoSolic="+TipoSolic);
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

function LlenarComboEstablecimiento(idtipoesta)
{
  ajax=objetoAjax();
	

	ajax=objetoAjax();
	
    opcion=6;
   ajax.open("POST", "ctrSolicitudesPorArea.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
	 //alert(idtipoesta);
	ajax.send("opcion="+opcion+"&idtipoesta="+idtipoesta);	
	//ajax.send("opcion="+opcion+"&idtipoesta="+idtipoesta);	 
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
  	ajax.open("POST", "ctrSolicitudesPorArea.php",true);
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
 // alert(idarea);
	ajax=objetoAjax();
	opcion=5;
   	ajax.open("POST", "ctrSolicitudesPorArea.php",true);
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


