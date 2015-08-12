<?php
session_start();
include_once("clsLab_Empleados.php");
$lugar = $_SESSION['Lugar'];
$corr  = $_SESSION['Correlativo'];
$area  = $_SESSION['Idarea'];

//consulta los datos por su id
$IdEmpleado = $_POST['idempleado'];
$obj        = new clsLab_Empleados;
$consulta   = $obj->consultarid($IdEmpleado, $lugar);
$row        = pg_fetch_array($consulta);

//valores de las consultas
$codempleado        = $row['idempleado'];
$idarea             = $row['idarea'];
$nombre             = $row['nombre'];
$apellido           = $row['apellido'];
$nombreempleado     = $row['nombreempleado'];
$nombrearea         = $row['nombrearea'];
$idcargoempleado    = $row['idcargoempleado'];
$cargo              = $row['cargo'];
$login              = $row['login'];
$idmodalidad        = $row['idmodalidad'];
$nombremodalidad    = $row['nombremodalidad'];
$id_area_mod_estab  = $row['id_area_mod_estab'];
$areamodestab       = $row['areamod'];
//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" >
    <input name="opcion" type="hidden" value="N" />
    <table width="57%" border="0" align="center" class="StormyWeatherFormTABLE">
        <tr>
            <td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Empleados de Laboratorio Cl&iacute;nico</strong></h3>
            </td>
        </tr>         
        <tr>
            <td class="StormyWeatherFieldCaptionTD" width="40%">C&oacute;digo del Empleado </td>
            <td class="StormyWeatherDataTD" width="60%"><input type="text" id="txtidempleado" name="txtidempleado" value="<?php echo $codempleado; ?>" disabled="disabled" style="width:60%"  class="form-control height placeholder" /></td>
        </tr>
        <tr>
            <td class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
            <td class="StormyWeatherDataTD">
                <select id="cmbArea" name="cmbArea" size="1" style="width:60%" class="form-control height" >			 
                    <?php
                    include('../Lab_Areas/clsLab_Areas.php');
                    $objeareas = new clsLab_Areas;
                    $consulta = $objeareas->consultarareas($lugar);
                    while ($row = pg_fetch_array($consulta)) {
                        echo "<option value='" . $row['idarea'] . "'>" . htmlentities($row['nombrearea']) . "</option>";
                    }
                    echo "<option value='" . $idarea . "' selected='selected'>" . htmlentities($nombrearea) . "</option>";
                    ?>		  
                </select>
            </td>
        </tr>
        <tr>
            <td class="StormyWeatherFieldCaptionTD"> Modalidad de Contrato</td>
            <td class="StormyWeatherDataTD">
                <select id="cmbModalidad" name="cmbModalidad" size="1" style="width:60%" class="form-control height">
                    <?php
                        $objeareas = new clsLab_Empleados;
                        $consulta = $objeareas->ModificarModalidad($lugar,$idmodalidad);
                        while ($row = pg_fetch_array($consulta)) {
                            
                           /* if($row[0] == $idmodalidad)
                                echo "<option value='" . $idmodalidad . "' selected='selected'>" . htmlentities($nombremodalidad) . "</option>";
                            else*/
                                echo "<option value='" . $row[0] . "'>" . htmlentities($row[1]) . "</option>";
                        }
                       echo "<option value='" . $idmodalidad . "' selected='selected'>" . htmlentities($nombremodalidad) . "</option>";
                    ?>        
                </select>        
            </td>
        </tr>
        <tr>
            <td class="StormyWeatherFieldCaptionTD"> Fondo de Contratación</td>
            <td class="StormyWeatherDataTD">
                <select id="cmbPago" name="cmbPago" size="1" style="width:60%" class="form-control height">
                    <option value="0" >--Seleccione Fondo de Contratación--</option>
                    <?php
                        $conempleador = $obj-> modificar_empleador($lugar,$id_area_mod_estab);
                        while ($row = pg_fetch_array($conempleador)) {
                            echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
                        }
                         echo "<option value='" . $id_area_mod_estab  . "' selected='selected'>" . htmlentities($areamodestab) . "</option>";
                    ?>    
                </select>        
                                    </td>
                                </tr>
        <tr>
            <td class="StormyWeatherFieldCaptionTD">Nombre del Empleado</td>
            <td class="StormyWeatherDataTD"><input type="text" id="txtnombreempleado" style="width:60%" name="txtnombreempleado" class="form-control height placeholder"  size="40" value="<?php echo htmlentities($nombre); ?>" /></td>
        </tr>
        <tr>
            <td class="StormyWeatherFieldCaptionTD">Apellido del Empleado</td>
            <td class="StormyWeatherDataTD"><input type="text" id="txtapellido" style="width:60%" name="txtapellido" class="form-control height placeholder"  size="40" value="<?php echo htmlentities($apellido); ?>" /></td>
        </tr>
        <tr>
            <td class="StormyWeatherFieldCaptionTD">Cargo</td>
            <td class="StormyWeatherDataTD">
                <select id="cmbCargo" name="cmbCargo" size="1" style="width:60%" class="form-control height">
                    <option value="0">--Seleccione Cargo--</option>";
                    <?php
                    require_once('clsLab_Empleados.php');
                    $objeareas = new clsLab_Empleados;
                    $consulta = $objeareas->LeerCargos();
                    while ($row = pg_fetch_array($consulta)) {
                        echo "<option value='" . $row['idcargoempleado'] . "'>" . htmlentities($row['cargo']) . "</option>";
                    }
                    echo "<option value='" . $idcargoempleado . "' selected='selected'>" . $cargo . "</option>";
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="StormyWeatherFieldCaptionTD">Usuario</td>
            <td class="StormyWeatherDataTD"><input type="text" id="txtlogin"  style="width:60%" class="form-control height placeholder"  name="txtlogin" size="40" value="<?php echo $login; ?>" /></td>
        </tr>
       <!-- <tr>
            <td class="StormyWeatherDataTD" colspan="2" align="right" >
                <input type="button" name="btnActualizar" value="Actualizar" onclick="Modificar();" />
                <input type="button" name="btnNuevo" value="Nuevo" onClick="window.location.replace('MntEmpleados.php')" />
            </td>
        </tr>-->
        
                         <tr>  
                            <td class="StormyWeatherDataTD" colspan="6" align="right">
                                <button type='button' align="center" class='btn btn-primary'   onclick="Modificar();"><span class='glyphicon glyphicon-floppy-disk'></span> Actualizar </button>
                                <button type='button' align="center" class='btn btn-primary' onClick="window.location.replace('MntEmpleados.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                            </td>
                         </tr>
    </table> 
</form>
