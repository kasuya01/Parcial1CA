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

function AgregarExamen(){
	var IdHistorialClinico=document.getElementById('IdHistorialClinico').value;
	var IdNumeroExp=document.getElementById('idexpediente').value;
	var FechaSolicitud=document.getElementById('FechaSolicitud').value;				
	var IdUsuarioReg=document.getElementById('IdUsuarioReg').value;				
	var FechaHoraReg=document.getElementById('FechaHoraReg').value;	

	var Parametros="?IdNumeroExp="+IdNumeroExp;
			Parametros+="&Fecha="+FechaSolicitud;
			Parametros+="&IdUsuarioReg="+IdUsuarioReg;
			Parametros+="&FechaHoraReg="+FechaHoraReg;
			Parametros+="&IdHistorialClinico="+IdHistorialClinico;
	location.href='./Solicitud.php'+Parametros;

}

function ListaExamenes(IdHistorialClinico,IdCitaServApoyo){
	window.location.href='ExamenesSolicitados.php?IdHistorialClinico='+IdHistorialClinico+'&IdCitaServApoyo='+IdCitaServApoyo;
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
	
	// Declaraci�n de par�metros
	
	var Proceso='Impresiones';
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
 	var NombreDiv=Examen+value;
	
	if (document.Solicitud.Examenes[value].checked == true) {
		Nombre=Examen;
		Origen='O'+ Examen;
		accion=1;
			
		// Crear Objeto Ajax
		ObjetoAjax=NuevoAjax();			
		
		// Hacer el Request y llamar o Dibujar el Resultado
		ObjetoAjax.onreadystatechange = CargarContenido;
		ObjetoAjax.open("POST", 'Procesar.php', true);
		ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		
		// Declaración de parámetros
		var IdExamen=Examen;
		var Proceso='Combos';
		var param = 'Proceso='+Proceso;
			//alert (IdExamen)
		// Concatenación y Envío de Parámetros
		param += '&IdExamen='+IdExamen;
		ObjetoAjax.send(param);  

	} // fin If Checkeado
 	else {		
		   document.getElementById(Examen).innerHTML="";
   		   document.getElementById("O"+Examen).innerHTML="";
		}


 }


/************************************************************************************************/
/* 			Funcion Para Mostrar  el origen y tipo de muestra de un examen						*/
/************************************************************************************************/
//fn pg
function MostrarOrigen(Muestra,Examen){	
	Nombre=Examen;
	Origen="O"+Examen;
 	var IdMuestra=Muestra;
	accion=2;
		
	// Crear Objeto Ajax
	ObjetoAjax=NuevoAjax();			
	
	// Hacer el Request y llamar o Dibujar el Resultado
	ObjetoAjax.onreadystatechange = CargarContenido;
	ObjetoAjax.open("POST", 'Procesar.php', true);
	ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	
	// Declaración de parámetros
	
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
		

// Comprobar todos los check box que esten chekeados,.....
	//for (i=0;i<Tope;i++){
			// Si esta checkeado entonces que guarde en la base de datos...!
					//var ID='Examenes'+i;

			if ((document.getElementById(ID).checked == true) && suma !=0) {
						
				// Parametros del Detalle de los examenes 
					NombreExamen=document.getElementById(ID).value;
					Muestra="M"+ NombreExamen;
					Origen="Origen"+ NombreExamen;
					NombreMuestra=document.getElementById(Muestra).value;
                                        
                                       
					if(NombreMuestra==0){
						NombreOrigen=0;
					}else{
						NombreOrigen=document.getElementById(Origen).value;
					}
					
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
						
						Retraso(ID);
						
					}//Estado == 4
				}//On ready state
			var Proceso='GuardarDatos';
                        alert(' Proceso '+Proceso+' IdHistorialClinico '+IdHistorialClinico+' IdNumeroExp '+IdNumeroExp+' FechaSolicitud '+FechaSolicitud+' IdUsuarioReg '+IdUsuarioReg+' NombreExamen '+NombreExamen+' Indicacion '+Indicacion+' NombreMuestra '+NombreMuestra+' NombreOrigen '+NombreOrigen+'&idexpediente:'+idexpediente);
               //         alert (NombreExamen)
		ajax2.open("GET",'Procesar.php?Proceso='+Proceso+'&IdHistorialClinico='+IdHistorialClinico+'&IdNumeroExp='+IdNumeroExp+'&idexpediente='+idexpediente+'&FechaSolicitud='+FechaSolicitud+'&IdUsuarioReg='+IdUsuarioReg+'&IdExamen='+NombreExamen+'&Indicacion='+Indicacion+'&IdTipoMuestra='+NombreMuestra+'&IdOrigen='+NombreOrigen,true);	/*	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) 
	{
		document.getElementById('Resultados').innerHTML = ajax.responseText
		
	}
  }
//alert (parametros)
  ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");*/
		ajax2.send(null);
		return false;
			
				
						
			}//If checked == true
	//}//for
                        
			// AKI CREAR LA cit_citasxserviciodeapoyo o actualizar la fecha de la cita una solo ves.
			if(BanderaExiste !=0){
				//Abre la hoja que muesta el detalle de la solicitud de estudios
                                 // alert(' Proceso '+Proceso+' IdHistorialClinico '+IdHistorialClinico+' IdNumeroExp '+IdNumeroExp+' FechaSolicitud '+FechaSolicitud+' IdUsuarioReg '+IdUsuarioReg+' FechaHoraReg '+FechaHoraReg+' NombreExamen '+NombreExamen+' Indicacion '+Indicacion+' NombreMuestra '+NombreMuestra+' NombreOrigen '+NombreOrigen+'&idexpediente:'+idexpediente);
                                 // alert ('Qie pasa')
                              //   return false;
                                alert ('WTFuuaaa!!')
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




/************************************************************************************************/
/* 			Funcion Para Guardar Cambios a los examenes solicitados								*/
/************************************************************************************************/
 function GuardarCambios(){	
	// Definicion de Variables		
		var Indicacion;
		var Indica;
		var IdExamen;
		var IdDetalle;
		var Contar=document.getElementById('total').value;
		accion=4;
		var j=0;
		var i=0;

// Comprobar todos los check box que esten chekeados,.....

	
	
	if(Contar==1 && document.Editar.ExamenesLab.checked == true){	
        /**** Pasos para Guardar los datos***/
        // Parametros del Detalle de los examenes
                IdExamen=document.Editar.ExamenesLab.value;
                IdDetalle=document.getElementById("IdDetalle"+IdExamen).value;
                //Detalle=document.getElementById("Detalle"+IdExamen).value;
        // Crear Objeto Ajax
                ObjetoAjax=NuevoAjax();			

                // Hacer el Request y llamar o Dibujar el Resultado
                ObjetoAjax.onreadystatechange = CargarContenido;
                ObjetoAjax.open("POST", 'Procesar.php', true);
                ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

                // Declaración de parámetros

                var Proceso='GuardarCambios';
                var param = 'Proceso='+Proceso;

                // Concatenación y Envío de Parámetros          
                param += '&IdDetalle='+IdDetalle;
                ObjetoAjax.send(param); 	
	}
	else if (Contar==1 && document.Editar.ExamenesLab.checked == false){	
            IdExamen=document.Editar.ExamenesLab.value;
            Indicacion=document.getElementById("Indicacion"+IdExamen).value;
            Detalle=document.getElementById("Detalle"+IdExamen);
            if (IdDetalle==null){
                Detalle=2;
            }
            else{
                Detalle=1;
            }                    

            if(Indicacion != ""){
                            // Crear Objeto Ajax
                ObjetoAjax=NuevoAjax();			

                // Hacer el Request y llamar o Dibujar el Resultado
                ObjetoAjax.onreadystatechange = CargarContenido;
                ObjetoAjax.open("POST", 'Procesar.php', true);
                ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

                // Declaración de parámetros

                var Proceso='ActualizarDatos';
                var param = 'Proceso='+Proceso;

                // Concatenación y Envío de Parámetros
                param += '&IdDetalle='+IdDetalle+"&Indicacion="+Indicacion+"&Detalle="+Detalle;
                ObjetoAjax.send(param); 
            } // Fin Else para Actualizar Datos
	}

	else{
	
		for (i=0;i<document.Editar.ExamenesLab.length;i++){
			// Si esta checkeado entonces que Borre los datos en la base de datos...!
				IdExamen=document.Editar.ExamenesLab[i].value;
				Indicacion=document.getElementById("Indicacion"+IdExamen).value;
				IdDetalle=document.getElementById("IdDetalle"+IdExamen).value;
			if (document.Editar.ExamenesLab[i].checked == true) {					
					/**** Pasos para Guardar los datos***/
					// Crear Objeto Ajax
						ObjetoAjax=NuevoAjax();			
						
						// Hacer el Request y llamar o Dibujar el Resultado
						ObjetoAjax.onreadystatechange = CargarContenido;
						ObjetoAjax.open("POST", 'Procesar.php', true);
						ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
						
						// Declaración de parámetros
						
						var Proceso='GuardarCambios';
						var param = 'Proceso='+Proceso;
							
						// Concatenación y Envío de Parámetros
						param += '&IdDetalle='+IdDetalle;
						ObjetoAjax.send(param); 
			} // Fin If

			// De lo Contrario si no esta checkeado que verifique si hay que actualizar datos
			else{
				if(Indicacion != ""){
					// Crear Objeto Ajax
						ObjetoAjax=NuevoAjax();			
						
						// Hacer el Request y llamar o Dibujar el Resultado
						ObjetoAjax.onreadystatechange = CargarContenido;
						ObjetoAjax.open("POST", 'Procesar.php', true);
						ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
						
						// Declaración de parámetros
						
						var Proceso='ActualizarDatos';
						var param = 'Proceso='+Proceso;
							
						// Concatenación y Envío de Parámetros
						param += '&IdDetalle='+IdDetalle+"&Indicacion="+Indicacion;
						ObjetoAjax.send(param); 
				} // Fin Else para Actualizar Datos

				
				
			}
					
		} // Fin For			
	}// Fin Else

	//MostrarDetalle();
 }

/************************************************************************************************/
/* 			Funcion para Cargar el Contenido de los Resultados									*/
/************************************************************************************************/

function CargarContenido(){
  // Si se Tarda en Cargar los datos que lanze los siguientes mensajes dependiendo del caso que sea	 
  if (ObjetoAjax.readyState==1) {                    
		
	if(accion==0)
	document.getElementById('RespuestaAjax').innerHTML = "<center><img src='loading.gif' alt='Cargando...' /><br>Cargando Resultados...! </center>";
	else if(accion==1)
	document.getElementById(Nombre).innerHTML = "<center><img src='loading.gif' alt='Cargando...' /><br>Cargando Resultados...! </center>";
	
	else if(accion==2)
	document.getElementById(Muestra).innerHTML = "<center><img src='loading.gif' alt='Cargando...' /><br>Cargando Resultados...! </center>";

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

			else if(accion==2)
				 document.getElementById("O"+Nombre).innerHTML = respuesta;	 
		
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
function Urgente(){	
    // Definicion de Variables
    var IdHistorialClinico=document.Editar.IdHistorialClinico.value;
    var largo=document.getElementById('totalurgente').value;
    var detalles_urgente=new Array(); 
    var e=0;
    var i;       
    //alert (largo)
    for (i=1;i< largo;i++){
        if (document.getElementById('Detalle'+[i]).checked==true){ 
            //alert (document.getElementById('Detalle'+[i]).value)
            detalles_urgente[e]=document.getElementById('Detalle'+[i]).value;// se captura y se almacena en el vector            
            e++;
        }
    }

    var cadena_ref=detalles_urgente.toString();// se convierte a string    
  //alert (cadena_ref+'--'+IdHistorialClinico);    
  
    //Crear Objeto Ajax
    ObjetoAjax=NuevoAjax();			
    // Hacer el Request y llamar o Dibujar el Resultado
    ObjetoAjax.onreadystatechange = function(){
        if(ObjetoAjax.readyState==4){
            //alert(ObjetoAjax.responseText);
            window.close();
        }
    }
    
    ObjetoAjax.open("GET","Procesar.php?Proceso=VerificarSolicitudUrgente"+"&IdDetallesUrgentes="+cadena_ref+"&IdHistorialClinico="+IdHistorialClinico,true);
    ObjetoAjax.send(null);
    return false;
	
 }