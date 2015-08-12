<?php
session_start();
$usuario = $_SESSION['Correlativo'];
$lugar   = $_SESSION['Lugar'];
$area    = $_SESSION['Idarea'];
include_once("clsLab_Areas.php");
//echo $lugar;
//consulta los datos por su id
$idarea   = $_POST['idarea'];
$objareas = new clsLab_Areas;
$consulta = $objareas->consultarid($idarea, $lugar);
$row = pg_fetch_array($consulta);

//valores de las consultas
$nombre = $row['nombrearea'];
$activo = $row['condicion'];
$tipo   = $row['administrativa'];
//muestra los datos consultados en los campos del formulario
//echo $activo;
?>

<form name= "frmModificar" action="" onSubmit="enviarDatos();
        return false">
    <input name="opcion" type="hidden" value="N" />
    <table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
        <tr>
            <td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de &Aacute;reas de Laboratorio Cl&iacute;nicos</strong></h3>
            </td>
        </tr>
        <tr>
            <td class="StormyWeatherFieldCaptionTD">C&oacute;digo del &Aacute;rea</td>
            <td class="StormyWeatherDataTD"><input name="idarea" type="text" value="<?php echo $idarea; ?>" disabled="disabled"/>
            </td>
        </tr>
        <tr>
            <td class="StormyWeatherFieldCaptionTD">Nombre del &Aacute;rea</td>
            <td class="StormyWeatherDataTD"><input name="nombrearea" size="50" type="text" value="<?php echo ($nombre); ?>" /></td>    
        </tr>
        <tr>
            <td class="StormyWeatherFieldCaptionTD">Activo </td>
            <td class="StormyWeatherDataTD">
                <select id="cmbActiva" name="cmbActiva" size="1" >
                    <?php
                    //include('clsLab_Areas.php');
                    echo $activo;
                    if ($activo == "H") {
                        echo "<option value='$activo' selected='selected'> SI </option>";
                        echo "<option value='I' >NO</option>";
                    } else {
                        echo "<option value='$activo' selected='selected'> NO </option>";
                        echo "<option value='H'>SI</option>";
                    }
                    ?>
            </td>
        </tr>
        <tr>
            <td class="StormyWeatherFieldCaptionTD">Tipo &Aacute;rea </td>
            <td class="StormyWeatherDataTD">
                <select id="cmbTipo" name="cmbTipo" size="1" >
                    <?php
                    if ($tipo == "S") {
                        echo "<option value='" . $tipo . "' selected='selected'> Administrativo</option>";
                        echo "<option value='N' >Tecnico</option>";
                    } else {
                        echo "<option value='" . $tipo . "' selected='selected'> Tecnico</option>";
                        echo "<option value='S' >Administrativo</option>";
                    }
                    ?>
            </td>


        </tr>
        <tr>
            <td colspan="2" align="right" class="StormyWeatherDataTD">
                <input type="submit" align="right" name="btnActualizar" value="Actualizar" />
                <input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onClick="window.location.replace('MntAreasLaboratorio.php')"></td>
        </tr>

    </table>
</form>

