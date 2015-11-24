   <?php session_start();
   include_once("./ClaseSolicitud.php"); //Agregamos el Archivo con las clases y funciones a utilizar
   $Proceso=$_REQUEST["Proceso"]; 
   $SolicitudLab= new SolicitudLaboratorio();
   $hist= new CrearHistorialClinico();
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
   case "SolicitudUrgente":
		$Bandera=$_POST["Bandera"];
		$IdHistorialClinico=$_POST["IdHistorialClinico"];
		$idsolicitudestudio=$_POST["idsolicitudestudio"];
		$SolicitudLab->SolicitudUrgente($Bandera,$IdHistorialClinico, $idsolicitudestudio);
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
                        /*
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
     //   echo '<br>No IDEXAMEN: '.$IdExamen.'-:<br/>';
  	$Indicacion=$_GET["Indicacion"];
  	$IdTipoMuestra=$_GET["IdTipoMuestra"];
	$IdOrigen=$_GET["IdOrigen"];
         if ($IdTipoMuestra==0){
            $IdTipoMuestra='NULL';
        }
        if ($IdOrigen==0){
            $IdOrigen='NULL';
        }
        if ($IdEstablecimiento!=$lugar){
            $id_expediente_referido=$idexpediente;
            $idexpediente='NULL';
        }
        else{
            $id_expediente_referido='NULL';
        }
        

	$sol=$SolicitudLab->GuardarDatos($IdHistorialClinico,$IdNumeroExp,$idexpediente,$FechaSolicitud,$IdUsuarioReg,$IdExamen,$Indicacion,$IdTipoMuestra,$IdOrigen,$IdEstablecimiento,$lugar, $id_expediente_referido);
      // echo '<br\>Solicitud: -'.$sol.' -fin solicitud';
        return $sol;
        
	 	
   break;
	
   case "GuardarCambios":
        $IdDetalle=$_POST["IdDetalle"];		 
        $SolicitudLab->BorrarExamen($IdDetalle);
   break;

   case "ActualizarDatos":
	$IdDetalle=$_POST["IdDetalle"];	
      $Indicacion=(empty($_POST['Indicacion'])) ? 'NULL' : "'" . pg_escape_string(trim($_POST['Indicacion'])) . "'";
 	//$Indicacion=$_POST["Indicacion"];				
 	$ftomamx=$_POST["ftomamx"];				
 	//$Detalle=$_POST["Detalle"];				
//        if ($Detalle==1){
//            $cambioEstado=$SolicitudLab->ActualizarSolicitud($IdDetalle, $ftomamx);
//            
//        }
	$St=$SolicitudLab->ActualizarIndicacion($IdDetalle,$Indicacion, $ftomamx);
       // echo $St. '-'.$IdDetalle.'-'.$Indicacion.'-'.$ftomamx;
   break;
   
   case "EliminarSolicitud":
      // var_dump('Holaaa!! ');
        $idsolicitud=$_GET["idsolicitud"];		 
        $bs=$SolicitudLab->BorrarSolicitud($idsolicitud);
       // echo 'ids: '.$idsolicitud;
       // echo 'BS: '.$bs.' -idsol- '.$idsolicitud;
   break;
     
   case 'VerificarSolicitudUrgente':  
        $IdDetalleUrgentes=$_GET["IdDetallesUrgentes"];
        $IdHistorialClinico=$_GET["IdHistorialClinico"]; 
        $TipoSolicitud='S';  
        //echo 'TS: '.$TipoSolicitud;
        /*$idsolicitud=$_GET["idsolicitud"];
        $cuantos=$_GET["cuantos"];
        if ($cuantos==0){
            $bs=$SolicitudLab->BorrarSolicitud($idsolicitud);
        }*/
           break;
   case 'seleccionarPruebas':
      $idperfil = $_POST["idperfil"];
      $sql=$hist->buscarperfilprueba($idperfil);
      if ($sql != false){
         $jsonresponse['status']=true;
         $jsonresponse['num_rows']= pg_num_rows($sql);
         $jsonresponse['data']=  pg_fetch_all($sql);
      }
      else{
         $jsonresponse['status'] = false;
      }
      echo json_encode($jsonresponse);
      break;

}  
  
?>