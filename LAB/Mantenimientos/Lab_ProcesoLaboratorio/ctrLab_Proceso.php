<?php

session_start();
$usuario = $_SESSION['Correlativo'];
$lugar   = $_SESSION['Lugar'];
$area    = $_SESSION['Idarea'];
include_once("clsLab_Proceso.php");

$opcion = $_POST['opcion'];

//actualiza los datos del empleados
$objdatos = new clsLab_Proceso();

switch ($opcion) {
    case 1:
        $query = $objdatos->getLabProceso();
        if($query !== false) {
            $jsonresponse['status'] = true;
            $jsonresponse['data']   = pg_fetch_all($query);
        } else {
            $jsonresponse['status'] = false;
        }
        echo json_encode($jsonresponse);
        break;
    case 2:
        $array = array();
        foreach ($_POST as $key => $value) {
            if($key !== 'opcion') {
                $array[] = $value;
            }
        }

        $query = $objdatos->updateProcLabEstablecimiento($lugar, $array);

        if($query !== false) {
            $jsonresponse['status'] = true;
        } else {
            $jsonresponse['status'] = false;
        }
        echo json_encode($jsonresponse);
        break;
}
?>
