<?php

include_once("../../../Conexion/ConexionBD.php");

//implementamos la clase lab_areas
class cls_Mnt_AreaExamenEstablecimiento {

    function cls_Mnt_AreaExamenEstablecimiento() {
    }

    function getAreaExamenEstablecimiento($idarea, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
        	$query = "WITH area_grupo_examen_atencion AS (
								SELECT *
							  	FROM (
								  	SELECT ti01.id AS id_grupo,
									  	   ti01.idgrupo AS codigo_grupo,
									  	   ti01.nombregrupo AS nombre_grupo,
									  	   ti02.id AS id_examen,
									  	   ti02.idestandar AS codigo_examen,
									  	   ti02.descripcion AS nombre_examen
								  	FROM lab_estandarxgrupo 		   ti01
									INNER JOIN ctl_examen_servicio_diagnostico ti02 ON (ti01.id = ti02.idgrupo)
									INNER JOIN ctl_atencion 		   ti03 ON (ti03.id = ti02.id_atencion)
									WHERE ti03.codigo_busqueda = 'DCOLAB' AND ti01.activo = true AND ti02.activo = true
							  	) AS tp01, (
									SELECT ti04.id AS id_area,
										   ti04.idarea AS codigo_area,
										   ti04.nombrearea AS nombre_area
									FROM ctl_area_servicio_diagnostico ti04
									INNER JOIN ctl_atencion 	   ti05 ON (ti05.id = ti04.id_atencion)
									WHERE ti05.codigo_busqueda = 'DCOLAB'
							  	) AS tp02
						   )
						   SELECT t01.id_area,
							      t01.codigo_area,
							      t01.nombre_area,
							      t01.id_grupo,
							      t01.codigo_grupo,
							      t01.nombre_grupo,
							      t01.id_examen,
							      t01.codigo_examen,
							      t01.nombre_examen,
							      CASE WHEN t02.activo IS NULL  THEN 'na'
								      WHEN t02.activo = true  THEN 'true'
								      WHEN t02.activo = false THEN 'false'
							      END AS activo
						   FROM area_grupo_examen_atencion t01
						   LEFT OUTER JOIN mnt_area_examen_establecimiento t02 ON (t01.id_area = t02.id_area_servicio_diagnostico AND t01.id_examen = t02.id_examen_servicio_diagnostico AND t02.id_establecimiento = $lugar)
						   WHERE t01.id_area = $idarea
						   ORDER BY t01.nombre_area, t01.nombre_grupo, SUBSTRING(t01.codigo_examen FROM '[a-zA-Z]+'), TO_NUMBER(SUBSTRING(t01.codigo_examen FROM '[0-9]+'), '99')";

			$result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function setAreaExamenEstastablecimiento($idarea, $lugar, $elementos, $usuario) {
    	$con = new ConexionBD;

        if ($con->conectar() == true) {

        if(isset($elementos['update'])) {
            $update_exam = implode(",",$elementos['update']);
        } else {
           $update_exam = "";
        }

            if($update_exam !== "") {
                $query1 = "UPDATE mnt_area_examen_establecimiento SET activo = true WHERE id_examen_servicio_diagnostico IN ($update_exam) AND id_area_servicio_diagnostico = $idarea AND id_establecimiento = $lugar;
                           UPDATE mnt_area_examen_establecimiento SET activo = false WHERE id_examen_servicio_diagnostico NOT IN ($update_exam) AND id_area_servicio_diagnostico = $idarea AND id_establecimiento = $lugar;";
            } else {
                $query1 = "UPDATE mnt_area_examen_establecimiento SET activo = false WHERE id_area_servicio_diagnostico = $idarea AND id_establecimiento = $lugar";
            }

            $result1 = @pg_query($query1);

            if(isset($elementos['insert'])) {
                if(count($elementos['insert']) > 0) {
                    $query2 = "";
                    foreach ($elementos['insert'] as $key => $idexam) {
                            $query2 = $query2."INSERT INTO mnt_area_examen_establecimiento(id_area_servicio_diagnostico, id_examen_servicio_diagnostico, id_establecimiento, id_usuario_reg, fecha_hora_reg, activo) VALUES($idarea, $idexam, $lugar, $usuario, NOW(), true);";
                    }

                    $result2 = @pg_query($query2);

                    if (!$result1 || !$result2)
                            return false;
                        else
                            return $result1;

                } else {
                    if (!$result1)
                            return false;
                        else
                            return $result1;
                }
            }
        }
    }
}
?>
