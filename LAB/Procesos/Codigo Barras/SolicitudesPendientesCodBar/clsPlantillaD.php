<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsPlantillaD
{
 //constructor	
 function clsPlantillaD (){
 }	

function Nombre_Establecimiento($lugar){
   $con = new ConexionBD;
   if($con->conectar()==true){ 
       	$query="SELECT Nombre FROM mnt_establecimiento WHERE IdEstablecimiento=$lugar";
	 $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
   }

}

 /*Funcion para obtener el nombre del Resultado del tabulador*/ 
function ObtenerNombreCodigo($tab){
 	 $con = new ConexionBD;
	 if($con->conectar()==true) 
	 {	$query=" SELECT Resultado FROM lab_codigosresultados WHERE IdResultado=$tab ";	
	     $result = mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
	 }
}


 //FUNCION PARA CAMBIAR EL ESTADO DEL DETALLLE DE LA SOLICITUD
 function CambiarEstadoDetalle($iddetalle)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) //Estado RC--> Resultados Completo
  {	$query="UPDATE sec_detallesolicitudestudios SET estadodetalle='RC' WHERE IdDetalleSolicitud=$iddetalle"	;	
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 function CambiarEstadoSolicitud($idsolicitud){
  $con = new ConexionBD;
   if($con->conectar()==true){ 
	$query="SELECT  IdDetalleSolicitud,IdExamen FROM sec_detallesolicitudestudios 
			WHERE IdSolicitudEstudio=$idsolicitud and EstadoDetalle <>'RC' and EstadoDetalle <>'RM'";
	$detalle=mysql_num_rows(mysql_query($query));
	if(empty($detalle)){
		$query="UPDATE sec_solicitudestudios SET estado='C' WHERE IdSolicitudEstudio=$idsolicitud"	;
		$result=@mysql_query($query);		
		return true;	  
    }else
		return false;
   }
   }

 
//FUNCION PARA VALIDAR QUE NO EXISTA ESE ELEMENTO DE TINCION EN EL RESULTADO
function ValidacionElemento($idresultado,$idelemento)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
  {	$query="SELECT COUNT(IdResultado) FROM lab_detalleresultado WHERE IdResultado=$idresultado AND IdElemento=$idelemento";	
     $result = @mysql_query($query);
	
     if (!$result)
       {return false;}
     else
		{
			$row= mysql_fetch_array($result);
			return $row[0];				
		}	      
   }
 }

//FUNCION PARA ELIMINAR ELEMENTO DE TINCION DEL DETALLE DE RESULTADOS
function EliminarElementoDetalle($idresultado,$idelemento)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
  {	$query="DELETE FROM lab_detalleresultado WHERE  IdResultado=$idresultado AND IdElemento=$idelemento";	
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
// funcion para leer las cantidades de tincion
function LeerCantidadTincion()
{
$con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT IdCantidadesTincion,CantidadTincion FROM lab_cantidadestincion";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}


// 	funcion para leer los elementos de tincion
function LeerElementosTincion()
{
$con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT IdElementosTincion,ElementoTincion FROM lab_elementostincion";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}

////**********************************************************************************************************************************************////
//INSERTA RESULTADOS   ENCABEZADO
 function insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$responsable,$usuario,$tab,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_resultados
	         (IdSolicitudEstudio,IdDetalleSolicitud,IdExamen,IdRecepcionMuestra,     
              Responsable,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdCodigoResultado,IdEstablecimiento) 
			  VALUES($idsolicitud,$iddetalle,'$idexamen',$idrecepcion,'$responsable',
			  $usuario,NOW(),$usuario,NOW(),$tab,$lugar)";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       $idultimo= @mysql_insert_id();
	   return $idultimo;	   
   }
 }
// INSERTAR DETALLE DE LOS RESULTADOS POR ELEMENTOS Y SUBELEMENTOS
//ELEMENTOS
function insertar_elemento($ultimo,$idelemento,$idcantidad,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_detalleresultado(IdResultado,IdElemento,IdCantidad,IdEstablecimiento ) 
			  VALUES($ultimo,$idelemento,$idcantidad,$lugar)";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }

//MOSTRAR ELEMENTOS DE INCION AGREGADOS A UN RESULTADO
function MostrarElementosAgregados($idresultado)
{
$con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query ="SELECT ElementoTincion,CantidadTincion FROM lab_detalleresultado AS a
			 INNER JOIN lab_elementostincion AS b ON a.IdElemento=b.IdElementosTincion
			 INNER JOIN lab_cantidadestincion AS c ON c.IdCantidadesTincion=a.IdCantidad
			 WHERE idResultado=$idresultado" ;
   $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return $result;	   
   }
}
 
//FUNCION PARA LEER LOS EM,PLEADOS
function DatosEmpleado($idempleado)
{
$con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT NombreEmpleado FROM mnt_empleados WHERE idempleado='$idempleado'";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}
 
//FUNCION PARA MOSTRAR DATOS GENERALES
function MostrarDatosGenerales($idsolicitud)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT DISTINCT lab_recepcionmuestra.IdSolicitudEstudio, sec_historial_clinico.IdNumeroExp,
		CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
		(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
		IF(Sexo=1,'Masculino','Femenino') AS Sexo,
		TelefonoCasa,Direccion,NombreSubServicio AS Origen,NombreServicio AS Procedencia,
		NumeroMuestra,
		DATE_FORMAT(CURRENT_DATE,'%d/%m/%Y') AS Fecha,DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') AS FechaNacimiento
		FROM lab_recepcionmuestra 
		INNER JOIN sec_solicitudestudios  ON sec_solicitudestudios.IdSolicitudEstudio=lab_recepcionmuestra.IdSolicitudEstudio
		INNER JOIN sec_historial_clinico  ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
		INNER JOIN mnt_expediente  ON mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
		INNER JOIN mnt_datospaciente  ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente 
		INNER JOIN mnt_subservicio  ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
		INNER JOIN mnt_servicio  ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
		WHERE lab_recepcionmuestra.IdSolicitudEstudio=$idsolicitud";
     		$result = @mysql_query($query);
     		if (!$result)
       			return false;
     		else
       			return $result;
    }
}


//FUNCION PARA LEER LOS DATOS DEL AREA Y EL EXAMEN
function LeerDatos($idexamen)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT NombreArea,NombreExamen 
		FROM lab_examenes AS b
		INNER JOIN lab_areas AS c ON b.IdArea=c.IdArea
		WHERE b.IdExamen='$idexamen'";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}
 
  
////*********************************************************************************************************************************************//// 

//ACTUALIZAR RESULTADOS
 function actualizar($idelemento,$idarea,$nomelemento,$idestandar,$plantilla)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_elementos SET nombreExamen='$nomelemento', idestandar='$idestandar', idarea='$idarea',IdPlantilla='$plantilla' WHERE idelemento='$idelemento'";
     $result = @mysql_query($query);
	 if (!$result)
       return false;
     else
       return true;
	   
   }
 }

 //ELIMINAR RESULTADOS
 function eliminar($idelemento)
{
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_elementos WHERE idelemento='$idelemento'";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
 
 
  
}//CLASE
?>
