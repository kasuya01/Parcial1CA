<?php session_start();
include('../Lab_Areas/clsLab_Areas.php');
include('clsLab_Examenes.php');
include('../Lab_CodigosEstandar/clsLab_CodigosEstandar.php');
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$obje=new clsLab_CodigosEstandar;
$objeareas=new clsLab_Areas;
$obj=new clsLab_Examenes;
 //echo $lugar;
?>
<html>
<head> 
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Mantenimiento de Examenes de Laboratorio</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_Examenes.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<script language="JavaScript" >
function Guardar(){
       	IngresarRegistro();
}
function AsignarIdExamen(idArea)
{
   SolicitarUltimoCodigo(idArea);
}
function Modificar()
{ 
	enviarDatos(); 
}
/*function Nuevo()
{ 
	MostrarFormularioNuevo(); 
}*/
function Buscar()
{ 
	BuscarDatos(); 
}
function Cancelar()
{ 	LimpiarCampos();
	show_event(1);
}

</script>
</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="show_event(1);">

<?php 

//$nivel=$_SESSION['NIVEL'];
//$_SESSION['correlativo']=$_SESSION['correlativo'];  
if ($nivel==1){
	include_once ('../../../PaginaPrincipal/index_laboratorio2.php');}
if ($nivel==2){
	include_once ('../../../PaginaPrincipal/index_laboratorio22.php');}
if ($nivel==31){
	include_once ('../../../PaginaPrincipal/index_laboratorio31.php');}
if ($nivel==33){
	include_once ('../../../PaginaPrincipal/index_laboratorio33.php');}
?><br>
<table align="center" width="100%">
    <tr>
        <td>
            <div id="divOculto">
                <input type="hidden" name="txtoculto" id="txtoculto" value="NNN"/>
            </div>
            <div  id="divFrmNuevo" >
                <form name="frmnuevo" >
                    <table width="70%" border="0" align="center" class="StormyWeatherFormTABLE">
                        <tr>
                            <td colspan="3" align="center" class="CobaltFieldCaptionTD"><strong><h3>Mantenimiento de Ex&aacute;menes de Laboratorio Cl&iacute;nico</h3></strong>
                            </td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD" >C&oacute;digo del Examen</td>
                            <td class="StormyWeatherDataTD"> <div id="divCodigo"><input type="text" id="txtidexamen"  name="txtidexamen" disabled="disabled" /></div></td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD" >C&oacute;digo del &Aacute;rea</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbArea" name="cmbArea" size="1" onChange="AsignarIdExamen(this.value);">
                                    <option value="0" >--Seleccione un &Aacute;rea--</option>
                                    <?php
                                        $consulta= $objeareas->consultaractivas($lugar);
					while($row = pg_fetch_array($consulta)){
                                            echo "<option value='" . $row['idarea']. "'>" . $row['nombrearea'] . "</option>";
					}
                                    ?>		  
				</select>
                            </td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD" >Nombre del Examen </td>
                            <td class="StormyWeatherDataTD"><input type="text" id="txtnombreexamen" name="txtnombreexamen" size="50" /></td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD" >Plantilla</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbPlantilla" name="cmbPlantilla" size="1">
                                    <option value="0">--Seleccione una Plantilla--</option>
                                        <?php
                                            $obje=new clsLab_Examenes;
                                            $consulta= $obje->LeerPlantilla();
                                            while($row = pg_fetch_array($consulta)){
						echo "<option value='" . $row[0]. "'>" . $row[1] . "</option>";
                                            }
					?>
				</select>
                            </td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD" >C&oacute;digo del Est&aacute;ndar</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbEstandar" name="cmbEstandar" size="1">
                                    <option value="0">--Seleccione un Est&aacute;ndar--</option>
					<?php
                                            $obje=new clsLab_CodigosEstandar;
                                            $consulta= $obje->consultar();
                                            while($row = pg_fetch_array($consulta)){
                                                echo "<option value='" . $row['0']. "'>" . $row['1'].'- '.$row['2'] . "</option>";
                                            }
								//mysql_free_result($row);		
					?>		 
				</select>   
                            </td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD" >Obsevaci&oacute;n </td>
                            <td class="StormyWeatherDataTD"><textarea name="txtobservacion" cols="60" id="txtobservacion"></textarea> </td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD"><strong>Solicitado en </strong> </td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbUbicacion" name="cmbUbicacion" size="1" >
                                    <option value="" >--Seleccione--</option>
                                    <option value="0" >Todas las procediencias</option>
                                    <option value="1" >Hospitalización y Emergencia</option>
                                    <option value="4" >Laboratorio</option>
				</select>
                            </td>
			</tr>
			
			<tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Formulario para Examen</td>
                            <td width="83%"  class="StormyWeatherDataTD">
                             
                                    <select name="cmbFormularios" id="cmbFormularios" size="1" > 
                                        <option value="0">Ninguno</option>
                                        <?php
                                            $consulta= $obj->consultar_formularios($lugar);
                                            while($row = pg_fetch_array($consulta)){
                                                echo "<option value='" . $row['0']. "'>" .$row['1'] . "</option>";
                                            }
                                        ?>	
                                    </select>
                                
				
                            </td>
                       </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD" >C&oacute;digo en tabulador</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbEstandarRep" name="cmbEstandarRep" size="1">
                                    <option value="0">--Seleccione un Est&aacute;ndar--</option>
                                        <?php
                                            $obje=new clsLab_CodigosEstandar;
                                            $consulta= $obje->consultar();
                                            while($row = pg_fetch_array($consulta)){
                                                echo "<option value='" . $row['0']. "'>" . $row['1'].'- '.$row['2'] . "</option>";
                                            }
								//mysql_free_result($row);		
					?>		 		
				</select>   
                            </td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD" >Tipo Etiqueta</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbEtiqueta" name="cmbEtiqueta" size="1" >
                                    <option value="0">--Seleccione un un tipo de etiqueta--</option>
                                    <option value="G">General</option>
                                    <option value="O">Especial</option>
				</select>
                            </td>
			</tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Examen Solicitado Urgente</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbUrgente" name="cmbUrgente" size="1">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>    
                            </td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Sexo al que se le realiza la prueba</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbsexo" name="cmbsexo" size="1">
                                     <option value="0">Ambos</option>
                                    <?php
                                        $obje1=new clsLab_Examenes;
                                        $consulta= $obje1->catalogo_sexo();
                                        while($row = pg_fetch_array($consulta)){
                                            echo "<option value='" . $row['0']. "'>" .$row['1'] . "</option>";
                                        }
				    ?>
                                </select>    
                            </td>
                         </tr>
                         <tr>
                            <td class="StormyWeatherFieldCaptionTD">Habilitar Prueba</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbHabilitar" name="cmbHabilitar" size="1">
                                    <option value="0">-- Seleccione Condici&oacute;n --</option>
                                    <option value="H">Habilitado</option>
                                    <option value="I">Inhabilitado</option>
                                </select>    
                            </td>
                         </tr>
                         <tr> 
                            <td nowrap class="StormyWeatherFieldCaptionTD">Tiempo Previo para <br>entrega de resultado(en dias)&nbsp;</td>
                            <td class="StormyWeatherDataTD">
                                <input id="inidate" name="inidate" class="CobaltInput" style="width:28px; height:20px;" maxlength=3 onkeypress='return isNumberKey(event);' ></td>
                        </tr>
                         <tr> 
                            <td colspan="2" align="right" class="StormyWeatherDataTD">
                                <input type="button" name="btnGuardar" value="Guardar" onClick="Guardar();">
				<input type="button" name="btnBuscar" value="Buscar" onClick="Buscar();">
				<input type="button" name="btnCancelar" value="Cancelar" onClick="Cancelar();">
                            </td>
			</tr>
                    </table>
		</form>
            </div>
	</td>
    </tr>
    <tr>
	<td>
            <div  id="divresultado" >

            </div>
	</td>
    </tr>
    <tr>
    	<td>
            <div  id="divFrmModificar" >

            </div>
	</td>
    </tr>
    <tr>
	<td>
            <div  id="divinicial" >

            </div>
	</td>
    </tr>
</table>
</body>
</html>
