<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsLab_TipoMuestrasPorExamen
{     
     //CONSTRUCTOR
	 function clsLab_TipoMuestrasPorExamen(){
}	

//FUNCION PARA INSERTAR
function insertar($idexamen,$idtipomuestra,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "INSERT INTO lab_tipomuestraporexamen(idexamen,idtipomuestra,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) VALUES('$idexamen',$idtipomuestra,$usuario,NOW(),$usuario,NOW())";
     $result = mysql_query($query);
	// echo $query;
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 //FUNCION QUE VERIFICA SI LA MUESTRA YA FUE ASOCIADA AL EXAMEN
  function Verificar_Muestra($idexamen,$idtipomuestra){
	$con = new ConexionBD;
	if($con->conectar()==true)
	{ $query="SELECT COUNT(*) as cantidad FROM lab_tipomuestraporexamen WHERE IdExamen='$idexamen' AND IdTipoMuestra=$idtipomuestra";
		$result = mysql_query($query);
		if (!$result)
			return false;
		else
			return $result;
	}
  }
  
function Eliminar($idexamen,$idtipomuestra)
{
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_tipomuestraporexamen WHERE IdExamen='$idexamen' AND IdTipoMuestra=$idtipomuestra";
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	}
}
  
 //FUNCION PARA BUSCAR ASOCIADOS
 function consultarasociados($idexamen)
 {
 $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
      $query = "SELECT IdTipoMuestraPorExamen,lab_tipomuestraporexamen.IdExamen,TipoMuestra,lab_tipomuestraporexamen.IdTipoMuestra
		FROM lab_tipomuestraporexamen 
		INNER JOIN lab_examenes  ON lab_tipomuestraporexamen.IdExamen=lab_examenes.IdExamen
		INNER JOIN lab_tipomuestra ON lab_tipomuestra.IdTipoMuestra=lab_tipomuestraporexamen.IdTipoMuestra
		WHERE lab_tipomuestraporexamen.idexamen='$idexamen'" ;
	 $result = mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
 }
 
//RECUPERAR EXAMENES POR AREA
 function ExamenesPorArea($idarea,$lugar)
 {
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
    if($con->conectar()==true){
     $query = "SELECT lab_examenes.IdExamen,NombreExamen 
			   FROM lab_examenes 
			   INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
			   WHERE IdArea='$idarea'
				AND lab_examenesxestablecimiento.Condicion='H' AND IdEstablecimiento=$lugar ORDER BY NombreExamen";
     $result = mysql_query($query);
     if (!$result)
	   return false;
     else
	   return $result;
   }
 }
	 
//CONSULTA EXAMEN POR EL CODIGO
 function consultarid($idexamen,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     //$query = "SELECT * FROM lab_examenes WHERE idexamen='$idexamen'";
	 $query = "SELECT lab_examenes.IdExamen,lab_examenes.IdEstandar,lab_examenes.IdArea,NombreExamen,Descripcion,NombreArea,
lab_examenes.IdPlantilla, Plantilla
FROM lab_examenes 
INNER JOIN lab_areas  ON lab_examenes.IdArea=lab_areas.IdArea
INNER JOIN lab_codigosestandar  ON lab_examenes.IdEstandar=lab_codigosestandar.IdEstandar
INNER JOIN lab_plantilla ON lab_plantilla.IdPlantilla=lab_examenes.IdPlantilla
INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
WHERE lab_examenesxestablecimiento.IdEstablecimiento=$lugar AND lab_examenes.IdExamen='$idexamen'";
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
  
}//CLASE
?>
