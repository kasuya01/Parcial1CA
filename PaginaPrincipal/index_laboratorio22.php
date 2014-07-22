<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<?php
//session_start();
include_once("../../../Conexion/ConexionBD.php"); //Agregamos el Archivo con las clases y funciones a utilizar  
$nivel=$_SESSION['NIVEL'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$corr=$_SESSION['Correlativo'];	
$cod=$_SESSION['IdEmpleado'];	
// Creamos un objeto Conexion, Pacie
$Conexion= new ConexionBD;   
$Conectar=$Conexion->Conectar();
$SQL= "SELECT   ctl_establecimiento.nombre,
                        mnt_empleado.nombreempleado 
                FROM    fos_user_user 
                    INNER JOIN mnt_empleado ON (fos_user_user.id_empleado=mnt_empleado.id 
                    AND fos_user_user.id_establecimiento=mnt_empleado.id_establecimiento) 
                    INNER JOIN ctl_establecimiento ON mnt_empleado.id_establecimiento=ctl_establecimiento.id 
                WHERE mnt_empleado.id='$cod' AND mnt_empleado.id_establecimiento=$lugar AND  fos_user_user.modulo='LAB'";



	
	$Resultado = pg_query($SQL) or die('La consulta fall&oacute;: ' . pg_error());
	$Rows = pg_fetch_array($Resultado);
	$NombreEmpleado=$Rows['nombreempleado'];
        $Establecimiento=$Rows['nombre'];
	//$IdTipoEmpleado=$Rows['IdTipoEmpleado'];	
	//$Correlativo=$Rows['Correlativo'];
		
	//$_SESSION['correlativo']=$Correlativo;
	echo '<!-- Start Required XML Menu markup for head tag -->
	<link href="../../../Menu/xm-style.css" rel="stylesheet" type="text/css">
	<script src="../../../Menu/xm-menu.js" type="text/javascript"></script>
	<!-- End Required XML Menu markup for head tag -->';
		  
	echo '<center>
	      <table width="100%" border="0" bgcolor="#FFFFFF">
	      <tr>
		 <td><img id="Image1" style="WIDTH: 204px; HEIGHT: 99px" height="86" src="../../../Imagenes/paisanito.gif" width="210" name="Image1"></td> 
		 <td style="vertical-align:top">
 		 <h2 align="center" >Ministerio de Salud <br> Sistema de Informaci&oacute;n de Atenci&oacute;n de Pacientes<br><br>'.htmlentities( $Establecimiento).'  <br><br>              
  <font face="Verdana" size="2" align="center">Usuario:. <font color="#ff0000" size="2"><strong>'.htmlentities($NombreEmpleado).'</strong></font></font></h2>
  </td>
  </tr>
</center><tr ><td bgcolor="#002157" colspan="2">
';
	
echo '	<!-- Start Required XML Menu markup for body tag -->
			<div id="id_xm"></div>
			<script type="text/javascript">new XmlMenu("id_xm", "../../../Menu/xm-data22.xml")</script>
			<!-- End Required XML Menu markup for body tag -->
		</tr></td></table>';

?>
