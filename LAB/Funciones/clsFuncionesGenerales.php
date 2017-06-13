<?php
include_once("../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsFuncionesGenerales {

   //constructor
   function clsFuncionesGenerales() {

   }
function LlenarProcedencia ($idestablecimiento){
    $con = new ConexionBD;
    if ($con->conectar() == true) {

       $consulta = "SELECT mnt_area_mod_estab.id as codigo,
            CASE WHEN id_servicio_externo_estab IS NOT NULL
                    THEN mnt_servicio_externo.abreviatura ||'--'  || ctl_area_atencion.nombre
                    ELSE       ctl_modalidad.nombre ||'--' || ctl_area_atencion.nombre
                    END as nombre
            FROM mnt_area_mod_estab
            INNER JOIN  ctl_area_atencion  on (ctl_area_atencion.id = mnt_area_mod_estab.id_area_atencion AND (ctl_area_atencion.id_tipo_atencion=1 OR ctl_area_atencion.id_tipo_atencion=4))
            INNER JOIN  mnt_modalidad_establecimiento ON mnt_modalidad_establecimiento.id=mnt_area_mod_estab.id_modalidad_estab
            INNER JOIN ctl_modalidad ON ctl_modalidad.id = mnt_modalidad_establecimiento.id_modalidad
            LEFT JOIN mnt_servicio_externo_establecimiento ON (mnt_servicio_externo_establecimiento.id = mnt_area_mod_estab.id_servicio_externo_estab)
            LEFT JOIN mnt_servicio_externo ON (mnt_servicio_externo.id = mnt_servicio_externo_establecimiento.id_servicio_externo)
            WHERE mnt_area_mod_estab.id_establecimiento=$idestablecimiento
            ORDER by ctl_modalidad.nombre,mnt_servicio_externo.nombre,ctl_area_atencion.nombre";

       $resultado = pg_query($consulta);
    }
    return $resultado;
}//Fin llenar Procedencia


function LlenarCmbServ($IdServ,$lugar){
$con = new ConexionBD;
 $condicionAmbiente="";
 $unionAmbiente='';
	if($con->conectar()==true){
        $sqlText = "SELECT id_area_atencion FROM mnt_area_mod_estab where id=$IdServ AND id_establecimiento=$lugar";
        $dt = pg_fetch_array(pg_query($sqlText));
        $IdAreaAtencion=$dt[0];
            if ($IdAreaAtencion==3){
                $condicionAmbiente=' AND mnt_3.nombre_ambiente IS NOT NULL';
                $unionAmbiente="UNION
                    SELECT mnt_3.id,cat.nombre
                    FROM  ctl_atencion cat
                              JOIN mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion)
                              JOIN mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id)
                              JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion in (1,4))
                              LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id
                              LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id
                              JOIN mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab)
                              JOIN ctl_modalidad cmo on (cmo.id=mme.id_modalidad)
                    WHERE  mnt_2.id=$IdServ  AND mnt_3.id_establecimiento=$lugar
                                    AND mnt_3.id_atencion ||'-'|| mnt_3.id_area_mod_estab ||'-'||mnt_3.id_establecimiento
                                    NOT IN (SELECT id_atencion ||'-'|| id_area_mod_estab ||'-'||id_establecimiento
                                            FROM mnt_aten_area_mod_estab WHERE nombre_ambiente IS NOT NULL)";
            }
       $sqlText = "WITH tbl_servicio as (SELECT mnt_3.id,
                  CASE
                      WHEN mnt_3.nombre_ambiente IS NOT NULL
                          THEN mnt_3.nombre_ambiente
                          ELSE cat.nombre
                  END AS nombre
                  FROM  ctl_atencion cat
                          JOIN mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion)
                          JOIN mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id)
                          JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion in (1,4))
                          LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id
                          LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id
                          JOIN mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab)
                          JOIN ctl_modalidad cmo on (cmo.id=mme.id_modalidad)
                  WHERE  mnt_2.id=$IdServ $condicionAmbiente
                           AND mnt_3.id_establecimiento=$lugar
                  $unionAmbiente
                  ORDER BY 2)
                  SELECT id, nombre FROM tbl_servicio WHERE nombre IS NOT NULL";

                $dt = pg_query($sqlText) ;
	}
	return $dt;
}

}

//CLASE
?>
