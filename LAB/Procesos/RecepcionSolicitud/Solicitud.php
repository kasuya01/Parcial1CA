<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<style type="text/css">
<!--
@media print{
#botonimp{display:none;}
}


.Estilo5 {font-size: 10pt}
.Estilo12 {font-size: 6pt}
-->
</style>
<title>Datos de Solicitud</title>
<script language="JavaScript" type="text/javascript" src="ajax_RecepcionSolicitud.js"></script>
<script language="JavaScript" >
function RecogeValor1()
{
var vtmp=location.search;
var vtmp2 = vtmp.substring(1,vtmp.length);
var query = unescape(top.location.search.substring(1));
var getVars = query.split(/&/);
for ( i = 0; i < getVars.length; i++)
	{
            if ( getVars[i].substr(0,5) == 'var1=' )//loops through this array and extract each name and value
                idexpediente = getVars[i].substr(5);
            if ( getVars[i].substr(0,5) == 'var2=' )
		fechacita= getVars[i].substr(5);
            if ( getVars[i].substr(0,5) == 'var3=' )
		idsolicitud = getVars[i].substr(5);
            if ( getVars[i].substr(0,5) == 'var4=' )
		establecimiento = getVars[i].substr(5);		
        }
//alert(idexpendiente);
//CargarDatosEtiqueta( idexpediente,fechacita);
}

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
</head>

<body onLoad="RecogeValor1();">




<?php 

include_once("clsRecepcionSolicitud.php");
//variables POST
$idexpediente=$_GET['var1'];
$fechacita=$_GET['var2'];
//echo $fechacita;
$Nfecha=explode("/",$fechacita);
$Nfechacita=$Nfecha[2]."-".$Nfecha[1]."-".$Nfecha[0]; 
$idsolicitud1=$_GET['var3'];
$idestablecimiento=$_GET['var4'];
//$idexpediente='5685-07';
//$fechacita='2008-07-07';
$estado='R';
//echo $idexpediente." - ".$fechacita." - ".$idsolicitud1;
$con = new ConexionBD;
if($con->conectar()==true) 
{
   $query ="SELECT DISTINCT mnt_empleados.IdEmpleado AS IdMedico,NombreEmpleado AS NombreMedico,
            NombreSubServicio AS Origen,sec_solicitudestudios.IdSolicitudEstudio,
            NombreServicio AS Precedencia, mnt_expediente.IdNumeroExp, 
            CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) as NombrePaciente,
            CURDATE() AS Fecha,(YEAR(CURRENT_DATE)-YEAR(FechaNacimiento))AS Edad,
            IF(Sexo=1,'Masculino','Femenino') AS Sexo,mnt_establecimiento.Nombre,
            DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') as FechaNacimiento,mnt_establecimiento.IdEstablecimiento,
            sec_examenfisico.Peso,sec_examenfisico.Talla,Diagnostico,ConocidoPor
            FROM sec_historial_clinico 
            INNER JOIN sec_solicitudestudios  ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico
            INNER JOIN mnt_empleados  ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
            INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp=mnt_expediente.IdNumeroExp
            INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente  
            INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
            INNER JOIN mnt_servicio ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
            INNER JOIN cit_citasxserviciodeapoyo ON sec_solicitudestudios.IdSolicitudEstudio=cit_citasxserviciodeapoyo.IdSolicitudEstudio
            INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento= mnt_establecimiento.IdEstablecimiento
            LEFT JOIN sec_examenfisico ON sec_historial_clinico.IdHistorialClinico=sec_examenfisico.IdHistorialClinico
            LEFT JOIN sec_diagnosticospaciente ON sec_historial_clinico.IdHistorialClinico=sec_diagnosticospaciente.IdHistorialClinico
            LEFT JOIN mnt_cie10 ON sec_diagnosticospaciente.IdDiagnostico1=mnt_cie10.IdCie10
            WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND sec_historial_clinico.IdNumeroExp='$idexpediente'
            AND sec_solicitudestudios.Estado='R' AND sec_solicitudestudios.IdSolicitudEstudio=$idsolicitud1 
            AND cit_citasxserviciodeapoyo.Fecha='$Nfechacita' ";
  // echo $query;
     $result = @mysql_query($query);

//recuperando los valores generales de la solicitud
//$consulta=$objdatos->DatosGenerales($idexpediente,$fechasolicitud,$estado);
$row = mysql_fetch_array( $result );
//valores de las consultas
$medico=$row['NombreMedico'];
$idmedico=$row['IdMedico'];
$paciente=$row['NombrePaciente'];
$edad=$row['Edad'];
$FechaNac=$row['FechaNacimiento'];
$sexo=$row['Sexo'];
$precedencia=$row['Precedencia'];
$origen=$row['Origen'];
$idsolicitud=$row['IdSolicitudEstudio'];
$establecimiento=$row['Nombre'];
$IdEstablecimiento=$row['IdEstablecimiento'];
$Peso=$row['Peso'];
$Talla=$row['Talla'];
$Diagnostico=$row['Diagnostico'];
$ConocidoPor=$row['ConocidoPor'];
//echo $IdEstablecimiento;
}
//echo $FechaNac;
//$consultadetalle=$objdatos->DetalleSolicitud($idexpediente,$fechasolicitud,$estado);
//para el detalle
 if($con->conectar()==true) 
   {
   $querydetalle = "SELECT sec_solicitudestudios.IdNumeroExp, lab_examenes.IdArea AS IdArea ,lab_examenes.IdExamen AS IdExamen, 					
		  NombreExamen,Indicacion,FechaSolicitud,sec_historial_clinico.IdSubServicio,sec_solicitudestudios.IdsolicitudEstudio,
                  lab_examenes.IdEstandar 
		  FROM sec_detallesolicitudestudios 
		  INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
		  INNER JOIN lab_examenes ON sec_detallesolicitudestudios.idExamen=lab_examenes.IdExamen
		  INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
		  INNER JOIN cit_citasxserviciodeapoyo ON cit_citasxserviciodeapoyo.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
		  WHERE sec_solicitudestudios.IdServicio ='DCOLAB' 
                  AND cit_citasxserviciodeapoyo.fecha='$Nfechacita' 
                  AND sec_solicitudestudios.IdSolicitudEstudio=$idsolicitud 
                  AND sec_detallesolicitudestudios.EstadoDetalle='D'      
                  AND sec_detallesolicitudestudios.IdEstablecimientoExterno=$IdEstablecimiento 
                       
                 
ORDER BY lab_examenes.IdArea";
//echo $querydetalle;
$resultdetalle = @mysql_query($querydetalle);
}

?>
<form id="frmSolicitud">
	<table width="80%" border="0" align="center" >
		<tr>
		    <td colspan="6" class="CobaltFieldCaptionTD" align="center" ><h3>DATOS SOLICITUD<h3></td>
		</tr>
		<tr align="right">
			
			
		</tr>
		<tr>
			<td colspan="1">Establecimiento Solicitante:</td>
			<td colspan="3" align="left"><?php echo htmlentities($establecimiento); ?></td>
                        
		</tr>
                <tr>    
                        <td colspan="1">No. Orden</td>
			<td colspan="1"><?php echo $idsolicitud ?></td>
                        <td>Fecha Recepci&oacute;n:</td>
			<td><?php echo htmlentities($fechacita); ?></td>
                </tr>
		<tr>
		   
                    <td colspan="1">Paciente:</td>
                    <td colspan="1"><?php echo htmlentities($paciente); ?>
                        <input name="txtpaciente" type="hidden" value="<?php echo $paciente; ?>" disabled="disabled" />
			<input name="suEdad" id="suEdad"  type="hidden" value="<?php echo $row['FechaNacimiento'] ?>"/>	
                    </td>
                    <td colspan="1">Expediente:</td>
		    <td colspan="1">
				<?php echo htmlentities($idexpediente); ?>
				<input name="txtExpediente" id="txtExpediente" type="hidden"  value=" <?php echo $idexpediente; ?>" />
                    </td>
		</tr>
                <tr>
                    <td colspan="1">Conocido Por:</td>
                    <td colspan="3"><?php echo $ConocidoPor; ?></td>
                </tr>
		<tr>
		    <td >Edad:</td>
		    <td>
			<div id="divsuedad">
                            <script language="JavaScript" type="text/javascript">
		           	calc_edad();
                            </script>
			</div> 
                    </td>
                    <td >Sexo:</td>
                    <td ><?php echo $sexo; ?></td>
		</tr>
		<tr>
		  	
			<td>Procedencia:</td>
			<td>
				<?php echo htmlentities($precedencia); ?>
				<input name="txtprecedencia2" id="txtprecedencia" type="hidden"  value="<?php echo $precedencia; ?>"/>
				<input name="txtidsolicitud" id="txtidsolicitud" type="hidden" value="<?php echo $idsolicitud; ?>"/>
				<input name="txtfecha" id="txtfecha" type="hidden" value="<?php echo $fechacita; ?>"/>
				
			</td>
			<td >Origen:</td>
			<td colspan="2"> <?php echo htmlentities($origen); ?></td>
		</tr>
		<tr>
                    <td colspan="1">M&eacute;dico:</td>
		    <td colspan="4"><?php echo htmlentities($medico); ?></td>
		</tr>
                <tr>
                    <td>Diagnostico:</td>
                    <td colspan="4"><?php echo htmlentities($Diagnostico); ?></td>
                </tr>
                <tr>
                     <td>Peso:</td>
            <?php if (!empty($Peso)){?>
                     <td><?php echo htmlentities($Peso); ?>&nbsp;&nbsp;Kg.c&nbsp;&nbsp;&nbsp;</td>
            <?php }else {?>
                     <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <?php         
                }?>         
                     <td>Talla:</td>
              <?php if (!empty($Peso)){?>       
                     <td><?php echo htmlentities($Talla); ?>&nbsp;&nbsp;cm.</td>
               <?php }else { ?>  
                     <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
               <?php }?>        
                </tr>     
		<tr>
                     <td colspan="5">&nbsp;</td>
		</tr>	
		<tr>
                    <td colspan="5" align="center" >ESTUDIOS SOLICITADOS</td></tr>
		<tr>
                    <td colspan="5">			
			<table border = 1 align='center' class="StormyWeatherFormTABLE">
                                <tr>
                                    
                                    <td class="CobaltFieldCaptionTD">Código de prueba</td>
                                    <td class="CobaltFieldCaptionTD">Área</td>
                                    <td class="CobaltFieldCaptionTD">Código Examen</td>
                                    <td class="CobaltFieldCaptionTD">Nombre Examen </td>
                                    <td class="CobaltFieldCaptionTD">Indicación</td>
                                </tr>
                                <?php
                              while($fila = mysql_fetch_array($resultdetalle)){
                                ?> 
                                <tr>
                                    <td ><?php echo $fila[8] ; ?>  </td>
                                    <td ><?php echo $fila[1] ; ?>  </td>
                                    <td> <?php echo $fila[2] ; ?>  </td>
                                    <td> <?php echo $fila[3] ; ?>  </td>
                           <?php if (!empty($fila[4])){?>
                                    <td><?php echo htmlentities($fila[4]); ?>  </td>
                           <?php }else{ ?>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                           <?php }?>         
                                </tr> 
                               <?php
                               }
                               ?>
			</table>
                    </td>		 
		</tr>
	</table>
<br>

	<table align="center">
		<tr>
			<td>
				<div id="botonimp"  >
					<input type="button" name="btnImpSolicitud" id="btnImpSolicitud" value="Imprimir" onClick="window.print();"/>
				</div> 
			</td>
		</tr>
	</table>



