<?php session_start();
if(isset($_SESSION['Correlativo']))
{
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$nivel=$_SESSION['NIVEL'];
 include_once("clsSolicitudesPorPaciente.php"); 
//consulta los datos por su id
$objdatos = new clsSolicitudesPorPaciente;
/*$consulta=$obj->DatosEstablecimiento($lugar);
$row = pg_fetch_array($consulta);
//valores de las consultas
$tipo=$row[0];
$nombrEstab=$row[1];
$nomtipo=$row[2];*/
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<style type="text/css">
<!--
@media print{
#boton{display:none;}
}

.Estilo5 {font-family: Helvetica; font-size: 7pt}
.Estilo6 {font-family: Helvetica; font-size: 8pt}
.Estilo7 {font-family: Helvetica; font-size: 9pt}
-->
</style>
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">-->
<title>Datos del Generales de la Solicitud</title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudPorPaciente.js"></script>
<script language="JavaScript" >
function RecogeValor()
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
			idsolicitud = getVars[i].substr(5);
	}

 //CargarDatosFormularioSolicitud(idexpediente,idsolicitud);
}

</script>
</head>

<body onLoad="RecogeValor();">
<?php 
$idexpediente = $_GET['var1'];
$idsolicitud = $_GET['var2'];
 // echo $idexpediente ."-solicitud -". $idsolicitud;        
    $consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
    $row = pg_fetch_array($consulta);
		//obteniedo los datos generales de la solicitud
		//valores de las consultas
    $idsolicitudPadre=$row[0];
    $medico         =$row['medico'];
    $idmedico       =$row[1];
    $paciente       =$row['paciente'];
    $edad           =$row['edad'];
    $sexo           =$row['sexo'];
    $precedencia    =$row['nombreservicio'];
    $origen         =$row['nombresubservicio'];
    //$DatosClinicos  =$row['DatosClinicos'];
    //$Estado         =$row['Estado'];
    //$fechasolicitud =$row['FechaSolicitud'];
    //$FechaNac       =$row['FechaNacimiento'];
    $ConocidoPor    =$row['conocodidox'];
    $Diagnostico    =$row['diagnostico'];
    //recuperando los valores del detalle de la solicitud
  // echo $edad;
    
    $consultadetalle=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
    ?>  
   
         

        <table width="100%" border="0" align="center">
	    <tr>
                <td  colspan="4">&nbsp;&nbsp;&nbsp;&nbsp</td>
	    </tr>
	    <tr>
                <td colspan="4" align="center" class="Estilo7"><h2>DATOS SOLICITUD</h2></td>
	    </tr>
	    <tr>
                <td width="22%" class="Estilo7">Establecimiento Solicitante</td>
                <td width="38%" class="Estilo6" colspan="3"><?php echo $row['estabext'] ?></td>
	    </tr>
	    <tr>
                <td width="22%" class="Estilo7">Paciente</td>
                <td width="38%" class="Estilo7"><?php echo htmlentities($paciente) ?> 
                    <input name="txtpaciente" id="txtpaciente" type="hidden" value="<?php echo $paciente?>" disabled="disabled" /></td>
                <td width="10%" class="Estilo7" >Expedinte</td> 
                <td width="22%" class="Estilo7"><?php echo $idexpediente ?></td>         
            </tr>
            <tr>
                <td class="Estilo7">Conocido por</td>
                <td class="Estilo7" colspan="3" ><?php echo htmlentities($ConocidoPor)?> </td>
	    </tr>
            <tr>
                <td class="Estilo7" >Edad</td>
                <td class="Estilo7"><?php echo $edad ?></td>
		<td class="Estilo7">Sexo</td>
		<td class="Estilo7"><?php echo $sexo ?></td>
            </tr>
            <tr>
                <td class="Estilo7" >Procedencia</td>
                <td class="Estilo7"><?php echo $precedencia ?> 
                    <input name="txtprecedencia" id="txtprecedencia" type="hidden" size="35" value="<?php echo $precedencia ?>" disabled="disabled" /></td>
                <td class="Estilo7">Origen</td>
                <td class="Estilo7"><?php echo htmlentities($origen)?>
                    <input name="txtorigen" id="txtorigen"  type="hidden" size="35" value="<?php echo $origen?>" disabled="disabled" />
                    <input name="idsolicitudPadre" id="idsolicitudPadre"  type="hidden" size="40" value="<?php echo $idsolicitudPadre ?>" disabled="disabled" />
		    <input name="idsolicitud" id="idsolicitud"  type="hidden" size="40" value="<?php echo $idsolicitud ?>" disabled="disabled" />
                    <input name="idexpediente" id="idexpediente"  type="hidden" size="40" value="<?php echo $idexpediente ?>" disabled="disabled" />
                </td>
	    </tr>
            <tr>
                <td class="Estilo7">M&eacute;dico</td>
                <td class="Estilo7" colspan="3"><?php echo htmlentities($medico) ?>
                    <input name="txtmedico" id="txtmedico"  type="hidden" size="70" value="<?php echo $medico ?> disabled="disabled" /></td>
            </tr>
            <tr>
                <td class="Estilo7">Diagnostico</td>
                <td class="Estilo7" colspan="3"><?php echo $Diagnostico ?></td>
            </tr>
            <tr> 
                <td class="Estilo7" colspan="4">&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp</td>
            </tr>
            <tr>
                <center>
                    <table width="100%" border="1" align="center" cellspacing="0">
                        <tr>
                            <td colspan="5" class="Estilo7" ><center>ESTUDIOS SOLICITADO</center></td>
                        </tr>
                        <tr>
                            <td align="center" class="Estilo5"> Código</td>
                            <td align="center" class="Estilo5"> Examen </td>
                            <td align="center" class="Estilo5"> Código Área </td>
                            <td align="center" class="Estilo5"> Indicacion Medica </td>
                            <td align="center" class="Estilo5"> Estado </td>
                        </tr>
		<?php $pos=0;
		while($fila = pg_fetch_array($consultadetalle)){?>
		        <tr>
                            <td class="Estilo5"><?php echo $fila['codigo_examen']?></td>
			    <td class="Estilo5"><?php echo htmlentities($fila['nombre_examen']) ?></td>	
                            <td class="Estilo5"><?php echo $fila['codigo_area'] ?></td>	
                <?php if (!empty($fila['indicacion'])){     ?>				
		   	    <td class="Estilo5"><?php echo htmlentities($fila['indicacion']) ?></td>
		            <td class="Estilo5"><?php echo $fila['estado'] ?></td>	
	      		</tr>
                <?php }else{ ?>
			    <td class="Estilo5">&nbsp;&nbsp;&nbsp;&nbsp</td>
			    <td class="Estilo5"><?php echo $fila['estado'] ?></td>
                     	</tr>	
		<?php		}
                     $pos=$pos + 1;
                }

            pg_free_result($consultadetalle);
?>

                    <input type="hidden" name="oculto" id="oculto" value="<?php echo $pos?>" />
                    </table>
                </center>
            </tr>
        </table>                        
	<div id="boton">
            <table align="center">
                    <tr>
	                <td  aling="center">
                                <button type="button" align="center" class="btn btn-primary"  onclick="window.print();"><span class="glyphicon glyphicon-print"></span> Imprimir </button>
                                <button type="button" align="center" class="btn btn-primary"  onClick="window.close();"><span class="glyphicon glyphicon-arrow-left"></span> Regresar </button>
                        </td>
                    </tr>
            </table>
        </div>
<div id="divFormulario">

</div>
<div id="divCambioEstado">
</div>
</body>
</html>
<?php
}
else{?>
<script language="javascript">
	window.location="../../../login.php";
</script>
<?php }?>