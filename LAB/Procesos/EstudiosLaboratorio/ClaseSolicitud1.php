 <?php
 include_once("../../../Conexion/ConexionBD.php");
////////////////////////////////////////////////////////////////////////
///     CLASE CONEXION A LA BASE DE DATOS							////
////////////////////////////////////////////////////////////////////////
/*class ConexionBD{
 var $conect;
 //Método constructor
 function ConexionBD(){
 }
 
 function conectar() {
   if(!($con=mysql_connect("localhost","segmed","clinic0"))){
     echo"Error al conectar a la base de datos";	
     exit();
   }
   if (!mysql_select_db("siap",$con)) {
     echo "Error al seleccionar la base de datos";  
     exit();
   }
   $this->conect=$con;
   return true;	
 }// Fin función Conexion
 
} // FIN CLASE CONEXION */





////////////////////////////////////////////////////////////////////////
///     CLASE DE PACIENTES								            ////
////////////////////////////////////////////////////////////////////////
class Paciente{


	//Método constructor
	function Paciente(){
	}		
	/*****************************************************************************************/
	/*Función para  Recuperar Nombre del Paciente                                            */
	/*****************************************************************************************/
	function RecuperarNombre($conectar,$IdNumeroExp) { 
		if($conectar==true){
			$SQL = "SELECT CONCAT_WS(' ', PrimerApellido, SegundoApellido, PrimerNombre, SegundoNombre, TercerNombre) AS Nombre
					FROM mnt_datospaciente INNER JOIN mnt_expediente ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
					where IdNumeroExp='$IdNumeroExp'";
			$Resultado = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
			$Rows = mysql_fetch_array($Resultado);
			$Nombre=$Rows['Nombre'];
			return $Nombre;		
		}
	}
        
        
	/*****************************************************************************************/
	/*Función para  Recuperar IdSolicituEstudio de la solicitud del Paciente                 */
	/*****************************************************************************************/
	function RecuperarIdSolicituEstudio($conectar,$IdNumeroExp,$IdHistorialClinico) { 
		if($conectar==true){
			$SQL = "SELECT IdSolicitudEstudio FROM sec_solicitudestudios WHERE IdHistorialClinico = '$IdHistorialClinico' AND IdNumeroExp = '$IdNumeroExp'";
                        //echo $SQL;
                        //exit;
			$Resultado = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());              
                        $num = mysql_num_rows($Resultado);
                        if($num == 1){
                            $Datos= mysql_fetch_array($Resultado);
                            return $Datos['IdSolicitudEstudio'];
                            }
                        else{
                            $Datos= 0;
                            return $Datos;
                            }
		}// fin if conectar
	}// fin function RecuperarIdSolicituEstudio
        
        
	/*****************************************************************************************/
	/*Función para  Crear la cita o actualizar la fecha de la cita                           */
	/*****************************************************************************************/
	function IdCitaServApoyoInsertUpdate($conectar,$IdSolicitudEstudio,$iduser,$IdNumeroExp,$LugardeAtencion,$IdCitaServApoyo,$badera) { 
            if($conectar==true){
                if($badera == 1) // crear la cita
                {
                    $InsertCit = "INSERT INTO cit_citasxserviciodeapoyo
                    (Fecha, IdSolicitudEstudio, IdUsuarioReg, FechaHoraReg, IdNumeroExp, IdEstablecimiento)
                    VALUES (CURDATE(),$IdSolicitudEstudio,$iduser,NOW(), '$IdNumeroExp',$LugardeAtencion)";
                    //$queryIns = 
                    mysql_query($InsertCit) or die('La consulta fall&oacute;: ' . mysql_error());
                    //$IdCitaServApoyo=mysql_insert_id(); 
                }
                else // actualizar la cita
                {
                    $UpdateCit = "UPDATE cit_citasxserviciodeapoyo SET Fecha = CURDATE() WHERE IdCitaServApoyo = $IdCitaServApoyo AND IdSolicitudEstudio = $IdSolicitudEstudio";
                    mysql_query($UpdateCit) or die('La consulta fall&oacute;: ' . mysql_error());  
               }                
            }// fin if conectar
	}// fin function IdCitaServApoyoInsertUpdate        
	
        
	function FechaHoraNow($conectar){
		if($conectar==true){
			$SQL = "SELECT now() as Ahora";
			$Resultado = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
			$Rows = mysql_fetch_array($Resultado);
			$Nombre=$Rows['Ahora'];
			return $Nombre;		
		}
	}

	} // Fin de la Clase PACIENTE


class SolicitudLaboratorio{
	// Método Constructor de la clase SolicitudLaboratorio
	function SolicitudLaboratorio(){	
	}	
	
	
	/**********************************************************************************/
	/*Función para  Desplegar las Areas de Laboratorio                                */
	/**********************************************************************************/
	function AreasLaboratorio($conectar){
		$SQL="Select * from lab_areas
			  Where Habilitado= 'S' 
			  Order by NombreArea Asc";
		
		if($conectar==true){
			$Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
			
		}// FIn If conectar
		
	}
	
/**********************************************************************************/
/*Función que devuelve la cantidad de Origenes de Muestra                         */
/**********************************************************************************/
function ExistenciaOrigenes($IdMuestra){
	$Conexion=new ConexionBD();
	$conectar=$Conexion->conectar();
		
	$SQL="SELECT count(*) as Total
		  FROM mnt_origenmuestra
		  WHERE IdTipoMuestra='$IdMuestra'";
			  
	if($conectar==true){
		$Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
		$Respuesta= mysql_fetch_array($Ejecutar);
		if($Respuesta['Total']>0)
			return 1;
		else 
			return 0;	
		
	}				
		
}
	
	
/**********************************************************************************/
/*Función para  Desplegar las Muestrar de cada Examen                             */
/**********************************************************************************/	
	
function MostrarMuestra($IdExamen){
		$Conexion=new ConexionBD();
		$conectar=$Conexion->conectar();
		
		$SQL="SELECT lab_tipomuestra.IdTipoMuestra as IdTipo,TipoMuestra as Muestra
			  FROM lab_tipomuestraporexamen INNER JOIN  lab_tipomuestra ON
			  	   lab_tipomuestraporexamen.IdTipoMuestra=lab_tipomuestra.IdTipoMuestra
			  WHERE IdExamen='$IdExamen'
			  ORDER BY Muestra ASC";

		$SQL2="SELECT count(lab_tipomuestra.IdTipoMuestra) as TotalMuestras
			  FROM lab_tipomuestraporexamen INNER JOIN  lab_tipomuestra ON
			  	   lab_tipomuestraporexamen.IdTipoMuestra=lab_tipomuestra.IdTipoMuestra
			  WHERE IdExamen='$IdExamen'";


		if($conectar==true){
			$Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
			$Respuesta= mysql_fetch_array($Ejecutar);	

			$TotalMuestra=mysql_query($SQL2) or die('La consulta fall&oacute;: ' . mysql_error());
			$Muestras= mysql_fetch_array($TotalMuestra);	

		if($Muestras[0]>1){

			if(mysql_num_rows($Ejecutar)>0){										  						  
					$Origenes=$this->ExistenciaOrigenes($Respuesta['IdTipo']);
					if($Origenes>0){
						echo "<strong><font color='red'>Tipo de Muestra:&nbsp;&nbsp;&nbsp;&nbsp;</font></strong> 
						  <select name= 'M".$IdExamen."' Id= 'M".$IdExamen."' style='width:140px' class='Muestra' 
								  onchange='MostrarOrigen(this.value,\"".$IdExamen."\");'>";	
						echo '<option value="0"> Seleccione Muestra</option>';
					}
					else{
						echo "<input type='hidden' value='0' name='Origen".$IdExamen."' id='Origen".$IdExamen."'>";
						echo "<strong><font color='red'>Tipo de Muestra:&nbsp;&nbsp;&nbsp;&nbsp;</font></strong> 
									  <select name= 'M".$IdExamen."' Id= 'M".$IdExamen."' style='width:140px' class='Muestra' 
											  onchange='MostrarOrigen(this.value,\"".$IdExamen."\");'>";	
					
					}	
					
					echo '<option value= "'.$Respuesta["IdTipo"].'">'
							.htmlentities($Respuesta["Muestra"]).'</option>';	
						
										
					while($Respuesta= mysql_fetch_array($Ejecutar)) {
					   echo '<option value= "'.$Respuesta["IdTipo"].'">'
							.htmlentities($Respuesta["Muestra"]).'</option>';
					} 
			
					echo "</select>";	

			} // Fin If Numero de Filas
			
		}else{
		
			echo "<input type='hidden' value='".$Respuesta["IdTipo"]."' name='M".$IdExamen."' id='M".$IdExamen."'>
	 	 			 	  <input type='hidden' value='0' name='Origen".$IdExamen."' id='Origen".$IdExamen."'>";
			} //Fin else Numero de Filas									
			
		}// FIn If conectar
		
	}// Fin función Mostrar Muestra
	
	
	
/**********************************************************************************/
/*Función para  Desplegar el Origen de la Muestra de un Examen                    */
/**********************************************************************************/	
	
function MostrarOrigenMuestra($IdMuestra,$IdExamen){
		$Conexion=new ConexionBD();
		$conectar=$Conexion->conectar();
		
		$SQL="SELECT IdOrigenMuestra, OrigenMuestra
			  FROM mnt_origenmuestra
			  WHERE IdTipoMuestra='$IdMuestra'
			  ORDER BY OrigenMuestra ASC";
			  
		if($conectar==true){
			$Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
			if(mysql_num_rows($Ejecutar)>0){
				echo "<strong><font color='darkblue'>Origen de Muestra:</font></strong> 
							  <select class='Origen' name='Origen".$IdExamen."'	Id='Origen".$IdExamen."' style='width:140px'>";

				while($Respuesta= mysql_fetch_array($Ejecutar)) {
			       echo '<option value= "'.$Respuesta["IdOrigenMuestra"].'">'
				   		.htmlentities($Respuesta["OrigenMuestra"]).'</option>';
			  	} 
		
				echo '</select>';				
			}	// end if Numero de filas
			else
				echo "<input name='Origen".$IdExamen."'	Id='Origen".$IdExamen."' value='0' type='hidden'>";
			
		}// FIn If conectar

		
	}// Fin función Mostrar Muestra
	
	
/**********************************************************************************/
/*Función para  Guardar Datos de la Solicitud					                  */
/**********************************************************************************/	
	
function GuardarDatos($IdHistorialClinico,$IdNumeroExp,$FechaSolicitud,$IdUsuarioReg,$IdExamen,$Indicacion,$IdTipoMuestra,$IdOrigen,$IdEstablecimiento,$lugar){
		$Conexion=new ConexionBD();
		$conectar=$Conexion->conectar();
		//echo "funcion GuardarDatos IdHistorialClinico ".$IdHistorialClinico." IdNumeroExp ";//.$IdNumeroExp ." FechaSolicitud ". $FechaSolicitud." IdUsuarioReg ".$IdUsuarioReg." FechaHoraReg ".$FechaHoraReg." IdExamen ".$IdExamen." Indicacion ".$Indicacion." IdTipoMuestra ".$IdTipoMuestra." IdOrigen ".$IdOrigen." IdEstablecimiento ".$IdEstablecimiento;
		if($conectar==true){
			$link = mysqli_connect('localhost','labor', 'clinic0', 'siap'); 
			
			/*COMPROBACION DE EXAMENES YA DADOS*/
			$resp= "select sec_detallesolicitudestudios.IdExamen 
					from sec_solicitudestudios
					inner join sec_detallesolicitudestudios
					on sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
					where sec_solicitudestudios.IdNumeroExp='$IdNumeroExp'
					and sec_solicitudestudios.FechaSolicitud='$FechaSolicitud'
					and IdHistorialClinico = $IdHistorialClinico
					and sec_detallesolicitudestudios.IdExamen='$IdExamen'";
                        //echo $resp;
                        $resp= mysql_query($resp);
			/***************************************/
			if(!$row=mysql_fetch_array($resp)){
                            //$call = "datos: ".$IdHistorialClinico.",".$IdNumeroExp.",".$FechaSolicitud.",".$IdUsuarioReg.",".$IdExamen.",".$Indicacion.",".$IdTipoMuestra.",".$IdOrigen.",".$IdEstablecimiento.",".$lugar.",".@erro; 
                            //echo $call;
                            //exit;
                            mysqli_query($link,"call SolicitudEstudiosExternos($IdHistorialClinico,'$IdNumeroExp','$FechaSolicitud',$IdUsuarioReg,'$IdExamen','$Indicacion',$IdTipoMuestra,$IdOrigen,$IdEstablecimiento,$lugar,@erro)");
                            
			}//fin de comprobacion de datos
		}// FIn If conectar

		
}// Fin función Guardar Datos


/**********************************************************************************/
/*Función para  Recuperar el Nombre de la Muestra del Examen	                  */
/**********************************************************************************/	
	
function RecuperarMuestra($IdMuestra){
	$SQL="SELECT TipoMuestra
		  FROM lab_tipomuestra
		  WHERE IdTipoMuestra=$IdMuestra";
			  
	$Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
	$Respuesta= mysql_fetch_array($Ejecutar);
	return $Respuesta["TipoMuestra"];
		
}// Fin función Recuperar Muestra


/**********************************************************************************/
/* Funcion para recuperar el nombre del origen de la muestra	                  */
/**********************************************************************************/	
	
function RecuperarOrigen($IdOrigenMuestra){
	$SQL="SELECT OrigenMuestra
		  FROM mnt_origenmuestra
		  WHERE IdOrigenMuestra=$IdOrigenMuestra";
			  
	$Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
	$Respuesta= mysql_fetch_array($Ejecutar);
	return $Respuesta["OrigenMuestra"];
		
}// Fin función Recuperar Muestra

	
/**********************************************************************************/
/*Función para  Mostrar Detalle de los examenes solicitados		                  */
/**********************************************************************************/			
function DetalleEstudiosLaboratorio($conectar,$IdHistorialClinico,$IdEstablecimiento){
		$Conexion=new ConexionBD();
		$conectar=$Conexion->conectar();
	$Bandera='S';
	$SQL="SELECT IdDetalleSolicitud, sec_detallesolicitudestudios.IdExamen AS IdExamen, 
                            Indicacion, IdTipoMuestra, IdOrigenMuestra, 
                            sec_solicitudestudios.IdHistorialClinico AS  IdHistorialClinico, IdServicio, NombreExamen, Urgente, sec_detallesolicitudestudios.IdSolicitudEstudio 
                    FROM sec_solicitudestudios 
                    INNER JOIN sec_detallesolicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio = sec_solicitudestudios.IdSolicitudEstudio 
                    INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico = sec_historial_clinico.IdHistorialClinico 
                    INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen = lab_examenes.IdExamen                           
                    INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
                WHERE sec_historial_clinico.IdHistorialClinico = $IdHistorialClinico
                    AND sec_solicitudestudios.IdServicio = 'DCOLAB'
                    AND sec_solicitudestudios.IdEstablecimiento = $IdEstablecimiento
                    ORDER BY IdArea asc, NombreExamen asc";
                    //echo $SQL;
		echo " <form  method='post' name='Editar' id='Editar'>
				<table class='General2'><tr><td colspan='8' class='TdTitulo2' >EXAMENES SOLICITADOS A LABORATORIO</td></tr>
								<tr class='TdEncabezado'><td>C&oacute;digo</td>
									<td>Nombre Examen</td>
									<td>Tipo Muestra</td>
									<td>Origen Muestra</td>
									<td>Indicaci&oacute;n</td>
                                                                        <td>Borrar</td>
                                                                        <td>Urgente</td>
								</tr>";
		if($conectar==true)	{
			$Ejecutar=mysql_query($SQL) or die ("Warning...: La consulta Falló ...!". mysql_error());
			$i=0;
                        $t=0;
			while($Respuesta= mysql_fetch_array($Ejecutar)) {
			      echo "<tr class='TdResultados'><td>".$Respuesta["IdExamen"]."</td>";				 	  				  
				  echo "<td>".htmlentities($Respuesta["NombreExamen"]). "</td>";
				  echo "<td><font color='red'><b>".htmlentities($this->RecuperarMuestra($Respuesta["IdTipoMuestra"])). 
				  			"</b></font></td>";
  				  echo "<td><font color='darkblue'><b>".htmlentities($this->RecuperarOrigen($Respuesta["IdOrigenMuestra"])).
				  		   "</b></font></td>";
  				  echo "<td><input type='text' id='Indicacion".$Respuesta['IdExamen']."' name='Indicacion".$Respuesta['IdExamen']."'
				  			 value='".htmlentities ($Respuesta["Indicacion"]). "'>";                                 
                                  echo "<input type='hidden' name='IdDetalle".$Respuesta['IdExamen']."' Id='IdDetalle".$Respuesta['IdExamen']."'
				  		 value='".$Respuesta['IdDetalleSolicitud']."'></td>";		
                        
                                  echo "<td><input type='checkbox' name='ExamenesLab' value='".$Respuesta['IdExamen']."'/></td>";	
                                  
            //$Bandera='S' SI YA HAY UNA SOLICITUD URGENTE          
			$Respuesta3=  $this->RecuperarData($IdHistorialClinico, $IdEstablecimiento,$Bandera);
			
			if($Respuesta3 !=NULL || $Respuesta3 !=''){
				$SQL4=	"SELECT sec_detallesolicitudestudios.IdExamen,IdDetalleSolicitud,Urgente FROM sec_detallesolicitudestudios
                                        INNER JOIN lab_examenesxestablecimiento ON sec_detallesolicitudestudios.IdExamen=lab_examenesxestablecimiento.IdExamen
					WHERE IdSolicitudEstudio =$Respuesta3";
				$Ejecutar4=mysql_query($SQL4) or die ("Warning...: La consulta Fallo ...!". mysql_error());
			                       
                        
                        while($Respuesta4= mysql_fetch_array($Ejecutar4)) {
                        				
                                if($Respuesta4["Urgente"]==1 && $Respuesta['IdDetalleSolicitud']==$Respuesta4['IdDetalleSolicitud']){	                                       
                                    echo "<td><input type='checkbox' id='Detalle".$t."' checked='checked' value='".$Respuesta4['IdDetalleSolicitud']."'/></td>";
                                 $t++;
                                    }
                        }
                        if($Respuesta["Urgente"]==1 && $Respuesta3 != $Respuesta['IdSolicitudEstudio']){	                                       
                                	echo "<td><input type='checkbox' id='Detalle".$t."' value='".$Respuesta['IdDetalleSolicitud']."'/></td>";
                                 $t++;
                              }     
			}                      
                                               
                        if($Respuesta3 == NULL OR $Respuesta3 ==''){				 
                                 if($Respuesta["Urgente"]==1){	                                     
                                       echo "<td><input type='checkbox' id='Detalle".$t."' value='".$Respuesta['IdDetalleSolicitud']."'/></td>";
                                  $t++;     
                                 }                            	
			}
				  echo "</tr>";
				  $i++;	 
				  
			} // Fin While para sacar datos
                        
			echo "<tr><td colspan='6'><br><b>Total de Examenes Solicitados: $i</b> </td></tr></table><br>";

			/***********************************************************************************/
			$SQL2="select Impresiones from sec_solicitudestudios where IdHistorialClinico=".$IdHistorialClinico." and IdServicio='DCOLAB'";
			$Ejecutar2=mysql_query($SQL2) or die ("Warning...: La consulta Fallo ...!". mysql_error());
			$Respuesta2=mysql_fetch_array($Ejecutar2);
			
			
				if($Respuesta2["Impresiones"]==1){
				$check="<input id='Imprimir' type='checkbox'onclick='ImprimirResultados(".$IdHistorialClinico.");' checked='checked'>";
				}else{
				$check="<input id='Imprimir' type='checkbox'onclick='ImprimirResultados(".$IdHistorialClinico.");'>";
				}
			
			echo "<tr><td colspan='6' align='right'><br><h3><b>".$check."Resultado de Examenes Impresos [Pre-Operatorios]</b></h3> </td></tr></table><br>";
			
			/**************************************************************************************/
			$estadocita="select Impresiones from sec_solicitudestudios where IdHistorialClinico=".$IdHistorialClinico." and IdServicio='DCOLAB'";
			$resultado=mysql_query($estadocita) or die ("Warning...: La consulta Fallo ...!". mysql_error());
			

			echo "<table class='General2'>
				 	<tr><td align='right'>
					<input type='button'  value='Guardar Cambios' class='boton2'  onclick='GuardarCambios();'> 
					<input type='button' name='Agregar' value='Agregar Examen' class='boton2' onclick='AgregarExamen();'> 
					<input type='button' name='Terminar' value='Terminar Solitud' class='boton2' onclick='Urgente();'> 
					</td></tr></table>
					<input type='hidden' name='total' id='total' value='".$i."'>
                                        <input type='hidden' id='totalurgente' value='".$t."'>    
					<input type='hidden' id='IdHistorialClinico' name= 'IdHistorialClinico' value='".$IdHistorialClinico."'>
					</form>";
		
		}	// Fin conectar
				

} // FIn funcion mostrar detalle de Laboratorio
	

/**********************************************************************************/
/* Funcion para Borrar un Examen de la solicitud				                  */
/**********************************************************************************/	
function BorrarExamen($IdDetalle){
		$Conexion=new ConexionBD();
		$conectar=$Conexion->conectar();
	$SQL="DELETE from sec_detallesolicitudestudios
		  WHERE IdDetalleSolicitud=$IdDetalle";	  
	$Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
}// Fin función Recuperar Muestra

function ActualizarIndicacion($IdDetalle,$Indicacion){
		$Conexion=new ConexionBD();
		$conectar=$Conexion->conectar();
		$SQL="UPDATE sec_detallesolicitudestudios
			  SET Indicacion='$Indicacion'
			  WHERE IdDetalleSolicitud=$IdDetalle";	  
		$Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());

}



/******************************************************/
function Impresiones($Bandera,$IdHistorialClinico){
		$Conexion=new ConexionBD();
		$conectar=$Conexion->conectar();

	$sql="update sec_solicitudestudios set Impresiones='".$Bandera."' where IdServicio='DCOLAB' and IdHistorialClinico='".$IdHistorialClinico."'";
		
			$Ejecutar = mysql_query($sql) or die('La consulta fall&oacute;: ' . mysql_error());
}
/************************************************************/

 function FechaServer(){
    $con = new ConexionBD;
    if ($con->conectar()==true){
        $SQL ="SELECT now()";
        $db = mysql_query($SQL) or die('La consulta Fall&oacute;:' . mysql_error());
        $vareturn = mysql_fetch_array($db);
    }
    return $vareturn[0];
 }

 function FechaActual(){
	$con = new ConexionBD;
	if ($con->conectar()==true){
		$SQL ="select DATE_FORMAT(current_date(),'%Y/%m/%d') as FechaServidor";
		$db = mysql_query($SQL) or die('La consulta Fall&oacute;:' . mysql_error());
		$vareturn = mysql_fetch_array($db);
	}
	return $vareturn[0];
 }
 
function CrearNuevaSolicitudUrgente($IdHistorialClinico,$IdEstablecimiento,$IdDetalle){
    $Conexion=new ConexionBD();
    $conectar=$Conexion->conectar();
    
    $FechaActual= $this->FechaActual();
    $FechaServer=  $this->FechaServer();
    $Detalles=explode(',',$IdDetalle);
    $tamano=sizeof($Detalles);
    $Bandera='S';
        
    $sql=   "SELECT IdNumeroExp,IdSolicitudEstudio,IdUsuarioReg 
            FROM sec_solicitudestudios WHERE IdHistorialClinico=$IdHistorialClinico 
            AND IdEstablecimiento=$IdEstablecimiento";
    $Ejecutar=mysql_query($sql) or die ("Warning...: La consulta Falló ...!". mysql_error());
    

    while($Answer= mysql_fetch_array($Ejecutar)){
        $NumeroExpediente=$Answer["IdNumeroExp"];
        $IdSolicitudEstudiosNormal=$Answer["IdSolicitudEstudio"];
        $IdUsuarioReg=$Answer["IdUsuarioReg"];
    }
    
    //Se crea la Nueva Solicitud de Estudios para los examenes URGENTES
    
    $sqlInsert= "INSERT INTO sec_solicitudestudios (IdNumeroExp,IdHistorialClinico,Estado,FechaSolicitud,IdUsuarioReg,FechaHoraReg,IdServicio,IdEstablecimiento,IdTipoSolicitud)
                VALUES ('$NumeroExpediente','$IdHistorialClinico','D','$FechaActual','$IdUsuarioReg','$FechaServer','DCOLAB','$IdEstablecimiento','S')";
    $queryIns = mysql_query($sqlInsert) or die('La consulta fall&oacute;:' . mysql_error());
    $IdSolicitudEstudiosUrgente=mysql_insert_id();
  
    for($e=0;$tamano>$e;$e++){
        $SQL=   "UPDATE sec_detallesolicitudestudios SET IdSolicitudEstudio=$IdSolicitudEstudiosUrgente 
                WHERE IdDetalleSolicitud=$Detalles[$e]";	  
       $Execute = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
    }

    $IdSolicitudEstudiosUrgentes=$this->RecuperarData($IdHistorialClinico, $IdEstablecimiento,$Bandera);  
        
    //Insertar la Cita de Solicitud Urgente
    $sqlInsertCita= "INSERT INTO cit_citasxserviciodeapoyo (Fecha,IdSolicitudEstudio,IdUsuarioReg,FechaHoraReg,IdEstablecimiento)
                VALUES ('$FechaActual','$IdSolicitudEstudiosUrgentes','$IdUsuarioReg','$FechaServer','$IdEstablecimiento')";
    $query = mysql_query($sqlInsertCita) or die('La consulta fall&oacute;:' . mysql_error());
     
}// Fin función CrearNuevaSolicitudUrgente

function SearchNuevaSolicitudUrgente($IdHistorialClinico,$IdEstablecimiento,$IdDetalleUrgentes){
    $Conexion=new ConexionBD();
    $conectar=$Conexion->conectar();
    
    $Detalles=  explode(",",$IdDetalleUrgentes);    
    $size=sizeof($Detalles);    
    $arreglo=array();
    $arregloultimo=array();
    $x=0;
    $y=0;
    
    $IdSolicitudEstudiosUrgente=  $this->RecuperarData($IdHistorialClinico, $IdEstablecimiento,'S');
    $IdSolicitudEstudios=  $this->RecuperarData($IdHistorialClinico, $IdEstablecimiento,'R');                              
  
    $SQL4=  "SELECT IdDetalleSolicitud  FROM sec_detallesolicitudestudios 
            WHERE IdSolicitudEstudio =$IdSolicitudEstudiosUrgente AND IdEstablecimiento=$IdEstablecimiento";
    $Ejecutar4=mysql_query($SQL4) or die ("Warning...: La consulta Fallo ...!". mysql_error());
   
    while($DetallesActuales=  mysql_fetch_array($Ejecutar4)){
        $arreglo[$x]= $DetallesActuales[0];
        $x++;
    }

    //Devolver el IdDetalle deschequeado
    
   foreach ($arreglo as $value2) {
    $encontrado=false;
    foreach ($Detalles as $value1) {
        if ($value1 == $value2){
            $encontrado=true;
            $break;
        }
    }
    if ($encontrado == false){
        $arregloultimo[$y]=$value2;   
        $y++;
    }
}
   //print_r($arregloultimo);
   $tamano=sizeof($arregloultimo);
    for($e=0;$tamano>$e;$e++){
       $SQL=   "UPDATE sec_detallesolicitudestudios SET IdSolicitudEstudio=$IdSolicitudEstudios 
                    WHERE IdDetalleSolicitud=$arregloultimo[$e] AND IdEstablecimiento=$IdEstablecimiento AND IdSolicitudEstudio=$IdSolicitudEstudiosUrgente";	  
       $Execute = mysql_query($SQL) or die('La consulta fall&oacute23;: ' . mysql_error());    
   }
   
   //Agregar IdDetalle's nuevos que fueron chequeados
    for($r=0;$size>$r;$r++){
        $SQLS=   "UPDATE sec_detallesolicitudestudios SET IdSolicitudEstudio=$IdSolicitudEstudiosUrgente 
                WHERE IdDetalleSolicitud=$Detalles[$r] AND IdEstablecimiento=$IdEstablecimiento AND IdSolicitudEstudio=$IdSolicitudEstudios";	  
       $Executes = mysql_query($SQLS) or die('La consulta fall&oacute24;: ' . mysql_error());
    }
   
        
}// Fin función SearchNuevaSolicitudUrgente

function BorrarSolicitudUrgente($IdHistorialClinico,$IdEstablecimiento){
    $Conexion=new ConexionBD();
    $conectar=$Conexion->conectar();
    
    $IdSolicitudEstudiosUrgente=  $this->RecuperarData($IdHistorialClinico, $IdEstablecimiento,'S');
    $IdSolicitudEstudios=  $this->RecuperarData($IdHistorialClinico, $IdEstablecimiento,'R');
    
    $SQL=   "UPDATE sec_detallesolicitudestudios SET IdSolicitudEstudio=$IdSolicitudEstudios 
             WHERE IdSolicitudEstudio=$IdSolicitudEstudiosUrgente AND IdEstablecimiento=$IdEstablecimiento";	  
    $Execute = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
    
    $sql=   "DELETE from cit_citasxserviciodeapoyo WHERE IdSolicitudEstudio=$IdSolicitudEstudiosUrgente";	  
    $Ejecutar = mysql_query($sql) or die('La consulta fall&oacute;: ' . mysql_error());
    
    $sqly=  "DELETE from sec_solicitudestudios 
            WHERE IdSolicitudEstudio=$IdSolicitudEstudiosUrgente AND IdHistorialClinico=$IdHistorialClinico AND IdEstablecimiento=$IdEstablecimiento";	  
    $Ejecutar = mysql_query($sqly) or die('La consulta fall&oacute;: ' . mysql_error());
    
}// Fin función BorrarSolicitudUrgente

function RecuperarData($IdHistorialClinico,$IdEstablecimiento,$Bandera){
    $Conexion=new ConexionBD();
    $conectar=$Conexion->conectar();   
    
     //select el nuevo IdSolicitudUrgente
    $sqlbusqueda=   "SELECT IdSolicitudEstudio FROM sec_solicitudestudios 
                    WHERE IdHistorialClinico=$IdHistorialClinico AND IdEstablecimiento=$IdEstablecimiento AND IdTipoSolicitud='$Bandera'";
    $querys = mysql_query($sqlbusqueda) or die('La consulta fall&oacute;:' . mysql_error());
    $row=mysql_fetch_array($querys);
    return $row[0];
    
}
	
} // Fin de la Clase SolicitudLaboratorio


class Establecimiento{
	// Método Constructor de la clase SolicitudLaboratorio
	function Establecimiento(){	
	}	

	function IdEstablecimiento($IdUsuarioReg){
		$Conexion=new ConexionBD();
		$conectar=$Conexion->conectar();
	$sql="SELECT IdEstablecimiento FROM mnt_usuarios WHERE IdUser=$IdUsuarioReg";
	$ejecutar=mysql_query($sql) or die('La consulta fall&oacute ' . mysql_error());
	$campo=mysql_fetch_array($ejecutar);
	return $campo[0];
	
	}

} //Fin clase Establecimiento



class CrearHistorialClinico{
    // Método Constructor de la clase SolicitudLaboratorio
    function Historia(){	
    }	

    function HistorialClinico($IdNumeroExp,$lugar,$IdSubServicio,$IdEmpleado,$FechaConsulta,$iduser,$ippc){
    //$ippc=$_SERVER["REMOTE_ADDR"];
    $sqlInsertCita= "
    INSERT INTO sec_historial_clinico
        (IdNumeroExp, FechaConsulta, IdEmpleado, IdSubServicio, 
        IdUsuarioReg, FechaHoraReg, Piloto, IpAddress, IdEstablecimiento)
    VALUES 
        ('$IdNumeroExp', '$FechaConsulta', '$IdEmpleado', '$IdSubServicio', 
        '$iduser', NOW(), 'V', '$ippc', '$lugar')";
    //echo $sqlInsertCita;
    $query = mysql_query($sqlInsertCita) or die('La consulta fall&oacute;:' . mysql_error());  
	return mysql_insert_id();          
/*

    $sql="SELECT IdEstablecimiento FROM mnt_usuarios WHERE IdUser=$IdUsuarioReg";
    $ejecutar=mysql_query($sql) or die('La consulta fall&oacute ' . mysql_error());
    $campo=mysql_fetch_array($ejecutar);
    return $campo[0];*/

    }

} //Fin clase HistorialClinico
?>
