<?php 
include_once("../../../Conexion/ConexionBD.php");

//implementamos la clase lab_areas
class clsReporteTabuladores
{
	 //constructor	
	 function clsReporteTabuladores()
	 {
	 }	
	
   //FN Pg
   //
   //
   function ExamenesPorArea($idarea,$lugar)
   {
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
            $query = "SELECT t02.id,
                     t02.codigo_examen AS idexamen,
                     t02.nombre_examen AS nombreexamen,
                     t03.idestandar
                     FROM mnt_area_examen_establecimiento t01
                     INNER JOIN lab_conf_examen_estab     t02 ON (t01.id = t02.idexamen)
                     JOIN ctl_examen_servicio_diagnostico t03 ON (t03.id = t01.id_examen_servicio_diagnostico)
                 WHERE t01.id_establecimiento = $lugar AND t01.id_area_servicio_diagnostico = $idarea
                     AND t02.condicion = 'H'
                 ORDER BY t02.nombre_examen";
           // $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
	}
   }
                //Funcion utilizada para el tabulador para Servicio d eProcedencia 
 	public function prxmes($mes, $anio, $lugar, $idarea, $idame){
           $con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){	
	$sql = " select distinct(t02.idexamen) as id_pruebadetsol, t06.idestandar
               from sec_detallesolicitudestudios 	t01 
               join lab_resultados 			t02 on (t01.id=t02.iddetallesolicitud)
               join ctl_establecimiento			t03 on (t03.id=t02.idestablecimiento)
               join lab_conf_examen_estab		t04 on (t04.id=t02.idexamen)
               join mnt_area_examen_establecimiento	t05 on (t05.id=t04.idexamen)
               join ctl_examen_servicio_diagnostico	t06 on (t06.id=t05.id_examen_servicio_diagnostico)
               join mnt_empleado			t07 on (t07.id=t02.idempleado)
               join fos_user_user			t08 on (t07.id=t08.id_empleado)
               where  extract('year' from fecha_resultado)=$anio	
               and extract('month' from fecha_resultado)=$mes
               and estadodetalle in (6,7)
               and t02.idestablecimiento=$lugar
               and (case  $idarea
               when 0 then id_area_servicio_diagnostico >0
               else id_area_servicio_diagnostico =$idarea
               end)
               and id_area_mod_estab=$idame
               order by t06.idestandar";	
     //   echo '<br>'.$sql.'<br/>';
	$result=  @pg_query($sql);
	if (!$result)
		return false;
	else
		return $result;
	}
   }//fin de la funcion consultarTipoResultado


   public function pruebasid($id){
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
       $sql = "select t01.id, t02.idestandar as v_codprueba, t02.descripcion 
            from lab_conf_examen_estab 	t01
            join ctl_examen_servicio_diagnostico	t02 on (t02.id=t01.idestandarrep)
            where t01.id=$id";
           $result =  @pg_query($sql);
       if (!$result)
         return false;
       else
         return $result;
      }
   }
 
//Funcion utilizada para el tabulador para resultado
 	public function prxdia($idpr, $idcod, $dia, $mes, $anio, $lugar, $idame){
           $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
	$sql = "select count (case when id_codigoresultado=$idcod then 'uno' else null end) as res 
               from lab_resultado_metodologia t01
               join lab_examen_metodologia t02 on (t02.id=t01.id_examen_metodologia)
               join sec_detallesolicitudestudios t03 on (t03.id=t01.id_detallesolicitudestudio)
               join mnt_empleado			t04 on (t04.id=t01.id_empleado)
	       join fos_user_user			t05 on (t04.id=t05.id_empleado)
               where id_conf_exa_estab=$idpr
               and extract('year' from fecha_resultado)=$anio
               and extract('month' from fecha_resultado)=$mes
               and extract('day' from fecha_resultado)=$dia
               and estadodetalle in (6,7)
               and id_area_mod_estab=$idame
               and t03.idestablecimiento=$lugar;";	
      //  echo '<br>'.$sql.'<br/>';
	$result= pg_query($sql);
	if (!$result)
		return false;
	else
		return $result;
	}//fin de la funcion consultarTipoResultado
        }
   
    //Funcion utilizada para el tabulador para Servicio d eProcedencia 
   public function prxservicio($idpr, $lugar, $dia, $mes, $anio, $idame){
         $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
   $sql = "select 
   count (case when id_area_atencion=1 and id_servicio_externo_estab is null  then 'uno' else null end) as uno,
   count (case when id_area_atencion=3 and id_servicio_externo_estab is null then 'dos' else null end) as dos,
   count (case when id_area_atencion=2 and id_servicio_externo_estab is null  then 'tres' else null end) as tres
   --count (case when id_servicio_externo_estab is not null then 'cinco' else null end) as otros
   from sec_detallesolicitudestudios t00 	
   join sec_solicitudestudios t01 on (t01.id=t00.idsolicitudestudio)
   join lab_resultado_metodologia t0c on (t00.id=t0c.id_detallesolicitudestudio)
   join sec_historial_clinico t02 on (t02.id=t01.id_historial_clinico)
   join mnt_aten_area_mod_estab t03 on (t03.id=t02.idsubservicio)
   join mnt_area_mod_estab	 t04  on (t04.id=t03.id_area_mod_estab)
   left join mnt_servicio_externo_establecimiento t06 on (t06.id=t04.id_servicio_externo_estab)
   join mnt_empleado			t07 on (t07.id=t0c.id_empleado)
   join fos_user_user			t08 on (t07.id=t08.id_empleado)
   where estadodetalle in (6,7)
   --and id_servicio_externo_estab is null
   and t00.idestablecimiento=$lugar
   and id_conf_examen_estab=$idpr
   and extract('year' from fecha_resultado)=$anio
   and extract('month' from fecha_resultado)=$mes
   and extract('day' from fecha_resultado)=$dia "
           . "and t08.id_area_mod_estab=$idame";	
    //   echo '<br>'.$sql.'<br/>';
	$result= pg_query($sql);
	if (!$result)
		return false;
	else
		return $result;
	}//fin de la funcion consultarTipoResultado
 }
 
    //Funcion utilizada para el tabulador para Servicio d eProcedencia  de referidos externos
   public function prxservicioref($idpr, $lugar, $dia, $mes, $anio, $idame){
         $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
   $sql = "select count (distinct(t0c.id)) as cuatro
         from sec_detallesolicitudestudios t00 
         join sec_solicitudestudios t01 on (t01.id=t00.idsolicitudestudio)
         join lab_resultado_metodologia t0c on (t00.id=t0c.id_detallesolicitudestudio)
         join mnt_empleado			t03 on (t03.id=t0c.id_empleado)
	 join fos_user_user			t04 on (t03.id=t04.id_empleado)
         where estadodetalle in (6,7)
         and t00.idestablecimiento=$lugar
         and  id_dato_referencia is not null
         and extract('year' from fecha_resultado)=$anio
         and extract('month' from fecha_resultado)=$mes
         and extract('day' from fecha_resultado)=$dia
         and id_conf_examen_estab=$idpr"
           . " and t04.id_area_mod_estab=$idame";	
    //   echo '<br>'.$sql.'<br/>';
	$result= pg_query($sql);
	if (!$result)
		return false;
	else
		return $result;
	}//fin de la funcion consultarTipoResultado
 }
 
    //Funcion utilizada para el tabulador para Servicio d eProcedencia 
 public function pruebatotallab($idpr, $mes, $anio, $lugar, $idame){
            $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
	$sql = "select count(*) as total
         from lab_resultado_metodologia t01
         join sec_detallesolicitudestudios t02 on (t02.id=t01.id_detallesolicitudestudio)
         join mnt_empleado			t03 on (t03.id=t01.id_empleado)
	 join fos_user_user			t04 on (t03.id=t04.id_empleado)
         where estadodetalle in (6,7)
         and extract('year' from fecha_resultado)=$anio
         and extract('month' from fecha_resultado)=$mes
         and id_conf_examen_estab= $idpr
         and idestablecimiento=$lugar
            and t04.id_area_mod_estab=$idame;";	
    //   echo '<br>'.$sql.'<br/>';
	$result= pg_query($sql);
	if (!$result)
		return false;
	else
		return $result;
	}//fin de la funcion consultarTipoResultado
      }

//Fn Pg
   function buscarinstitucion($idestab) {
   //   echo 'buscarinstitucion:'.$idestab;
 //  include_once("DBManager.php"); 
   
      $con=new ConexionBD();
//     // echo 'Conexion: '.$con->conectar();
      if ($con->conectar()==true){
         $sql="select distinct t01.id,
               (case when id_servicio_externo_estab is null then t05.nombre
                    else t03.nombre end)   as institucion
               from mnt_area_mod_estab t01
               left join mnt_servicio_externo_establecimiento t02 on (t02.id=t01.id_servicio_externo_estab)
               left join mnt_servicio_externo t03 on (t03.id=t02.id_servicio_externo)
               join mnt_modalidad_establecimiento t04 on (t04.id=t01.id_modalidad_estab)
               join ctl_modalidad t05 on (t05.id=t04.id_modalidad)
               join fos_user_user t06 on (t01.id=t06.id_area_mod_estab)
               where id_area_atencion=1
               and enabled=true
               and t06.id_establecimiento=$idestab
               and modulo='LAB'
               order by 1;";
         $result=@pg_query($sql);
         if (!$result)
            return false;
         else
            return $result;
//      return true;
         
      }
      
   }     

//Fn Pg
   function nombreinstitucion($idame) {
   //   echo 'buscarinstitucion:'.$idestab;
 //  include_once("DBManager.php"); 
   
      $con=new ConexionBD();
//     // echo 'Conexion: '.$con->conectar();
      if ($con->conectar()==true){
         $sql=" select distinct t01.id,
               (case when id_servicio_externo_estab is null then t05.nombre
                    else t03.nombre end)   as institucion
               from mnt_area_mod_estab t01
               left join mnt_servicio_externo_establecimiento t02 on (t02.id=t01.id_servicio_externo_estab)
               left join mnt_servicio_externo t03 on (t03.id=t02.id_servicio_externo)
               join mnt_modalidad_establecimiento t04 on (t04.id=t01.id_modalidad_estab)
               join ctl_modalidad t05 on (t05.id=t04.id_modalidad)
               where t01.id=$idame
               order by 1;";
         $result=@pg_query($sql);
         if (!$result)
            return false;
         else
            return $result;
//      return true;
         
      }
      
   }     
   //Fin funcion Postgres

}//CLASE
?>
