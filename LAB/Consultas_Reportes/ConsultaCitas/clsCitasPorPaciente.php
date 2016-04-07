<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsCitasPorPaciente
{
	 //constructor	
	 function clsCitasPorPaciente()
	 {
	 }	
	 
function Nombre_Establecimiento($lugar){
   $con = new ConexionBD;
   if($con->conectar()==true){ 
       	$query="SELECT nombre FROM ctl_establecimiento WHERE id=$lugar";
                $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
   }

}


function DatosEstablecimiento($lugar){
$con = new ConexionBD;
	if($con->conectar()==true){			  
		$conNom  = "SELECT t02.id AS idtipoestablecimiento,
                              t01.nombre,
                              t02.nombre AS nombretipoestablecimiento
                        FROM ctl_establecimiento t01
			INNER JOIN ctl_tipo_establecimiento t02 ON (t02.id = t01.id_tipo_establecimiento)
			WHERE t01.id = $lugar";
                
		$resul = pg_query($conNom);
	}
 return $resul;
}

function LlenarCmbEstablecimiento($Idtipoesta){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT id,nombre FROM ctl_establecimiento WHERE id_tipo_establecimiento=$Idtipoesta ORDER BY nombre";
                        $dt = pg_query($sqlText) ;
	}
	return $dt;
}

function LlenarTodosEstablecimientos() {

      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $sqlText = "SELECT id, nombre FROM ctl_establecimiento ORDER BY nombre";
         $dt = pg_query($sqlText);
      }
      return $dt;
   }

function LlenarCmbServ($IdServ,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "with tbl_servicio as (select mnt_3.id,
                        CASE
                        WHEN mnt_3.nombre_ambiente IS NOT NULL
                        THEN  	
                                CASE WHEN id_servicio_externo_estab IS NOT NULL
                                        THEN mnt_ser.abreviatura ||'-->' ||mnt_3.nombre_ambiente
                                        ELSE mnt_3.nombre_ambiente
                                END

                        ELSE
                        CASE WHEN id_servicio_externo_estab IS NOT NULL 
                                THEN mnt_ser.abreviatura ||'--> ' || cat.nombre
                             WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=cat.nombre)
                                THEN cmo.nombre||'-'||cat.nombre
                        END
                        END AS servicio 
                        from ctl_atencion cat 
                        join mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion)
                        join mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id)
                        JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion=1)
                        LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id
                        LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id
                        join mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab)
                        join ctl_modalidad cmo on (cmo.id=mme.id_modalidad)
                        where  mnt_2.id=$IdServ
                        and mnt_3.id_establecimiento=$lugar
                        order by 2)
                        select id, servicio from tbl_servicio where servicio is not null";
                        
                        /*"WITH tbl_servicio AS (
                            SELECT t02.id,
                                CASE WHEN t02.nombre_ambiente IS NOT NULL THEN  	
                                    CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'-->' ||t02.nombre_ambiente
                                         ELSE t02.nombre_ambiente
                                    END
                                ELSE
                                    CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'--> ' || t01.nombre
                                         WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=t01.nombre) THEN t01.nombre
                                    END
                                END AS servicio 
                            FROM  ctl_atencion 				    t01 
                            INNER JOIN mnt_aten_area_mod_estab              t02 ON (t01.id = t02.id_atencion)
                            INNER JOIN mnt_area_mod_estab 	   	    t03 ON (t03.id = t02.id_area_mod_estab)
                            LEFT  JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab)
                            LEFT  JOIN mnt_servicio_externo 		    t05 ON (t05.id = t04.id_servicio_externo)
                            WHERE id_area_atencion = $IdServ and t02.id_establecimiento = $lugar
                            ORDER BY 2)
                        SELECT id, servicio FROM tbl_servicio WHERE servicio IS NOT NULL";*/
                        
		$dt = pg_query($sqlText) ;
	}
	return $dt;
}

function ExamenesPorArea($idarea,$lugar)
{
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
		 $query = "SELECT lab_examenes.IdExamen,NombreExamen FROM lab_examenes 
		       INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
		       WHERE IdEstablecimiento = $lugar AND IdArea='$idarea'
		       AND lab_examenesxestablecimiento.Condicion='H' ORDER BY NombreExamen ASC ";
		 $result = @pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	}
}
	 //FUNCION PARA MOSTRAR DATOS GENERALES DE LA SOLICITUD PROCESADAS POR AREA Y ESTADO
	  function LeerEspecialidades()
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	   $query= "SELECT IdSubServicio,NombreSubServicio FROM mnt_subservicio
	            WHERE IdServicio='CONEXT' ORDER BY NombreSubServicio";
	 
	     $result = @pg_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
	   }
	 }
 
 
 //FUNCION PARA MOSTRAR CITAS DEL PACIENTE
  function BuscarCitasPaciente($query_search)
 {
  //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = $query_search;
	 $result = @pg_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }  
}
	 


  function consultarpag($query_search,$RegistrosAEmpezar,$RegistrosAMostrar)
 {
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
   $query = $query_search ." LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
     $result = @pg_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
  function NumeroDeRegistros($query){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = $query;
	 $numreg = pg_num_rows(pg_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
 
 
}//CLASE
?>
