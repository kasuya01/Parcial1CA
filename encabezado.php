<?php //session_start();
include_once("Conexion/ConexionBD.php"); //Agregamos el Archivo con las clases y funciones a utilizar
$nivel = $_SESSION['NIVEL'];
$lugar = $_SESSION['Lugar'];
$area  = $_SESSION['Idarea'];
$corr  = $_SESSION['Correlativo'];
$cod   = $_SESSION['IdEmpleado'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
include_once $ROOT_PATH.'/public/css.php';
// Creamos un objeto Conexion, Paciente
$Conexion = new ConexionBD;
$Conectar = $Conexion->Conectar();
 $SQL = "SELECT t03.nombre,
               t02.nombreempleado
        FROM fos_user_user             t01
        INNER JOIN mnt_empleado        t02 ON (t02.id = t01.id_empleado AND t01.id_establecimiento = t02.id_establecimiento)
        INNER JOIN ctl_establecimiento t03 ON (t03.id = t02.id_establecimiento)
        WHERE t02.id = '$cod'
            AND t02.id_establecimiento = $lugar
            AND t01.modulo = 'LAB'";

$Resultado = pg_query($SQL);

$Rows = pg_fetch_array($Resultado);

$NombreEmpleado  = $Rows['nombreempleado'];
$Establecimiento = $Rows['nombre'];

echo '<center>
      <div id="bannerlogo">
        <img id="img-large" src="/Laboratorio/Imagenes/header-SUIS.png">
        <img id="img-small" src="/Laboratorio/Imagenes/header-SUIS_small.png" >
   </div>';
echo '<table width="100%" border="0" bgcolor="#FFFFFF">
                     <tr>
                        <td style="vertical-align:top">
 		<h2 align="center" > ' . htmlentities($Establecimiento) . '</br>
                <font face="Verdana" size="2" align="center"><br>Usuario:</b> <font color="blue" size="2"><strong>' . htmlentities($NombreEmpleado) . '</strong></font></font></h2>
            </td>
                     </tr>
     </table>';

?>
