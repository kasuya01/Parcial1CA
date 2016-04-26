<?php
include_once("../../../Conexion/ConexionBD.php");

//implementamos la clase lab_areas
class cls_Mnt_confexatipolab {

    function cls_Mnt_confexatipolab() {
    }

    function getconfexatipolab() {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
        	$query = "select distinct t2.id, t2.nombre
                    from ctl_establecimiento	t1
                    join ctl_tipo_establecimiento	t2 on (t2.id=t1.id_tipo_establecimiento)
                    join lab_establecimiento_tipo_laboratorio	t3 on (t1.id=t3.id_establecimiento)
                    where t1.activo=true
                    order by  nombre;";

			$result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }
    function getconfexatipolabconf($id) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
        	$query = "select t1.*, t2.id as idestab, t2.id_tipo_establecimiento
                    from lab_conf_establecimiento_tipo_laboratorio 	t1
                    join ctl_establecimiento			t2 on (t2.id=t1.id_establecimiento)
                    where t1.activo=true
                    and (fecha_fin > current_date or fecha_fin is null)
                    and id_tipo_establecimiento=$id;";
                    echo $query;
			$result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }
    function getconfestab($idt) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
        	$query = "SELECT t1.id as idestab, t1.nombre as nombrestab, t3.id as idestipolab
                    from ctl_establecimiento	t1
                    join ctl_tipo_establecimiento	t2 on (t2.id=t1.id_tipo_establecimiento)
                    join lab_establecimiento_tipo_laboratorio	t3 on (t1.id=t3.id_establecimiento)
                    where t1.activo=true
                    and id_tipo_establecimiento=$idt
                    and configurado is null
                    order by  t1.nombre;";

			$result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

function setfechafin(){
    $con = new ConexionBD;
    if ($con->conectar() == true) {
        $query0="UPDATE lab_conf_establecimiento_tipo_laboratorio
            SET fecha_fin=current_date,
            activo=false
            WHERE fecha_fin is null;";
        $result0=@pg_query($query0);
        if (!$result0)
            return false;
        else
            return $result0;
    }
}



function setconfexatipolab($lugar, $elementos, $usuario) {
    	$con = new ConexionBD;

        if ($con->conectar() == true) {

            $query1="SELECT * from lab_conf_establecimiento_tipo_laboratorio;";
            $result1=@pg_query($query1);
            $b=0;
            while ($row1= pg_fetch_array($result1)) {
                // var_dump('Viene de pantalla: '.$elementos[0].' - '.$elementos[1]);
                // var_dump('Viene de BD: '.$row1['id_establecimiento'].' - '.$row1['id_establecimiento_tipo_laboratorio']);
                // var_dump($row1['id_establecimiento'].' - '.$elementos[0]);
                // var_dump($row1['id_establecimiento_tipo_laboratorio'].' - '.$elementos[1].'----------');
                if (($row1['id_establecimiento']==$elementos[0]) && ($row1['id_establecimiento_tipo_laboratorio']==$elementos[1])){
                    $query2="UPDATE lab_conf_establecimiento_tipo_laboratorio
                            SET fecha_fin=null,
                            activo=true
                            WHERE fecha_fin is not null
                            AND id_establecimiento=$elementos[0]
                            AND id_establecimiento_tipo_laboratorio=$elementos[1];";
                    $b=1;
                }

            }
            if ($b==0){
                $query2 = "INSERT INTO lab_conf_establecimiento_tipo_laboratorio (id_establecimiento, id_establecimiento_tipo_laboratorio, fecha_inicio, fecha_fin, activo)
                VALUES ($elementos[0], $elementos[1],current_date, null, true);";
            }
            //for ($i=0;$i<count($elementos);$i++){


             $result2 = @pg_query($query2);

        // }
        if (!$result2)
            return false;
        else
            return $result2;
        }
    }
}
?>
