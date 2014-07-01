<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

include_once("clsConsultarElementosPlantillaC.php");
$obj = new clsConsultarElementosPlantillaC;

$idsolicitud=$_GET['var1'];
$idexamen=$_GET['var2'];
$resultado=$_GET['var3'];
$responsable=$_GET['var4'];
$procedencia=$_GET['var5'];
$origen=$_GET['var6'];
$observacion=$_GET['var7'];
$valores_antibioticos=$_GET['var8'];
$codigos_antibioticos=$_GET['var9'];
$idbacteria=$_GET['var10'];
$cantidad=$_GET['var11'];
$idtarjeta=$_GET['var12'];
$nombrearea=$_GET['var13'];
$establecimiento=$_GET['var14'];

$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
$row_estab = mysql_fetch_array($Consulta_Estab);

?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script> 
<!--<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">-->

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


.Estilo5 {font-size: 9pt}
.Estilo6 {font-size: 8pt}
.Estilo12 {font-size: 11pt}
-->
</style>

</script>
</head>

<body>
<?php 

	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$lugar);
	$row_generales= mysql_fetch_array($datos_generales);
	$consulta_datos=$obj->LeerDatos($idexamen);
	$row_area= mysql_fetch_array($consulta_datos);
	$bateria=$obj->NombreBacteria($idbacteria);
	$row_nombrebacteria= mysql_fetch_array($bateria);
	$consulta=$obj->LeerAntibioticos($idtarjeta);
	$vector_valores=EXPLODE("/",$valores_antibioticos);
    $vector_antibioticos=EXPLODE("/",$codigos_antibioticos);
	//$obj = new clsConsultarElementosPlantillaC;
    $tamano_vector=count($vector_valores);
	$tamano_vectoantibiotico=count($vector_antibioticos); 
        $FechaRes=$obj->ObtenerFechaResultado($idsolicitud,$idexamen,$lugar);
	$row_fecha=mysql_fetch_array($FechaRes);

  
  $posele=0;
  $ban=0;
	//$datos_empleado=$obj->DatosEmpleado($idempleado);
	//$row_empleado = mysql_fetch_array($datos_empleado);
	//$datos_observacion=$obj->LeerObservacion($observacion);
	//$row_observacion = mysql_fetch_array($datos_observacion);
$nombre=$row_area['NombreArea'];
?>
	<table width='100%' border='0' align='center' class='StormyWeatherFormTABLE' cellspacing="0">
            <tr>
                <td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo1.png" width="210" name="Image1"></td>
                <td align="center" colspan="4" width="60%" ><span class="Estilo6">
                    <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                    <p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
                    <p><strong>&Aacute;REA DE <?php echo htmlentities($nombrearea)?> </strong></p></span>
                </td>
                <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.gif" width="210" name="Image3"></td>
            </tr>
            <tr>
		<td colspan='6'>&nbsp;</td>
            </tr>
            <tr>
		<td colspan='1' class="Estilo5"><strong>Establecimiento Solicitante:</strong></td>
		<td colspan='2' class="Estilo6"><?php echo $establecimiento?></td>
		<td colspan='1' class="Estilo5"><strong>Fecha Resultado:</strong></td>
	  	<td colspan='2' class="Estilo6" colspan='1'><?php echo $row_fecha['FechaResultado']?></td>
			<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
            </tr>
           
            <tr>
            	<td colspan='1' class="Estilo5" ><strong>NEC</strong></td>
              	<td colspan='5' class="Estilo6" ><?php echo $row_generales['IdNumeroExp']?></td>
            </tr>
            <tr>     
		<td colspan='1' class="Estilo5"><strong>Paciente:</strong></td>
            	<td colspan='5' class="Estilo6"><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
            </tr>
            <tr>
		<td colspan='1' class="Estilo5"><strong>Edad:</strong></td>
		<td colspan='2' class="Estilo6">
                    <div id="divsuedad">
        		<script language="JavaScript" type="text/javascript">
               			calc_edad();
          		</script>
    		    </div>
                </td>
		<td colspan='1' class="Estilo5"><strong>Sexo:</strong></td>
		<td colspan='2' class="Estilo6"><?php echo $row_generales['Sexo'];?></td>
            </tr>
             <tr>	
		<td colspan='1' class="Estilo5"><strong>Procedencia:</strong></td>
		<td colspan='2' class="Estilo6" ><?php echo htmlentities($row_generales['Procedencia']);?></td>
		<td colspan='1' class="Estilo5"><strong>Servicio:</strong></td>
		<td colspan='2' class="Estilo6"><?php echo htmlentities($origen);?></td>
            </tr>
            <tr>
		<td colspan='1' class="Estilo5"><strong>Examen Realizado:</strong></td>
	  	<td colspan='5' class="Estilo6"><?php echo htmlentities($row_area['NombreExamen']);?></td>
            </tr>				 
            
            	
            <tr>
            	<td colspan='1' class="Estilo5"><strong>Validado Por:</strong></td>
		<td colspan='5' class="Estilo6"><?php echo $responsable;?></td>
            </tr>
           <tr>
		<td colspan='6'>&nbsp;</td>
           </tr>
           <tr>
                <td colspan='1' class="Estilo5"><strong>Rsultado:</strong></td>
                <td colspan='2' class="Estilo6"><?php echo "Positivo"?></td>
           </tr> 
           <tr>
                <td colspan='1' class="Estilo5"><strong>Organismo:</strong></td>
                <td colspan='5' class="Estilo6"><?php echo htmlentities($row_nombrebacteria['Bacteria']); ?></td>			 
           </tr>
           <tr>
                <td colspan='1' class="Estilo5"><strong>Cultivo con Cuenta de Colonias:</strong></td>
                <td colspan='4' class="Estilo6"><?php echo htmlentities($cantidad)?></td>
           </tr>
           <tr>
		<td colspan='6'>&nbsp;</td>
            </tr>             
            <tr>
                <td colspan='6'>
                    <table width='60%' border='0' align='left' class='StormyWeatherFormTABLE' cellspacing="0">
                       
                        <tr><td colspan='4'>&nbsp;</td></tr>
                        <tr>
                           <td colspan='4'>
                                <table width="100%" border="0" align="left" cellspacing="0" >
                                    <tr>
                                        <td colspan='1' class="Estilo5"><strong>ANTIBIOTICO</strong></td>
                                        <td colspan='2' class="Estilo5"><strong>INTERPRETACI&Oacute;N</strong>
                                        </td>
                                    </tr>	
                                    <?php	//mysql_free_result($consulta_datos);
                                                    mysql_free_result($datos_generales);
                                                    $pos=0;

                                    while($row = mysql_fetch_array($consulta))//ELEMENTOS)
                                    {?>
                                    <tr>
                                        <td colspan='1' class="Estilo6"><?php echo $row['Antibiotico']?></td>
                                        <td colspan='2' class="Estilo6"><?php echo htmlentities($vector_valores[$pos])?>
                                                <input name='oidantibiotico[".$pos."]' type='hidden' id='oidantibiotico[<?php $pos ?>]' value='<?php $row['IdAntibiotico']?>'>
                                        </td>

                                    </tr>
                                        <?php $pos=$pos+1;		
                                    }
                                    mysql_free_result($consulta);?>
                                </table>
                           </td> 
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <div id="boton">
            <table align='center' border="0"> 
                <tr>
                    <td colspan="7" align="center" >
                        <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                        <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;"/>
                    </td>
                </tr>
            </table>   
         </div>

	