<?php
session_start();
$nivel = $_SESSION['NIVEL'];
$corr  = $_SESSION['Correlativo'];
$lugar = $_SESSION['Lugar'];
$area  = $_SESSION['Idarea'];
include('clsLab_Formularios.php');
?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
        <title>Mantenimiento de Formulario</title>
        <script language="JavaScript" type="text/javascript" src="ajax_Lab_Formularios.js"></script>
        <link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
        <link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
    </head>
    <script language="JavaScript" >
        function Guardar() {
            IngresarRegistro();
        }
    </script>
    <body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="show_event(1);">
        <?php
        if ($nivel == 1) {
            include_once ('../../../PaginaPrincipal/index_laboratorio2.php');
        }
        if ($nivel == 2) {
            include_once ('../../../PaginaPrincipal/index_laboratorio22.php');
        }
        if ($nivel == 31) {
            include_once ('../../../PaginaPrincipal/index_laboratorio31.php');
        }
        if ($nivel == 33) {
            include_once ('../../../PaginaPrincipal/index_laboratorio33.php');
        }
        ?><br>
        <table align="center" width="100%">
            <tr>
                <td>
                    <div  id="divFrmNuevo" >
                        <form name="frmnuevo" id="frmnuevo">
                            <table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
                                <tr>
                                    <td colspan="2" class="CobaltFieldCaptionTD" align="center">
                                        <h3><strong>Mantenimiento de Formularios por Programa</strong></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="StormyWeatherFieldCaptionTD">IdFormulario</td>
                                    <td class="StormyWeatherDataTD"><input type="text" name="txtIdForm" id="txtIdForm" disabled="disabled" /></td>
                                </tr>
                                <tr>
                                    <td class="StormyWeatherFieldCaptionTD">Nombre del Formulario </td>
                                    <td class="StormyWeatherDataTD"><input type="text" name="txtForm" id="txtForm" size="50" /></td>
                                </tr>
                                <tr>
                                    <td class="StormyWeatherFieldCaptionTD">Programa</td>
                                    <td class="StormyWeatherDataTD">
                                        <select id="cmbPrograma" name="cmbPrograma" size="1"  >
                                            <option value="0" >--Seleccione un programa--</option>
                                            <?php
                                            $obje = new clsLab_Formularios;
                                            $consulta = $obje->consultarProgramas($lugar);
                                            while ($row = pg_fetch_array($consulta)) {
                                                echo "<option value='" . $row['idprograma'] . "'>" . $row['nombreprograma'] . "</option>";
                                            }
                                            ?>		  
                                        </select>		 
                                    </td>
                                </tr>
                                <tr>
                                    <td class="StormyWeatherDataTD" colspan="2" align="right">
                                        <input type="button" name="Guardar" value="Guardar" onClick="IngresarRegistro();">
                                        <input type="button" name="Buscar" value="Buscar" onClick="BuscarCodigo();">
                                        <input type="button" name="btnNuevo" id="btnNuevo" value="Cancelar" onClick="window.location.replace('MntFormularios.php')">
                                    </td>
                                </tr>
                            </table>
                        </form>
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
