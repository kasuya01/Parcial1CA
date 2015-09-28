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
    $idSolicitud      = $_POST['idSolicitud'] ? $_POST['idSolicitud'] : '';
    $fechaCita        = $_POST['fechaCita'] ? $_POST['fechaCita'] : '';
    $numeroExpediente = $_POST['numeroExpediente'] ? $_POST['numeroExpediente'] : '';
    $idExpediente     = $_POST['idExpediente'] ? $_POST['idExpediente'] : '';
    ?>
    <html>
        <head>
            <!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
            <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
            <title>Recepción de Solicitudes Servicio de Laboratorio Clinico</title>
            <script language="JavaScript" type="text/javascript" src="ajax_RecepcionSolicitud.js"></script>
            <?php include_once $ROOT_PATH."/public/css.php";?>
            <?php include_once $ROOT_PATH."/public/js.php";?>
            <link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
            <link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
            <script language="JavaScript" >
                jQuery(document).ready(function($) {
                    var idSolicitud = '<?php echo $idSolicitud; ?>';
                    var fechaCita = '<?php echo $fechaCita; ?>';
                    var numeroExpediente = '<?php echo $numeroExpediente; ?>';

                    if(idSolicitud !== 'undefined' && idSolicitud !== null && idSolicitud !== '') {
                        $('#txtidexpediente').val(numeroExpediente);
                        $('#txtfechasolicitud').val(fechaCita);
                    }
                });

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
                    k=0;
                    setTimeout(function() {
                        var html =  '<center>\
                                        <div style="background-color:#FFFFFF;padding: 20px 0 20px 0;">\
                                            <div class="table-responsive" style="width: 80%;">\
                                                <table class="table table-hover table-bordered table-condensed table-white">\
                                            <thead>\
                                                <tr>\
                                                    <th>N&deg; Expediente</th>\
                                                    <th>Fecha Consulta</th>\
                                                    <th>Fecha Cita</th>\
                                                    <th>Nombre Paciente</th>\
                                                    <th>Procedencia</th>\
                                                    <th>Estado</th>\
                                                    <th>Tipo Solicitud</th>\
                                                </tr>\
                                            </thead>\
                                            <tbody>';
                        if(object.num_rows > 0) {
                            jQuery.each(object.data, function(idx,val) {
                                if (val.fecha_cita==null){
                                    date_cita='';
                                }
                                else
                                    date_cita=val.fecha_cita
                                if (date_cita!=''){
                                    k++;
                                html = html + '<tr>\
                                                <td><a href="#" onclick="VerificarExistencia('+' \''+val.numero_expediente+'\', \''+val.fecha_cita+'\', '+val.id_establecimiento+', true,'+val.id+');return false;" style="padding-left:7px;">'+val.numero_expediente+'</a><input id="idsolicitud" value="'+val.id+'" name="idsolicitud" type="hidden" /></td>\
                                                <td>'+val.fecha_consulta+'</td>\
                                                <td>'+date_cita+'</td>\
                                                <td>'+val.nombre_paciente+'</td>\
                                                <td>'+val.servicio+'</td>\
                                                <td>'+val.estado+'</td>\
                                                <td>'+val.tiposolicitud+'</td>\
                                            </tr>';
                                }
                                
                            });
                        } else {
                            html = html + '<tr><td colspan="6" style="color:#888888; font-weit">No se encontraron solicitudes para mostrar...</td></tr>';
                            k=1;
                        }
                        
                        if(k==0)
                                {
                                   html = html + '<tr><td colspan="6" style="color:#888888; font-weit">No se encontraron solicitudes para mostrar...</td></tr>'; 
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
            if ($nivel == 5) {
                include_once ('../../../PaginaPrincipal/index_laboratorio52.php');}
            if ($nivel == 6) {
                include_once ('../../../PaginaPrincipal/index_laboratorio62.php');}
            if ($nivel == 7) {
                include_once ('../../../PaginaPrincipal/index_laboratorio72.php'); } 
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
                                            <select name="cmbTipoEstablec" class="form-control height" id="cmbTipoEstablec"  onChange="BuscarEstablecimiento(this.value);" style="width:400px" >
                                                <option value="0">--Seleccione Tipo Establecimiento--</option>
                                                <?php
                                                include_once("../../../Conexion/ConexionBD.php");
                                                $con = new ConexionBD;
                                                if ($con->conectar() == true) {
                                                    $consulta = "SELECT id AS idtipoestablecimiento, nombre AS nombretipoestablecimiento FROM ctl_tipo_establecimiento WHERE id != $tipo ORDER BY nombre";
                                                    $resultado = @pg_query($consulta);
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
                                           <div id="divEstablecimiento" style="display: block">
                                                <select name="cmbEstablecimiento" id="cmbEstablecimiento" class="form-control height" style="width:400px" ><?php
                                                    echo '<option value="' . $lugar . '" selected="selected">' . htmlentities($nombrEstab) . '</option>';
                                                    include_once("../../../Conexion/ConexionBD.php");
                                                    $con = new ConexionBD;
                                                    if ($con->conectar() == true) {
                                                        $consulta = "SELECT id AS idestablecimiento, nombre FROM ctl_establecimiento where id_tipo_establecimiento = $tipo ORDER BY nombre";
                                                        $resultado = @pg_query($consulta);
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
                                        <td class="StormyWeatherDataTD"><input type="text" name="txtidexpediente" id="txtidexpediente"  class='form-control height' style="width:400px"/><input type="hidden" id="idsolicitudtxt" name="idsolicitudtxt"></td>
                                    </tr>
                                    <tr>
                                        <td class="StormyWeatherFieldCaptionTD">Fecha de Cita</td>
                                        <td class="StormyWeatherDataTD">
                                            <input type="text" name="txtfechasolicitud" id="txtfechasolicitud" value="<?php //echo date("d/m/Y"); ?>" class="date form-control height"  placeholder="aaaa-mm-dd" style="width:400px" />
                                        </td>
                                    </tr>
                                    <!--<tr>
                                        <td colspan="2" class="StormyWeatherDataTD" align="right">
                                            <input type="button" name="btnBuscar" value="Buscar Solicitud" onClick="BuscarDatos();" />
                                        </td>
                                    </tr> -->
                                    
                                    <tr>
                                             <td class="StormyWeatherDataTD" colspan="4" align="right">
                        
                                                <button type='button' align="center" class='btn btn-primary' id='buscarsolicitud' onclick='BuscarDatos(); '><span class='glyphicon glyphicon-search'></span> Buscar Solicitudes</button>
                                                <button type='button' align="center" class='btn btn-primary' id='nuevabusqueda' onclick="window.location.replace('Proc_RecepcionSolicitud.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                                                                                                                                                                                    
                                            </td>
                                    </tr>
                                    
                                    
                                </table>
                            </form>
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
