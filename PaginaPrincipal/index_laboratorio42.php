<?php ?>
<link rel="stylesheet" type="text/css" href="../Themes/Cobalt/Style.css">
<?php
include_once("../../../Conexion/ConexionBD.php"); //Agregamos el Archivo con las clases y funciones a utilizar
	//$usuario=$_SESSION['user'];
	//$_SESSION['NIVEL']=$_SESSION['NIVEL'];
	$nivel=$_SESSION['NIVEL'];
        $lugar=$_SESSION['Lugar'];
	$area=$_SESSION['Idarea'];
        $corr=$_SESSION['Correlativo'];	
        $cod=$_SESSION['IdEmpleado'];
       // $ext=$_SESSION['Externo'];
// Creamos un objeto Conexion, Paciente
	$Conexion= new ConexionBD;   
	$Conectar=$Conexion->Conectar();
	$SQL= "SELECT ctl_establecimiento.nombre,mnt_empleado.nombreempleado
		FROM mnt_usuarios 
		INNER JOIN mnt_empleado
		ON (mnt_usuarios.idempleado=mnt_empleado.idempleado AND mnt_usuarios.idestablecimiento=mnt_empleado.id_establecimiento) 
		INNER JOIN ctl_establecimiento
		ON mnt_empleado.id_establecimiento=ctl_establecimiento.id_establecimiento_padre
	    WHERE mnt_empleado.idempleado='$cod' AND mnt_empleado.id_establecimiento=$lugar AND  mnt_usuarios.modulo='LAB'";
	
			$Resultado = pg_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
			$Rows = pg_fetch_array($Resultado);
			$NombreEmpleado=$Rows['NombreEmpleado'];
			$Establecimiento=$Rows['Nombre'];  			
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
 			 <h2 align="center" >Ministerio de Salud <br> Sistema de Informaci&oacute;n de Atenci&oacute;n de Pacientes<br><br>'.htmlentities($Establecimiento).'  <br><br>              
  <font face="Verdana" size="2" align="center">Usuario:. <font color="#ff0000" size="2"><strong>'.htmlentities($NombreEmpleado).'</strong></font></font></h2>
  </td>
  </tr>

</center><tr ><td bgcolor="#002157" colspan="2">';
	echo '	<!-- Start Required XML Menu markup for body tag -->
			<div id="id_xm"></div>
			<script type="text/javascript">new XmlMenu("id_xm", "../../../Menu/xm-data4.xml")</script>
			<!-- End Required XML Menu markup for body tag -->
		</tr></td></table>';


?>
