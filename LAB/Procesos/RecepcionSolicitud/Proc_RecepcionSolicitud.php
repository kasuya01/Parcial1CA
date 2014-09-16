<?php
session_start();
if (isset($_SESSION['Correlativo'])) {
    $nivel = $_SESSION['NIVEL'];
    $corr  = $_SESSION['Correlativo'];
    $lugar = $_SESSION['Lugar'];
    $area  = $_SESSION['Idarea'];
    $ROOT_PATH = $_SESSION['ROOT_PATH'];
    $base_url  = $_SESSION['base_url'];
    include_once("clsRecepcionSolicitud.php");

    //consulta los datos por su id
    $obj      = new clsRecepcionSolicitud;
    $consulta = $obj->DatosEstablecimiento($lugar);
    $row      = pg_fetch_array($consulta);
    $ConArea  = $obj->DatosArea($area);
    $rowArea  = pg_fetch_array($ConArea);

    //valores de las consultas
    $tipo       = $row[0];
    $nombrEstab = $row[1];
    $nomtipo    = $row[2];
    $tipoarea   = $rowArea[1];

    if ($tipoarea == 'S') {
        $area1 = 0;
        $nomarea = "Seleccione un Area";
    } else {
        $area1 = $area;
        $nomarea = $rowArea[0];
    }

    ?>
    <html>
        <head>
            <!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
            <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
            <title>Recepcion de Solicitudes Servicio de Laboratorio Clinico</title>
            <script language="JavaScript" type="text/javascript" src="ajax_RecepcionSolicitud.js"></script>
            <?php include_once $ROOT_PATH."/public/css.php";?>
            <?php include_once $ROOT_PATH."/public/js.php";?>
            <!--referencias del estilo del calendario-->
            <link rel="stylesheet" type="text/css" media="all" href="../../../calendarstructure/skins/aqua/theme.css" title="Aqua" />
            <link rel="alternate stylesheet" type="text/css" media="all" href="../../../calendarstructure/calendar-blue.css" title="blue" />
            <link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
            <link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
            <!--llamado al archivo de funciones del calendario-->
            <script type="text/javascript" src="../../../calendarstructure/calendar.js"></script>
            <script type="text/javascript" src="../../../calendarstructure/calendar-es.js"></script>
            <script type="text/javascript" src="../../../calendarstructure/calendar-setup.js"></script>
            <script language="JavaScript" >
                function BuscarEstablecimiento(IdTipoEstab) {
                    LlenarEstablecimiento(IdTipoEstab);
                }

                //function MostrarListaExamenes()
                function ImprimirExamenes(posicion)
                {
                    imprimiretiquetas(posicion);
                }
                function MostrarDatos()
                {
                    BuscarDatos();
                }

                function AsignarNumeroMuestra(posicion)
                {
                    RegistrarNumeroMuestra(posicion);
                }

                function ImprimirSolicitud(posicion)
                {
                    EnviarDatosSolicitud(posicion);
                }
            </script>
            <script type="text/javascript">
                function searchAllBuild(object) {
                    jQuery('#divResultado').empty();
                    jQuery('#divResultado').append('<center><img id="wait" src="<?php echo $base_url; ?>/Laboratorio/public/images/spin.gif" alt="wait" width="24" height="24"><div id="search-message" class="search-label">Buscando...</div></center>');

                    setTimeout(function() {
                        var html =  '<center>\
                                        <div style="background-color:#FAFAFA;padding: 20px 0 20px 0;">\
                                            <div class="table-responsive" style="width: 80%;">\
                                                <table class="table table-hover table-bordered table-condensed table-white">\
                                            <thead>\
                                                <tr>\
                                                    <th>N&deg; Expediente</th>\
                                                    <th>Fecha Consulta</th>\
                                                    <th>Fecha Cita</th>\
                                                    <th>Nombre Paciente</th>\
                                                    <th>Estado</th>\
                                                </tr>\
                                            </thead>\
                                            <tbody>';
                        if(object.num_rows > 0) {
                            jQuery.each(object.data, function(idx,val) {
                                html = html + '<tr>\
                                                <td><a href="#" onclick="VerificarExistencia('+val.numero_expediente+', \''+val.fecha_cita+'\', '+val.id_establecimiento+', true);return false;" style="padding-left:7px;">'+val.numero_expediente+'</a></td>\
                                                <td>'+val.fecha_consulta+'</td>\
                                                <td>'+val.fecha_cita+'</td>\
                                                <td>'+val.nombre_paciente+'</td>\
                                                <td>'+val.estado+'</td>\
                                            </tr>';
                            });
                        } else {
                            html = html + '<tr><td colspan="5" style="color:#888888; font-weit">No se encontraron solicitudes para mostrar...</td></tr>';
                        }

                        html = html + '     </tbody>\
                                        </table>\
                                    </center>';
                        jQuery('#divResultado').empty();
                        jQuery('#divResultado').append(html);
                    }, 500);
                }
            </script>
        </head>
        <body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7">

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
            if ($nivel == 4) {
                include_once ('../../../PaginaPrincipal/index_laboratorio42.php');
            }
            ?><br>
            <table align="center" width="90%" >
                <tr>
                    <td>
                        <div  id="divFrmNuevo" >
                            <form name="FrmBuscar" >
                                <table width="60%" border="1" align="center" class="StormyWeatherFormTABLE">
                                    <tr>
                                        <td colspan="3" align="center" class="CobaltFieldCaptionTD">
                                            <strong><h3 class="h3-table-header">Recepci&oacute;n de Solicitudes de Ex&aacute;menes de Laboratorio</h3></strong>
                                        </td>
                                    </tr>
                                    <tr>	<td class="StormyWeatherFieldCaptionTD">Tipo Establecimiento</TD>
                                        <td class="StormyWeatherDataTD">
                                            <select name="cmbTipoEstablec" class="MailboxSelect" id="cmbTipoEstablec"  onChange="BuscarEstablecimiento(this.value);" >
                                                <option value="0">--Seleccione Tipo Establecimiento--</option>
                                                <?php
                                                include_once("../../../Conexion/ConexionBD.php");
                                                $con = new ConexionBD;
                                                if ($con->conectar() == true) {
                                                    $consulta = "SELECT id AS idtipoestablecimiento, nombre AS nombretipoestablecimiento FROM ctl_tipo_establecimiento WHERE id != $tipo ORDER BY nombre";
                                                    $resultado = @pg_query($consulta) or die('La consulta fall&oacute;: ' . @pg_error());
                                                    //por cada registro encontrado en la tabla me genera un <option>
                                                    while ($rows = @pg_fetch_array($resultado)) {
                                                        echo '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
                                                    }
                                                    echo '<option value="' . $tipo . '" selected="selected">' . htmlentities($nomtipo) . '</option>';
                                                    //@pg_free_result($consulta); // Liberar memoria usada por consulta.
                                                }
                                                ?>
                                            </select>
                                        </TD>
                                    </tr>
                                    <tr>
                                        <TD class="StormyWeatherFieldCaptionTD">Establecimiento Solicitante</TD>
                                        <td class="StormyWeatherDataTD">
                                            <div id="divEstablecimiento">
                                                <select name="cmbEstablecimiento" id="cmbEstablecimiento" class="MailboxSelect" style="width:400px"><?php
                                                    echo '<option value="' . $lugar . '" selected="selected">' . htmlentities($nombrEstab) . '</option>';
                                                    include_once("../../../Conexion/ConexionBD.php");
                                                    $con = new ConexionBD;
                                                    if ($con->conectar() == true) {
                                                        $consulta = "SELECT id AS idestablecimiento, nombre FROM ctl_establecimiento where id_tipo_establecimiento = $tipo ORDER BY nombre";
                                                        $resultado = @pg_query($consulta) or die('La consulta fall&oacute;: ' . @pg_error());
                                                        //por cada registro encontrado en la tabla me genera un <option>
                                                        while ($rows = @pg_fetch_array($resultado)) {
                                                            echo '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
                                                        }
                                                        //@pg_free_result($consulta); // Liberar memoria usada por consulta.
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="StormyWeatherFieldCaptionTD">N&ordm; de Expediente </td>
                                        <td class="StormyWeatherDataTD"><input type="text" name="txtidexpediente" id="txtidexpediente" /></td>
                                    </tr>
                                    <tr>
                                        <td class="StormyWeatherFieldCaptionTD">Fecha de Cita</td>
                                        <td class="StormyWeatherDataTD">
                                            <input type="text" name="txtfechasolicitud" id="txtfechasolicitud" value="<?php //echo date("d/m/Y"); ?>"/>
                                            <input type="button" value="..." id="trigger">dd/mm/aaaa
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="StormyWeatherDataTD" align="right">
                                            <input type="button" name="btnBuscar" value="Buscar Solicitud" onClick="BuscarDatos();" />
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <script type="text/javascript">
                                Calendar.setup(
                                        {
                                            inputField: "txtfechasolicitud", // el ID texto
                                            ifFormat: "%d/%m/%Y", // formato de la fecha
                                            button: "trigger"       // el ID del boton
                                        }
                                );
                            </script>
                        </div>
                        <div id="divResultado">
                        </div>
                        <div id="divCambioEstado">
                        </div>
                        <div id="divImprimir">
                        </div>
                        <div id="divArchivo">
                        </div>
                    </TD>
                </TR>
        </body>
    </html>
    <?php
} else {
    ?>
    <script language="javascript">
        window.location = "../../../login.php";
    </script>
<?php
}?>
