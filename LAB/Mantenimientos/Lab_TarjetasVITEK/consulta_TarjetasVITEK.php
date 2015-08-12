<?php
session_start();
include_once("clsLab_TarjetasVITEK.php");
$lugar = $_SESSION['Lugar'];
$usuario = $_SESSION['Correlativo'];
$area = $_SESSION['Idarea'];
//echo $lugar;
//consulta los datos por su id
$idtarjeta = $_POST['idtarjeta'];
$obj = new clsLab_TarjetasVITEK;
$consulta = $obj->consultarid($idtarjeta, $lugar);
$row = pg_fetch_array($consulta);
//valores de las consultas
$nombretarjeta = $row[1];
$Fechaini = $row[2];
$Fechafin = $row[3];
?>

<form name= "frmModificar">
    <input name="opcion" type="hidden" value="N" />
    <table width="60%" border="0" align="center" class="StormyWeatherFormTABLE">
        <tr>
            <td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Tarjetas VITEK</strong></h3>
            </td>
        </tr>	
        <!--tr>
            <td  width="21%" class="StormyWeatherFieldCaptionTD">IdTarjeta</td>
            <td   class="StormyWeatherDataTD"><input name="idtarjeta" id="idtarjeta" type="text" value="<?php echo $idtarjeta; ?>" disabled="disabled" /></td>
        </tr-->
        <tr>
        <td   width="21%" class="StormyWeatherFieldCaptionTD">Nombre Tarjeta</td>
            <td class="StormyWeatherDataTD"> <input name="nombretarjeta" id="nombretarjeta" type="text" value="<?php echo $nombretarjeta; ?>" class="form-control height placeholder" />
                <input name="idtarjeta" id="idtarjeta" type="hidden" value="<?php echo $idtarjeta; ?>" disabled="disabled" />
            </td>    
        </tr>
        <tr>
            <td colspan="2" class="StormyWeatherDataTD">
                <table width="100%" border="0" align="center" class="StormyWeatherFormTABLE">
                    <tr>
                        <td  width="20%" class="StormyWeatherFieldCaptionTD">Fecha Inicio</td>
                        <td width="30%" class="StormyWeatherDataTD"><input name="txtFechainicio1" type="text" id="txtFechainicio" value="<?php echo $Fechaini; ?>" class="form-control height placeholder" size="69"></td>
                        <td width="20%" class="StormyWeatherFieldCaptionTD">Fecha Final</td>
                        <td width="30%" class="StormyWeatherDataTD">
                           <?php
                           if ($Fechafin=="") {
                           ?>    
                             <input name="txtFechaFin1" type="text" id="txtFechaFin"    placeholder="Ingrese fecha dd/mm/aaa"  class="date form-control height placeholder" >
                          <?php  } 
                          else {
                              ?>
                             <input name="txtFechaFin1" type="text" id="txtFechaFin" value="<?php echo $Fechafin; ?>" class="form-control height placeholder" >
                         <?php
                          }
                                    ?>
                            </td>
                    </tr>
                </table>
            </td>		
        </tr>
        <!--<tr>
            <td class="StormyWeatherDataTD" colspan="2" align="right">
                <input type="button" name="btnActualizar" value="Actualizar" onclick="ActualizarDatos();" />
                <input type="button" name="btnNuevo" value="Nuevo" onClick="window.location.replace('MntTarjetasVITEK.php')" />
            </td>
        </tr>-->
        
        <tr>
                <td class="StormyWeatherDataTD" colspan="6" align="right">
                                
                                <button type='button' align="center" class='btn btn-primary'  onclick="ActualizarDatos();"><span class='glyphicon glyphicon-repeat'></span> Actualizar</button>
                                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntTarjetasVITEK.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                
                </td>
        </tr>  
    </table>
</form>

