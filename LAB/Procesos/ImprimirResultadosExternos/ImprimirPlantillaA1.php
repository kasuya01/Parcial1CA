<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//Datos recibidos
$iddetalle=$_GET['var1'];
$idsolicitud=$_GET['var2'];
$idplatilla=$_GET['var3'];
$expediente=$_GET['var4'];
$idarea=$_GET['var5'];
$sexo=$_GET['var6'];
$fechanac=$_GET['var7'];
//echo $fechanac."    ".$sexo;
//echo $iddetalle."*sol".$idsolicitud."*plan".$idplatilla."*exp".$expediente."*area".$idarea
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_ImprimirResultado.js"></script>
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


.Estilo5 {font-family: Helvetica; font-size: 9pt}
.Estilo6 {font-family: Helvetica; font-size: 8pt}
.Estilo7 {font-family: Helvetica; font-size: 10pt}
-->
</style>

</script>
</head>

<body>
<?php
include_once("clsImprimirResultado.php");
  $objdatos = new clsImprimirResultado;

//echo $lugar;
//echo $idsolicitud."-".$idarea."-".$lugar;

  $consulta1=$objdatos->MostrarResultadoGenerales1($idsolicitud,$idarea,$lugar);
  $row = mysql_fetch_array($consulta1);
  $Establecimiento=$row['IdEstablecimiento'];
 // $nombre=$row['NombreArea'];
   //echo $Establecimiento;
  $Consulta_Estab=$objdatos->Nombre_Establecimiento($Establecimiento);
  $row_estab = mysql_fetch_array($Consulta_Estab);

  $Cuentadias=$objdatos->CalculoDias($fechanac);
  $Cdias= mysql_fetch_array($Cuentadias);
  $dias=$Cdias[0];

  $ConRangos=$objdatos->ObtenerCodigoRango($dias);
  $row_rangos=  mysql_fetch_array($ConRangos);
  $idedad=$row_rangos[0];

  $FechaRes=$objdatos->ObtenerFechaResultado($idsolicitud,$idarea,$lugar);
  $row_fecha=mysql_fetch_array($FechaRes);
  $FechaResultado=$row_fecha[0];
  $IdEmpleado=$row_fecha[1];
 // echo $IdEmpleado;

  $consulta_empleado=$objdatos->BuscarEmpleadoValidador($IdEmpleado);
  $fila_empleado = mysql_fetch_array($consulta_empleado);
//echo  $idedad."   ".$sexo;
    $consulta=$objdatos->DetalleExamenes($idsolicitud,$idarea,$lugar,$idedad,$sexo);

?>
    <table width='100%' align='center' class='StormyWeatherFormTABLE'>
        <tr>
            <td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
            <td align="center" colspan="4" width="60%" class="Estilo6">
                <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                <p><strong><?php echo $row['Nombre'] ?></strong></p>
                <p><strong>ÁREA DE <?php echo htmlentities($row['NombreArea'])?></strong></p>
            </td>
            <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
	</tr>
        <tr>
            <td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr>
            <td colspan="1" class="Estilo5"><strong>Establecimiento Solicitante:</strong></td>
            <td colspan="2" class="Estilo6"><?php echo htmlentities($row_estab['Nombre'])?></td>
            <td colspan="1" class="Estilo5"><strong>Fecha Resultado:</strong></td>
            <td colspan="2" class="Estilo6"><?php echo $FechaResultado;?></td>
		<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row['FechaNacimiento']?>>
	</tr>

	<tr>
            <td colspan='1' class="Estilo5"><strong>NEC</strong></td>
            <td colspan='5' class="Estilo7"><?php echo $row['IdNumeroExp']?></td>

        </tr>

        <tr>
            <td colspan='1' class="Estilo5"><strong>Paciente:</strong></td>
            <td colspan='5' class="Estilo6"><?php echo htmlentities($row['NombrePaciente'])?></td>
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
            <td colspan='2' class="Estilo6"><?php echo $row['Sexo']?></td>
        </tr>

        <tr>
            <td colspan="1" class="Estilo5"><strong>Procedencia:</strong></td>
            <td colspan="2" class="Estilo6"><?php echo htmlentities($row['Procedencia'])?></td>
            <td colspan="1" class="Estilo5"><strong>Servicio:</strong></td>
            <td colspan="2" class="Estilo6"><?php echo htmlentities($row['Origen'])?></td>
	</tr>
         <tr>
            <td colspan='1' class="Estilo5"><strong>Validado Por:</strong></td>
            <td colspan='2' class="Estilo6"><?php echo $fila_empleado['NombreEmpleado']?></td>
        </tr>
	<tr>
            <td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr>
            <td colspan='6' align='center' class="Estilo5"><strong>DETALLE DE RESULTADOS</strong></td>
	</tr>
        <tr>
             <td  colspan="6">
                <table width='100%'  align='center' border='0' class='StormyWeatherFormTABLE'>
                    <tr >
                        <td align="left" class="Estilo5"><strong>Prueba Realizada </strong></td>
                        <td align="center" class="Estilo5"><strong>Resultado</strong></td>
                        <td align="center" class="Estilo5"><strong>Unidades</strong></td>
                        <td align="center" class="Estilo5"><strong>Rangos Normales</strong></td>
                        <td align="center" class="Estilo5"><strong>Lectura</strong></td>
                        <td align="center" class="Estilo5"><strong>Interpretación</strong></td>
                        <td align="center" class="Estilo5"><strong>Observación</strong></td>


                    </tr>
                                <?php $pos=0;
                        while($rowdet = mysql_fetch_array($consulta)){?>
                    <tr>
                        <td align="left"   class="Estilo5"><?php echo htmlentities($rowdet['NombreExamen'])?></td>
                        <td align="center" class="Estilo5"><?php echo htmlentities($rowdet['Resultado'])?></td>
                        <td align="center" class="Estilo5"><?php echo htmlentities($rowdet['Unidades'])?></td>
                        <td align="center" class="Estilo5"><?php echo $rowdet['RangoInicio']."-".$rowdet['RangoFin']?></td>
                        <td align="center" class="Estilo5"><?php echo htmlentities($rowdet['Lectura'])?></td>
                        <td align='justify' class="Estilo5"><?php echo htmlentities($rowdet['Interpretacion'])?></td>
                        <td align='justify' class="Estilo5"><?php echo htmlentities($rowdet['Observacion'])?></td>


                    </tr>
                        <?php

                                $pos=$pos + 1;
                        }
                        ?>
                    </table>
                </td>
            </tr>
    </table>
     <div id="boton">
            <table align='center' border="0">

                    <tr>
                        <td colspan="7" align="center" >

                            <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                            <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="window.close();" />

                        </td>
                    </tr>
            </table>
     </div>
