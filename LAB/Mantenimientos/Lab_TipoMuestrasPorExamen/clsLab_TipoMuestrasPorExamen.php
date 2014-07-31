<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsLab_TipoMuestrasPorExamen
{     
     //CONSTRUCTOR
	 function clsLab_TipoMuestrasPorExamen(){
}	
//Fn PG
//FUNCION PARA INSERTAR
function insertar($idexamen,$idtipomuestra,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
      $sql= "SELECT * 
FROM lab_tipomuestraporexamen 
WHERE idexamen=$idexamen 
AND idtipomuestra=$idtipomuestra";
      $resulta = pg_query($sql);
      $row= pg_fetch_array($resulta);
      if ($row['habilitado']=='f'){
           $query ="UPDATE lab_tipomuestraporexamen
                    set habilitado = true,
                    idusuariomod= $usuario,
                    fechahoramod= NOW()    
                    WHERE idexamen= $idexamen
                    and idtipomuestra =$idtipomuestra";
           $result= pg_query($query);
      }
      else{
     $query = "INSERT INTO lab_tipomuestraporexamen(idexamen,idtipomuestra,
            idusuarioreg,fechahorareg) 
            VALUES($idexamen,$idtipomuestra,$usuario,NOW())";
     $result = pg_query($query);
      }
	// echo $query;
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 //Fn PG
 //FUNCION QUE VERIFICA SI LA MUESTRA YA FUE ASOCIADA AL EXAMEN
  function Verificar_Muestra($idexamen,$idtipomuestra){
	$con = new ConexionBD;
	if($con->conectar()==true)
	{ $query="SELECT COUNT(*) as cantidad FROM lab_tipomuestraporexamen WHERE idexamen=$idexamen AND idtipomuestra=$idtipomuestra and habilitado=true";
		$result = pg_query($query);
		if (!$result)
			return false;
		else
			return $result;
	}
  }
//Fn PG  
function Eliminar($idexamen,$idtipomuestra, $usuario)
{
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
       $query ="UPDATE lab_tipomuestraporexamen
set habilitado = false,
idusuariomod= $usuario,
fechahoramod= NOW()    
WHERE idexamen= $idexamen
and idtipomuestra =$idtipomuestra";
    // $query = "DELETE FROM lab_tipomuestraporexamen WHERE IdExamen='$idexamen' AND IdTipoMuestra=$idtipomuestra";
     $result = pg_query($query);
	// echo 'QUERY:'.$query;
     if (!$result)
       return false;
     else
       return true;
	}
}
  //Fn Pg
 //FUNCION PARA BUSCAR ASOCIADOS
 function consultarasociados($idexamen)
 {
 $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
      $query = "SELECT lte.id as idtipomuestraporexamen,lte.idexamen, lex.idexamen as codexamen, tipomuestra,lte.idtipomuestra
            FROM lab_tipomuestraporexamen lte
            INNER JOIN lab_examenes lex  ON lte.idexamen=lex.id
            INNER JOIN lab_tipomuestra ltm ON ltm.id=lte.idtipomuestra
            WHERE lte.idexamen=$idexamen
                and lte.habilitado=true
                order by tipomuestra;" ;
	 $result = pg_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
 }
 //Fn PG
//RECUPERAR EXAMENES POR AREA
 function ExamenesPorArea($idarea,$lugar)
 {
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
    if($con->conectar()==true){
     $query = "SELECT lab_examenes.id,nombreexamen 
                FROM lab_examenes 
                INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id=lab_examenesxestablecimiento.idexamen
                WHERE idarea=$idarea
                AND lab_examenesxestablecimiento.Condicion='H' 
                AND IdEstablecimiento=$lugar 
                ORDER BY nombreexamen";
     $result = pg_query($query);
   //  echo 'query'.$query.'<br/>';
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
      $query= "SELECT lab_examenes.idexamen,lab_examenes.idestandar,lab_examenes.idarea,nombreexamen,descripcion ,nombrearea,
lab_plantilla.id, Plantilla, lab_examenes.id, lab_codigosestandar.idestandar
FROM lab_examenes 
INNER JOIN lab_areas  ON lab_examenes.idarea=lab_areas.id
INNER JOIN lab_codigosestandar  ON lab_examenes.idestandar=lab_codigosestandar.id
INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id=lab_examenesxestablecimiento.idexamen
INNER JOIN lab_plantilla ON lab_plantilla.id=lab_examenesxestablecimiento.idplantilla
WHERE lab_examenesxestablecimiento.IdEstablecimiento=$lugar
 AND lab_examenes.id=$idexamen" ;
     //$query = "SELECT * FROM lab_examenes WHERE idexamen='$idexamen'";
	/* $query = "SELECT lab_examenes.IdExamen,lab_examenes.IdEstandar,lab_examenes.IdArea,NombreExamen,Descripcion,NombreArea,
lab_examenes.IdPlantilla, Plantilla
FROM lab_examenes 
INNER JOIN lab_areas  ON lab_examenes.IdArea=lab_areas.IdArea
INNER JOIN lab_codigosestandar  ON lab_examenes.IdEstandar=lab_codigosestandar.IdEstandar
INNER JOIN lab_plantilla ON lab_plantilla.IdPlantilla=lab_examenes.IdPlantilla
INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
WHERE lab_examenesxestablecimiento.IdEstablecimiento=$lugar AND lab_examenes.IdExamen='$idexamen'";*/
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
  
}//CLASE
?>
