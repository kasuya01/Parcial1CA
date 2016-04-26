<?php
session_start();
$usuario = $_SESSION['Correlativo'];
$lugar   = $_SESSION['Lugar'];
$area    = $_SESSION['Idarea'];

include_once("../Lab_Areas/clsLab_Areas.php");
include_once("cls_Mnt_confexatipolab.php");
$mntconfexatipolab = new cls_Mnt_confexatipolab;

if(isset($_POST['accion'])) {
	$accion = $_POST['accion'];

	$parameters = array('lugar'     => $lugar,
						'usuario'   => $usuario,
						'user_area' => $area);

	if(isset($_POST['parameters'])) {
		$parameters['post'] = $_POST['parameters'];
	}

	return call_user_func($accion, $parameters);
} else {
	$opcion = $_POST['opcion'];
	switch ($opcion) {
		default:
		break;
	}
}

function getLabAreas($parameters) {
	$labAreas = new clsLab_Areas;
	$result = $labAreas->consultaractivas($parameters['lugar']);

	if($result !== false) {
        $jsonresponse['status'] = true;
        $jsonresponse['data']   = pg_fetch_all($result);
    } else {
    	$jsonresponse['status'] = false;
    }

	echo json_encode($jsonresponse);
	return;
}

function getconfexatipolab($parameters) {
	global $mntconfexatipolab;

	$arr = $mntconfexatipolab->getconfexatipolab($parameters['idarea'], $parameters['lugar']);

	if($arr !== false) {

		$result = array();
		$arr = pg_fetch_all($arr);
		foreach ($arr as $row) {
			$id = $row['id'];

			if( ! isset( $result[$id] ) ){
				$result[$id] = array();
				$result[$id]['id'] 	   = $id;
				//$result[$id]['codigo'] = $row['codigo_area'];
				$result[$id]['nombre'] = $row['nombre_area'];
			}


			// if( ! isset($result[$id]['grupos']) )
			// 	$result[$id]['grupos'] = array();
			//
			// $result[$id]['grupos'] = addElementToGroups( $result[$id]['grupos'], array($row['id_grupo'], $row['codigo_grupo'], $row['nombre_grupo']), array($row['id_examen'], $row['codigo_examen'], $row['nombre_examen'], $row['activo'] ) );
		}

        $jsonresponse['status'] = true;
        $jsonresponse['data']   = $result;
    } else {
    	$jsonresponse['status'] = false;
    }

	echo json_encode($jsonresponse);
	return;
}


function updateRegisters($parameters) {
	global $mntconfexatipolab;

	$array = array();
 	$b=0;
        if ($parameters['post']['form']!==''){
		   $setfechafin = $mntconfexatipolab->setfechafin();

           foreach ($parameters['post']['form'] as $value) {
               $tmp_array = explode('_',$value['value']);
			//    $array['idest'][] = $tmp_array[0];
			//    $array['idestipolab'][] = $tmp_array[1];

             /*  if($tmp_array[1] !== 'na') {
                       $array['update'][] = $tmp_array[0];
               } else {
                       $array['insert'][] = $tmp_array[0];
               }*/
			   $query = $mntconfexatipolab->setconfexatipolab($parameters['lugar'], $tmp_array, $parameters['usuario']);

            	if ($query===false)
                  $b=1;
				// var_dump($tmp_array);
		   }
        }
        // else{
        //    $array['update']=array();
        //    $array['insert']=array();
        // }



	//exit();

    if($b !== 1) {
        $jsonresponse['status'] = true;
    } else {
        $jsonresponse['status'] = false;
    }
    echo json_encode($jsonresponse);
}
