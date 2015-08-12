var ObjetoAjax;
var accion=0;
/************************************************************************************************/
/*  					Funcion Para Cerrar una ventana											*/
/************************************************************************************************/
function CerrarVentana(){
	window.close();
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
/************************************************************************************************/
/* 			Funcion Para Mostrar los Detalles de la Solicitud de Laboratorio					*/
/************************************************************************************************/
function SolicitudesLaboratorio(IdExpediente,pagina){	
		//alert('Numero Expediente '+IdExpediente+'  Pagina '+pagina);
		 accion=0;
		// Crear Objeto Ajax
		ObjetoAjax=NuevoAjax();			
		// Hacer el Request y llamar o Dibujar el Resultado
		ObjetoAjax.onreadystatechange = CargarContenido;
		ObjetoAjax.open("POST", 'ResultadosEstudios.php', true);
		ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		// Declaraci�n de par�metros

		var NumeroExp=IdExpediente;
		var NoPagina=pagina;
		var Proceso='ConsultasLaboratorio';
		var param = 'Proceso='+Proceso;
		
		// Concatenaci�n y Env�o de Par�metros
		param += '&IdNumeroExp='+NumeroExp+'&pag='+NoPagina;
		ObjetoAjax.send(param);  
		
}




/************************************************************************************************/
/* 			Funcion Para Mostrar los Detalles de la Solicitud de Laboratorio					*/
/************************************************************************************************/
function MostrarDetalleSolicitud(IdSolicitudEstudio,FechaSolicitud){	
		 accion=1;
		// Crear Objeto Ajax
		ObjetoAjax=NuevoAjax();			
		// Hacer el Request y llamar o Dibujar el Resultado
		ObjetoAjax.onreadystatechange = CargarContenido;
		ObjetoAjax.open("POST", 'ResultadosEstudios.php', true);
		ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		// Declaraci�n de par�metros
		var IdSolicitud=IdSolicitudEstudio;
		var Fecha=FechaSolicitud;		
		var Proceso='DetalleSolicitud'
		var param = 'Proceso='+Proceso;
		
		// Concatenaci�n y Env�o de Par�metros
		param += '&IdSolicitudEstudio='+IdSolicitud+'&FechaSolicitud='+Fecha;
		ObjetoAjax.send(param);  
		
}



/************************************************************************************************/
/*  Funcion Para Mostrar Resultados de  Laboratorio	 Plantilla A y Detalle de Platilla B		*/
/************************************************************************************************/
function MostrarResultadosExamen(IdSolicitudEstudio,IdArea){
		accion=2;
		// Crear Objeto Ajax
		ObjetoAjax=NuevoAjax();
		
		// Hacer el Request y llamar o Dibujar el Resultado
		ObjetoAjax.onreadystatechange = CargarContenido;
		ObjetoAjax.open("POST", 'ResultadosEstudios.php', true);
		ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		// Declaraci�n de par�metros
		var IdSolicitud=IdSolicitudEstudio;
		var IdAreas=IdArea;
		var Proceso='DetalleResultado'
		var param = 'Proceso='+Proceso;
		// Concatenaci�n y Env�o de Par�metros
		param += '&IdSolicitudEstudio='+IdSolicitud;
		param += '&IdArea='+IdAreas;
		ObjetoAjax.send(param);  
		
}

/************************************************************************************************/
/* 			Funcion Para Mostrar Resultados de  Laboratorio	 para Plantilla B					*/
/************************************************************************************************/
function ResultadosPlantillaB(IdDetalleSolicitud,Sexo,idedad){
		accion=3;
		// Crear Objeto Ajax
		ObjetoAjax=NuevoAjax();
		//alert (Sexo+""+idedad);
		// Hacer el Request y llamar o Dibujar el Resultado
		ObjetoAjax.onreadystatechange = CargarContenido;
		ObjetoAjax.open("POST", 'ResultadosEstudios.php', true);
		ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		// Declaraci�n de par�metros
		var IdDetalleResultado=IdDetalleSolicitud;
		var Proceso='ResultadosPlantillaB'
		var param = 'Proceso='+Proceso+'&Sexo='+Sexo+'&idedad='+idedad;
		// Concatenaci�n y Env�o de Par�metros
		param += '&IdDetalleSolicitud='+IdDetalleResultado;
		ObjetoAjax.send(param);  
		
}


/************************************************************************************************/
/* 			Funcion Para Mostrar Resultados de  Laboratorio	 para Plantilla C					*/
/************************************************************************************************/
function ResultadosPlantillaC(IdDetalleSolicitud,Resultado){
		accion=7;
		// Crear Objeto Ajax
		ObjetoAjax=NuevoAjax();
		
		// Hacer el Request y llamar o Dibujar el Resultado
		ObjetoAjax.onreadystatechange = CargarContenido;
		ObjetoAjax.open("POST", 'ResultadosEstudios.php', true);
		ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		// Declaraci�n de par�metros
		var IdDetalleResultado=IdDetalleSolicitud;
		var TipoResultado=Resultado;
		var Proceso='ResultadosPlantillaC';
		var param = 'Proceso='+Proceso;
		// Concatenaci�n y Env�o de Par�metros
		param += '&IdDetalleSolicitud='+IdDetalleResultado+'&Resultado='+TipoResultado;
		ObjetoAjax.send(param);  
		
}


/************************************************************************************************/
/* 			Funcion Para Mostrar Resultados de  Laboratorio	 para Plantilla D					*/
/************************************************************************************************/
function ResultadosPlantillaD(IdDetalleSolicitud,Resultado){
		accion=7;
		// Crear Objeto Ajax
		ObjetoAjax=NuevoAjax();
		
		// Hacer el Request y llamar o Dibujar el Resultado
		ObjetoAjax.onreadystatechange = CargarContenido;
		ObjetoAjax.open("POST", 'ResultadosEstudios.php', true);
		ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		// Declaraci�n de par�metros
		var IdDetalleResultado=IdDetalleSolicitud;
		var TipoResultado=Resultado;
		var Proceso='ResultadosPlantillaD';
		var param = 'Proceso='+Proceso;
		// Concatenaci�n y Env�o de Par�metros
		param += '&IdDetalleSolicitud='+IdDetalleResultado+'&Resultado='+TipoResultado;
		ObjetoAjax.send(param);  
		
}


/************************************************************************************************/
/* 			Funcion Para Mostrar Resultados de  Laboratorio	 para Plantilla D					*/
/************************************************************************************************/
function ResultadosPlantillaE(IdDetalleSolicitud,Resultado){
		accion=8;
		// Crear Objeto Ajax
		ObjetoAjax=NuevoAjax();
		
		// Hacer el Request y llamar o Dibujar el Resultado
		ObjetoAjax.onreadystatechange = CargarContenido;
		ObjetoAjax.open("POST", 'ResultadosEstudios.php', true);
		ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		// Declaraci�n de par�metros
		var IdDetalleResultado=IdDetalleSolicitud;
		var TipoResultado=Resultado;
		var Proceso='ResultadosPlantillaE';
		var param = 'Proceso='+Proceso;
		// Concatenaci�n y Env�o de Par�metros
		param += '&IdDetalleSolicitud='+IdDetalleResultado+'&Resultado='+TipoResultado;
		ObjetoAjax.send(param);  
		
}



/************************************************************************************************/
/* 			Funcion Para Mostrar los Detalles de la Solicitud de RAYOS X 						*/
/************************************************************************************************/
function DetalleSolicitudRx(IdSolicitudEstudio,FechaSolicitud){	
		 accion=4;
		// Crear Objeto Ajax
		ObjetoAjax=NuevoAjax();			
		// Hacer el Request y llamar o Dibujar el Resultado
		ObjetoAjax.onreadystatechange = CargarContenido;
		ObjetoAjax.open("POST", 'ResultadosEstudios.php', true);
		ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		// Declaraci�n de par�metros
		var IdSolicitud=IdSolicitudEstudio;
		var Fecha=FechaSolicitud;
		var Proceso='DetalleSolicitudRx'
		var param = 'Proceso='+Proceso;
		
		// Concatenaci�n y Env�o de Par�metros
		param += '&IdSolicitudEstudio='+IdSolicitud+'&FechaSolicitud='+Fecha;
		ObjetoAjax.send(param);  
		
}


/************************************************************************************************/
/* 			Funcion Para Mostrar los Detalles Resultados de RAYOS X 							*/
/************************************************************************************************/
function ResultadosRx(IdDetalleSolicitud,NombreExamen){	
		 accion=5;
		// Crear Objeto Ajax
		ObjetoAjax=NuevoAjax();			
		// Hacer el Request y llamar o Dibujar el Resultado
		ObjetoAjax.onreadystatechange = CargarContenido;
		ObjetoAjax.open("POST", 'ResultadosEstudios.php', true);
		ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		// Declaraci�n de par�metros
		var IdSolicitud=IdDetalleSolicitud;
		var Nombre=NombreExamen;
		var Proceso='ResultadosRx'
		var param = 'Proceso='+Proceso;
		
		// Concatenaci�n y Env�o de Par�metros
		param += '&IdDetalleSolicitud='+IdSolicitud+'&NombreExamen='+Nombre;
		ObjetoAjax.send(param);  
		
}


/************************************************************************************************/
/* 			Funcion Para Mostrar los Detalles de la Solicitud de Laboratorio					*/
/************************************************************************************************/
function SolicitudesRX(IdExpediente,pagina){	
		//alert('Numero Expediente '+IdExpediente+'  Pagina '+pagina);
		 accion=6;
		// Crear Objeto Ajax
		ObjetoAjax=NuevoAjax();			
		// Hacer el Request y llamar o Dibujar el Resultado
		ObjetoAjax.onreadystatechange = CargarContenido;
		ObjetoAjax.open("POST", 'ResultadosEstudios.php', true);
		ObjetoAjax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		// Declaraci�n de par�metros

		var NumeroExp=IdExpediente;
		var NoPagina=pagina;
		var Proceso='ConsultasRX';
		var param = 'Proceso='+Proceso;
		
		// Concatenaci�n y Env�o de Par�metros
		param += '&IdNumeroExp='+NumeroExp+'&pag='+NoPagina;
		ObjetoAjax.send(param);  
		
}


/************************************************************************************************/
/* 			Funcion para Cargar el Contenido de los Resultados									*/
/************************************************************************************************/

function CargarContenido(){
  // Si se Tarda en Cargar los datos que lanze los siguientes mensajes dependiendo del caso que sea	 
  if (ObjetoAjax.readyState==1) {                    
		
	 if(accion==0)
	document.getElementById('Consultas').innerHTML = "<center><img src='loading.gif' alt='Cargando...' /><br>Cargando Resultados...! </center>";
		
		if(accion==1)
	document.getElementById('Solicitudes').innerHTML = "<center><img src='loading.gif' alt='Cargando...' /><br>Cargando Resultados...! </center>";
	 	  else if(accion==2)	
				document.getElementById('DetalleResultados').innerHTML = "<center><img src='loading.gif' alt='Cargando...' /><br>Cargando...! </center>";												         
		  else if(accion==3)	
				document.getElementById('PlantillaB').innerHTML = "<center><img src='loading.gif' alt='Cargando Resultados...' /><br>Cargando...! </center>";
		  else if(accion==4)	
				document.getElementById('DetalleSolicitudesRx').innerHTML = "<center><img src='loading.gif' alt='Cargando Resultados...' /><br>Cargando...! </center>";
		  else if(accion==5)	
				document.getElementById('ResultadosRx').innerHTML ="<center><img src='loading.gif' alt='Cargando...' /><br>Cargando Resultados...! </center>";
	
		 else if(accion==6)	
				document.getElementById('ConsultasRX').innerHTML ="<center><img src='loading.gif' alt='Cargando...' /><br>Cargando Resultados...! </center>";

		else if(accion==7)	
				document.getElementById('PlantillaB').innerHTML ="<center><img src='loading.gif' alt='Cargando...' /><br>Cargando Resultados...! </center>";
		
		else if(accion==8)	
				document.getElementById('PlantillaB').innerHTML ="<center><img src='loading.gif' alt='Cargando...' /><br>Cargando Resultados...! </center>";
			
  }	 // Fin Estado Cargando

  else if (ObjetoAjax.readyState == 4){//4 The request se Complet� y vamos a cargar
	 

	  if (ObjetoAjax.status == 200){//200 Significa que vamos cargar porque el request se Realiz�....!
		  respuesta = ObjetoAjax.responseText;	
		  /*************Dependiendo la Accion ejecutamos la Respuesta: En este caso cargar Solicitudes**************/
			
			if(accion==0)
				 document.getElementById('Consultas').innerHTML = respuesta;
				 
			if(accion==1)
				 document.getElementById('Solicitudes').innerHTML = respuesta;
			
		  /*************Dependiendo la Accion ejecutamos la Respuesta: En este caso Mostrar Detalle**************/	
			else if(accion==2)
					document.getElementById('DetalleResultados').innerHTML = respuesta;
	
		/************* Mostrar Detalle Plantilla B**************/	
			else if(accion==3)
					document.getElementById('PlantillaB').innerHTML = respuesta;

		/************* Mostrar Detalle Solicitudes Rayos X**************/	
			else if(accion==4)
					document.getElementById('DetalleSolicitudesRx').innerHTML = respuesta;

			else if(accion==7)
					document.getElementById('PlantillaB').innerHTML = respuesta;
			
			else if(accion==8)
					document.getElementById('PlantillaB').innerHTML = respuesta;

	  /************* Resultados Estudios Rayos X**************/	
			else if(accion==5)
					document.getElementById('ResultadosRx').innerHTML = respuesta;
			
			else if(accion==6)
					document.getElementById('ConsultasRx').innerHTML = respuesta;		
	   }   // Fin  If Status 200
	   
	  
	   else if(ObjetoAjax.status==404){ //  Reques no completado...Error Cuando No Existe la direcci�n donde se ejectura el Proceso
			if(accion==1)
				document.getElementById('Solicitudes').innerHTML = "La direccion no existe";
			else if(accion==2)	
				document.getElementById('DetalleResultados').innerHTML = "La direccion no existe";
         }
         else{ 
 			if(accion==1)
				document.getElementById('Solicitudes').innerHTML == "Error: ".ObjetoAjax.status;
			else if(accion==2)	
				document.getElementById('DetalleResultados').innerHTML == "Error: ".ObjetoAjax.status;
  		 } // Fin Errores Status 404
    
  } // Fin ReadyState 4
	 
        
}// Fin de la Funcion ProcesaEsp