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
    $tipoestab  = $row['idtipoestablecimiento'];

    if($tipoestab==14){
       if ($tipoarea == 'S') {
        $area1 = 0;
        $nomarea = "Seleccione un Area";
       }else {
            $area1 = $area;
            $nomarea = $rowArea[0];
        }
    }
    else{
         $area1 = 0;
        $nomarea = "Seleccione un Area";
        
    }
    ?>
    <html>
        <head>
            <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
            <!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
            <link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
            <link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
            <title>Ingreso de Resultados</title>

            <?php include_once $ROOT_PATH."/public/js.php";?>
             <?php include_once $ROOT_PATH."/public/css.php";?>
            <script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script>
            <script language="JavaScript" type="text/javascript">
                function MostrarSolicitudes() {
                    //var error = [];
                    //var errorMessage = "";

                    //if ((document.getElementById('cmbTipoEstab').value === "") || (document.getElementById('cmbEstablecimiento').value === "")) {


                      //  if(document.getElementById('cmbTipoEstab').value === "0")
                        //    error.push('Tipo de Establecimiento');

                        //if(document.getElementById('cmbEstablecimiento').value === "0")
                          //  error.push('Establecimiento');


                        //for (i = 0; i < error.length; i++) {
                          //  errorMessage += error[i] + "\n";
                       // }

                        //if(error.length === 1)
                          //  errorMessage = "Error...\n\nEl siguiente campo es requerido: \n\n" + errorMessage;
                        //else
                        //    errorMessage = "Error...\n\nLos siguientes campos son requeridos: \n\n" + errorMessage;

                      //  alert(errorMessage);

                   // } else {
                        jQuery('#divBusqueda').empty();
                        jQuery('#divBusqueda').append('<center><img id="wait" src="<?php //echo $base_url; ?>/Laboratorio/public/images/spin.gif" alt="wait" width="24" height="24"><div id="search-message" style="color:#888888;font-weight: bold;">Buscando...</div></center>');

                       setTimeout(function() {
                       jQuery('#divBusqueda').empty();
                            SolicitudesPorArea();
                        }, 500);
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
                    LlenarComboServicio(IdServicio);
                }

             //   llenarComboTipoSolicitud();
            </script>
            <?php include_once $ROOT_PATH."/public/css.php";?>
            <?php include_once $ROOT_PATH."/public/js.php";?>
            <script  type="text/javascript">
                $(document).ready(function() {
                    MostrarSolicitudes();
                $("#cmbEstablecimiento").select2({
                 allowClear: true,
                 dropdownAutoWidth: true
             });
        });</script>
        </head>
        <body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onload="BuscarExamen(<?php echo $area; ?>)">
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


            <div  id="divInicial">
                <form  method="get" action="ProcDatosResultadosExamen_PA.php" name="frmdatos_resultado" enctype="multipart/form-data">
                    <table  align="center" class="StormyWeatherFormTABLE" width="67%" pos>
                        <tr>
                            <td colspan="4" align="center" class="CobaltFieldCaptionTD" >
                                <h3>
                                    <strong>Ingreso de Resultados</strong>
                                </h3>
                            </td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD" width="20%">Tipo Establecimiento</td>
                            <td class="StormyWeatherDataTD" width="35%">
                                <select name="cmbTipoEstab" id="cmbTipoEstab" style="width:443px" onChange="BuscarEstablecimiento(this.value)" class="form-control height">
                                    <option value="0" > Todos los Tipo de Establecimiento</option>
                                    <?php
                                        $db = new ConexionBD;
                                        if ($db->conectar() == true) {
                                            $consulta = "SELECT id, nombre
                                                         FROM ctl_tipo_establecimiento ORDER BY nombre";
                                            $resultado = pg_query($consulta);
                                            //por cada registro encontrado en la tabla me genera un <option>
                                            while ($rows = pg_fetch_array($resultado)) {
                                                echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>';
                                            }
                                            //echo '<option value="' . $tipo . '" selected="selected">' . htmlentities($nomtipo) . '</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                            <td class="StormyWeatherFieldCaptionTD" width="20%">Establecimiento</td>
                            <td class="StormyWeatherDataTD"  width="35%">
                                <div id="divEstablecimiento">
                                    <select name="cmbEstablecimiento" id="cmbEstablecimiento"  style="width:500px"  class="js-example-basic-single">

                                      <option value="0" selected="selected">Todos los Establecimientos</option>
                                        <?php
                                        //class="js-example-basic-single"
                                        echo '<option value="' . $lugar . '" >' . htmlentities($nombrEstab) . '</option>';
                                        include_once("../../../Conexion/ConexionBD.php");
                                        $con = new ConexionBD;
                                        if ($con->conectar() == true) {
                                            $consulta = "SELECT id, nombre FROM ctl_establecimiento  ORDER BY nombre";
                                           // where id_tipo_establecimiento = $tipo
                                            $resultado = @pg_query($consulta);
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
                                <select name="CmbServicio" id="CmbServicio" style="width:443px" onChange="BuscarServicio(this.value)" class="form-control height">
                                    <option value="0" selected="selected" align="center"> Seleccione Procedencia </option>
                                    <?php
                                    $db = new ConexionBD;
                                    if ($db->conectar() == true) {
                                          $consulta = "SELECT mnt_area_mod_estab.id as codigo ,CASE WHEN id_servicio_externo_estab IS NOT NULL THEN mnt_servicio_externo.abreviatura ||'-->'  || ctl_area_atencion.nombre
                                                       ELSE   ctl_modalidad.nombre ||'-->' || ctl_area_atencion.nombre
                                                       END
                                                       FROM mnt_area_mod_estab
                                                       INNER JOIN  ctl_area_atencion  on (ctl_area_atencion.id = mnt_area_mod_estab.id_area_atencion AND ctl_area_atencion.id_tipo_atencion=1)
                                                       --LEFT JOIN mnt_aten_area_mod_estab ON (ctl_area_atencion.id = mnt_aten_area_mod_estab.id_atencion)
                                                       INNER JOIN  mnt_modalidad_establecimiento ON mnt_modalidad_establecimiento.id=mnt_area_mod_estab.id_modalidad_estab
                                                       INNER JOIN ctl_modalidad ON ctl_modalidad.id = mnt_modalidad_establecimiento.id_modalidad
                                                       LEFT JOIN mnt_servicio_externo_establecimiento ON (mnt_servicio_externo_establecimiento.id = mnt_area_mod_estab.id_servicio_externo_estab)
                                                       LEFT JOIN mnt_servicio_externo ON (mnt_servicio_externo.id = mnt_servicio_externo_establecimiento.id_servicio_externo)
                                                       ORDER by ctl_modalidad.nombre,ctl_area_atencion.nombre ";

                                        $resultado = pg_query($consulta);

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
                                <div id="divsubserv" >
                                    <select name="cmbSubServ" id="cmbSubServ" style="width:500px" class="form-control height">
                                        <option value="0" selected="selected"> Seleccione un Servicio </option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">&Aacute;rea de Laboratorio</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbArea" name="cmbArea"  size="1" onChange="BuscarExamen(this.value)" style="width:443px" class="form-control height">
                                     <!--<option value="0" >Seleccione un Área</option>-->
                                    <?php
                                     include('../../../../Laboratorio/LAB/Mantenimientos/Lab_Areas/clsLab_Areas.php');
                                     $objeareas = new clsLab_Areas;
                                     if($tipoestab==14){
                                        if ($tipoarea == 'S') {
                                            echo '<option value="0" >Seleccione un Área</option>';
                                           
                                            $consulta = $objeareas->consultaractivas($lugar);
                                            while ($row = pg_fetch_array($consulta)) {
                                               
                                                    echo "<option value='" . $row['idarea'] . "'>" . htmlentities($row['nombrearea']) . "</option>";
                                               
                                            }
                                        }else {
                                             echo '<option value="' . $area1 . '" selected="selected">' . htmlentities($nomarea) . '</option>';
                                            $consulta = $objeareas->consultaractivas($lugar);
                                            while ($row = pg_fetch_array($consulta)) {
                                                if ($row['idarea']!=$area){
                                                    echo "<option value='" . $row['idarea'] . "'>" . htmlentities($row['nombrearea']) . "</option>";
                                                }
                                            }
                                            echo '<option value="0" >Seleccione un Área</option>';
                                     
                                        }  
                                     }else{
                                         echo '<option value="0" >Seleccione un Área</option>';
                                           
                                            $consulta = $objeareas->consultaractivas($lugar);
                                            while ($row = pg_fetch_array($consulta)) {
                                                if ($row['idarea']!=$area){
                                                    echo "<option value='" . $row['idarea'] . "'>" . htmlentities($row['nombrearea']) . "</option>";
                                                }
                                            }
                                     }
                                   // echo $area;
                                     //echo '<option value="' . $area1 . '" selected="selected">' . htmlentities($nomarea) . '</option>';
                                 
                                     //   include('../../../../Laboratorio/LAB/Mantenimientos/Lab_Areas/clsLab_Areas.php');
                                      //  $objeareas = new clsLab_Areas;
                                        //$consulta = $objeareas->consultaractivas($lugar);
                                        //while ($row = pg_fetch_array($consulta)) {
                                         // if ($row['idarea']!=$area){
                                           // echo "<option value='" . $row['idarea'] . "'>" . htmlentities($row['nombrearea']) . "</option>";
                                          // }
                                      //  }
                                    
                                    
                                    ?>
                                </select>
                            </td>
                            <td  class="StormyWeatherFieldCaptionTD">Examen</td>
                            <td  class="StormyWeatherDataTD" >
                                <div id="divExamen">
                                    <select name="cmbExamen" id="cmbExamen" class="form-control height" style="width:500px" >
                                        <option value="0"> Seleccione Examen </option>
                                    </select>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD" align="left" ><strong>Tipo Solicitud</strong></td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbTipoSolic" name="cmbTipoSolic" size="1" style="width:443px" class="form-control height">
                                    <option value="0">Seleccione un Tipo de Solicitud</option>
                                    <?php
                                        $option=$obj->consultarTipoSolicitud();
                                        while($rowop = pg_fetch_array($option)){
                                            echo '<option value='.$rowop[idtiposolicitud].'>'.$rowop["tiposolicitud"].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                            <td class="StormyWeatherFieldCaptionTD" width="15%" >Fecha Consulta</td>
                            <td  class="StormyWeatherDataTD" width="20%" >
                                <input type="text" size="28" name="txtfechasolicitud" id="txtfechasolicitud" class="date form-control height placeholder"  placeholder="aaaa-mm-dd" style="width:500px"/>

                            </td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD" >Expediente</td>
                            <td  class="StormyWeatherDataTD"><input type="text" size="40" name="txtexpediente" id="txtexpediente"  class="form-control height" style="width:443px" placeholder="Ingrese Expediente"/></td>

                            <td class="StormyWeatherFieldCaptionTD" align="left"><strong>Fecha Recepi&oacute;n</strong></td>
                            <td  class="StormyWeatherDataTD">
                                <input type="text" size="28" name="txtfecharecep" id="txtfecharecep" class="date form-control height placeholder"  placeholder="aaaa-mm-dd" style="width:500px"/>
                            </td>
                        </tr>
                        <tr>
                            <td  class="StormyWeatherFieldCaptionTD" align="left"><strong>Primer Nombre&nbsp;</strong>   </td>
                            <td class="StormyWeatherDataTD" >
                               <input maxlength="35" size="28" name="PrimerNombre" id="PrimerNombre" class="form-control height" style="width:443px" placeholder="Ingrese Primer Nombre"/></td>
                            <td class="StormyWeatherFieldCaptionTD" align="left"><strong>Segundo Nombre</strong>   </td> <td class="StormyWeatherDataTD">
                                <input  maxlength="35" size="28" name="SegundoNombre" id="SegundoNombre" class="form-control height" style="width:500px" placeholder="Ingrese Segundo Nombre"/></td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD" align="left"><strong>Primer Apellido</strong></td>
                            <td class="StormyWeatherDataTD">
                               <input  maxlength="35" size="28" name="PrimerApellido" id="PrimerApellido" class="form-control height" style="width:443px" placeholder="Ingrese Primer Apellido"/></td>
                            <td  class="StormyWeatherFieldCaptionTD" align="left"><strong>Segundo Apellido</strong></td>
                            <td class="StormyWeatherDataTD" >
                                <input maxlength="35" size="28" name="SegundoApellido" id="SegundoApellido" class="form-control height" style="width:500px" placeholder="Ingrese Segundo Apellido"/></td>
                        </tr>
                        <!--<tr>
                            <td class="StormyWeatherDataTD" colspan="4" align="right">
                                <input type="button" name="Submit" value="Buscar Solicitudes" onClick="MostrarSolicitudes()">
                                <input type="button" id="btnClear" value="Nueva Busqueda" class="MailboxButton" onClick="window.location.replace('Proc_SolicitudesProcesadas.php')">

                            </td>
                        </tr>-->
                        <tr>
                            <td class="StormyWeatherDataTD" colspan="4" align="right">

                                                <button type='button' align="center" class='btn btn-primary' id='buscarsolicitud' onclick='MostrarSolicitudes(); '><span class='glyphicon glyphicon-search'></span> Buscar Solicitudes</button>
                                                <button type='button' align="center" class='btn btn-primary' id='nuevabusqueda' onclick="window.location.replace('Proc_SolicitudesProcesadas.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>

                            </td>
                        </tr>


                    </table>
                </form>
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
    <?php //style="height:60%; position:absolute ; top:550px; left:30px  "
} else {
    ?>
    <script language="javascript">
        window.location = "../../../login.php";
    </script>
<?php
}?>
