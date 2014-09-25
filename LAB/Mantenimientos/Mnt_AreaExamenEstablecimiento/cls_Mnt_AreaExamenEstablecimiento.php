<?php

include_once("../../../Conexion/ConexionBD.php");

//implementamos la clase lab_areas
class cls_Mnt_AreaExamenEstablecimiento {

    function cls_Mnt_AreaExamenEstablecimiento() {
    }

    function getAreaExamenEstablecimiento($idarea, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
        	echo $query = "WITH area_grupo_examen_atencion AS (
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
									WHERE ti03.codigo_busqueda = 'DCOLAB'
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
						   LEFT OUTER JOIN mnt_area_examen_establecimiento t02 ON (t01.id_area = t02.id_area_servicio_diagnostico AND t01.id_examen = t02.id_examen_servicio_diagnostico)
						   WHERE t01.id_area = $idarea AND t02.id_establecimiento = $lugar";

			$result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }
}
?>