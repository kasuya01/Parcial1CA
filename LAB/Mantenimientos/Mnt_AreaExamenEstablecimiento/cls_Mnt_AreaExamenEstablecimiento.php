<?php

include_once("../../../Conexion/ConexionBD.php");

//implementamos la clase lab_areas
class cls_Mnt_AreaExamenEstablecimiento {

    function cls_Mnt_AreaExamenEstablecimiento() {
    }

    function getAreaExamenEstablecimiento($idarea, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
        	echo $query = "SELECT t01.id AS id_grupo,
						     t01.idgrupo AS codigo_grupo,
						     t01.nombregrupo AS nombre_grupo,
						     t02.id AS id_examen,
						     t02.idestandar AS codigo_examen,
						     t02.descripcion AS nombre_examen,
						     t05.id AS id_area,
						     t05.idarea AS codigo_area,
						     t05.nombrearea AS nombre_area,
						     CASE WHEN t04.activo IS NULL  THEN 'false'
						   		WHEN t04.activo = true  THEN 'true'
						   		WHEN t04.activo = false THEN 'false'
						     END AS activo
					  FROM lab_estandarxgrupo 				   		  t01
					  INNER JOIN ctl_examen_servicio_diagnostico 	  t02 ON (t01.id = t02.idgrupo)
					  INNER JOIN ctl_atencion					   	  t03 ON (t03.id = t02.id_atencion
					  		AND t03.codigo_busqueda = 'DCOLAB')
					  LEFT OUTER JOIN mnt_area_examen_establecimiento t04 ON (t02.id = t04.id_examen_servicio_diagnostico
					  		AND t04.id_establecimiento = $lugar)
					  LEFT OUTER JOIN ctl_area_servicio_diagnostico   t05 ON (t05.id = t04.id_area_servicio_diagnostico 
							AND t05.id_atencion = (
								SELECT id 
								FROM ctl_atencion 
								WHERE codigo_busqueda = 'DCOLAB'))
					  WHERE t04.id_area_servicio_diagnostico = $idarea
					  ORDER BY nombre_grupo, nombre_examen, nombre_area";

			$result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }
}
?>