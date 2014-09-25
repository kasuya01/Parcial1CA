<?php
session_start();
$usuario = $_SESSION['Correlativo'];
$lugar   = $_SESSION['Lugar'];
$area    = $_SESSION['Idarea'];

include_once("../Lab_Areas/clsLab_Areas.php");
include_once("cls_Mnt_AreaExamenEstablecimiento.php");
$mntAreaExamenEstab = new cls_Mnt_AreaExamenEstablecimiento;

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

function getAreaExamenEstablecimiento($parameters) {
	global $mntAreaExamenEstab;

	// var_dump($parameters);exit();

	$arr = $mntAreaExamenEstab->getAreaExamenEstablecimiento($parameters['post']['idarea'], $parameters['lugar']);
	//$result = $mntAreaExamenEstab->getAreaExamenEstablecimiento($parameters['post']['idarea'], $parameters['lugar']);

	if($result !== false) {
		
		$result = array();
		$arr = pg_fetch_all($arr);
		foreach ($arr as $row) {
			$id = $row['id_area'];
			
			if( ! isset( $result[$id] ) ){
				$result[$id] = array();
				$result[$id]['id'] 	   = $id;
				$result[$id]['codigo'] = $row['codigo_area'];
				$result[$id]['nombre'] = $row['nombre_area'];
			}


			if( ! isset($result[$id]['grupos']) )
				$result[$id]['grupos'] = array();

			$result[$id]['grupos'] = addElementToGroups( $result[$id]['grupos'], array( $row['id_grupo'], $row['codigo_grupo'], $row['nombre_grupo']), array($row['id_examen'], $row['codigo_examen'], $row['nombre_examen'], $row['activo'] ) );
		}
		//var_dump($result);
        $jsonresponse['status'] = true;
        $jsonresponse['data']   = $result;
    } else {
    	$jsonresponse['status'] = false;
    }

	echo json_encode($jsonresponse);
	return;
}

function addElementToGroups($groups, $newGroup, $newExam){
	
	if( ! isset($groups[ $newGroup[0] ]) ){
		$groups[ $newGroup[0] ] = array('id'	   => $newGroup[0],
										'codigo'   => $newGroup[1],
										'nombre'   => $newGroup[2]
									   );
	}

	if( ! isset( $groups[ $newGroup[0] ]['examenes'] ) )
		$groups[ $newGroup[0] ]['examenes'] = array();

	$groups[ $newGroup[0] ]['examenes'] = addElementToExams($groups[ $newGroup[0] ]['examenes'], $newExam);

	return $groups;
}

function addElementToExams($exams, $newExam){
	if( ! isset($exams[ $newExam[0] ]) ){
		$exams[ $newExam[0] ] = array('id'	   => $newExam[0],
									  'codigo' => $newExam[1],
									  'nombre' => $newExam[2],
									  'activo' => $newExam[3]
									 );
	}

	return $exams;
}