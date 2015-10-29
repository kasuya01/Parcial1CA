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
 //FUNCION QUE DESHABILITAR LAS MUESTRA ASOCIADAS AL EXAMEN
  function deshabilitar_tm($idexamen, $usuario){
	$con = new ConexionBD;
	if($con->conectar()==true)
	{ $query="update lab_tipomuestraporexamen
                    set habilitado=false, 
                    idusuariomod= $usuario,
                    fechahoramod= NOW() 
                    where idexamen=$idexamen;";
		$result = pg_query($query);
		if (!$result)
			return false;
		else
			return $result;
	}
  }
 //Fn PG
 //aCTUALIZA EL ESTADO DE LA MUESTRA PARA HABILITAR SOLAMENTE LAS SELECCIONADAS
  function actualizarmuestra($idexamen, $idtipomuestra,$iduser){
	$con = new ConexionBD;
	if($con->conectar()==true)
	{ 
	 $query=" update lab_tipomuestraporexamen
                    set habilitado=true,
                    idusuariomod=$iduser,
                    fechahoramod=NOW()
                    where idexamen=$idexamen
                    and idtipomuestra=$idtipomuestra;";
		$result = pg_query($query);
               // echo $query;
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
            /* $query = "select lte.id as idtipomuestraporexamen, lte.idexamen, cex.idestandar as codexamen, tipomuestra, lte.idtipomuestra
              from lab_tipomuestraporexamen lte
              join ctl_examen_servicio_diagnostico cex on (cex.id=lte.idexamen)
              join lab_tipomuestra  ltm on (ltm.id=lte.idtipomuestra)
              where cex.id=$idexamen
              and lte.habilitado=true
              order by tipomuestra" ;*/
            $query="select lte.id as idtipomuestraporexamen, lte.idexamen, lce.codigo_examen as codexamen, tipomuestra, lte.idtipomuestra
                    from lab_tipomuestraporexamen 	lte
                    join lab_conf_examen_estab 	lce on (lce.id= lte.idexamen)
                    join lab_tipomuestra 		ltm on (ltm.id= lte.idtipomuestra)
                    where lce.id=$idexamen
                    and lte.habilitado=true";
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
     $query ="SELECT mnt_area_examen_establecimiento.id, nombre_examen, lab_conf_examen_estab.id as idexamen 
                   FROM lab_conf_examen_estab  
                   JOIN mnt_area_examen_establecimiento  ON (lab_conf_examen_estab.idexamen = mnt_area_examen_establecimiento.id) 
                   INNER JOIN ctl_examen_servicio_diagnostico ON ctl_examen_servicio_diagnostico.id=mnt_area_examen_establecimiento.id_examen_servicio_diagnostico
                   WHERE condicion='H' AND mnt_area_examen_establecimiento.activo=true 
                   AND mnt_area_examen_establecimiento.activo=TRUE
                   AND ctl_examen_servicio_diagnostico.activo=TRUE
                   AND lab_conf_examen_estab.condicion='H'
                   AND id_establecimiento=$lugar
                   AND id_area_servicio_diagnostico=$idarea
                   ORDER BY nombre_examen";
            /*"select mnt4.id, nombre_examen, lex.id as idexamen 
                from lab_conf_examen_estab lex
                join mnt_area_examen_establecimiento mnt4 on (lex.idexamen = mnt4.id)
                where condicion='H'
                and mnt4.activo=true
                and id_establecimiento=$lugar
                and id_area_servicio_diagnostico=$idarea
                order by nombre_examen";*/
     $result = pg_query($query);
     //echo 'query'.$query.'<br/>';
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
