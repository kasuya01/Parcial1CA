<?php
session_start();
include_once("clsLab_Programas.php");
$lugar = $_SESSION['Lugar'];
$usuario = $_SESSION['Correlativo'];
$area = $_SESSION['Idarea'];
//consulta los datos por su id
$IdPrograma = $_POST['IdPrograma'];
//echo $IdPrograma;
$obj = new clsLab_Programas;
$consulta = $obj->consultarid($IdPrograma);
$row = mysql_fetch_array($consulta);

//valores de las consultas
$Programa = $row[1];

//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" action="" 
      onsubmit="enviarDatos();
        return false">
    <input name="opcion" type="hidden" value="N" />
    <table width="40%" border="0" align="center" class="StormyWeatherFormTABLE">

        <tr>
            <td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Programas</strong></h3>
            </td>
        </tr><tr>
            <td class="StormyWeatherFieldCaptionTD">IdPrograma</td>
            <td class="StormyWeatherDataTD"><input name="IdPrograma" id="IdPrograma" type="text" value="<?php echo $IdPrograma; ?>" disabled="disabled" /></td>
        </tr>
        <tr>
            <td class="StormyWeatherFieldCaptionTD">Programa</td>
            <td class="StormyWeatherDataTD"><input name="Programa" id="Programa" type="text" value="<?php echo htmlentities($Programa); ?>" size="50" />
            </td>    
        </tr>
        <td class="StormyWeatherDataTD" colspan="2" align="right">
            <input type="submit" name="btnActualizar" value="Actualizar" />
            <input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onClick="window.location.replace('MntProgramas.php')">
        </td>
    </table>
</form>

