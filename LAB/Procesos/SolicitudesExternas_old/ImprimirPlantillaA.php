<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

$IdSolicitud=$_GET['var1'];
$IdExamen=$_GET['var2'];
$resultado=$_GET['var4'];
//$lectura=$_GET['var5'];
//$interpretacion=$_GET['var6'];
$observacion=$_GET['var7'];
$responsable=$_GET['var8'];
$sexo=$_GET['var9'];
$idedad=$_GET['var10'];
$IdEstabExt=$_GET['var11'];
//echo $IdEstabExt; 
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script> 
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<script language="JavaScript">
    function calc_edad()
    {
      var fecnac1=document.getElementById("suEdad").value;
      var fecnac2=fecnac1.substring(0,10);
    //alert (fecnac2);
      var suEdades=calcular_edad(fecnac2);
     // alert(suEdades);

      document.getElementById("divsuedad").innerHTML=suEdades;
    }

</script>

<style type="text/css">
<!--
@media print{
#boton{display:none;}
}


.Estilo5 {font-size: 10pt}
.Estilo6 {font-size: 9pt}
.Estilo12 {font-size: 11pt}
-->
</style>

</script>
</head>

<body>
<?php 
include_once("clsSolicitudesProcesadas.php");
$objdatos = new clsSolicitudesProcesadas;
$Consulta_Estab=$objdatos->Nombre_Establecimiento($IdEstabExt);
$row_estab = mysql_fetch_array($Consulta_Estab);
//echo $sexo."***".$idedad."***".$IdSolicitud."***".$IdExamen;
$consulta=$objdatos->MostrarResultadoGenerales($IdSolicitud,$IdExamen,$lugar,$sexo,$idedad);
$row = mysql_fetch_array($consulta);
$nombre=$row['NombreArea'];
 $Consulta_fecha=$objdatos->ObtenerFechaResultado1($IdSolicitud,$IdExamen,$IdEstabExt);
 $rowfecha = mysql_fetch_array($Consulta_fecha);
 $fechares=$rowfecha[0];
 //echo $fechares;
?>

<div  id="divImpresion" >
    <form name="frmimpresion" >
        <table width='100%' align='center' class='StormyWeatherFormTABLE' cellspacing="0">
           <tr>
               <td colspan='1' align="left" width='20%'><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo1.png" width="210" name="Image1"></td>
               <td align="center" colspan='4' width='60%'><img id="Image2" style="WIDTH: 100px; HEIGHT: 55px" height="86" src="../../../Imagenes/INS.png" width="210" name="Image2"></td>
               <td colspan='1' align="right" width='20%'><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.gif" width="210" name="Image3"></td>
           </tr> 
           <tr>
                <td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
                <td colspan='4' align='center' >
                        <p><strong> MINISTERIO DE SALUD</strong> </p>
                     	<p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
                        <p><strong> ÁREA DE LABORATORIO CL&Iacute;NICO</strong></p>
					<!--<p><strong>&Aacute;rea de <?php //echo $nombre; ?> </strong></p>-->
		</td>
                <td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
	    </tr>
            <tr>
	        <td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
               
            </tr>
	    <tr>
		<td colspan='1' style='font:bold'><strong>Establecimiento:</strong></td>
		<td colspan='1' ><?php echo htmlentities($row['Nombre']);?></td>
		<td colspan='1' style='font:bold'><strong>Fecha:</strong></td>
		<td colspan='3' ><?php echo $fechares;?></td>
                        <input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row['FechaNacimiento']?>>
	    </tr>
	    <tr>
		<td colspan='1' style='font:bold'><strong>NEC:</strong></td>
		<td colspan='1'><?php echo$row['IdNumeroExp'];?></td>
		<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
		<td colspan='3'><?php echo htmlentities($row['NombrePaciente'])?></td>
				
	    <tr>
		<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
		<td colspan='1'>
                     <div id="divsuedad">
                           <script language="JavaScript" type="text/javascript">
                                  calc_edad();
                           </script>
                     </div>
                </td>
		<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
		<td colspan='3' ><?php echo $row['Sexo']?></td>					
	    </tr>	
	    <tr>
		<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
		<td colspan='1' ><?php echo htmlentities($row['Procedencia'])?></td>
		<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
		<td colspan='3' ><?php echo htmlentities($row['Origen'])?></td>
            </tr>
                            <?php 
				$consulta_empleado=$objdatos->BuscarEmpleadoValidador($responsable);
				$fila_empleado = mysql_fetch_array($consulta_empleado);
				$consulta2=$objdatos->MostrarDatosFijosPlantillaA($IdExamen,$lugar,$sexo,$idedad);
				$fila = mysql_fetch_array($consulta2);
                            ?>
            <tr>
            	<td  colspan='1' style='font:bold'><strong>Validado Por: </strong></td>
		<td  colspan='5''><?php echo htmlentities($fila_empleado['NombreEmpleado'])?></td>
            </tr>
            <tr>
		<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
		<td colspan='6' align='center' style="font:bold" >DETALLE DE RESULTADOS</td>
            </tr>        
            <tr>
                <td colspan='6'>
                    <table width="100%"  align="center" border="1" cellspacing="0" class="StormyWeatherFormTABLE">
                        <tr>
                            <td align="center" style="font:bold">Prueba Realizada </td>
                            <td align="center" style="font:bold">Resultado</td>
                            <td align="center"style="font:bold">Unidades</td>
                            <td align="center" style="font:bold">Rangos Normales </td>
                                    <!--	<td align="center">Lectura</td>
                                            <td align="center">Interpretaci&oacute;n</td>-->
                            <td align="center"style="font:bold">Observaci&oacute;n</td>
                        </tr>	
                        <tr>
                            <td align="center"><?php echo htmlentities($fila['NombreExamen'])?></td>
                            <td align="center"><?php echo htmlentities($resultado)?></td>
                       <?php if (!empty($fila['Unidades'])){ ?>
                            <td align="center"><?php echo htmlentities($fila['Unidades']) ?></td>
                       <?php }else{?>
                           <td  align="center">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                       <?php } ?>  
                            <td align="center"><?php echo $fila['RangoInicio']." - ".$fila['RangoFin']?></td>
                                                <!--<td align="justify"><?php //echo htmlentities($lectura)?></td>
                                                <td align="justify"><?php //echo htmlentities($interpretacion)?></td>-->
                       <?php if (!empty($observacion)){ ?>    
                            <td align="justify"><?php echo htmlentities($observacion)?></td>
                       <?php }else{?>
                            <td  align="center">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                       <?php } ?>  
                        </tr> 
                       
                       
                      </table>
                  </td>
              </tr>
               <tr>
                  <td colspan="6" align="center" >
                      <div id="boton">	
                          <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                          <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;" />
                      </div>
                   </td>
                </tr>
       </table>
    </form>
</div>
