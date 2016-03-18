<?php
include_once("../../../Conexion/ConexionBD.php");

//implementamos la clase lab_areas
class cls_Mnt_confestipolab {

    function cls_Mnt_confestipolab() {
    }

    function getconfestipolab($idarea, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
        	$query = "select distinct t2.id, t2.nombre
                    from ctl_establecimiento	t1
                    join ctl_tipo_establecimiento	t2 on (t2.id=t1.id_tipo_establecimiento)
                    join lab_establecimiento_tipo_laboratorio	t3 on (t1.id=t3.id_establecimiento)
                    order by  nombre;";

			$result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function setconfestipolab($idarea, $lugar, $elementos, $usuario) {
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
