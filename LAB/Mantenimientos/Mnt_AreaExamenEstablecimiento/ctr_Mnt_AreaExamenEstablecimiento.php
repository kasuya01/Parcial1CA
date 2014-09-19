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

	$result = $mntAreaExamenEstab->getAreaExamenEstablecimiento($parameters['post']['idarea'], $parameters['lugar']);

	if($result !== false) {
		/*$result = array();
		foreach ($arr as $data) {
			$id = $data['id'];
			if (isset($result[$id])) {
			    $result[$id][] = $data;
			} else {
			    $result[$id] = array($data);
			}
		}*/

        $jsonresponse['status'] = true;
        $jsonresponse['data']   = pg_fetch_all($result);
    } else {
    	$jsonresponse['status'] = false;
    }

	echo json_encode($jsonresponse);
	return;
}