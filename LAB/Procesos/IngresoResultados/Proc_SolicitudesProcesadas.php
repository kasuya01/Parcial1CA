<?php
session_start();
if (isset($_SESSION['Correlativo'])) {
    $nivel = $_SESSION['NIVEL'];
    $corr  = $_SESSION['Correlativo'];
    $lugar = $_SESSION['Lugar'];
    $area  = $_SESSION['Idarea'];
    $ROOT_PATH = $_SESSION['ROOT_PATH'];
    $base_url  = $_SESSION['base_url'];
    include_once("clsSolicitudesProcesadas.php");
    
    //consulta los datos por su id
    $obj      = new clsSolicitudesProcesadas;
    $consulta = $obj->DatosEstablecimiento($lugar);
    $row      = pg_fetch_array($consulta);

    $ConArea = $obj->DatosArea($area);
    $rowArea = pg_fetch_array($ConArea);
    
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
            <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
            <!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
            <link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
            <link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
            <title>Solicitudes Procesadas</title>
            <script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script>
            <?php include_once $ROOT_PATH."/public/js.php";?>
            <!--referencias del estilo del calendario-->
            <link rel="stylesheet" type="text/css" media="all" href="../../../calendarstructure/skins/aqua/theme.css" title="Aqua" />
            <link rel="alternate stylesheet" type="text/css" media="all" href="../../../calendarstructure/calendar-blue.css" title="blue" />
            <!--llamado al archivo de funciones del calendario-->
            <script type="text/javascript" src="../../../calendarstructure/calendar.js"></script>
            <script type="text/javascript" src="../../../calendarstructure/calendar-es.js"></script>
            <script type="text/javascript" src="../../../calendarstructure/calendar-setup.js"></script>
            <script language="JavaScript" type="text/javascript">
                function MostrarSolicitudes() {
                    var error = [];
                    var errorMessage = "";
                    
                    if ((document.getElementById('cmbArea').value === "0") || (document.getElementById('cmbTipoEstab').value === "0") || (document.getElementById('cmbEstablecimiento').value === "0")) {
                        if(document.getElementById('cmbArea').value === "0")
                            error.push('Area de Laboratorio');
                        
                        if(document.getElementById('cmbTipoEstab').value === "0")
                            error.push('Tipo de Establecimiento');
                        
                        if(document.getElementById('cmbEstablecimiento').value === "0")
                            error.push('Establecimiento');
                        
                        
                        for (i = 0; i < error.length; i++) {
                            errorMessage += error[i] + "\n";
                        }
                        
                        if(error.length === 1)
                            errorMessage = "Error...\n\nEl siguiente campo es requerido: \n\n" + errorMessage;
                        else
                            errorMessage = "Error...\n\nLos siguientes campos son requeridos: \n\n" + errorMessage;
                        
                        alert(errorMessage);

                    } else {
                        jQuery('#divBusqueda').empty();
                        jQuery('#divBusqueda').append('<center><img id="wait" src="<?php echo $base_url; ?>/Laboratorio/public/images/spin.gif" alt="wait" width="24" height="24"><div id="search-message" style="color:#888888;font-weight: bold;">Buscando...</div></center>');
                        
                        setTimeout(function() {
                            jQuery('#divBusqueda').empty();
                            SolicitudesPorArea();
                        }, 500);
                    }
                }
                
                function BuscarEstablecimiento(idtipoesta) {
                    LlenarComboEstablecimiento(idtipoesta);
                }

                function BuscarExamen(idarea) {

                    if (document.getElementById('cmbArea').value === 0) {
                        alert("Debe Seleccionar una Area");
                    }
                    else {
                        LlenarComboExamen(idarea);
                    }
                }

                function BuscarServicio(IdServicio) {
                    /*if (document.getElementById('CmbServicio').value == 0){
                     alert("Debe Seleccionar una Procedencia");
                     } 
                     else{*/
                    LlenarComboServicio(IdServicio);
                    //}
                }
                
                llenarComboTipoSolicitud();
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


            <div  id="divInicial" >
                <form  method="get" action="ProcDatosResultadosExamen_PA.php" name="frmdatos_resultado" enctype="multipart/form-data">
                    <table  align="center" class="StormyWeatherFormTABLE" width="83%">
                        <tr>
                            <td colspan="4" align="center" class="CobaltFieldCaptionTD">
                                <h3>
                                    <strong>Solicitudes Procesadas por &Aacute;rea</strong>
                                </h3>
                            </td>
                        </tr>
                        <tr>		
                            <td class="StormyWeatherFieldCaptionTD">Tipo Establecimiento</td>
                            <td class="StormyWeatherDataTD">
                                <select name="cmbTipoEstab" id="cmbTipoEstab" style="width:405px" onChange="BuscarEstablecimiento(this.value)">
                                    <option value="0" >Seleccione un Tipo de Establecimiento</option>
                                    <?php
                                    $db = new ConexionBD;
                                    if ($db->conectar() == true) {
                                        $consulta = "SELECT id, nombre
                                                     FROM ctl_tipo_establecimiento ORDER BY nombre";
                                        $resultado = pg_query($consulta) or die('La consulta fall&oacute;: ' . pg_error());
                                        //por cada registro encontrado en la tabla me genera un <option>
                                        while ($rows = pg_fetch_array($resultado)) {
                                            echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>';
                                        }
                                        echo '<option value="' . $tipo . '" selected="selected">' . htmlentities($nomtipo) . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="StormyWeatherFieldCaptionTD">Establecimiento</td>
                            <td class="StormyWeatherDataTD" >
                                <div id="divEstablecimiento">
                                    <select name="cmbEstablecimiento" id="cmbEstablecimiento"  style="width:375px"> <option value="0" >Seleccione un Establecimiento</option>
                                        <?php
                                        echo '<option value="' . $lugar . '" selected="selected">' . htmlentities($nombrEstab) . '</option>';
                                        include_once("../../../Conexion/ConexionBD.php");
                                        $con = new ConexionBD;
                                        if ($con->conectar() == true) {
                                            $consulta = "SELECT id, nombre FROM ctl_establecimiento where id_tipo_establecimiento = $tipo ORDER BY nombre";
                                            $resultado = @pg_query($consulta) or die('La consulta fall&oacute;: ' . @pg_error());
                                            //por cada registro encontrado en la tabla me genera un <option>
                                            while ($rows = @pg_fetch_array($resultado)) {
                                                echo '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
                                            }
                                        }
                                        ?>	
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>	
                            <td class="StormyWeatherFieldCaptionTD">Procedencia</td>
                            <td class="StormyWeatherDataTD">
                                <select name="CmbServicio" id="CmbServicio" style="width:355px" onChange="BuscarServicio(this.value)" >
                                    <option value="0" selected="selected" align="center"> Seleccione Procedencia </option>
                                    <?php
                                    $db = new ConexionBD;
                                    if ($db->conectar() == true) {
                                        $consulta = "SELECT t01.id,
                                                                 t01.nombre
                                                          FROM ctl_area_atencion t01
                                                          WHERE t01.id IN (
                                                                SELECT DISTINCT id_area_atencion FROM mnt_area_mod_estab WHERE id_establecimiento = $lugar)";
                                        $resultado = pg_query($consulta) or die('La consulta fall&oacute;: ' . pg_error());

                                        //por cada registro encontrado en la tabla me genera un <option>
                                        while ($rows = pg_fetch_array($resultado)) {
                                            echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="StormyWeatherFieldCaptionTD">Servicio</td>
                            <td class="StormyWeatherDataTD">
                                <div id="divsubserv">
                                    <select name="cmbSubServ" id="cmbSubServ" style="width:375px" >
                                        <option value="0" selected="selected"> Seleccione un Servicio </option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>                	
                            <td class="StormyWeatherFieldCaptionTD">&Aacute;rea de Laboratorio</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbArea" name="cmbArea"  size="1" onChange="BuscarExamen(this.value)" >
                                    <?php
                                    echo '<option value="' . $area1 . '" selected="selected">' . htmlentities($nomarea) . '</option>';
                                    include('../../../../Laboratorio/LAB/Mantenimientos/Lab_Areas/clsLab_Areas.php');
                                    $objeareas = new clsLab_Areas;
                                    $consulta = $objeareas->consultaractivas($lugar);
                                    while ($row = pg_fetch_array($consulta)) {
                                        echo "<option value='" . $row['idarea'] . "'>" . htmlentities($row['nombrearea']) . "</option>";
                                    }
                                    ?>		  
                                </select> 
                            </td>
                            <td  class="StormyWeatherFieldCaptionTD">Examen</td>
                            <td  class="StormyWeatherDataTD" >
                                <div id="divExamen">
                                    <select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:250px"> 
                                        <option value="0"> Seleccione Examen </option>
                                    </select>
                                </div>
                            </td> 

                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD" >Expediente</td>
                            <td  class="StormyWeatherDataTD"><input type="text" size="24" name="txtexpediente" id="txtexpediente" /></td>
                            <td class="StormyWeatherFieldCaptionTD" width="15%" >Fecha Consulta</td>
                            <td  class="StormyWeatherDataTD" width="20%" >
                                <input type="text" size="15" name="txtfechasolicitud" id="txtfechasolicitud" />
                                <input type="button" value="..." id="trigger1">aaaa/mm/dd
                            </td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD" align="left" ><strong>Tipo Solicitud</strong></td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbTipoSolic" name="cmbTipoSolic" size="1" >
                                    <option value="0">Seleccione un Tipo de Solicitud</option>
                                </select>
                            </td>
                            <td class="StormyWeatherFieldCaptionTD" align="left"><strong>Fecha Recepi&oacute;n</strong></td>
                            <td  class="StormyWeatherDataTD">
                                <input type="text" size="15" name="txtfecharecep" id="txtfecharecep"/>
                                <input type="button" value="..." id="trigger2">aaaa/mm/dd
                            </td>
                        </tr>
                        <tr>
                            <td  class="StormyWeatherFieldCaptionTD" align="left"><strong>Primer Nombre&nbsp;</strong>   </td> 
                            <td class="StormyWeatherDataTD" >
                                <input class="MailboxInput" maxlength="35" size="28" name="PrimerNombre" id="PrimerNombre"></td> 
                            <td class="StormyWeatherFieldCaptionTD" align="left"><strong>Segundo Nombre</strong>   </td> <td class="StormyWeatherDataTD">
                                <input  maxlength="35" size="28" name="SegundoNombre" id="SegundoNombre"></td> 
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD" align="left"><strong>Primer Apellido</strong></td> 
                            <td class="StormyWeatherDataTD">
                                <input class="MailboxInput" maxlength="35" size="28" name="PrimerApellido" id="PrimerApellido"></td> 
                            <td  class="StormyWeatherFieldCaptionTD" align="left"><strong>Segundo Apellido</strong></td> 
                            <td class="StormyWeatherDataTD" >
                                <input class="MailboxInput" maxlength="35" size="28" name="SegundoApellido" id="SegundoApellido" ></td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherDataTD" colspan="4" align="right">		
                                <input type="button" name="Submit" value="Buscar Solicitudes" onClick="MostrarSolicitudes()">
                                <input type="button" id="btnClear" value="Nueva Busqueda" class="MailboxButton" onClick="window.location.replace('Proc_SolicitudesProcesadas.php')">

                            </td>
                        </tr>
                    </table>
                </form>
                <script type="text/javascript">
                    Calendar.setup(
                            {
                                inputField: "txtfechasolicitud", // el ID texto 
                                ifFormat: "%Y-%m-%d", // formato de la fecha
                                button: "trigger1"       // el ID del boton			  	  
                            }
                    );
            
                    Calendar.setup(
                            {
                                inputField: "txtfecharecep", // el ID texto 
                                ifFormat: "%Y-%m-%d", // formato de la fecha
                                button: "trigger2"       // el ID del boton			  	  
                            }
                    );
                </script>
            </div>
            <div id="divBusqueda">

            </div>
            <div id="divResultado">

            </div>
            <div id="divCambioEstado">

            </div>
            <div id="divImprimir">

            </div>
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