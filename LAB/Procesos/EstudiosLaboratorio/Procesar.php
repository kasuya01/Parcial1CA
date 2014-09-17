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
		$idsolicitudestudio=$_POST["idsolicitudestudio"];
		$SolicitudLab->Impresiones($Bandera,$IdHistorialClinico, $idsolicitudestudio);
   break;

   case "Combos":
	    $IdExamen=$_POST["IdExamen"];
            $Solicitud=$SolicitudLab->CantiMuestra($IdExamen);
            $cant= pg_num_rows($Solicitud);
            
           // echo 'cant:'.$cant;
	   // $Solicitud=$SolicitudLab->MostrarMuestra($IdExamen);
          //  $sol=  pg_fetch_array($Solicitud); 
            
            if ($cant>1){
               // if ($cant>0){
                    echo "<strong><font color='red'>Tipo de Muestra:&nbsp;&nbsp;&nbsp;&nbsp;  </font></strong>";
                    echo "<select name= 'M".$IdExamen."' Id= 'M".$IdExamen."' style='width:140px' class='Muestra' onchange='MostrarOrigen(this.value,\"".$IdExamen."\");'>";
                    echo '<option value="0"> Seleccione una Muestra</option>';
                    while($Respuesta= pg_fetch_array($Solicitud)) {
                        $idtipo=$Respuesta['idtipo'];
                        $muestra=$Respuesta['muestra'];
                        echo '<option value= "'.$idtipo.'">'.$muestra.'</option>';
                        
                        $Origenes=$SolicitudLab->ExistenciaOrigenes($idtipo);                   
			$cuantosor= pg_fetch_array($Origenes);
                        
                        if ($cuantosor['total']==0){
                            echo "<input type='hidden' value='0' name='Origen".$IdExamen."' id='Origen".$IdExamen."'>";
                        }//else origenes*/
                          
                                           
                    } //fin while pg_fetch_array

                    echo "</select>";

            }//fin cant>0?
            else{
                $Respuesta=  pg_fetch_array($Solicitud);
                echo "<input type='hidden' value='".$Respuesta["idtipo"]."' name='M".$IdExamen."' id='M".$IdExamen."'> <input type='hidden' value='0' name='Origen".$IdExamen."' id='Origen".$IdExamen."'>";
            }
                
   break;
   
   case "OrigenMuestra":
	     $IdMuestra=$_POST["IdMuestra"];
 	     $IdExamen=$_POST["IdExamen"];
	 	$origen= $SolicitudLab->MostrarOrigenMuestra($IdMuestra,$IdExamen);
                 $cant=  pg_num_rows($origen);
                 if ($cant>0){
                     echo "<strong><font color='darkblue'>Origen de Muestra:</font></strong> ";
                     echo "<select class='Origen' name='Origen".$IdExamen."'	Id='Origen".$IdExamen."' style='width:140px'>";
                     while ($Respuesta=  pg_fetch_array($origen)){
                         echo '<option value= "'.$Respuesta["id"].'">'.($Respuesta["origenmuestra"]).'</option>';
                     }//fin while
                     echo '</select>';
                 }//fin if cant
                 else
                     echo "<input name='Origen".$IdExamen."'	Id='Origen".$IdExamen."' value='0' type='hidden'>";
   break;
 
   case "MostrarDetalle":
		 $IdHistorialClinico=$_POST["IdHistorialClinico"];
	 	// $Conectar=""; 
      	 $result=$SolicitudLab->DetalleEstudiosLaboratorio($Conectar,$IdHistorialClinico,$IdEstablecimiento);
         
         
         
	break;
	 
   case "GuardarDatos":
	// Parametros Generales	echo 'establecimiento: '.$IdEstablecimiento;       
	$IdHistorialClinico=$_GET["IdHistorialClinico"];
        $IdNumeroExp=$_GET["IdNumeroExp"];
        $idexpediente=$_GET["idexpediente"];
		
	$FechaSolicitud=$_GET["FechaSolicitud"];
 	$IdUsuarioReg=$_GET["IdUsuarioReg"];
 	//$IdCitaServApoyo=$_GET["IdCitaServApoyo"];
		 
	// Parametros del Detalle
        
 	$IdExamen=$_GET["IdExamen"];
        //echo '<br>No IDEXAMEN: '.$IdExamen.'-:<br/>';
  	$Indicacion=$_GET["Indicacion"];
  	$IdTipoMuestra=$_GET["IdTipoMuestra"];
	$IdOrigen=$_GET["IdOrigen"];
        if ($IdOrigen==0){
            $IdOrigen='NULL';
        }
        

	$sol=$SolicitudLab->GuardarDatos($IdHistorialClinico,$IdNumeroExp,$idexpediente,$FechaSolicitud,$IdUsuarioReg,$IdExamen,$Indicacion,$IdTipoMuestra,$IdOrigen,$IdEstablecimiento,$lugar);
        return $sol;
        
	 	
   break;
	
   case "GuardarCambios":
        $IdDetalle=$_POST["IdDetalle"];		 
        $SolicitudLab->BorrarExamen($IdDetalle);
   break;

   case "ActualizarDatos":
	$IdDetalle=$_POST["IdDetalle"];		 
 	$Indicacion=$_POST["Indicacion"];				
 	$Detalle=$_POST["Detalle"];				
        if ($Detalle==1){
            $cambioEstado=$SolicitudLab->ActualizarSolicitud($IdDetalle, $Detalle);
            
        }
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