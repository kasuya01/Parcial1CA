<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<?php
include_once("../../../Conexion/ConexionBD.php"); //Agregamos el Archivo con las clases y funciones a utilizar

$nivel = $_SESSION['NIVEL'];
$lugar = $_SESSION['Lugar'];
$area  = $_SESSION['Idarea'];
$corr  = $_SESSION['Correlativo'];
$cod   = $_SESSION['IdEmpleado'];

// Creamos un objeto Conexion, Pacie
$Conexion = new ConexionBD;
$Conectar = $Conexion->Conectar();
 $SQL = "SELECT t03.nombre,
               t02.nombreempleado
        FROM fos_user_user             t01
        INNER JOIN mnt_empleado        t02 ON (t02.id = t01.id_empleado AND t01.id_establecimiento = t02.id_establecimiento)
        INNER JOIN ctl_establecimiento t03 ON (t03.id = t02.id_establecimiento)
        WHERE t02.id = '$cod' AND t02.id_establecimiento = $lugar AND t01.modulo='LAB'";


$Resultado = pg_query($SQL) or die('La consulta fall&oacute;: ' . pg_error());
$Rows = pg_fetch_array($Resultado);

$NombreEmpleado = $Rows['nombreempleado'];
$Establecimiento = $Rows['nombre'];

echo '<!-- Start Required XML Menu markup for head tag -->
	<link href="../../../Menu/xm-style.css" rel="stylesheet" type="text/css">
	<script src="../../../Menu/xm-menu.js" type="text/javascript"></script>
      <!-- End Required XML Menu markup for head tag -->
      <center>
	<table width="100%" border="0" bgcolor="#FFFFFF">
            <tr>
		<td><img id="Image1" style="width: 181px; height: 76px;" height="86" Src="../../../Imagenes/paisanito.png" width="210" name="Image1"></td>
		<td style="vertical-align:top">
                    <h2 align="center" >Ministerio de Salud  <br> Sistema de Informaci&oacute;n de Atenci&oacute;n de Pacientes<br><br>' . htmlentities($Establecimiento) . '  <br><br>
                    <font face="Verdana" size="2" align="center">Usuario:. <font color="#ff0000" size="2"><strong>' . htmlentities($NombreEmpleado) . '</strong></font></font></h2>
                </td>
            </tr>
            </center>
            <tr>
                <td bgcolor="#002157" colspan="2">
                    <!-- Start Required XML Menu markup for body tag -->
                        <div id="id_xm"></div>
			<script type="text/javascript">new XmlMenu("id_xm", "../../../Menu/xm-data2.xml")</script>
                    <!-- End Required XML Menu markup for body tag -->
		</td>
            </tr>
        </table>';
?>
