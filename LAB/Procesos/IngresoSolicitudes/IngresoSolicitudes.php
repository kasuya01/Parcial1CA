<?php session_start();
// include("../indexCitas2.php");
include_once("../../../Conexion/ConexionBD.php");
$IdEstablecimiento = $_SESSION['Lugar'];
$LugardeAtencion = $_SESSION['Modalidad'];
$conn = new ConexionBD;
/*
 * HOJA PRINCIPAL DE FILTRACION DE EXAMENES PARA GENERAR LA SOLICITUD
 * SE RECIBE EL IdNumeroExp DEL PACIENTE Y EL IdHistorialClinico
 */
if (!isset($_SESSION['iduser'])) {
    ?>
    <script language="javascript">
        alert("La sesion ha caducado \n inicie sesion nuevamente!");
        window.close();
    </script>
    <?php
} else {

    if (!isset($_GET["IdNumeroExp"])) {
        ?>
        <script language="javascript">
            alert("No hay datos iniciales para realizar procesos!");
            window.close();
        </script>
    <?php
    } else {

        $IdNumeroExp = $_GET["IdNumeroExp"];
        ?>
        <html>
            <head>
                <script type="text/javascript" src="IncludeFiles/Ajax.js"></script>
                <!-- AUTOCOMPLETAR -->
                <script type="text/javascript" src="scripts/prototype.js"></script>
                <script type="text/javascript" src="scripts/autocomplete.js"></script>
                <link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
                <!--  -->

            </head>
            <body onload="DatosPaciente('<?php echo $IdNumeroExp; ?>');">
            <center>
                <br/>
                <table width="650">
                    <tr><td colspan="2" align="right"><a href="#" onclick="Terminar(<?= $IdNumeroExp;?>);">CERRAR</a></td></tr>
                    <tr>
                        <td colspan="2"><div id="avisos_errores" style="color:red;"></div></td>
                    </tr>
                    <tr>
                        <td colspan="2">Datos Generales del Paciente</td>
                    </tr>
                    <tr>
                        <td>Expediente:</td><td><?= $IdNumeroExp; ?></td>
                    </tr>
                    <tr>
                        <td>Nombre:</td><td><div id="NombrePaciente"></div></td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr/></td>
                    </tr>
                </table>
                <table width="650" style="border-color:black;border:solid;">
                    <tr>
                        <td colspan="2">INGRESO DE SOLICITUD DE ESTUDIOS</td>
                    </tr>
                    <tr>
                        <td width="35%">Area de solicitud:</td><td>Laboratorio Clinico</td>
                    </tr>
                    <tr>
                        <td>Medico que solicita:</td><td>
                            <input type="hidden" id="IdEmpleado"/>
                            <input type="text" id="NombreEmpleado" name="NombreEmpleado" size="40" onkeypress="return acceptNum(event,this.id)"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Especialidad:</td>
                        <td><div id="combo_especialidades"></div></td>
                    </tr>
                    <tr>
                        <td>Examen: </td><td>
                            <input type="hidden" id="IdExamen"/>
                            <input type="hidden" id="IdSolicitudEstudio"/>
                            <input type="text" id="NombreExamen" name="NombreExamen" size="40"  onkeypress="return acceptNum(event,this.id)"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right">
                            <input type="button" id="agregar" name="agregar" value="Agregar Estudio"/>
                            <input type="button" id="cancelar" name="cancelar" value="Cancelar Solicitud" onclick="EliminarSolicitud();"/>
                            <input type="button" id="cerrar" name="cerrar" style="display:none;" value="Cerrar Ventana" onclick="window.close();"/>
                            <input type="hidden" id="IdNumeroExp" name="IdNumeroExp" value="<?= $IdNumeroExp; ?>"/>
                        </td>
                    </tr>
                    <tr><td colspan="2"><div align="center" id="Detalle" style="height:160;border:solid;overflow:scroll;"></div></td></tr>
                    <tr><td colspan="2" align="right"><input type="button" id="eliminar" name="eliminar" value="Eliminar Seleccionado(s)" onclick="EliminarExamenes();"/></td></tr>
                    
                </table>
            </center>

            <script>
                new Autocomplete('NombreExamen', function() { 
                    return 'respuesta.php?Bandera=1&q=' + this.value; 
                });
            </script>
            <script>
                new Autocomplete('NombreEmpleado', function() { 
                    return 'respuesta.php?Bandera=2&q=' + this.value; 
                });
            </script>
        </body>
        </html>
        <?php
    }
}
?>