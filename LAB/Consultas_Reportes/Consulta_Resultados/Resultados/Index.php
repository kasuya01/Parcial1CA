<!-- Incluimos los archivos, Recibimos Parametros e Instanciamos Clases-->
<?php
	include_once("./Clases.php"); //Agregamos el Archivo con las clases y funciones a utilizar
	$IdNumeroExp=$_GET['IdNumeroExp'];	
	
	// Creamos un objeto Conexion, Paciente, Laboratorio
	$Conexion= new ConexionBD;   
	$Paciente= new Paciente;   
	$Laboratorio= new Laboratorio;   
	//Abrimos la Conexion
	$Conectar=$Conexion->Conectar();
	// Recuperamos el Nombre del Paciente
	$Nombre=$Paciente->RecuperarNombre($Conectar,$IdNumeroExp);?>
    
<html>
<head>
<title>Historial de Estudios de Laboratorio Paciente - Consulta Externa</title>
<link rel="stylesheet" type="text/css" href="../Themes/Salad/Style.css">
<link rel="stylesheet" type="text/css" href="./Estilo.css">
<script language="javascript" src="ajax.js"></script>
</head>
<body link="#000099" alink="#ff0000" vlink="darkblue" text="#000000" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#ffffff">

<center> <a align="center"><img src='./Themes/Laboratorio.jpg' align="absmiddle"></a></center>


<!-- Recuperar los Datos del Paciente -->

<?php echo "<form><table width='100%' align='center'>	
			<tr>
				<td width='45%'><div><h2>No Expediente: ".$IdNumeroExp."	<br>
								Nombre: ".$Nombre."	</h2></div></td>
				<td align='right'><input class='aladButton' type='button' value='Cerrar Historial' name='salir' style='WIDTH: 164px; HEIGHT: 33px' onclick='javascript:CerrarVentana();'></td>
			</tr>	
			</table></form>";
?>

   
    <!-------------------------------- Recuperar las Solicitudes realizadas a Laboratorio---------------------------->
	<div id="Consultas" >    
	<?php 
		$Laboratorio->SolicitudesLaboratorio($Conectar,$IdNumeroExp,5);
	 ?>
     </div>
  
<div id="Solicitudes" >
</div>

<div id="Paginador" >

</div>

<div id="DetalleResultados">
</div>

<div id="PlantillaB">
</div>

<div id="PlantillaE">
</div>
</body>
</html>
