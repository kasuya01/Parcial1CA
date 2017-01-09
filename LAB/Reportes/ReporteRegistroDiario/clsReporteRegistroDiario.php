<?php
include_once("../../../Conexion/ConexionBD.php");
        //include($_SERVER['DOCUMENT_ROOT']."/Laboratorio/Conexion/ConexionBD.php");
//include_once("DBManager.php");
//implementamos la clase lab_areas
class clsReporteRegistroDiario
{
   //constructor
   function clsReporteDemanda()
   {
   }

   function Nombre_Establecimiento($lugar) {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $query = "SELECT nombre FROM ctl_establecimiento WHERE id = $lugar";
         $result = @pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function Nombre_Area($area) {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $query = "SELECT nombrearea nombre FROM ctl_area_servicio_diagnostico WHERE id = $area";
         $result = @pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

 function fechamuestra()
   {
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
            $query = "select to_char(current_date, 'YYYY-MM')||'-01' as fechamuestra";
           // $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
	}
   }//Fin fechamuestra


 function estadoRechazo()
   {
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
         $query = "select *,replace(estado, ' ', '') as rtab from lab_estado_rechazo "
                    . "where id>1"
                    . " and habilitado=true"
                    . " order by id;";
           // $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
	}
   }

 function posiblesrechazos()
   {
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
           $query = "select * from lab_posible_observacion"
                    . " where  habilitado=true"
                    . " order by id;";
           // $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
	}
   }


 function getTotalEstado($area, $idarea, $exa, $examenes, $fecha, $d_fechadesde, $d_fechahasta)
   {
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
        $query = "select count(*) as cantidad, t02.estado, t02.id
from sec_detallesolicitudestudios t01
join lab_estado_rechazo t02 		on (t02.id=t01.id_estado_rechazo)
join lab_conf_examen_estab t03		on (t03.id=t01.id_conf_examen_estab)
join mnt_area_examen_establecimiento t05 on (t05.id=t03.idexamen)
where case $fecha
	when 0 then t01.f_estado between date(to_char(current_date, 'YYYY-MM')||'-01') and '$d_fechahasta'
	else t01.f_estado between '$d_fechadesde' and '$d_fechahasta'
	end
and case $area
	when 0 then t05.id_area_servicio_diagnostico >=1
	else t05.id_area_servicio_diagnostico=$idarea
	end
and case $exa
	when 0 then t03.id >=1
	else t03.id in ($examenes)
	end
group by t02.estado, t02.id
having t02.id >1
order by t02.id;";
           // $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
	}
   }

    function getRegistroDiario($idarea, $d_fechadesde, $lugar)
    {
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
        $query = "WITH tbl_servicio AS ( SELECT t02.id,
                CASE WHEN t02.nombre_ambiente IS NOT NULL THEN
                    t02.nombre_ambiente
                    ELSE
                        CASE WHEN id_servicio_externo_estab IS NOT NULL
                                THEN t05.abreviatura  ||'   -   ' ||  t01.nombre
                            WHEN not exists (select nombre_ambiente
              							 from mnt_aten_area_mod_estab maame
              							 join mnt_area_mod_estab mame on (maame.id_area_mod_estab = mame.id)
              							 where nombre_ambiente=t01.nombre
              							 and mame.id_area_atencion=t03.id_area_atencion)

                                    THEN t01.nombre
                    END

                END AS servicio,
               (CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'-->'  || t06.nombre
                    ELSE   t07.nombre ||'-->' || t06.nombre
                END) as procedencia
                FROM ctl_atencion t01
                INNER JOIN mnt_aten_area_mod_estab t02 ON (t01.id = t02.id_atencion)
                INNER JOIN mnt_area_mod_estab t03 ON (t03.id = t02.id_area_mod_estab)
                LEFT JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab)
                LEFT JOIN mnt_servicio_externo t05 ON (t05.id = t04.id_servicio_externo)
                INNER JOIN  ctl_area_atencion t06  on  t06.id = t03.id_area_atencion
                INNER JOIN ctl_modalidad  t07 ON t07.id = t03.id_modalidad_estab
                WHERE t02.id_establecimiento =  $lugar ORDER BY 2)
                    SELECT ordenar.* FROM (
                       SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                       t02.id AS idsolicitudestudio,
                       t04.idplantilla,
                       t01.id AS iddetallesolicitud,
                       t03.numeromuestra,
                       t06.numero AS idnumeroexp,
                       t03.id AS idrecepcionmuestra,
                       t04.id AS idexamen,
                       t04.nombre_examen AS nombreexamen,
                       t04.codigo_examen AS codigoexamen,
                       t01.indicacion, t08.nombrearea,
                       CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,
                       t07.segundo_apellido,t07.apellido_casada) AS paciente,
                       t13.id AS idservicio,
                       t20.servicio AS nombresubservicio,
                       t20.procedencia AS nombreservicio,
                       t02.impresiones,
                       t14.nombre,
                       t09.id AS idhistorialclinico,
                       TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud,
                       t17.tiposolicitud AS prioridad,
                       t07.fecha_nacimiento AS fechanacimiento,
                       age(t02.fecha_solicitud,t07.fecha_nacimiento) AS edad,
                       t19.abreviatura AS sexo,
                       t18.idestandar,
                       t02.id_establecimiento_externo as IdEstab,
                       (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                       false AS referido, TO_CHAR(f_tomamuestra,'dd/mm/YYYY HH12:MI') AS f_tomamuestra,
                       (SELECT tipomuestra FROM lab_tipomuestra WHERE id=t01.idtipomuestra) AS tipomuestra,
                       t17.idtiposolicitud,t08.id as idarea,t18.idestandar as estandar
                FROM sec_detallesolicitudestudios t01
                INNER JOIN sec_solicitudestudios t02 ON (t02.id = t01.idsolicitudestudio)
                INNER JOIN lab_recepcionmuestra t03 ON (t02.id = t03.idsolicitudestudio)
                INNER JOIN lab_conf_examen_estab t04 ON (t04.id = t01.id_conf_examen_estab)
                INNER JOIN mnt_area_examen_establecimiento t05 ON (t05.id = t04.idexamen)
                INNER JOIN mnt_expediente t06 ON (t06.id = t02.id_expediente)
                INNER JOIN mnt_paciente t07 ON (t07.id = t06.id_paciente)
                INNER JOIN ctl_area_servicio_diagnostico t08 ON (t08.id = t05.id_area_servicio_diagnostico
                AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                INNER JOIN sec_historial_clinico t09 ON (t09.id = t02.id_historial_clinico)
                INNER JOIN mnt_aten_area_mod_estab t10 ON (t10.id = t09.idsubservicio)
                INNER JOIN ctl_atencion t11 ON (t11.id = t10.id_atencion)
                INNER JOIN mnt_area_mod_estab t12 ON (t12.id = t10.id_area_mod_estab)
                INNER JOIN ctl_area_atencion t13 ON (t13.id = t12.id_area_atencion)
                INNER JOIN ctl_establecimiento t14 ON (t14.id = t09.idestablecimiento)
                INNER JOIN cit_citas_serviciodeapoyo t15 ON (t02.id = t15.id_solicitudestudios)
                INNER JOIN ctl_estado_servicio_diagnostico t16 ON (t16.id = t01.estadodetalle)
                INNER JOIN lab_tiposolicitud t17 ON (t17.id = t02.idtiposolicitud)
                INNER JOIN ctl_examen_servicio_diagnostico t18 ON (t18.id = t05.id_examen_servicio_diagnostico)
                INNER JOIN ctl_sexo t19 ON (t19.id = t07.id_sexo)
                INNER JOIN tbl_servicio t20 ON (t20.id = t10.id AND t20.servicio IS NOT NULL)

                WHERE t16.idestado != 'RM' AND t02.id_establecimiento = $lugar
                AND t08.id = $idarea
                AND t03.fecharecepcion = '$d_fechadesde'
                AND t01.estadodetalle in (3,5)

                UNION

                SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                       t02.id AS idsolicitudestudio,
                       t04.idplantilla,
                       t01.id AS iddetallesolicitud,
                       t03.numeromuestra,
                       t06.numero AS idnumeroexp,
                       t03.id AS idrecepcionmuestra,
                       t04.id AS idexamen,
                       t04.nombre_examen AS nombreexamen,
                       t04.codigo_examen AS codigoexamen,
                       t01.indicacion, t08.nombrearea,
                       CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,
                       t07.apellido_casada) AS paciente,
                       t13.id AS idservicio,
                       t11.nombre AS nombresubservicio,
                       t13.nombre AS nombreservicio,
                       t02.impresiones,
                       t14.nombre,
                       t09.id AS idhistorialclinico,
                       TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud,
                       t17.tiposolicitud AS prioridad,
                       t07.fecha_nacimiento AS fechanacimiento,
                       age(t02.fecha_solicitud,t07.fecha_nacimiento) AS edad,
                       t19.abreviatura AS sexo,
                       t18.idestandar,
                       t02.id_establecimiento_externo,
                       (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                       true AS referido,TO_CHAR(f_tomamuestra,'dd/mm/YYYY HH12:MI') AS f_tomamuestra,
                       (SELECT tipomuestra FROM lab_tipomuestra WHERE id=t01.idtipomuestra) AS tipomuestra,
                       t17.idtiposolicitud,t08.id as idarea,t18.idestandar as estandar
                FROM sec_detallesolicitudestudios t01
                INNER JOIN sec_solicitudestudios t02 ON (t02.id = t01.idsolicitudestudio)
                INNER JOIN lab_recepcionmuestra t03 ON (t02.id = t03.idsolicitudestudio)
                INNER JOIN lab_conf_examen_estab t04 ON (t04.id = t01.id_conf_examen_estab)
                INNER JOIN mnt_area_examen_establecimiento t05 ON (t05.id = t04.idexamen)
                INNER JOIN mnt_dato_referencia t09 ON t09.id=t02.id_dato_referencia
                INNER JOIN mnt_expediente_referido t06 ON (t06.id = t09.id_expediente_referido)
                INNER JOIN mnt_paciente_referido t07 ON (t07.id = t06.id_referido)
                INNER JOIN ctl_area_servicio_diagnostico t08 ON (t08.id = t05.id_area_servicio_diagnostico
                AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                INNER JOIN mnt_aten_area_mod_estab t10 ON (t10.id = t09.id_aten_area_mod_estab)
                INNER JOIN ctl_atencion t11 ON (t11.id = t10.id_atencion)
                INNER JOIN mnt_area_mod_estab t12 ON (t12.id = t10.id_area_mod_estab)
                INNER JOIN ctl_area_atencion t13 ON (t13.id = t12.id_area_atencion)
                INNER JOIN ctl_establecimiento t14 ON (t14.id = t09.id_establecimiento)
                INNER JOIN cit_citas_serviciodeapoyo t15 ON (t02.id = t15.id_solicitudestudios)
                INNER JOIN ctl_estado_servicio_diagnostico t16 ON (t16.id = t01.estadodetalle)
                INNER JOIN lab_tiposolicitud t17 ON (t17.id = t02.idtiposolicitud)
                INNER JOIN ctl_examen_servicio_diagnostico t18 ON (t18.id = t05.id_examen_servicio_diagnostico)
                INNER JOIN ctl_sexo t19 ON (t19.id = t07.id_sexo)

                WHERE t16.idestado != 'RM' AND t02.id_establecimiento = $lugar
                AND t08.id = $idarea
                AND t01.estadodetalle in (3,5)
                AND t03.fecharecepcion = '$d_fechadesde' ) ordenar
                ORDER BY ordenar.numeromuestra ASC









                 ";
           // $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
	}
   }




 function resultadomotivo($area, $idarea, $exa, $examenes, $fecha, $d_fechadesde, $d_fechahasta)
   {
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
         $query = "select  posible_observacion,
                  count (case when id_estado_rechazo=2 then t02.estado end) as temporal,
                  count (case when id_estado_rechazo=3 then t02.estado end) as definitivo
                  from sec_detallesolicitudestudios t01
                  join lab_estado_rechazo t02 on (t02.id=t01.id_estado_rechazo)
                  join lab_conf_examen_estab t03	on (t03.id=t01.id_conf_examen_estab)
                  join lab_posible_observacion t04 on (t04.id=t01.id_posible_observacion)
                  join mnt_area_examen_establecimiento t05 on (t05.id=t03.idexamen)
                  where case $fecha
                        when 0 then t01.f_estado between date(to_char(current_date, 'YYYY-MM')||'-01') and '$d_fechahasta'
                        else t01.f_estado between '$d_fechadesde' and '$d_fechahasta' end
                  and case $area
                      when 0 then t05.id_area_servicio_diagnostico >=1
                      else t05.id_area_servicio_diagnostico=$idarea end
                  and case $exa
                      when 0 then t03.id >=1
                      else t03.id in ($examenes) end
                  group by posible_observacion, t04.id
                  order by t04.posible_observacion;";
           // $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
	}
   }


 function resultadoposobservacion($area, $idarea, $exa, $examenes, $fecha, $d_fechadesde, $d_fechahasta, $idestrecha)
   {
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
        $query = "select nombrearea, nombre_examen
      ,sum(b_ct)::int ct_total
      ,count(*)::int  ct_distinct_b
      ,array_to_string(array_agg(id_posible_observacion ||'|' || b_ct), ',') as idposres_cant
from (
	select count(*) as b_ct, nombrearea, nombre_examen, id_posible_observacion
	from sec_detallesolicitudestudios t01
	join lab_conf_examen_estab t03		on (t03.id=t01.id_conf_examen_estab) --53
	join mnt_area_examen_establecimiento t05 on (t05.id=t03.idexamen)
	join ctl_area_servicio_diagnostico t06 	on (t06.id=t05.id_area_servicio_diagnostico)
	 where case $fecha
            when 0 then t01.f_estado between date(to_char(current_date, 'YYYY-MM')||'-01') and '$d_fechahasta'
            else t01.f_estado between '$d_fechadesde' and '$d_fechahasta'
            end
         and case $area
            when 0 then t05.id_area_servicio_diagnostico >=1
            else t05.id_area_servicio_diagnostico=$idarea
            end
         and case $exa
            when 0 then t03.id >=1
            else t03.id in ($examenes)
            end
         and t01.id_estado_rechazo =$idestrecha
        group by nombre_examen, nombrearea, id_posible_observacion
        order by nombrearea,nombre_examen, id_posible_observacion) t
         group by nombrearea, nombre_examen
         order by nombrearea, nombre_examen;";
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
 	public function prxmes($mes, $anio, $lugar, $idarea){
           $con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
//	$sql = "select distinct(t02.idexamen) as id_pruebadetsol
//               from sec_detallesolicitudestudios 	t01
//               join lab_resultados 			t02 on (t01.id=t02.iddetallesolicitud)
//               join ctl_establecimiento		t03 on (t03.id=t02.idestablecimiento)
//               where  extract('year' from fecha_resultado)=$anio
//               and extract('month' from fecha_resultado)=$mes
//               and estadodetalle in (6,7)
//               and t02.idestablecimiento=$lugar
//               order by t02.idexamen";
	$sql = " select distinct(t02.idexamen) as id_pruebadetsol, t06.idestandar
               from sec_detallesolicitudestudios 	t01
               join lab_resultados 			t02 on (t01.id=t02.iddetallesolicitud)
               join ctl_establecimiento			t03 on (t03.id=t02.idestablecimiento)
               join lab_conf_examen_estab		t04 on (t04.id=t02.idexamen)
               join mnt_area_examen_establecimiento	t05 on (t05.id=t04.idexamen)
               join ctl_examen_servicio_diagnostico	t06 on (t06.id=t05.id_examen_servicio_diagnostico)
               where  extract('year' from fecha_resultado)=$anio
               and extract('month' from fecha_resultado)=$mes
               and estadodetalle in (6,7)
               and t02.idestablecimiento=$lugar
               and (case  $idarea
               when 0 then id_area_servicio_diagnostico >0
               else id_area_servicio_diagnostico =$idarea
               end)
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
//       $sql = "select t01.id, t03.idestandar, t03.descripcion
//            from lab_conf_examen_estab t01
//            join mnt_area_examen_establecimiento t02 on (t02.id=t01.idexamen)
//            join ctl_examen_servicio_diagnostico t03 on (t03.id=t02.id_examen_servicio_diagnostico)
//            where t01.id =$id";
           $result =  @pg_query($sql);
       if (!$result)
         return false;
       else
         return $result;
      }
   }

//Funcion utilizada para el tabulador para resultado
 	public function prxdia($idpr, $idcod, $dia, $mes, $anio, $lugar){
           $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
	$sql = "select count (case when id_codigoresultado=$idcod then 'uno' else null end) as res
               from lab_resultado_metodologia t01
               join lab_examen_metodologia t02 on (t02.id=t01.id_examen_metodologia)
               join sec_detallesolicitudestudios t03 on (t03.id=t01.id_detallesolicitudestudio)
               where id_conf_exa_estab=$idpr
               and extract('year' from fecha_resultado)=$anio
               and extract('month' from fecha_resultado)=$mes
               and extract('day' from fecha_resultado)=$dia
               and estadodetalle in (6,7)
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
   public function prxservicio($idpr, $lugar, $dia, $mes, $anio){
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
   where estadodetalle in (6,7)
   --and id_servicio_externo_estab is null
   and t00.idestablecimiento=$lugar
   and id_conf_examen_estab=$idpr
   and extract('year' from fecha_resultado)=$anio
   and extract('month' from fecha_resultado)=$mes
   and extract('day' from fecha_resultado)=$dia";
    //   echo '<br>'.$sql.'<br/>';
	$result= pg_query($sql);
	if (!$result)
		return false;
	else
		return $result;
	}//fin de la funcion consultarTipoResultado
 }

    //Funcion utilizada para el tabulador para Servicio d eProcedencia  de referidos externos
   public function prxservicioref($idpr, $lugar, $dia, $mes, $anio){
         $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
   $sql = "select count (distinct(t0c.id)) as cuatro
         from sec_detallesolicitudestudios t00
         join sec_solicitudestudios t01 on (t01.id=t00.idsolicitudestudio)
         join lab_resultado_metodologia t0c on (t00.id=t0c.id_detallesolicitudestudio)
         where estadodetalle in (6,7)
         and t00.idestablecimiento=$lugar
         and  id_dato_referencia is not null
         and extract('year' from fecha_resultado)=$anio
         and extract('month' from fecha_resultado)=$mes|
         and extract('day' from fecha_resultado)=$dia
         and id_conf_examen_estab=$idpr";
    //   echo '<br>'.$sql.'<br/>';
	$result= pg_query($sql);
	if (!$result)
		return false;
	else
		return $result;
	}//fin de la funcion consultarTipoResultado
 }

    //Funcion utilizada para el tabulador para Servicio d eProcedencia
 public function pruebatotallab($idpr, $mes, $anio, $lugar){
            $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
	$sql = "select count(*) as total
         from lab_resultado_metodologia t01
         join sec_detallesolicitudestudios t02 on (t02.id=t01.id_detallesolicitudestudio)
         where estadodetalle in (6,7)
         and extract('year' from fecha_resultado)=$anio
         and extract('month' from fecha_resultado)=$mes
         and id_conf_examen_estab= $idpr
         and idestablecimiento=$lugar;";
    //   echo '<br>'.$sql.'<br/>';
	$result= pg_query($sql);
	if (!$result)
		return false;
	else
		return $result;
	}//fin de la funcion consultarTipoResultado
      }


   //Fin funcion Postgres



















//
//
//
//    function LeerCodigosResultados(){
//	$con = new ConexionBD;
//            if($con->conectar()==true)
//	{ $query="select IdResultado from lab_codigosresultados";
//			$result = @mysql_query($query);
//			if (!$result)
//			return false;
//			else
//			return $result;
//		}
//     }
//
//    function LeerProcedencias(){
//	$con = new ConexionBD;
//		if($con->conectar()==true)
//		{ $query="SELECT IdProcedencia,NombreServicio
//			  FROM mnt_servicio
//			  WHERE IdTipoServicio='CON' ORDER BY IdProcedencia";
//			$result = @mysql_query($query);
//			if (!$result)
//				return false;
//			else
//				return $result;
//		}
//
//     }
//
//     function ExamenesxArea($IdArea,$lugar){
//        $con = new ConexionBD;
//	//usamos el metodo conectar para realizar la conexion
//	if($con->conectar()==true){
//        	$query ="";
//
//                 $result = @mysql_query($query);
//            if (!$result)
//                return false;
//            else
//                 return $result;
//
//     }
//
//     }
//
//     function NumeroDeProcedencias(){
//	//creamos el objeto $con a partir de la clase ConexionBD
//      $con = new ConexionBD;
//	//usamos el metodo conectar para realizar la conexion
//	if($con->conectar()==true){
//        	$query ="SELECT IdProcedencia
//                	  FROM mnt_servicio
//			  WHERE IdTipoServicio='CON' OR IdTipoServicio='REF' ORDER BY IdProcedencia";
//		$numreg = mysql_num_rows(mysql_query($query));
//		 if (!$numreg )
//		   return false;
//		 else
//		   return $numreg ;
//	   }
//	}
//
// function NumeroDeCodigos(){
//   //creamos el objeto $con a partir de la clase ConexionBD
//   $con = new ConexionBD;
//   //usamos el metodo conectar para realizar la conexion
//   if($con->conectar()==true){
//     $query ="select IdResultado from lab_codigosresultados";
//	 $numreg = mysql_num_rows(mysql_query($query));
//         //echo $numreg;
//	 if (!$numreg )
//	   return false;
//	 else
//         //  echo $numreg;
//	   return $numreg ;
//   }
//  }
//
//
//  function NumeroDeExamenes($IdArea,$lugar){
//     //creamos el objeto $con a partir de la clase ConexionBD
//   $con = new ConexionBD;
//   //usamos el metodo conectar para realizar la conexion
//   if($con->conectar()==true){
//     $query ="SELECT *
//              FROM lab_examenes
//              INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
//              WHERE IdArea='$IdArea' AND IdEstablecimiento=$lugar AND Condicion='H'";
//     $numreg = mysql_num_rows(mysql_query($query));
//         //echo $numreg;
//     if (!$numreg )
//	return false;
//     else
//         //  echo $numreg;
//        return $numreg ;
//   }
//  }
//
//  // consulta las areas de la BD
// function consultaractivas($lugar){
//   //creamos el objeto $con a partir de la clase ConexionBD
//   $con = new ConexionBD;
//   //usamos el metodo conectar para realizar la conexion
//   if($con->conectar()==true){
//     $query = "SELECT lab_areas.IdArea,NombreArea
//	       FROM lab_areas
//	       INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea= lab_areasxestablecimiento.IdArea
//               WHERE lab_areasxestablecimiento.Condicion='H' AND lab_areas.Administrativa='N'
//	       AND lab_areasxestablecimiento.IdEstablecimiento=$lugar
//	       ORDER BY NombreArea";
//	 $result = @mysql_query($query);
//	 if (!$result)
//	   return false;
//	 else
//	   return $result;
//   }
//  }
//
//
//
//
//	 //FUNCION PARA MOSTRAR DATOS DE BUSQUEDA
// function BuscarExamenesporCodigo($query_search)
// {
//  //creamos el objeto $con a partir de la clase ConexionBD
//	  $con = new ConexionBD;
//	 //usamos el metodo conectar para realizar la conexion
//	 if($con->conectar()==true){
//	    $query = $query_search;
//	    $result = @mysql_query($query);
//	    if (!$result)
//		   return false;
//	    else
//		   return $result;
//	 }
//
// }
//
// function ContarDatos($query_search){
//       $con = new ConexionBD;
//	 //usamos el metodo conectar para realizar la conexion
//	 if($con->conectar()==true){
//	    $query = $query_search;
//	     $numreg = mysql_num_rows(mysql_query($query));
//         //echo $numreg;
//             if (!$numreg )
//                return 0;
//            else
//              //  echo $numreg;
//                 return $numreg ;
//            }
//
// }
//
//function consultarareas($lugar){
//    $con = new ConexionBD;
//    if($con->conectar()==true){
//     $query="SELECT lab_areas.IdArea,NombreArea FROM lab_areas
//             INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
//	     WHERE Condicion='H' AND IdEstablecimiento=$lugar
//	     ORDER BY  NombreArea ASC";
//	 $dt = mysql_query($query) or die('La consulta fall&oacute;:' . mysql_error());
//	}
//	return $dt;
//}
//
//function consultaSubservicios($procedencia){
//$con = new ConexionBD;
//	if($con->conectar()==true){
//	  $query="SELECT IdSubServicio,NombreSubservicio,IdServicio FROM mnt_subservicio
//	  WHERE IdServicio='$procedencia'";
//	  $dt = mysql_query( $query) or die('La consulta fall&oacute;:' . mysql_error());
//	}
//	return $dt;
//}
//
//
//function NombreServicio($procedencia){
//$con = new ConexionBD;
//	if($con->conectar()==true){
//	  $query="select NombreServicio from mnt_servicio where IdServicio='$procedencia'";
//	  $dt = mysql_query( $query) or die('La consulta fall&oacute;:' . mysql_error());
//	}
//	return $dt;
//}
//
//function subserviciosxservicio($servicio,$ffechaini,$ffechafin,$cadena,$lugar){
//$con = new ConexionBD;
//	if($con->conectar()==true){
//	  $query="SELECT $cadena NombreSubServicio as origen,
//sum(if(sec_detallesolicitudestudios.IdExamen<>'',1,0)) as total ,mnt_servicio.IdServicio, mnt_servicio.NombreServicio
//from sec_detallesolicitudestudios
//INNER JOIN lab_resultados ON sec_detallesolicitudestudios.IdDetalleSolicitud=lab_resultados.IdDetalleSolicitud
//INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
//INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
//INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
//INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
//INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
//INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio= mnt_servicio.IdServicio
//INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
//WHERE sec_detallesolicitudestudios.EstadoDetalle='RC' AND lab_areasxestablecimiento.Condicion='H'
//AND lab_areasxestablecimiento.IdEstablecimiento=$lugar
//AND (lab_resultados.FechaHoraReg >='$ffechaini' AND lab_resultados.FechaHoraReg <='$ffechafin')
//AND mnt_subservicio.IdServicio='$servicio'
//GROUP BY sec_historial_clinico.IdSubServicio ORDER BY sec_historial_clinico.IdSubServicio";
// $dt = mysql_query( $query) or die('La consulta fall&oacute;:' . mysql_error());
//	}
//	return $dt;
//
//}
//
//function DatosGenerales($lugar,$IdArea){
//        $con = new ConexionBD;
//	if($con->conectar()==true){
//	  $query="SELECT Nombre,Nombrearea
//                FROM `lab_areasxestablecimiento`
//                INNER JOIN lab_areas ON lab_areasxestablecimiento.IdArea=lab_areas.IdArea
//                INNER JOIN mnt_establecimiento ON lab_areasxestablecimiento.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
//                WHERE lab_areasxestablecimiento.IdArea='$IdArea' AND lab_areasxestablecimiento.IdEstablecimiento=$lugar";
//          $dt = mysql_query( $query) or die('La consulta fall&oacute;:' . mysql_error());
//	}
//	return $dt;
//}
//
//
//function CodigosEstardarxarea($IdArea,$lugar)
// {	$con = new ConexionBD;
//	if($con->conectar()==true){
//            $sqlText = "SELECT IdEstandar FROM lab_examenes
//	    INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
//	    WHERE IdArea='$IdArea' AND lab_examenesxestablecimiento.Condicion = 'H'
//            AND IdEstablecimiento=$lugar ORDER BY IdEstandar ASC";
//            $dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
//	}
//	return $dt;
// }
//


}//CLASE
?>
