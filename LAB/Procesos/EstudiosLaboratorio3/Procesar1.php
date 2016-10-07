   <?php session_start();
   include_once("./ClaseSolicitud.php"); //Agregamos el Archivo con las clases y funciones a utilizar
   $Proceso=$_REQUEST["Proceso"]; 
   $SolicitudLab= new SolicitudLaboratorio();
   $IdEstablecimiento=$_SESSION["IdEstablecimiento"];// establecimiento que solicita los examenes
   $lugar=$_SESSION["lugar"]; // establecimiento que atiende la muestra
      //echo $lugar;  
   switch($Proceso){

   case "Impresiones":
		$Bandera=$_POST["Bandera"];
		$IdHistorialClinico=$_POST["IdHistorialClinico"];
		$SolicitudLab->Impresiones($Bandera,$IdHistorialClinico);
   break;

   case "Combos":
	    $IdExamen=$_POST["IdExamen"];
		$SolicitudLab->MostrarMuestra($IdExamen);
   break;
   
   case "OrigenMuestra":
	     $IdMuestra=$_POST["IdMuestra"];
 	     $IdExamen=$_POST["IdExamen"];
	 	 $SolicitudLab->MostrarOrigenMuestra($IdMuestra,$IdExamen);
   break;
 
   case "MostrarDetalle":
		 $IdHistorialClinico=$_POST["IdHistorialClinico"];
	 	 $Conectar=""; 
      	 $SolicitudLab->DetalleEstudiosLaboratorio($Conectar,$IdHistorialClinico,$IdEstablecimiento);
	break;
	 
   case "GuardarDatos":
	// Parametros Generales	echo 'establecimiento: '.$IdEstablecimiento;       
	$IdHistorialClinico=$_GET["IdHistorialClinico"];
        $IdNumeroExp=$_GET["IdNumeroExp"];
		
	$FechaSolicitud=$_GET["FechaSolicitud"];
 	$IdUsuarioReg=$_GET["IdUsuarioReg"];
 	//$IdCitaServApoyo=$_GET["IdCitaServApoyo"];
		 
	// Parametros del Detalle
 	$IdExamen=$_GET["IdExamen"];
  	$Indicacion=$_GET["Indicacion"];
  	$IdTipoMuestra=$_GET["IdTipoMuestra"];
	$IdOrigen=$_GET["IdOrigen"];

	$SolicitudLab->GuardarDatos($IdHistorialClinico,$IdNumeroExp,$FechaSolicitud,$IdUsuarioReg,$IdExamen,$Indicacion,$IdTipoMuestra,$IdOrigen,$IdEstablecimiento,$lugar);
	 	
   break;
	
   case "GuardarCambios":
        $IdDetalle=$_POST["IdDetalle"];		 
        $SolicitudLab->BorrarExamen($IdDetalle);
   break;

   case "ActualizarDatos":
	$IdDetalle=$_POST["IdDetalle"];		 
 	$Indicacion=$_POST["Indicacion"];				
	$SolicitudLab->ActualizarIndicacion($IdDetalle,$Indicacion);
   break;
     
   case 'VerificarSolicitudUrgente':  
        $IdDetalleUrgentes=$_GET["IdDetallesUrgentes"];
        $IdHistorialClinico=$_GET["IdHistorialClinico"]; 
        $TipoSolicitud='S';       // echo $IdHistorialClinico.'--'.$IdDetalleUrgentes;
        
        $recuperarData = $SolicitudLab->RecuperarData($IdHistorialClinico,$IdEstablecimiento,$TipoSolicitud);
       
        //No hay solicitud previa de urgentes
	if(($IdDetalleUrgentes !='' OR $IdDetalleUrgentes != null) AND ($recuperarData=='' OR $recuperarData == null)){
            $SolicitudLab->CrearNuevaSolicitudUrgente($IdHistorialClinico,$IdEstablecimiento,$IdDetalleUrgentes);  
	}                   
        
        //Se agregaron o borraron nuevos IdDetallesUrgentes
        if(($IdDetalleUrgentes !='' OR $IdDetalleUrgentes != null) AND ($recuperarData !='' OR $recuperarData != null)){
	    $SolicitudLab->SearchNuevaSolicitudUrgente($IdHistorialClinico,$IdEstablecimiento,$IdDetalleUrgentes);  
	}
        
        //Todos los urgentes fueron unchecked
        if(($IdDetalleUrgentes =='' OR $IdDetalleUrgentes == null) AND ($recuperarData !='' OR $recuperarData != null)){
	    $SolicitudLab->BorrarSolicitudUrgente($IdHistorialClinico,$IdEstablecimiento);  
	}
            
   break;

break;  
}  
  
?>