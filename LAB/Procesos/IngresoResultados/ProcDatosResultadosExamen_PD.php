<?php session_start();
include_once("../../../Conexion/ConexionBD.php");
$db = new ConexionBD;
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

 $nec = $_GET['var1']; 
 $examen = $_GET['var2'];
 $codigoex = $_GET['var3'];
 $area= $_GET['var4'];		   
 $iddetallesol = $_GET['var5'];  
 $idsolicitud = $_GET['var6']; 
 $paciente = $_GET['var7'];
 $idrecepcionsol = $_GET['var8'];
 $nombrearea =$_GET['var9'];
 $procedencia = $_GET['var10'];
 $origen = $_GET['var11'];
 $impresion = $_GET['var12'];
 $establecimiento = $_GET['var13'];
 $fechanac = $_GET['var14'];
 $sexo = $_GET['var15'];
 $IdEstandar = $_GET['var16'];
 $IdHistorial = $_GET['var17'];
 $estabext = $_GET['var18'];
   
if($db->conectar()==true) {
           $condatos = "SELECT t07.peso,t07.talla,t06.sct_name_es AS diagnostico,especificacion,conocido_por
                        FROM sec_historial_clinico               t01
                        INNER JOIN mnt_expediente                t02 ON (t02.id = t01.id_numero_expediente)
                        INNER JOIN mnt_paciente                  t03 ON (t03.id = t02.id_paciente)
                        LEFT OUTER JOIN sec_diagnostico_paciente t04 ON (t01.id = t04.id_historial_clinico)
                        LEFT OUTER JOIN mnt_snomed_cie10         t06 ON (t06.id = t04.id_snomed)
                        LEFT OUTER JOIN sec_signos_vitales       t07 ON (t01.id = t07.id_historial_clinico)
                        WHERE t01.id = $IdHistorial AND t01.idestablecimiento = $lugar";

            $resultado = pg_query($condatos);
            $rows = pg_fetch_array($resultado);

            $Peso=  $rows['peso'];
            $Talla= $rows['talla'];
            $Diagnostico= $rows['diagnostico'];
            $Especificacion= $rows['especificacion'];
            $ConocidoPor= $rows['conocido_por'];
          //  echo $Diagnostico." - ".$Especificacion;
}

?>
<html>
<head> 
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Resultados de Ex&aacute;menes de Laboratorio</title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<link type="text/css" href="../../../public/jquery-ui-1.10.3.custom/css/cupertino/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
<link type="text/css" href="../../../public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
<script language="JavaScript" >
function Guardar(){
   	IngresarRegistro();
}
function LlenarComboExamen(idArea)
{
  LlenarExamenes(idArea);
}
function Buscar(){
	BuscarDatos();
}
function Actualizar(){
	enviarDatos();
}
function Nuevo(){
	MostraFormularioNuevo();
}
function Cancelar()
{
    show_event(1);
}
function calc_edad()
{
  var fecnac1=document.getElementById("suEdad").value;
  var fecnac2=fecnac1.substring(0,10);
  var suEdades=calcular_edad(fecnac2);
  document.getElementById("divsuedad").innerHTML=suEdades;
}

function CargarDatos(){  
	
	LlenarComboResponsable(document.getElementById('txtarea').value);
	
}
<?php   
 
	$bandera=$_GET['var12'];
        $IdEstandar=$_GET['var16'];
        echo $_GET['var4']; 
        //echo $IdEstandar;
        //$db = new ConexionBD;
        /*if($db->conectar()==true) {
           $condatos = "SELECT t07.peso,t07.talla,t06.sct_name_es AS diagnostico,especificacion,conocido_por
                        FROM sec_historial_clinico               t01
                        INNER JOIN mnt_expediente                t02 ON (t02.id = t01.id_numero_expediente)
                        INNER JOIN mnt_paciente                  t03 ON (t03.id = t02.id_paciente)
                        LEFT OUTER JOIN sec_diagnostico_paciente t04 ON (t01.id = t04.id_historial_clinico)
                        LEFT OUTER JOIN mnt_snomed_cie10         t06 ON (t06.id = t04.id_snomed)
                        LEFT OUTER JOIN sec_signos_vitales       t07 ON (t01.id = t07.id_historial_clinico)
                        WHERE t01.id = $IdHistorial AND t01.idestablecimiento = $lugar";

            $resultado = pg_query($condatos);
            $rows = pg_fetch_array($resultado);

            $Peso=  $rows['peso'];
            $Talla= $rows['talla'];
            $Diagnostico= $rows['diagnostico'];
            $Especificacion= $rows['especificacion'];
            $ConocidoPor= $rows['conocido_por'];
            echo $Diagnostico." - ".$Especificacion;
        }*/
       
?>
</script>
</head>

<body onLoad="CargarDatos();">
<table align="center" width="100%">
<tr>
    <td>
        <div  id="divFrmNuevo" >
            <form name="frmnuevo">
                <table width="70%" border="0" align="center" class="StormyWeatherFormTABLE">
                    <tr class="CobaltButton" ><td colspan="4" align="center"> <h3>DATOS GENERALES</h3></td></tr>
                    <tr>
                        <td class="StormyWeatherFieldCaptionTD">Establecimiento</td>
                        <td class="StormyWeatherDataTD"><?php echo $_GET['var18'];?></td>
                    </tr>	    

                    <tr>
                            <td width="30%" class="StormyWeatherFieldCaptionTD">NEC</td>
                            <td width="70%" class="StormyWeatherDataTD"><?php echo $nec ?>
                            <input type="hidden" name="txtnec" id="txtnec" disabled="disabled" />
                            <input type="hidden" name="txtidsolicitud" id="txtidsolicitud" value="<?php echo $idsolicitud;?>"/>
                            <input type="hidden" name="txtiddetalle" id="txtiddetalle" value="<?php echo $iddetallesol;?>"/>
                            <input type="hidden" name="txtidexamen" id="txtidexamen" value="<?php echo $codigoex;?> "/>
                            <input type="hidden" name="txtidrecepcion" id="txtidrecepcion" value="<?php echo $idrecepcionsol;?> " />
                            <input type="hidden" name="txtarea" id="txtarea"  value="<?php echo $area;?>">
                            <input type="hidden" name="txtprocedencia" id="txtprocedencia" value="<?php echo $procedencia;?>"/>
                            <input type="hidden" name="txtorigen" id="txtorigen"  value="<?php echo $origen;?>"/>
                            <input type="hidden" name="txtestablecimiento" id="txtestablecimiento" value="<?php echo $establecimiento;?>" />	
                            <input type="hidden" name="txtIdEstandar" id="txtIdEstandar" value="<?php echo $IdEstandar?>" />
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" class="StormyWeatherFieldCaptionTD">Paciente</td>
                        <td width="70%" class="StormyWeatherDataTD"> <?php echo $paciente?>
                                <input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" /></td>
                    </tr>
                    <tr>
                        <td class="StormyWeatherFieldCaptionTD">Procedencia</td>
                        <td class="StormyWeatherDataTD"><?php echo  $procedencia;?></td>
                    </tr>
                    <tr>
                        <td class="StormyWeatherFieldCaptionTD">Origen</td>
                        <td class="StormyWeatherDataTD"><?php echo $origen;?></td>
                    </tr>
                    <tr>
                        <td class="StormyWeatherFieldCaptionTD">Diagnostico</td>
                        <td colspan="3" class="StormyWeatherDataTD"><?php echo htmlentities($Diagnostico);?>
                                 <input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" />
                        </td>
                    </tr>
                    <tr>
                        <td class="StormyWeatherFieldCaptionTD">Datos Clinicos</td>
                        <td colspan="3" class="StormyWeatherDataTD"><?php echo $Especificacion;?>
                                <input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" />
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
                        <td width="70%" class="StormyWeatherDataTD"><?php echo $nombrearea ?>
                                <input type="hidden" name="txtnombrearea" id="txtnombrearea" disabled="disabled" size="60" />  </td>
                    </tr>
                    <tr>
                        <td width="30%" class="StormyWeatherFieldCaptionTD">Examen </td>
                        <td width="70%" class="StormyWeatherDataTD"><?php echo $_GET['var2'] ?>
                            <input type="hidden" name="txtexamen" id="txtexamen" disabled="disabled" size="60" />

                        </td>
                    </tr>
                    <tr>
                        <td width="30%" class="StormyWeatherFieldCaptionTD">*Validado Por</td>
                        <td width="70%" class="StormyWeatherDataTD">
                            <div id="divEncargado">
                                <select id="cmbEmpleados" name="cmbEmpleados" size="1" >
                                    <option value="0" >--Seleccione Empleado--</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="StormyWeatherFieldCaptionTD">Fecha y hora inicio Proceso</td>
                        <td class="StormyWeatherDataTD">
                            <input type="text" class="datepicker" id="txtresultrealiza"  name="txtresultrealiza" size="15">										
                        </td>
                    </tr>
                    <tr>
                        <td class="StormyWeatherFieldCaptionTD">Fecha Resultado</td>
                        <td class="StormyWeatherDataTD" >
                                        <input type="text" class="datepicker" name="txtresultfin" id="txtresultfin" size="15"  value="<?php echo date("Y-m-d h:m"); ?>"  />	
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" class="StormyWeatherFieldCaptionTD">Elemento Encontrado </td>
                        <td width="70%" class="StormyWeatherDataTD">
                            <select id="cmbElemento" name="cmbElemento" size="1" >
                                <option value="0" >--Seleccione Elemento--</option>
                                    <?php
                                        include('clsPlantillaD.php');
                                        $obj=new clsPlantillaD;
                                        $consulta_bac= $obj->LeerElementosTincion();
                                        while($row = pg_fetch_array($consulta_bac)){
                                            echo "<option value='" . $row['id']. "'>" . $row['elementotincion'] . "</option>";
                                        }
                                        pg_free_result($consulta_bac);
                                        ?>	
                                        </select>	
                        </td>
                    </tr>		  
                    <tr>
                        <td width="20%" class="StormyWeatherFieldCaptionTD">Cantidad</td>
                        <td width="80%" class="StormyWeatherDataTD">
                            <select id="cmbCantidad" name="cmbCantidad" size="1" >
                                <option value="0" >--Seleccione Cantidad--</option>
                                    <?php
                                            $obj=new clsPlantillaD;
                                            $consulta_bac= $obj->LeerCantidadTincion();
                                            while($row = pg_fetch_array($consulta_bac)){
                                            echo "<option value='" . $row[0]. "'>" . $row[1] . "</option>";
                                            }
                                            pg_free_result($consulta);
                                    ?>	
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td  class="StormyWeatherFieldCaptionTD">*Resultado Tabulador</td>
                        <td width="65%" class="StormyWeatherDataTD">
                            <select id="cmbResultado2" name="cmbResultado2" size="1">
                                <option value="0" >--Seleccione Resultado--</option>
                                    <?php 
                                          //  $db = new ConexionBD;
                                        if($db->conectar()==true){
                                            $consulta = "SELECT idresultado,resultado 
                                                    FROM lab_codigosxexamen
                                                    INNER JOIN ctl_examen_servicio_diagnostico ON ctl_examen_servicio_diagnostico.id = lab_codigosxexamen.idestandar 
                                                    INNER JOIN lab_codigosresultados ON lab_codigosresultados.id = lab_codigosxexamen.idresultado
                                                    WHERE ctl_examen_servicio_diagnostico.idestandar='$IdEstandar'";
                                            //        echo $consulta;
                                            $resultado = pg_query($consulta);
                                            while ($rows = pg_fetch_array($resultado)){
                                                            echo '<option value="' . $rows[0] . '">' . $rows[0] . '  -  ' . $rows[1] . '</option>'; 
                                            }
      
                                        }
                                                    ?>
                            </select>
                        </td>
                    </tr>
                <?php 
                if ($bandera==1){
                ?>
                    <tr>
                        <td colspan="2" class="StormyWeatherDataTD" align="center" style="color:#DD0000; font:bold"><h3>El m&eacute;dico ha solicitado la impresi&oacute;n de este Resultado </h3></td>
                    </tr>
                              <?php 
                              }?>
                    <tr>
                        <td colspan="2" class="StormyWeatherDataTD" align="rigth">
                                    <div id="divBotonGuardar"  align="rigth">
                                   <input type="button" name="btnGuardar" value="Guardar Resultado" Onclick="GuardarPlantillaD()">
                                </div>  
                        </td>
                    </tr>
                    <tr>
                            <td colspan="2" class="StormyWeatherDataTD" align="rigth">
                              <div id="divBotonPrevie" style="display:none" >
                                <table width="100%" >
                                      <td class="StormyWeatherDataTD" colspan="2" align="rigth">
                                              <input type="button" name="btnGuardar" value="Agregar Elemento" Onclick="GuardarElemento()">
                                              <input type="button" name="btnVista"   value="Vista Previa" Onclick="VistaPrevia()">
                                      </td>
                                </table>
                              </div>		  
                            </td>
                    </tr>
	</table>
    <script type="text/javascript" src="../../../public/datepicker/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="../../../public/datepicker/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../../../public/datepicker/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="../../../public/datepicker/jquery-ui-timepicker-addon-i18n.min.js"></script>
    <script type="text/javascript" src="../../../public/datepicker/jquery-ui-timepicker-es.js"></script>
    <script type="text/javascript" src="../../../public/datepicker/jquery-ui-sliderAccess.js"></script>
    <script type="text/javascript" src="../../../public/datepicker/script.js">
</script>   
		   
</form>
</div></td>
</tr>
<tr>
<td align="center">
<div  id="divresultado" >
</div>

</td>
</tr>
<tr>
<td align="center">
<div  id="divpreview" >
</div>
</td>
</tr>
</table>
</body>
</html>
