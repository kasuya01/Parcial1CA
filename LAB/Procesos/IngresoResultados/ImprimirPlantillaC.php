<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

include_once("clsConsultarElementosPlantillaC.php");
$obj = new clsConsultarElementosPlantillaC;

$idsolicitud=$_GET['var1'];
$idexamen=$_GET['var2'];
//echo "sol= ".$idsolicitud."Exa= ".$idexamen;
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
 //$valores_interpretacion=$_GET['var15'];
$idobservacion=$_GET['var15'];
$valores_interpretacion=$_GET['var16'];
//echo $idobservacion;
$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
$row_estab = pg_fetch_array($Consulta_Estab);

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


.Estilo5 {font-family: Helvetica; font-size: 10pt}
.Estilo6 {font-family: Helvetica; font-size: 9pt}
.Estilo7 {font-family: Helvetica; font-size: 11pt}
-->
</style>

</script>
</head>

<body>
<?php

	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$lugar);
	$row_generales= pg_fetch_array($datos_generales);
	$consulta_datos=$obj->LeerDatos($idexamen);
	$row_area= pg_fetch_array($consulta_datos);
	
        $bateria=$obj->NombreBacteria($idbacteria);
	$row_nombrebacteria= pg_fetch_array($bateria);
	$consulta=$obj->LeerAntibioticos($idtarjeta);
	$vector_valores=EXPLODE("/",$valores_antibioticos);
        $vector_antibioticos=EXPLODE("/",$codigos_antibioticos);
        
       // print_r($valores_interpretacion);
        
       // $vector_interpretacion=EXPLODE("/",$valores_interpretacion);
	//$obj = new clsConsultarElementosPlantillaC;
        $tamano_vector=count($vector_valores);
	$tamano_vectoantibiotico=count($vector_antibioticos);
       // $tamano_vectorinterpretacion=count($vector_interpretacion);
        
        //echo $tamano_vectorinterpretacion;
        $FechaRes=$obj->ObtenerFechaResultado($idsolicitud,$idexamen,$lugar);
	$row_fecha=pg_fetch_array($FechaRes);
        
         
        $posele=0;
        $ban=0;
	$nombre=$row_area['nombrearea'];
?>
	<table width='100%' border='0' align='center' class='StormyWeatherFormTABLE' cellspacing="0">
            <tr>
                <td colspan="1" align="left" width="20%"><img id="Image1" style="width: auto; height: 55px;" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                <td align="center" colspan="4" width="60%" ><span class="Estilo5">
                    <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                    <p><strong><?php echo $row_estab['nombre'] ?></strong></p>
                    <p><strong>&Aacute;REA DE <?php echo htmlentities($row_area['nombrearea'])?> </strong></p></span>
                </td>
                <td colspan="1" align="right" width="20%"><img id="Image3" style="width: auto; height: 55px;" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
            </tr>
            <tr>
		<td colspan='6'>&nbsp;</td>
            </tr>
            <tr>
		<td colspan='1' class="Estilo5"><strong>Est. Solicitante:</strong></td>
		<td colspan='2' class="Estilo6"><?php echo $row_generales['estabext']?></td>
		<td colspan='1' class="Estilo5"><strong>Fecha Resultado:</strong></td>
	  	<td colspan='2' class="Estilo6" colspan='1'><?php echo $row_fecha['fecharesultado']?></td>
			
            </tr>

            <tr>
            	<td colspan='1' class="Estilo5" ><strong>Expediente:</strong></td>
              	<td colspan='2' class="Estilo7" ><?php echo $row_generales['numero']?></td>
                <td colspan='1' class="Estilo5" ><strong>Fecha Recepción:</strong></td>
                <td colspan='2' class="Estilo6" ><?php echo $row_generales['fecharecep']?></td>
            </tr>
            <tr>
		<td colspan='1' class="Estilo5"><strong>Paciente:</strong></td>
            	<td colspan='5' class="Estilo6"><?php echo htmlentities($row_generales['paciente'])?></td>
            </tr>
            <tr>
		<td colspan='1' class="Estilo5"><strong>Edad:</strong></td>
		<td colspan='2' class="Estilo6"><?php echo $row_generales['edad']?></td>
		<td colspan='1' class="Estilo5"><strong>Sexo:</strong></td>
		<td colspan='2' class="Estilo6"><?php echo $row_generales['sexo'];?></td>
            </tr>
             <tr>
		<td colspan='1' class="Estilo5"><strong>Procedencia:</strong></td>
		<td colspan='2' class="Estilo6" ><?php echo htmlentities($row_generales['procedencia']);?></td>
		<td colspan='1' class="Estilo5"><strong>Servicio:</strong></td>
		<td colspan='2' class="Estilo6"><?php echo htmlentities($row_generales['subservicio']);?></td>
            </tr>
            <tr>
		<td colspan='1' class="Estilo5"><strong>Examen Realizado:</strong></td>
	  	<td colspan='5' class="Estilo6"><?php echo htmlentities($row_area['nombre_examen']);?></td>
            </tr>
            <tr>
                <td colspan='1' class="Estilo5"><strong>Rsultado:</strong></td>
                <td colspan='2' class="Estilo5">Positivo</td>
                
           </tr>  
            <tr>
		<td colspan='6'>&nbsp;</td>
            </tr>
            
     <?php 
      $contar=$obj->contar_resultados($idsolicitud,$idexamen);
            //$row_totresult= pg_fetch_array($contar); 
      while($row_totresult = pg_fetch_array($contar)){
        ?>
            <tr>
                <td colspan='1' class="Estilo5"><strong>Validado Por:</strong></td>
                <td colspan='5' class="Estilo6"><?php echo $row_totresult['nombreempleado'];?></td>
           </tr>
                  
            <tr>
                <td colspan='1' class="Estilo5"><strong>Observación:</strong></td>
                <td colspan='5' class="Estilo5"><?php echo $row_totresult['observacion']; ?></td>
           </tr>
 <?php
   $detalle = $obj->obtener_detalle_resultado($row_totresult['idresultado']);
    //$row_det= pg_fetch_array($detalle);
         while($row_det = pg_fetch_array($detalle)){?>
            <tr>
                <td colspan='1' class="Estilo5"><strong>Organismo:</strong></td>
                <td colspan='5' class="Estilo5"><?php echo htmlentities($row_det['bacteria']); ?></td>
            </tr>
           <tr>
                <td colspan='2' class="Estilo5"><strong>Cultivo con Cuenta de Colonias:</strong></td>
                <td colspan='1' class="Estilo5"><?php echo htmlentities($row_det['cantidad'])?></td>
           </tr>
           <tr>
		<td colspan='6'>&nbsp;</td>
            </tr>
           <tr> 
                <td colspan='6'>
                    <table width='60%' border='0' align='left' class='StormyWeatherFormTABLE' cellspacing="0">

                        <tr>
                            <td colspan='4'>
                                <table width="100%" border="0" align="left" cellspacing="0" >
                                    <tr>
                                        <td colspan='1' class="Estilo5"><strong>ANTIBIOTICO</strong></td>
                                        <td colspan='1' class="Estilo5"><strong>LECTURA</strong></td>
                                        <td colspan='2' class="Estilo5"><strong>INTERPRETACI&Oacute;N</strong></td>
                                    </tr>
         <?php
                     $res_tarjeta= $obj->obtener_resultadoxtarjeta($row_det['iddetalleresultado']);
                    //$row_tarjeta= pg_fetch_array($res_tarjeta);
                        while($row_tarjeta = pg_fetch_array($res_tarjeta)){?>
                                    <tr>
                                        <td colspan='1' class="Estilo5"><?php echo $row_tarjeta['antibiotico']?></td>
                                        <td colspan='1' class="Estilo5"><?php echo htmlentities($row_tarjeta['valor'])?></td>
                                        <td colspan='2' class="Estilo5"> <?php echo htmlentities( $row_tarjeta['posible_resultado']) ?></td>
                                    </tr>
            
                <?php
                }?>
                                </table>
                           </td>
                        </tr>
                        <tr>
		<td colspan='6'>&nbsp;</td>
           </tr> 
           
                    </table><?php 
    }
}?>
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
