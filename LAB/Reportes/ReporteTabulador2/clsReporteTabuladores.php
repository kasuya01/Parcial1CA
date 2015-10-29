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
        $query = "SELECT distinct t03.id, t03.idestandar, t03.descripcion as nombreexamen, t03.idestandar::bytea as codestandar
                  FROM mnt_area_examen_establecimiento t01
                  INNER JOIN lab_conf_examen_estab     t02 ON (t01.id = t02.idexamen)
                  JOIN ctl_examen_servicio_diagnostico t03 ON (t03.id = t02.idestandarrep)
                  WHERE t01.id_establecimiento = $lugar "
                 . "and t03.idgrupo =$idarea
                  AND t02.condicion = 'H'
                  ORDER BY t03.idestandar::bytea;";
//            $query = "SELECT t02.id,
//                     t02.codigo_examen AS idexamen,
//                     t02.nombre_examen AS nombreexamen,
//                     t03.idestandar
//                     FROM mnt_area_examen_establecimiento t01
//                     INNER JOIN lab_conf_examen_estab     t02 ON (t01.id = t02.idexamen)
//                     JOIN ctl_examen_servicio_diagnostico t03 ON (t03.id = t01.id_examen_servicio_diagnostico)
//                 WHERE t01.id_establecimiento = $lugar AND t01.id_area_servicio_diagnostico = $idarea
//                     AND t02.condicion = 'H'
//                 ORDER BY t02.nombre_examen";
           // $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
	}
   }
   //FN Pg
   //
   //
   function AreasGrupos($lugar)
   {
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
            $query = "select * from lab_estandarxgrupo where activo=true order by idgrupo;";
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
	$sql = " with tbl_prueba as(
               select distinct t06.idestandar, t06.id as id_pruebadetsol, id_area_servicio_diagnostico, id_area_mod_estab
                      from sec_detallesolicitudestudios 	t01 
                      join lab_resultados 			t02 on (t01.id=t02.iddetallesolicitud)
                      join ctl_establecimiento			t03 on (t03.id=t02.idestablecimiento)
                      join lab_conf_examen_estab		t04 on (t04.id=t02.idexamen)
                      join mnt_area_examen_establecimiento	t05 on (t05.id=t04.idexamen)
                      join ctl_examen_servicio_diagnostico	t06 on (t06.id=t04.idestandarrep)
                      join mnt_empleado			t07 on (t07.id=t02.idempleado)
                      join fos_user_user			t08 on (t07.id=t08.id_empleado)
                      where  extract('year' from fecha_resultado)=$anio
                      and extract('month' from fecha_resultado)=$mes
                      and estadodetalle in (6,7)
                      and t02.idestablecimiento=$lugar
               union
               select distinct t03.idestandar, t03.id as id_pruebadetsol, id_area_servicio_diagnostico, id_area_mod_estab
                       from sec_detallesolicitudestudios	t01
                       join lab_conf_examen_estab		t02 on (t02.id=t01.id_conf_examen_estab)
                       join ctl_examen_servicio_diagnostico	t03 on (t03.id=t02.idestandarrep)
                       join mnt_area_examen_establecimiento	t04 on (t04.id=t02.idexamen)
                       join mnt_empleado			t07 on (t07.id=t01.idempleado)
                       join fos_user_user			t08 on (t07.id=t08.id_empleado)
                       where id_area_servicio_diagnostico =14
                       and extract('year' from f_tomamuestra)=$anio	
                       and extract('month' from f_tomamuestra)=$mes
                       and estadodetalle in (6,7)
                       and t01.idestablecimiento=$lugar
               )
               select * from tbl_prueba
               where (case  $idarea
                      when 0 then id_area_servicio_diagnostico >0
                      else id_area_servicio_diagnostico =$idarea
                      end)
               and id_area_mod_estab=$idame 
               order by idestandar;";	
        //Lo comente el 28/09/2015
//	$sql = " select distinct(t02.idexamen) as id_pruebadetsol, t06.idestandar
//               from sec_detallesolicitudestudios 	t01 
//               join lab_resultados 			t02 on (t01.id=t02.iddetallesolicitud)
//               join ctl_establecimiento			t03 on (t03.id=t02.idestablecimiento)
//               join lab_conf_examen_estab		t04 on (t04.id=t02.idexamen)
//               join mnt_area_examen_establecimiento	t05 on (t05.id=t04.idexamen)
//               join ctl_examen_servicio_diagnostico	t06 on (t06.id=t05.id_examen_servicio_diagnostico)
//               join mnt_empleado			t07 on (t07.id=t02.idempleado)
//               join fos_user_user			t08 on (t07.id=t08.id_empleado)
//               where  extract('year' from fecha_resultado)=$anio	
//               and extract('month' from fecha_resultado)=$mes
//               and estadodetalle in (6,7)
//               and t02.idestablecimiento=$lugar
//               and (case  $idarea
//               when 0 then id_area_servicio_diagnostico >0
//               else id_area_servicio_diagnostico =$idarea
//               end)
//               and id_area_mod_estab=$idame
//               order by t06.idestandar";	
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
       $sql = "select t01.id, t01.idestandar as v_codprueba, t01.descripcion 
            from ctl_examen_servicio_diagnostico t01	
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
         ///Estado detalle quedo solo 7 que sea que los finalizaron, los rechazos de muestras quedaran en la funcion pruebasrechazadas
	$sql = "select count (case when id_codigoresultado=$idcod then 'uno' else null end) as res 
               from lab_resultado_metodologia t01
               join lab_examen_metodologia t02 on (t02.id=t01.id_examen_metodologia)
               join sec_detallesolicitudestudios t03 on (t03.id=t01.id_detallesolicitudestudio)
               join mnt_empleado			t04 on (t04.id=t01.id_empleado)
	       join fos_user_user			t05 on (t04.id=t05.id_empleado)
               join lab_conf_examen_estab		t06 on (t06.id=t02.id_conf_exa_estab)
               where idestandarrep=$idpr
               and extract('year' from fecha_resultado)=$anio
               and extract('month' from fecha_resultado)=$mes
               and extract('day' from fecha_resultado)=$dia
               and estadodetalle in (7)
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
         $sql="with sumacodigos as(
	select 
	count (case when id_area_atencion=1 and id_servicio_externo_estab is null and t01.id_historial_clinico is not null  then 'uno' else null end) as uno,
	count (case when id_area_atencion=3 and id_servicio_externo_estab is null and t01.id_historial_clinico is not null then 'dos' else null end) as dos,
	count (case when id_area_atencion=2 and id_servicio_externo_estab is null and t01.id_historial_clinico is not null  then 'tres' else null end) as tres,
	count (case when id_area_atencion=1 and id_servicio_externo_estab is null and t01.id_dato_referencia is not null  then 'cuatro' else null end) as cuatro,
	count (case when id_servicio_externo_estab is not null then 'cinco' else null end) as cinco
	from sec_detallesolicitudestudios t00 	
	join sec_solicitudestudios t01 on (t01.id=t00.idsolicitudestudio)
	join lab_resultado_metodologia t0c on (t00.id=t0c.id_detallesolicitudestudio)
	left join sec_historial_clinico t02 on (t02.id=t01.id_historial_clinico)
	left join mnt_dato_referencia		t02b on (t02b.id=t01.id_dato_referencia)
	join mnt_aten_area_mod_estab 		t03 on (t03.id=t02.idsubservicio or t03.id=t02b.id_aten_area_mod_estab)
	join mnt_area_mod_estab	 t04  on (t04.id=t03.id_area_mod_estab)
	left join mnt_servicio_externo_establecimiento t06 on (t06.id=t04.id_servicio_externo_estab)
	join mnt_empleado			t07 on (t07.id=t0c.id_empleado)
	join fos_user_user			t08 on (t07.id=t08.id_empleado)
	join lab_conf_examen_estab		t09 on (t09.id=t00.id_conf_examen_estab)
	where estadodetalle in (6,7)
	and t00.idestablecimiento=$lugar
	and idestandarrep=$idpr
	and extract('year' from fecha_resultado)=$anio
	and extract('month' from fecha_resultado)=$mes
	and extract('day' from fecha_resultado)=$dia
	and t08.id_area_mod_estab=$idame
union
	select 
	count (case when id_area_atencion=1 and id_servicio_externo_estab is null and t01.id_historial_clinico is not null  then 'uno' else null end) as uno,
	count (case when id_area_atencion=3 and id_servicio_externo_estab is null and t01.id_historial_clinico is not null then 'dos' else null end) as dos,
	count (case when id_area_atencion=2 and id_servicio_externo_estab is null and t01.id_historial_clinico is not null  then 'tres' else null end) as tres,
	count (case when id_area_atencion=1 and id_servicio_externo_estab is null and t01.id_dato_referencia is not null  then 'cuatro' else null end) as cuatro,
	count (case when id_servicio_externo_estab is not null then 'cinco' else null end) as cinco
	from sec_detallesolicitudestudios 	t00 	
	join sec_solicitudestudios 		t01 on (t01.id=t00.idsolicitudestudio)
	left join sec_historial_clinico 	t02 on (t02.id=t01.id_historial_clinico)
	left join mnt_dato_referencia		t02b on (t02b.id=t01.id_dato_referencia)
	join mnt_aten_area_mod_estab 		t03 on (t03.id=t02.idsubservicio or t03.id=t02b.id_aten_area_mod_estab)
	join mnt_area_mod_estab	 		t04  on (t04.id=t03.id_area_mod_estab)
	join lab_recepcionmuestra		t06 on (t01.id=t06.idsolicitudestudio)
	join fos_user_user			t07 on (t07.id=t06.idusuarioreg)
	join lab_conf_examen_estab		t08 on (t08.id=t00.id_conf_examen_estab)
        join mnt_area_examen_establecimiento	t09 on (t09.id=t08.idexamen)
	where estadodetalle in (6,7)
        and id_area_servicio_diagnostico =14
	and t00.idestablecimiento=$lugar
	and idestandarrep=$idpr
	and extract('year' from f_tomamuestra)=$anio
	and extract('month' from f_tomamuestra)=$mes
	and extract('day' from f_tomamuestra)=$dia
	and t07.id_area_mod_estab=$idame
)
select sum(uno) as uno, sum(dos) as dos, sum(tres) as tres, sum (cuatro) as cuatro, sum(cinco) as cinco from sumacodigos;";
         
        // var_dump($sql);
//   $sql = "select 
//   count (case when id_area_atencion=1 and id_servicio_externo_estab is null  then 'uno' else null end) as uno,
//   count (case when id_area_atencion=3 and id_servicio_externo_estab is null then 'dos' else null end) as dos,
//   count (case when id_area_atencion=2 and id_servicio_externo_estab is null  then 'tres' else null end) as tres
//   --count (case when id_servicio_externo_estab is not null then 'cinco' else null end) as otros
//   from sec_detallesolicitudestudios t00 	
//   join sec_solicitudestudios t01 on (t01.id=t00.idsolicitudestudio)
//   join lab_resultado_metodologia t0c on (t00.id=t0c.id_detallesolicitudestudio)
//   join sec_historial_clinico t02 on (t02.id=t01.id_historial_clinico)
//   join mnt_aten_area_mod_estab t03 on (t03.id=t02.idsubservicio)
//   join mnt_area_mod_estab	 t04  on (t04.id=t03.id_area_mod_estab)
//   left join mnt_servicio_externo_establecimiento t06 on (t06.id=t04.id_servicio_externo_estab)
//   join mnt_empleado			t07 on (t07.id=t0c.id_empleado)
//   join fos_user_user			t08 on (t07.id=t08.id_empleado)
//   where estadodetalle in (6,7)
//   --and id_servicio_externo_estab is null
//   and t00.idestablecimiento=$lugar
//   and id_conf_examen_estab=$idpr
//   and extract('year' from fecha_resultado)=$anio
//   and extract('month' from fecha_resultado)=$mes
//   and extract('day' from fecha_resultado)=$dia "
//           . "and t08.id_area_mod_estab=$idame";	
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
         join lab_conf_examen_estab 		t05 on (t05.id=t00.id_conf_examen_estab)
         where estadodetalle in (6,7)
         and t00.idestablecimiento=$lugar
         and  id_dato_referencia is not null
         and extract('year' from fecha_resultado)=$anio
         and extract('month' from fecha_resultado)=$mes
         and extract('day' from fecha_resultado)=$dia
         and idestandarrep=$idpr"
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
	$sql = "with tbl_totalprueba as(select count(*) as total
	from lab_resultado_metodologia 		t01
	join sec_detallesolicitudestudios 	t02 on (t02.id=t01.id_detallesolicitudestudio)
	join mnt_empleado			t03 on (t03.id=t01.id_empleado)
	join fos_user_user			t04 on (t03.id=t04.id_empleado)
	join lab_conf_examen_estab		t05 on (t05.id=t02.id_conf_examen_estab)
	where estadodetalle in (6,7)
	and extract('year' from fecha_resultado)=$anio
	and extract('month' from fecha_resultado)=$mes
	and idestandarrep= $idpr
	and t02.idestablecimiento=$lugar
	and t04.id_area_mod_estab=$idame
union
select count(*) as total
	from sec_detallesolicitudestudios 	t01 
	join lab_recepcionmuestra 		t02 on (t01.idsolicitudestudio=t02.idsolicitudestudio)
	join fos_user_user			t03 on (t03.id=t02.idusuarioreg)
	join lab_conf_examen_estab		t04 on (t04.id=t01.id_conf_examen_estab)
	join mnt_area_examen_establecimiento	t05 on (t05.id=t04.idexamen)	        
	where estadodetalle in (6,7)
	and id_area_servicio_diagnostico =14 
	and extract('year' from f_tomamuestra)=$anio
	and extract('month' from f_tomamuestra)=$mes
	and idestandarrep=$idpr
	and t01.idestablecimiento=$lugar
	and t03.id_area_mod_estab=$idame
	)
select sum (total) as total from tbl_totalprueba;

";	
	/*$sql = "select count(*) as total
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
    //   echo '<br>'.$sql.'<br/>';*/
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
//Fn Pg
   function pruebasrechazadas($idpr, $dia, $month, $year, $lugar, $idame) {
   //   echo 'buscarinstitucion:'.$idestab;
 //  include_once("DBManager.php"); 
   
      $con=new ConexionBD();
//     // echo 'Conexion: '.$con->conectar();
      if ($con->conectar()==true){
         $sql="select count(*) as res
            from sec_detallesolicitudestudios  	t01
            join lab_conf_examen_estab		t02 on (t02.id=t01.id_conf_examen_estab)
            join ctl_examen_servicio_diagnostico	t03 on (t03.id=t02.idestandarrep)
            join lab_recepcionmuestra 		t04 on (t04.idsolicitudestudio=t01.idsolicitudestudio)
            join fos_user_user			t05 on (t05.id=t04.idusuarioreg)
            where estadodetalle=6
            and idestandarrep=$idpr
            and extract('year' from f_tomamuestra)=$year	
            and extract('month' from f_tomamuestra)=$month
             and extract('day' from f_tomamuestra)=$dia
            and id_area_mod_estab=$idame
            and t01.idestablecimiento=$lugar";
         $result=@pg_query($sql);
         if (!$result)
            return false;
         else
            return $result;
//      return true;
         
      }
      
   }     
   
  //Fn Pg
   function pruebastmx($idpr, $dia, $month, $year, $lugar, $idame) {
   //   echo 'buscarinstitucion:'.$idestab;
 //  include_once("DBManager.php"); prxservicio
   
      $con=new ConexionBD();
//     // echo 'Conexion: '.$con->conectar();
      if ($con->conectar()==true){
         $sql="select count(*) as res
            from sec_solicitudestudios 		t01 
            join sec_detallesolicitudestudios 	t02 on (t01.id=t02.idsolicitudestudio)
            join lab_recepcionmuestra		t03 on (t01.id=t03.idsolicitudestudio)
            join lab_conf_examen_estab		t04 on (t04.id=t02.id_conf_examen_estab)
            join fos_user_user			t05 on (t05.id=t03.idusuarioreg)
            join mnt_area_examen_establecimiento	t06 on (t06.id=t04.idexamen)
            join ctl_examen_servicio_diagnostico	t07 on (t07.id=t04.idestandarrep)
            where estadodetalle=7
            and id_area_servicio_diagnostico =14
            and extract('year' from f_tomamuestra)=$year	
            and extract('month' from f_tomamuestra)=$month
            and extract('day' from f_tomamuestra)=$dia
            and id_area_mod_estab=$idame
            and t03.idestablecimiento=$lugar
            and idestandarrep=$idpr
            and idgrupo=17;";
         //var_dump($sql);
         $result=@pg_query($sql);
         if (!$result)
            return false;
         else
            return $result;
//      return true;
         
      }
      
   }
   //Fin funcion Postgres
//----------------------------------------------------------------
//----------------------------------------------------------------
////DE AQUI PARA ABAJO SON DE xid.php
   
   
function  prxtiporesultado($idpr, $j, $n, $month, $year, $lugar, $idame){
   $con=new ConexionBD();
//     // echo 'Conexion: '.$con->conectar();
      if ($con->conectar()==true){
        $sql="with tb_tiporesultado as(
         --Aca id resultado es no nulo
         select count(idestandarrep) as res,idestandarrep, id_area_mod_estab, date(fecha_resultado), t4.nombre_examen, id_codigoresultado, estadodetalle
         from sec_detallesolicitudestudios 	t1
         join lab_resultado_metodologia		t2 on (t1.id=t2.id_detallesolicitudestudio)
         join fos_user_user			t3 on (t3.id_empleado=t2.id_empleado)
         join lab_conf_examen_estab		t4 on (t4.id=t1.id_conf_examen_estab)
         where  extract('year' from fecha_resultado) =$year
         and extract('month' from fecha_resultado)=$month
         and extract ('day' from fecha_resultado) =$n
         and estadodetalle in (6,7)
         group by idestandarrep, id_area_mod_estab, date(fecha_resultado), t4.nombre_examen, id_codigoresultado ,estadodetalle
         union
         --aca idresultado es nulo
         select count(idestandarrep) as res,idestandarrep, id_area_mod_estab, date(f_tomamuestra), t05.nombre_examen, 
         case when estadodetalle=7 then 6
          when estadodetalle=6 then 5
         else null
         end as id_codigoresultado, estadodetalle
         from sec_detallesolicitudestudios 	t01
         left join lab_resultado_metodologia 	t02 on (t01.id=t02.id_detallesolicitudestudio)
         join lab_recepcionmuestra		t03 on (t01.idsolicitudestudio=t03.idsolicitudestudio)
         join fos_user_user			t04 on (t04.id=t03.idusuarioreg)
         join lab_conf_examen_estab		t05 on (t05.id=t01.id_conf_examen_estab)
         join mnt_area_examen_establecimiento	t06 on (t06.id=t05.idexamen)
         where extract('year' from f_tomamuestra) =$year
         and extract('month' from f_tomamuestra)=$month
         and extract ('day' from f_tomamuestra) =$n
         and t02.id is null
         and case when estadodetalle=7 then id_area_servicio_diagnostico=14
              when estadodetalle=6 then id_area_servicio_diagnostico>0
             else null end
         group by idestandarrep, id_area_mod_estab, date(f_tomamuestra), t05.nombre_examen, id_codigoresultado, estadodetalle
         )
         select sum(res) as suma from tb_tiporesultado
         where idestandarrep=$idpr
         and id_area_mod_estab=$idame
         and id_codigoresultado=$j
         ;";
     $result=@pg_query($sql);
         if (!$result)
            return false;
         else
            return $result;
         }
      
}//fin funcion prxtiporesultado
   
   
//----------------------------------------------------------------
//----------------------------------------------------------------
////DE AQUI PARA ABAJO SON DE xid.php

}//CLASE
?>
