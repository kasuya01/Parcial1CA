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

////Funcion Demanda

function LlenarComboExamen(idarea)
{
 	ajax=objetoAjax();
	opcion=2;
   	ajax.open("POST", "ctrLab_ReporteDemanda.php",true);
		  //muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		  //enviando los valores
                 // alert (idarea);
	ajax.send("opcion="+opcion+"&idarea="+idarea);	 
	ajax.onreadystatechange=function() 
	{
		
		if (ajax.readyState == 4){//4 The request is complete
			if (ajax.status == 200){//200 means no error.
	  			//respuesta = ajax.responseText;	
	 			// alert (respuesta)
		 document.getElementById('divExamen').innerHTML = ajax.responseText;
                 // $('#cmbExamen').multiselect();
                   $("#cmbExamen").select2({
                     placeholder: "--Seleccione examenes--",
                     allowClear: true,
                     dropdownAutoWidth: true
                  });
			}	  	
			
   		}
   	}
}


function MostrarReporteDemanda()
{
   idarea=$('#cmbArea').val();
   idexamen=$('#cmbExamen').val();
   d_fechadesde=$('#d_fechadesde').val();
   d_fechahasta=$('#d_fechahasta').val();
  	ajax=objetoAjax();
  	opcion=1;
  	ajax.open("POST", "ctrLab_ReporteDemanda.php",true);
  	//muy importante este encabezado ya que hacemos uso de un formulario
  	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  	//enviando los valores
	ajax.send("opcion="+opcion+"&idarea="+idarea+"&idexamen="+idexamen+"&d_fechadesde="+d_fechadesde+"&d_fechahasta="+d_fechahasta);	
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
//        
//         jQuery.ajaxSetup({
//        error: function(jqXHR, exception) {
//            if (jqXHR.status === 0) {
//                alert('Not connect.\n Verify Network.');
//            } else if (jqXHR.status == 404) {
//                alert('Requested page not found. [404]');
//            } else if (jqXHR.status == 500) {
//                alert('Internal Server Error [500].');
//            } else if (exception === 'parsererror') {
//                alert('Requested JSON parse failed.');
//            } else if (exception === 'timeout') {
//                alert('Time out error.');
//            } else if (exception === 'abort') {
//                alert('Ajax request aborted.');
//            } else {
//                alert('Uncaught Error.\n' + jqXHR.responseText);
//            }
//        }
//    });
//
//    jQuery.ajax({
//        url: 'ctrLab_ReporteDemanda.php',
//        async: true,
//        dataType: 'json',
//        type: 'POST',
//        data: { opcion: 1, idarea:idarea, idexamen:idexamen, d_fechadesde:d_fechadesde, d_fechahasta:dfechahasta },
//        success: function(object) {
//            if(object.status) {
//                var estado;
//                
//                if(object.data[0].numero === "0") {
//                    estado = 'P';
//                } else {
//                    estado = 'R';
//                }
//
//                idsolicitud = document.getElementById('txtidsolicitud[' + posicion + ']').value;
//                valor = document.getElementById('txtprecedencia[' + posicion + ']').value;
//                if (valor != " ") {
//                    //VERIFICANDO QUE LA SOLICITUD HAYA SIDO PROCESADA
//                    //Cambia el estado de la solicitud
//                    CambiarEstadoSolicitud(estado, idsolicitud, posicion);
//                    //CambiarEstadoDetalleSolicitud('TR');
//                    //Habilita el boton para la impresion
//                    div = document.getElementById('divoculto[' + posicion + ']');
//                    div.style.display = "block";
//                } else {
//                    alert("No se encontraron datos que procesar...");
//                }
//            } else {
//                alert('Error al actualizar el estado de la Solicitud')
//            }
//        }
//    });
}

//
////Fin Funcion Demanda





















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


function GenResultados(){
   
   
}//fin funcion generarResultado

//funcion prueba
function enviaxfis(){
    var ban=0;
    var idrol=$("#idrol").val()
//    if (idrol==7){
//        var regionid2=(document.getElementById('regionid2').value);
//        if (regionid2==0){
//           // alert ("No ha elegido la Región a consultar");
//             $( "#dialog" ).dialog({
//        autoOpen: false,
//        modal: true       
//        });
//        $( "#dialog" ).dialog( "open" );
//           ban=1;
//            return false;
//        }
//    }
    if (ban==0){
    var i=0;
    $.ajax({
        type: "POST",
        cache: false,
    //    data: parametros,
        success: function(response)
        {
            window.open(response);
        }
    });
  }
}
//funcion prueba
function enviaxfis2(){
    var ban=0;
    var idrol=$("#idrol").val()
//    if (idrol==7){
//        var regionid2=(document.getElementById('regionid2').value);
//        if (regionid2==0){
//           // alert ("No ha elegido la Región a consultar");
//             $( "#dialog" ).dialog({
//        autoOpen: false,
//        modal: true       
//        });
//        $( "#dialog" ).dialog( "open" );
//           ban=1;
//            return false;
//        }
//    }
    if (ban==0){
    var i=0;
    $.ajax({
        type: "POST",
        cache: false,
    //    data: parametros,
        success: function(response)
        {
            window.open(response);
        }
    });
  }
}



//FUNCION PARA BUSCAR DATOS DE LA SOLICITUD
function BuscarDatos()
{	
	
	opcion=1;
	//procedencia=document.getElementById('cmbProcedencia').value;
	area=document.getElementById('cmbArea').value;
        examen=document.getElementById('cmbExamen').value;
	fechainicio=document.getElementById('txtfechainicio').value;
	fechafin=document.getElementById('txtfechafin').value;
		//alert(area+" ## "+examen+" ## "+area+" ## "+fechainicio+" ## "+fechafin);
                //alert(examen);
		//instanciamos el objetoAjax
	ajax=objetoAjax();
		//archivo que realizar� la operacion ->actualizacion.php
	ajax.open("POST", "ctrLab_ReporteDemanda.php",true);
		//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//enviando los valores
	ajax.send("opcion="+opcion+"&fechainicio="+fechainicio+"&fechafin="+fechafin+"&area="+area+"&examen="+examen)
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
	