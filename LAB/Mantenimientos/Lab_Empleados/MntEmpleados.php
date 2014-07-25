<?php
session_start();
include('clsLab_Empleados.php');
include('../Lab_Areas/clsLab_Areas.php');
$nivel = $_SESSION['NIVEL'];
$corr  = $_SESSION['Correlativo'];
$lugar = $_SESSION['Lugar'];
$area  = $_SESSION['Idarea'];
?>
<html>
    <head>

        <meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
        <title>Mantenimiento de Empleados de Laboratorio</title>
        <script language="JavaScript" type="text/javascript" src="ajax_Lab_Empleados.js"></script>
        <link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
        <link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
        <script language="JavaScript" >
            function Guardar() {
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
            function Nuevo()
            {
                MostrarFormularioNuevo();
            }
            function Buscar()
            {
                BuscarDatos();
            }
            function Cancelar()
            {
                LimpiarCampos();
                show_event(1);
            }
        </script>
    </head>

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
                    <div id="divOculto">
                        <input type="hidden" name="txtoculto" id="txtoculto" value="NNN"/>
                    </div>
                    <div  id="divFrmNuevo" >
                        <form name="frmnuevo" >
                            <table width="57%" border="0" align="center" class="StormyWeatherFormTABLE">
                                <tr>
                                    <td colspan="2" align="center" class="CobaltFieldCaptionTD"><h3><strong>Mantenimiento de Empleados de Laboratorio Cl&iacute;nico</h3></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td  class="StormyWeatherFieldCaptionTD" width="40%">C&oacute;digo del Empleado</td>
                                    <td width="60%" class="StormyWeatherDataTD"> <div id="divCodigo"><input type="text" id="txtidempleado"  name="txtidempleado" disabled="disabled" /></div></td>
                                </tr>
                                <tr>
                                    <td class="StormyWeatherFieldCaptionTD"> &Aacute;rea</td>
                                    <td class="StormyWeatherDataTD">
                                        <select id="cmbArea" name="cmbArea" size="1" onclick="AsignarCodigoEmpleado();" >
                                            <option value="0" >--Seleccione un &Aacute;rea--</option>
                                            <?php
                                            $objeareas = new clsLab_Areas;
                                            $consulta = $objeareas->consultarareas($lugar);
                                            while ($row = mysql_fetch_array($consulta)) {
                                                echo "<option value='" . $row['IdArea'] . "'>" . $row['NombreArea'] . "</option>";
                                            }
                                            ?>		  
                                        </select>		 
                                    </td>
                                </tr>
                                <tr>
                                    <td class="StormyWeatherFieldCaptionTD">Nombre del Empleado</td>
                                    <td class="StormyWeatherDataTD"><input type="text" id="txtnombre" name="txtnombre" size="40" /></td>
                                </tr>
                                <tr>
                                    <td class="StormyWeatherFieldCaptionTD">Cargo</td>
                                    <td class="StormyWeatherDataTD">
                                        <select id="cmbCargo" name="cmbCargo" size="1">
                                            <option value="0">--Seleccione Cargo--</option>
                                            <?php
                                            $objeareas = new clsLab_Empleados;
                                            $consulta = $objeareas->LeerCargos();
                                            while ($row = mysql_fetch_array($consulta)) {
                                                echo "<option value='" . $row['IdCargoEmpleado'] . "'>" . $row['Cargo'] . "</option>";
                                            }
                                            ?>	
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <TD class="StormyWeatherFieldCaptionTD">Usuario</TD>
                                    <td class="StormyWeatherDataTD"><input type="text" id="txtlogin" name="txtlogin" size="40" /></td>
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
