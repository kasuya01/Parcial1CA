<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<?php

include_once("../../../Conexion/ConexionBD.php"); //Agregamos el Archivo con las clases y funciones a utilizar
include_once ("../../../encabezado.php");
$nivel = $_SESSION['NIVEL'];
$lugar = $_SESSION['Lugar'];
$area  = $_SESSION['Idarea'];
$corr  = $_SESSION['Correlativo'];
$cod   = $_SESSION['IdEmpleado'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
include_once $ROOT_PATH.'/public/css.php';
/* Administrador Regional*/
echo '<!-- Start Required XML Menu markup for head tag -->
	<link href="../../../Menu/xm-style.css" rel="stylesheet" type="text/css">
	<script src="../../../Menu/xm-menu.js" type="text/javascript"></script>
      <!-- End Required XML Menu markup for head tag -->
	<table width="100%" border="0" bgcolor="#FFFFFF">           
            <tr >
                <td bgcolor="#002157" colspan="2">
                    <!-- Start Required XML Menu markup for body tag -->
			<div id="id_xm"></div>
			<script type="text/javascript">new XmlMenu("id_xm", "../../../Menu/xm-data52.xml")</script>
                    <!-- End Required XML Menu markup for body tag -->
		</td>
            </tr>
        </table>';
?>
