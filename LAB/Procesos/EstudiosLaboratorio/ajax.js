//******************************************************************************************//
function xmlhttp(){
		var xmlhttp;
		try{xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");}
		catch(e){
			try{xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}
			catch(e){
				try{xmlhttp = new XMLHttpRequest();}
				catch(e){
					xmlhttp = false;
				}
			}
		}
		if (!xmlhttp)
				return null;
			else
				return xmlhttp;
}//xmlhttp
//**********************************************************************************************//



// JavaScript Document
var ObjetoAjax;
var accion=0;
var BanderaExiste=0;

/************************************************************************************************/
/*  					Funcion Para Cerrar una ventana											*/
/************************************************************************************************/
function CerrarVentana(){
	window.close();
}


jQuery(document).ready(function ($) {
   if (band!=0){
      eliminarsolicitud(id_solicitud);
   }
});


function AgregarExamen(){
	var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
	var idexpediente=document.getElementById('idexpediente').value;
	var IdNumeroExp=document.getElementById('IdNumeroExp').value;
	var FechaSolicitud=document.getElementById('FechaSolicitud').value;
	var IdUsuarioReg=document.getElementById('IdUsuarioReg').value;
	var FechaHoraReg=document.getElementById('FechaHoraReg').value;
	var lugar=document.getElementById('lugar').value;
	var IdEstablecimiento=document.getElementById('IdEstablecimiento').value;
	var IdCitaServApoyo=document.getElementById('IdCitaServApoyo').value;
	var Sexo=document.getElementById('Sexo').value;
	var FechaConsulta=document.getElementById('FechaConsulta').value;


	var Parametros="?IdNumeroExp="+IdNumeroExp;
        Parametros+="&IdEstablecimiento="+IdEstablecimiento;
        Parametros+="&lugar="+lugar;
        Parametros+="&IdCitaServApoyo="+IdCitaServApoyo;
			Parametros+="&Fecha="+FechaSolicitud;
			Parametros+="&IdUsuarioReg="+IdUsuarioReg;
			Parametros+="&FechaHoraReg="+FechaHoraReg;
			Parametros+="&IdHistorialClinico="+IdHistorialClinico;
			Parametros+="&idexpediente="+idexpediente;
			Parametros+="&Sexo="+Sexo;
			Parametros+="&FechaConsulta="+FechaConsulta;

	location.href='./Solicitud.php'+Parametros;

}

function ListaExamenes(IdHistorialClinico,IdCitaServApoyo, band){
   if (band=='undefined' || band=='' || band==null){
      band=0;
   }
   window.location.href='ExamenesSolicitados.php?IdHistorialClinico='+IdHistorialClinico+'&IdCitaServApoyo='+IdCitaServApoyo+'&band='+band;

        classdatepick();
	//MostrarDetalle(IdHistorialClinico);
}



/************************************************************************************************/
/*  					Funcion Para Crear un Objeto Ajax										*/
/************************************************************************************************/
function NuevoAjax(){
	var ObjetoAjax=false;
	//Para navegadores distintos a internet explorer
	try {
		ObjetoAjax = new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch (e) {
		try {  /*Para explorer*/
			ObjetoAjax = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			ObjetoAjax = false;
	 	  }
	}

	if (!ObjetoAjax && typeof XMLHttpRequest!='undefined') {
		ObjetoAjax = new XMLHttpRequest();
	}
	return ObjetoAjax; //Retornar Objeto Ajax
}



/***************************************************************************************/
//Fn PG
function ImprimirResultados(IdHistorialClinico, idsolicitudestudio){
	var bandera=document.getElementById('Imprimir');
	if(bandera.checked==true){
		var Bandera='1';
		//alert('SI');
	}else{
		var Bandera='0';
	}

	//accion=2;

	// Crear Objeto Ajax
	ObjetoAjax=NuevoAjax();

	// Hacer el Request y llamar o Dibujar el Resultado
	//ObjetoAjax.onreadystatechange = CargarContenido;
	ObjetoAjax.open("POST", 'Procesar.php', true);
	ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	// Declaraci�n de parametros

	var Proceso='Impresiones';
	var param = 'Proceso='+Proceso;

	// Concatenaci�n y Env�o de Par�metros
	param += "&Bandera="+Bandera+"&IdHistorialClinico="+IdHistorialClinico+"&idsolicitudestudio="+idsolicitudestudio;
	//alert(param);
	ObjetoAjax.send(param);

 }
//Fn PG
function SolicitudUrgente(IdHistorialClinico, idsolicitudestudio){
	var bandera=document.getElementById('tiposolgen');
	if(bandera.checked==true){
		var Bandera='1';
		//alert('SI');
	}else{
		var Bandera='2';
	}

	//accion=2;

	// Crear Objeto Ajax
	ObjetoAjax=NuevoAjax();

	// Hacer el Request y llamar o Dibujar el Resultado
	//ObjetoAjax.onreadystatechange = CargarContenido;
	ObjetoAjax.open("POST", 'Procesar.php', true);
	ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	// Declaraci�n de parametros

	var Proceso='SolicitudUrgente';
	var param = 'Proceso='+Proceso;

	// Concatenaci�n y Env�o de Par�metros
	param += "&Bandera="+Bandera+"&IdHistorialClinico="+IdHistorialClinico+"&idsolicitudestudio="+idsolicitudestudio;
	//alert(param);
	ObjetoAjax.send(param);

 }

/**************************************************************************************/



/************************************************************************************************/
/* 			Funcion Para Mostrar  el origen y tipo de muestra de un examen						*/
/************************************************************************************************/
var Nombre;
var Origen;
 function MostrarLista(Examen,value){
   // alert (Examen+' - -'+value)
 	var NombreDiv=Examen+value;

	if (document.Solicitud.Examenes[value].checked == true) {
		Nombre=Examen;
		Origen='O'+ Examen;
		accion=1;

		// Crear Objeto Ajax
		ObjetoAjax=NuevoAjax();

		// Hacer el Request y llamar o Dibujar el Resultado
		//ObjetoAjax.onreadystatechange = CargarContenido;
                ObjetoAjax.open("POST", 'Procesar.php', true);
                // Declaración de parámetros
		var IdExamen=Examen;
		var Proceso='Combos';
		var param = 'Proceso='+Proceso;
		// Concatenación y Envío de Parámetros
		param += '&IdExamen='+IdExamen;

                 ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		ObjetoAjax.send(param);
               // console.log('ObjetoAjax.readyState: '+ObjetoAjax.readyState)
                //console.log
                ObjetoAjax.onreadystatechange = function(){

                     if(ObjetoAjax.readyState==4){
                     //   console.log('resp:'+ObjetoAjax.responseText);
                        document.getElementById(Examen).innerHTML=ObjetoAjax.responseText;
                        //return false;
                     }
                 }



                console.log(param);

	} // fin If Checkeado
 	else {
		   document.getElementById(Examen).innerHTML="";
   		   document.getElementById("O"+Examen).innerHTML="";
		}


 }//fn MostrarLista


  function MostrarLista2(Examen,value){

 	var NombreDiv=Examen+value;

	if (document.Solicitud.Examenes[value].checked == true) {
             //  alert (Examen+' - -'+value)
		Nombre=Examen;
		Origen='O'+ Examen;
		accion=1;
                var IdExamen=Examen;
                //alert ('Nombreluego:'+Nombre)
                jQuery.ajax({
                   url:'Procesar.php',
                  type:'post',
                  dataType:'html',
                  async: true,
                  data: {Proceso: 'Combos', IdExamen: IdExamen },
                  success: function(data){
                 //    alert (data+' NOMBREEEE:  '+Examen)
                     document.getElementById(Examen).innerHTML=data;
                  },
                  error: function(jqXHR, exception) {
                        if (jqXHR.status === 0) {
                            alert('Not connect.\n Verify Network.');
                        } else if (jqXHR.status == 404) {
                            alert('Requested page not found. [404]');
                        } else if (jqXHR.status == 500) {
                            alert('Internal Server Error [500].');
                        } else if (exception === 'parsererror') {
                            alert('Requested JSON parse failed.');
                        } else if (exception === 'timeout') {
                            alert('Time out error.');
                        } else if (exception === 'abort') {
                            alert('Ajax request aborted.');
                        } else if (textStatus==="timeout"){
                            alert('Error timeout');
                        } else {
                            alert('Uncaught Error.\n' + jqXHR.responseText);
                        }
                    }
      })


	} // fin If Checkeado
 	else {
             //  console.log('no else')
               document.getElementById(Examen).innerHTML="";
               document.getElementById("O"+Examen).innerHTML="";
		}
               // console.log('regreso')


 }//fn MostrarLista2


/************************************************************************************************/
/* 			Funcion Para Mostrar  el origen y tipo de muestra de un examen						*/
/************************************************************************************************/
//fn pg
function MostrarOrigen(Muestra,Examen){

    //return false;
	Nombre=Examen;
	Origen="O"+Examen;
 	var IdMuestra=Muestra;
	accion=2;

	// Crear Objeto Ajax
	ObjetoAjax=NuevoAjax();

	// Hacer el Request y llamar o Dibujar el Resultado
         //alert (Muestra+'-'+ Examen)
	ObjetoAjax.onreadystatechange = CargarContenido;
	ObjetoAjax.open("POST", 'Procesar.php', true);
	ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	// Declaración de parámetros
	 //console.log (Muestra+'-'+ Examen)
	var Proceso='OrigenMuestra';
	var IdExamen=Examen;
	var param = 'Proceso='+Proceso;

	// Concatenación y Envío de Parámetros
	param += '&IdMuestra='+IdMuestra+'&IdExamen='+IdExamen;
	ObjetoAjax.send(param);

 }

function MostrarDetalle(IdHistorialClinico){
	// Crear Objeto Ajax
	ObjetoAjax=NuevoAjax();
	accion=0;
	// Hacer el Request y llamar o Dibujar el Resultado
	ObjetoAjax.onreadystatechange = CargarContenido;
	ObjetoAjax.open("POST", 'Procesar.php', true);
	ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	// Declaración de parámetros

	var Proceso='MostrarDetalle';
	var param = 'Proceso='+Proceso+'&IdHistorialClinico='+IdHistorialClinico;

	// Concatenación y Envío de Parámetros
	ObjetoAjax.send(param);

 }



/************************************************************************************************/
/* 			Funcion Para Guardar Datos de los Examenes de Laboratorio							*/
/************************************************************************************************/
 function GuardarSolicitud(){
	// Definicion de Variables
		var Muestra;
		var Origen;
		var Examen;
		var Indicacion='';
              // console.log('Muestra: '+Muestra+' Origen: '+Origen+' Examen: '+Examen)
		 //accion=3;

		//alert('Estas en Guardar Informacion');
		// Parametros Generales
                document.getElementById('Enviar').value='Enviando....!';
                document.getElementById('Enviar').disabled=true;
		var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
		var IdNumeroExp=document.getElementById('IdNumeroExp').value;
		var idexpediente=document.getElementById('idexpediente').value;
		var FechaSolicitud=document.getElementById('FechaSolicitud').value;
		var IdUsuarioReg=document.getElementById('IdUsuarioReg').value;
		var IdCitaServApoyo=document.getElementById('IdCitaServApoyo').value;
		var IdEstablecimiento=document.getElementById('IdEstablecimiento').value;
		var lugar=document.getElementById('lugar').value;

		var Tope = document.getElementsByName('Examenes').length;
		var suma=0;
		var Resta=0;
		var i=0;
		var j=0;


				for (i=0;i<Tope;i++){
					var ID='Examenes'+i;
					if (document.getElementById(ID).checked == true){
						suma++;
						Resta++;
						break;
					}

				}
				CantCheck=$('input:checkbox:checked').length;
			//	console.log('CantCheck'+CantCheck);

// Comprobar todos los check box que esten chekeados,.....
	//for (i=0;i<Tope;i++){
			// Si esta checkeado entonces que guarde en la base de datos...!
					//var ID='Examenes'+i;

			if ((document.getElementById(ID).checked == true) && suma !=0) {

				// Parametros del Detalle de los examenes
                                    NombreExamen=document.getElementById(ID).value;
                                   // console.log('NombreExamen: '+NombreExamen);
                                    Muestra='M'+ NombreExamen;
                                    Origen='Origen'+ NombreExamen;
                                    Suministrante='sumi_'+ NombreExamen;
                                   // NombreMuestra=document.getElementById(Muestra).value;
                                    NombreMuestra=$('#'+Muestra).val();
                                    NombreSuministrante=$('#'+Suministrante).val();
                                   // console.log('Muestra: '+Muestra+' Origen: '+Origen+' NombreMuestra: '+NombreMuestra)
                                   // alert (Muestra)
                                    cantselectmuest=$('#'+Muestra+' option').size();
                                    cantselectsumi=$('#'+Suministrante+' option').size();
                                    //alert ('cantselectmuest:'+cantselectmuest+'  NombreMuestra:'+NombreMuestra)
                                    if (cantselectmuest>0 && NombreMuestra==0){
                                        alert ('Debe seleccionar los tipos de muestra de las pruebas seleccionadas, que lo requieran');
                                        $('#Enviar').removeAttr('disabled')
                                        return false;
                                    }

                                    if (cantselectsumi>0 && NombreSuministrante==0){
                                        alert ('Debe seleccionar la forma o suministrante de realizar la prueba');
                                        $('#Enviar').removeAttr('disabled')
                                        return false;
                                    }

                                    else {

					if(NombreMuestra==0){
						NombreOrigen=0;
					}else{
						//NombreOrigen=document.getElementById(Origen).value;
						NombreOrigen=$('#'+Origen).val();;
					}
					//console.log ('NombreMuestra: '+NombreMuestra+'   NombreOrigen:'+NombreOrigen)
					if(NombreOrigen==''){
						NombreOrigen=0;
					}
				//************************************************************************************
				var ajax2 = xmlhttp();

				ajax2.onreadystatechange=function(){
					if(ajax2.readyState==2){

									/*NOTHING*/

                                var Objeto = 'Nombre'+NombreExamen;

                                var LETRA=document.getElementById(Objeto).value;
			document.getElementById('Resultados').innerHTML = "<center><strong><h3>ENVIANDO: <u>"+LETRA+"</u></h3></strong><br><img src='loading.gif' alt='Enviando!' /></center>";


					}//Estado == 1
					if(ajax2.readyState==4){

						j++;
						Resta--;
						//console.log(ID+ '  Resta:'+Resta)
						Retraso(ID);
						//alert(ajax2.responseText);
					}//Estado == 4
				}//On ready state
			var Proceso='GuardarDatos';
                     //   alert(' Proceso '+Proceso+' IdHistorialClinico '+IdHistorialClinico+' IdNumeroExp '+IdNumeroExp+' FechaSolicitud '+FechaSolicitud+' IdUsuarioReg '+IdUsuarioReg+' IdExamen '+NombreExamen+' Indicacion '+Indicacion+' NombreMuestra '+NombreMuestra+' NombreOrigen '+NombreOrigen+'idexpediente:'+idexpediente+'IdEstablecimiento'+IdEstablecimiento+'lugar'+lugar);
					// console.log(NombreSuministrante+' NS'+ ' idexamen: '+NombreExamen)
		ajax2.open("GET",'Procesar.php?Proceso='+Proceso+'&IdHistorialClinico='+IdHistorialClinico+'&IdNumeroExp='+IdNumeroExp+'&idexpediente='+idexpediente+'&FechaSolicitud='+FechaSolicitud+'&IdUsuarioReg='+IdUsuarioReg+'&IdExamen='+NombreExamen+'&Indicacion='+Indicacion+'&IdTipoMuestra='+NombreMuestra+'&IdOrigen='+NombreOrigen+"&idsuministrante="+NombreSuministrante,true);
//console.log('SE fue y regreso')
				/*	ajax.onreadystatechange=function() {
	if (ajax.readyState==4)
	{
		document.getElementById('Resultados').innerHTML = ajax.responseText

	}
  }
//alert (parametros)
  ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");*/
		ajax2.send(null);
		return false;


                        }//fin else
			}//If checked == true
	//}//for

			// AKI CREAR LA cit_citasxserviciodeapoyo o actualizar la fecha de la cita una solo ves.
                       // alert (BanderaExiste)
			if(BanderaExiste !=0){
				//Abre la hoja que muesta el detalle de la solicitud de estudios
                                 // alert(' Proceso '+Proceso+' IdHistorialClinico '+IdHistorialClinico+' IdNumeroExp '+IdNumeroExp+' FechaSolicitud '+FechaSolicitud+' IdUsuarioReg '+IdUsuarioReg+' FechaHoraReg '+FechaHoraReg+' NombreExamen '+NombreExamen+' Indicacion '+Indicacion+' NombreMuestra '+NombreMuestra+' NombreOrigen '+NombreOrigen+'&idexpediente:'+idexpediente);
                                 // alert ('Qie pasa')
                              //   return false;
                            //    alert ('WTFuuaaa!!')
                              //  return false;
                                ListaExamenes(IdHistorialClinico,IdCitaServApoyo);
			}

			if(BanderaExiste==0){
				alert('No has seleccionado ningun Examen....!');
				document.getElementById('Enviar').value='ENVIAR SOLICITUD';
				document.getElementById('Enviar').disabled=false;
				return false;
			}




 }//Fuicniopn Guardar Solicitud



function Retraso(ID){
	document.getElementById(ID).checked=false;
	BanderaExiste=1;
	setTimeout('GuardarSolicitud()', 900);
}//Retraso

function CambioEstadoSol(idsol){
	ObjetoAjax=NuevoAjax();
	ObjetoAjax.open("POST", 'Procesar.php', true);
	ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// Declaración de parámetros
	var Proceso='verificarestado';
	var param = 'Proceso='+Proceso;
	// Concatenación y Envío de Parámetros
	param += '&idsolicitud='+idsol;
	ObjetoAjax.send(param);
	ObjetoAjax.onreadystatechange=function() {
	  if (ObjetoAjax.readyState==4)
	  {
	//	console.log('respuesta'+ObjetoAjax.responseText);
		return true;
	  }
	}



/*
	ajax.open("POST", "Procesar.php", true);

	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	//enviando los valores
	console.log(idsol)
	ajax.send("Proceso=" + Proceso + "&idsolicitud=" + idsol);
	ajax.onreadystatechange = function()
	{
		if (ajax.readyState == 4)
		{
			if (ajax.status == 200)
			{  //mostrar los nuevos registros en esta capa
				console.log('true'+ajax.responseText);
				//exit();

			}
		}
	}*/
}//cambioestadosol



/************************************************************************************************/
/* 			Funcion Para Guardar Cambios a los examenes solicitados								*/
/************************************************************************************************/
 function GuardarCambios(band){
	 //console.log (band)
	// Definicion de Variables
		var Indicacion;
		var Indica;
		var IdExamen;
		var IdDetalle;
		var Contar=document.getElementById('total').value;
        var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
        var  IdCitaServApoyo=document.getElementById('IdCitaServApoyo').value;
		var  id_solicitudest=document.getElementById('id_solicitudest').value;
		var accion=4;
		var j=0;
		var i=0;
                var k=0;
	//Verificar si son todos examenes referidos, pasar el estado de la solicitud a finalizado
	CambioEstadoSol(id_solicitudest);
// Comprobar todos los check box que esten chekeados,.....
         //console.log(Contar+'-'+band)
	if (Contar==0 && band!=0){
           eliminarsolicitud(band);
        }
	if(Contar==1 && document.Editar.ExamenesLab.checked == true){
        /**** Pasos para Guardar los datos***/
        // Parametros del Detalle de los examenes
                IdExamen=document.Editar.ExamenesLab.value;
                IdDetalle=document.getElementById("IdDetalle"+IdExamen).value;
                ftomamx=document.getElementById("ftomamx"+IdExamen).value;
                //Detalle=document.getElementById("Detalle"+IdExamen).value;
        // Crear Objeto Ajax
                ObjetoAjax=NuevoAjax();
                // Hacer el Request y llamar o Dibujar el Resultado
                ObjetoAjax.onreadystatechange = CargarContenido();
                ObjetoAjax.open("POST", 'Procesar.php', true);
                ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                // Declaración de parámetros
                var Proceso='GuardarCambios';
                var param = 'Proceso='+Proceso;
                // Concatenación y Envío de Parámetros
                param += '&IdDetalle='+IdDetalle;
                ObjetoAjax.send(param);
                ObjetoAjax.onreadystatechange=function() {
                  if (ObjetoAjax.readyState==4)
                  {
                     ListaExamenes(IdHistorialClinico,IdCitaServApoyo, band);
                  }
               }
	}
	else{
       /***/
         $('input[name="ExamenesLab"]').each(function(index) {
            //console.log($(this).val(), index);
            IdExamen=$(this).val();
            Indicacion=$('#Indicacion'+IdExamen).val();
            IdDetalle=$('#IdDetalle'+IdExamen).val();
            ftomamx=$('#ftomamx'+IdExamen).val();
             if($(this).is(':checked')){
               ObjetoAjax=NuevoAjax();

               // Hacer el Request y llamar o Dibujar el Resultado

               ObjetoAjax.onreadystatechange = CargarContenido();

               ObjetoAjax.open("POST", 'Procesar.php', true);
               ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

               // Declaración de parámetros

               var Proceso='GuardarCambios';
               var param = 'Proceso='+Proceso;

               // Concatenación y Envío de Parámetros
               param += '&IdDetalle='+IdDetalle;
               ObjetoAjax.send(param);
            }
            else{
               k=1;
               ObjetoAjax=NuevoAjax();

               // Hacer el Request y llamar o Dibujar el Resultado
               ObjetoAjax.onreadystatechange = CargarContenido();
               ObjetoAjax.open("POST", 'Procesar.php', true);
               ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

               // Declaración de parámetros

               var Proceso='ActualizarDatos';
               var param = 'Proceso='+Proceso;

               // Concatenación y Envío de Parámetros
               param += '&IdDetalle='+IdDetalle+"&Indicacion="+Indicacion+"&ftomamx="+ftomamx;
               ObjetoAjax.send(param);
                  //} // Fin Else para Actualizar Datos
            }

         });
		ObjetoAjax.onreadystatechange=function() {
               if (ObjetoAjax.readyState==4)
               {
                  if (band==0)
                        ListaExamenes(IdHistorialClinico,IdCitaServApoyo)
                  else{
                     if (k==0){
                        ListaExamenes(IdHistorialClinico,IdCitaServApoyo, band)
                     }
                     else{
                         ListaExamenes(IdHistorialClinico,IdCitaServApoyo,band)
                         //alert('no es cerrar')
                        window.close();
                     }
                    //
                  //  alert ('cerrar')
                  }
               }
            }


	}// Fin Else
       // }//fin else
//alert (parametros)
  //ObjetoAjax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	//MostrarDetalle();
 }


 //fn pg
 //funcion para guardar o actualizar los datos adicionales
 function guardardatosadicionales(){
    IdExamen=document.Editar.ExamenesLab.value;
   Indicacion=document.getElementById("Indicacion"+IdExamen).value;
   IdDetalle=document.getElementById("IdDetalle"+IdExamen).value;
   ftomamx=document.getElementById("ftomamx"+IdExamen).value;

   ObjetoAjax=NuevoAjax();

   // Hacer el Request y llamar o Dibujar el Resultado
   ObjetoAjax.onreadystatechange = CargarContenido();
   ObjetoAjax.open("POST", 'Procesar.php', true);
   ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

   // Declaración de parámetros

   var Proceso='ActualizarDatos';
   var param = 'Proceso='+Proceso;

   // Concatenación y Envío de Parámetros
   param += '&IdDetalle='+IdDetalle+"&Indicacion="+Indicacion+"&ftomamx="+ftomamx;
   ObjetoAjax.send(param);
 }

/************************************************************************************************/
/* 			Funcion para Cargar el Contenido de los Resultados									*/
/************************************************************************************************/

function CargarContenido(){
  // Si se Tarda en Cargar los datos que lanze los siguientes mensajes dependiendo del caso que sea

  if (ObjetoAjax.readyState==1) {

	if(accion==0)
	document.getElementById('RespuestaAjax').innerHTML = "<center><img src='loading.gif' alt='Cargando1...' /><br>Cargando Resultados...! </center>";
	else if(accion==1)
	document.getElementById(Nombre).innerHTML = "<center><img src='loading.gif' alt='Cargando...' /><br>Cargando Resultados...! </center>";

	else if(accion==2){
//            alert (Muestra)
//	document.getElementById(Muestra).innerHTML = "<center><img src='loading.gif' alt='Cargando...' /><br>Cargando Resultados...! </center>";
     }

	else if(accion==3)
	document.getElementById('Resultados').innerHTML = "<center><img src='loading.gif' alt='Cargando...' /><br>Cargando Resultados...! </center>";



  }	 // Fin Estado Cargando

  else if (ObjetoAjax.readyState == 4){//4 The request se Completo y vamos a cargar
	  if (ObjetoAjax.status == 200){//200 Significa que vamos cargar porque el request se Realizó....!
		  respuesta = ObjetoAjax.responseText;
		  /*************Dependiendo la Accion ejecutamos la Respuesta: En este caso cargar Solicitudes**************/
			if(accion==0){
				 //document.getElementById('Cambios').innerHTML = "";
				 document.getElementById('RespuestaAjax').innerHTML = respuesta;
			}
			else if(accion==1)
				 document.getElementById(Nombre).innerHTML = respuesta;

			else if(accion==2){
				 document.getElementById("O"+Nombre).innerHTML = respuesta;	 }

			else if(accion==3)
				 document.getElementById("Resultados").innerHTML = respuesta;

			else if(accion==4){
				 document.getElementById("Cambios").innerHTML = respuesta;
				 var IdHistorialClinico = document.getElementById('IdHistorialClinico').value;
				 MostrarDetalle(IdHistorialClinico);
			}

	   		}   // Fin  If Status 200


	   else if(ObjetoAjax.status==404){ //  Reques no completado...Error Cuando No Existe la dirección donde se ejectura el Proceso

			if(accion==0)
				document.getElementById('RespuestaAjax').innerHTML = "La direccion no existe";

			else if(accion==1)
				document.getElementById(Nombre).innerHTML = "La direccion no existe";

			else if(accion==2)
				document.getElementById("O"+Nombre).innerHTML = "La direccion no existe";

			else if(accion==3)
				document.getElementById("Resultados").innerHTML = "La direccion no existe";

			else if(accion==4)
				document.getElementById("Cambios").innerHTML = "La direccion no existe";
         }
         else{

			if(accion==0)
				document.getElementById('RespuestaAjax').innerHTML == "Error: "+ObjetoAjax.status;

			else if(accion==1)
				document.getElementById(Nombre).innerHTML == "Error: "+ObjetoAjax.status;
			else if(accion==2)
				document.getElementById("O"+Nombre).innerHTML == "Error: "+ObjetoAjax.status;

			else if(accion==3)
				document.getElementById("Resultados").innerHTML == "Error: "+ObjetoAjax.status;

			else if(accion==4)
				document.getElementById("Cambios").innerHTML == "Error: "+ObjetoAjax.status;

  		 } // Fin Errores Status 404
  } // Fin ReadyState 4
}// Fin de la Funcion ProcesaEsp


/************************************************************************************************/
/* 			Funcion Para Verificar si el Examen es Urgente.. Crea una Solicitud NUEVA 								*/
/************************************************************************************************/

 //fn pg
 //Eliminar solicitud completa
 function eliminarsolicitud(idsolicitud){
    var cuantos = document.getElementById('total').value;
   // alert('aqui 0');
    if (cuantos ==0){
      // Crear Objeto Ajax
      var eliminar = confirm("No ha seleccionado ningún examen, desea eliminar la solicitud");
    //  alert (eliminar)
      if (eliminar) {
      // Hacer el Request y llamar o Dibujar el Resultado
      ObjetoAjax2=NuevoAjax();
      //alert('aqui 1');
      ObjetoAjax2.onreadystatechange = function(){
          if(ObjetoAjax2.readyState==4){
             window.close();
          }
      }
      // Declaración de parámetros
      ObjetoAjax2.open("GET", 'Procesar.php?Proceso=EliminarSolicitud'+'&idsolicitud='+idsolicitud, true);
      ObjetoAjax2.send(null);


      }
      else{
        //  alert('aqui 3');
          return false;
      }
    }
 }//fin eliminarsolicitud

 //fn pg
 function updatealldates(){
    $( "input[name^='ftomamx']" ).val( $('#fgentomamxgen').val() );
 }

function seleccionarpruebas(idperfil) {
   jQuery.ajax({
      url:'Procesar.php',
      type:'post',
      dataType:'json',
      async: false,
      timeout:8000,

      data: {Proceso: 'seleccionarPruebas', idperfil: idperfil },
      success: function(object){
         if (object.status){
            if (object.num_rows>0){
         jQuery.each(object.data, function(idx, val) {

            $('input[type="checkbox"][value='+val.id_conf_examen_estab+']').each(function() {
               $(this).prop("checked", true);

               data=($(this).attr('id')).split('Examenes');
              // console.log(data[1]+' --- '+val.id_conf_examen_estab);
              MostrarLista2(val.id_conf_examen_estab, data[1]);

              //console.log('data:'+data)

            });




         });
      }
      $('#btnperfil'+idperfil).addClass( "disabled" )
         }
         else
            alert('Error al seleccionar las pruebas del perfil...')
      },
      error: function(jqXHR, exception) {
            if (jqXHR.status === 0) {
                alert('Not connect.\n Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found. [404]');
            } else if (jqXHR.status == 500) {
                alert('Internal Server Error [500].');
            } else if (exception === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                alert('Time out error.');
            } else if (exception === 'abort') {
                alert('Ajax request aborted.');
            } else if (textStatus==="timeout"){
                alert('Error timeout');
            } else {
                alert('Uncaught Error.\n' + jqXHR.responseText);
            }
        }

   })

}//fin seleccionarpruebas

function seleccionaridpruebas(){
   setTimeout(function(object) {
      if (object.num_rows>0){
         jQuery.each(object.data, function(idx, val) {
            $("#Examenes"+val.id_conf_examen_estab).attr ( "checked" ,"checked" );
         });
      }
                    }, 500);
}

function realizaProceso(valorCaja1, valorCaja2){

        var parametros = {
                "valorCaja1" : valorCaja1,
                "valorCaja2" : valorCaja2
        };
        $.ajax({
                data:  parametros,
                url:   'ejemplo.php',
                type:  'get',
                dataType:'json',
                async: true,
                 /*data: {Proceso: 'seleccionarPruebas', idperfil: idperfil },*/
                beforeSend: function () {
                        $("#resultado").html("Procesando, espere por favor...");
                },
//                success:  function (response) {
//                        $("#resultado").html(response);
//                }
success: function(object){
         if (object.status)
            $("#resultado").html(response);
         else
            alert('Error al seleccionar las pruebas del perfil...')
      }
        });
}
