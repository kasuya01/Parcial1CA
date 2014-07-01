<?php
	include_once("./Clases.php"); //Agregamos el Archivo con las clases y funciones a utilizar
	//$IdMedico='MED0053';	
	$Proceso=$_REQUEST['Proceso'];	
	
	//$Nombre="";
	//$Paciente="";
	// Creamos un objeto de clase Conexion y Laboratorio
	$Conexion= new ConexionBD();   	
	$Laboratorio= new Laboratorio();
	$Paciente= new Paciente;   
	$Imagenologia= new Imagenologia();  

	//Abrimos la Conexion
	$Conectar=$Conexion->Conectar();
if(isset($_GET['Flag'])){
?>
<script language="javascript" src="ajax.js"></script>
<link rel="stylesheet" type="text/css" href="../../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../../Themes/StormyWeather/Style.css">


<?php  
$IdNumeroExp=$_REQUEST['IdNumeroExp'];
$FechaSolicitud=$_REQUEST['FechaSolicitud'];
$FechaRecepcion=$_REQUEST['FechaRecepcion'];
//$IdSolicitud==$_REQUEST['IdSolicitudEstudio'];	
$IdSolicitudEstudio=$_REQUEST['IdSolicitudEstudio']; 
$IdEstab=$_REQUEST['IdEstab'];
$lugar=$_REQUEST['lugar'];
//$Establecimiento=$_REQUEST['Establecimiento'];  
$Nombre=$Paciente->RecuperarNombre($Conectar,$IdNumeroExp,$IdSolicitudEstudio);
$Rows = mysql_fetch_array($Nombre);
$Sexo=$Rows['Sexo'];
$fechanac=$Rows['FechaNacimiento'];
//echo $Rows['Sexo']."##".$Rows['FechaNacimiento'];
 $Cuentadias=$Paciente->CalculoDias($Conectar,$fechanac);
 $Cdias= mysql_fetch_array($Cuentadias);
 $dias=$Cdias[0];
	
 $ConRangos=$Paciente->ObtenerCodigoRango($Conectar,$dias);
 $row_rangos=  mysql_fetch_array($ConRangos);
 $idedad=$row_rangos[0];  
 //echo $dias."--".$Sexo."--". $idedad;
//echo $Sexo."##".$idedad."##".$IdSolicitudEstudio."##".$IdEstab."##".$lugar;
			//$Nombre=
			//$Medico=$Rows['Medico'];
 echo "<form><table width='100%' align='center'>	
			<tr>
			<td width='50%'><h2>No.Expediente: ".$IdNumeroExp."	
								<br>
								Paciente: ".$Rows['Nombre']."
								<br>
								Fecha Recepcion: ".$FechaRecepcion."
								</h2>
													
				</td>
				<td width='50%'><h2>	Establecimiento:".$Rows['Establecimiento']."
                                                        <br>
							Procedencia: ".$Rows['Procedencia']."
								<br>
								Origen: ".$Rows['Origen']."
								<br>
								M&eacute;dico: ".$Rows['Medico']."
								</h2> 								
				</td>
				
			</tr>	
			</table></form>";
}

	// Dependiendo del Proceso, Ejecutamos la funcion para cargar el Grid
	switch($Proceso){
		
		case 'ConsultasLaboratorio':			
			$IdNumeroExp=$_REQUEST['IdNumeroExp'];	
			$NoPagina=$_REQUEST['pag'];			
			$Laboratorio->SolicitudesLaboratorio($Conectar,$IdNumeroExp,$NoPagina);

		break;
		
		case 'DetalleSolicitud':			
			$IdSolicitudEstudio=$_REQUEST['IdSolicitudEstudio'];	
			$FechaSolicitud=$_REQUEST['FechaSolicitud'];	
  		    $Laboratorio->DetalleSolicitudLaboratorio($IdSolicitudEstudio,$FechaSolicitud,$Conectar);
		break;

		case 'DetalleResultado':
			//$IdSolicitudEstudio=$_REQUEST['IdSolicitudEstudio'];	
                    
			$IdArea=$_REQUEST['IdArea'];	
			$Laboratorio->ResultadosExamen($IdSolicitudEstudio,$IdArea,$Sexo,$idedad,$Conectar,$IdEstab,$lugar);
			break;

		case 'ResultadosPlantillaB':
			$IdDetalleSolicitud=$_POST['IdDetalleSolicitud'];
                        $Sexo=$_POST['Sexo'];	
			$idedad=$_POST['idedad']; 
			$Laboratorio->DetalleResultadoPlantillaB($IdDetalleSolicitud,$Conectar,$Sexo,$idedad);
			break;

		case 'ResultadosPlantillaC':
			$IdDetalleSolicitud=$_POST['IdDetalleSolicitud'];	
			$Resultado=$_POST['Resultado'];	
			$Laboratorio->DetalleResultadoPlantillaC($IdDetalleSolicitud,$Conectar,$Resultado);
			break;
			
		case 'ResultadosPlantillaD':
			$IdDetalleSolicitud=$_POST['IdDetalleSolicitud'];	
			$Resultado=$_POST['Resultado'];	
			$Laboratorio->DetalleResultadoPlantillaD($IdDetalleSolicitud,$Conectar,$Resultado);
			break;
		
		case 'ResultadosPlantillaE':
			$IdDetalleSolicitud=$_POST['IdDetalleSolicitud'];	
			$Resultado=$_POST['Resultado'];	
			$Laboratorio->DetalleResultadoPlantillaE($IdDetalleSolicitud,$Conectar,$Resultado);
			break;
		
	    case 'DetalleSolicitudRx':
			$IdSolicitudEstudio=$_POST['IdSolicitudEstudio'];	
			$FechaSolicitud=$_POST['FechaSolicitud'];	
			$Imagenologia->DetalleSolicitudRx($IdSolicitudEstudio,$Conectar,$FechaSolicitud);
			break;
			
	case 'ResultadosRx':
			$IdDetalleSolicitud=$_POST['IdDetalleSolicitud'];	
			$NombreExamen=$_POST['NombreExamen'];	
			$Imagenologia->ResultadosRx($IdDetalleSolicitud,$NombreExamen,$Conectar);
			break;
	}
	
	?>
    