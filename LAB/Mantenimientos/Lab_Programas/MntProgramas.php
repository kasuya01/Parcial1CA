<?php
session_start();
$nivel = $_SESSION['NIVEL'];
$corr  = $_SESSION['Correlativo'];
$lugar = $_SESSION['Lugar'];
$area  = $_SESSION['Idarea'];
?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
        <title>Mantenimiento de Programas</title>
        <script language="JavaScript" type="text/javascript" src="ajax_Lab_Programas.js"></script>
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
        if ($nivel == 5) {
            include_once ('../../../PaginaPrincipal/index_laboratorio52.php');}
        if ($nivel == 6) {
            include_once ('../../../PaginaPrincipal/index_laboratorio62.php');}
        if ($nivel == 7) {
            include_once ('../../../PaginaPrincipal/index_laboratorio72.php'); } 
        ?><br>
        <table align="center" width="100%">
            <tr>
                <td>
                    <div  id="divFrmNuevo" >
                        <form name="frmnuevo" id="frmnuevo">
                            <table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
                                <tr>
                                    <td colspan="2" class="CobaltFieldCaptionTD" align="center">
                                        <h3><strong>Mantenimiento de Programas de Salud</strong></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="StormyWeatherFieldCaptionTD">IdPrograma</td>
                                    <td class="StormyWeatherDataTD"><input type="text" name="txtIdPrograma" id="txtIdPrograma" disabled="disabled" /></td>
                                </tr>
                                <tr>
                                    <td class="StormyWeatherFieldCaptionTD">Programa de Salud </td>
                                    <td class="StormyWeatherDataTD"><input type="text" name="txtPrograma" id="txtPrograma" size="50" /></td>
                                </tr>
                                <tr>
                                    <td class="StormyWeatherDataTD" colspan="2" align="right">
                                        <input type="button" name="Guardar" value="Guardar" onClick="IngresarRegistro();">
                                        <input type="button" name="Buscar" value="Buscar" onClick="BuscarCodigo();">
                                        <input type="button" name="btnNuevo" id="btnNuevo" value="Cancelar" onClick="window.location.replace('MntProgramas.php')"></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
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
