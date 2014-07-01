<link rel="stylesheet" type="text/css" href="./Themes/Cobalt/Style.css">
<?php
session_start();

include_once("../conexion/ConexionBD.php"); //Agregamos el Archivo con las clases y funciones a utilizar
	$usuario=$_SESSION['user'];
		
// Creamos un objeto Conexion, Pacie
	$Conexion= new ConexionBD;   
	$Conectar=$Conexion->Conectar();
	$SQL= "SELECT 	mnt_empleados.IdTipoEmpleado,NombreEmpleado
		   FROM mnt_usuarios  INNER JOIN mnt_empleados
		   					  ON mnt_usuarios.IdEmpleado=mnt_empleados.IdEmpleado
				  		      INNER JOIN mnt_tipoempleado
							  ON mnt_tipoempleado.IdTipoEmpleado=mnt_empleados.IdTipoEmpleado
		   WHERE login='$usuario'";
	
	$Resultado = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
			$Rows = mysql_fetch_array($Resultado);
			$NombreEmpleado=$Rows['NombreEmpleado'];
			$IdTipoEmpleado=$Rows['IdTipoEmpleado'];	
	
		
	echo '<!-- menu script itself. you should not modify this file -->
		  <script language="JavaScript" src="../Funciones/menu.js"></script>
		  <!-- items structure. menu hierarchy and links are stored there -->
		  <link href="../Themes/menu.css" type="text/css" rel="stylesheet">';
		  
	
echo '<center>
		<table width="100%" border="0" bgcolor="#FFFFFF">
		<tr>
		    <td><img id="Image1" style="WIDTH: 204px; HEIGHT: 99px" height="86" src="../Imagenes/paisanito.gif" width="210" name="Image1"></td> 
		    <td style="vertical-align:top">
 			 <h2 align="center" >Ministerio de Salud P&uacute;blica <br> Sistema de Informaci&oacute;n de Atenci&oacute;n de Pacientes<br><br>              
  <font face="Verdana" size="2" align="center">Usuario:. <font color="#ff0000" size="2"><strong>'.$NombreEmpleado.'</strong></font></font></h2>
  </td>
  </tr>
</table>
</center><br>';
	echo '
		<script language="JavaScript" src="../Funciones/menu_items.js"></script>
		<!-- files with geometry and styles structures -->
		<script language="JavaScript" src="../Funciones/menu_tpl.js"></script>
		<script language="JavaScript">
		<!--//
		// Note where menu initialization block is located in HTML document.	
		// some table cell or other HTML element. Always put it before        

		// each menu gets two parameters (see demo files)
		// 1. items structure
		// 2. geometry structure
		new menu (MENU_ITEMS, MENU_POS);
		// make sure files containing definitions for these variables are linked to the document
		// if you got some javascript error like "MENU_POS is not defined", then youve made syntax
		// error in menu_tpl.js file or that file isnt linked properly.
		// also take a look at stylesheets loaded in header in order to set styles
		//-->
		</script>';


?>
