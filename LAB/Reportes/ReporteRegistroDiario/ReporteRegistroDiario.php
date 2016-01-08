<?php
session_start();
include ("clsReporteRegistroDiario.php");
//creando los objetos de las clases
//if (isset($_SESSION['Correlativo'] $_SESSION['Correlativo'] ) || isset($_SESSION["ADM"])) {
if (isset($_SESSION['Correlativo']) || isset($_SESSION["ADM"])) {
    $nivel = $_SESSION['NIVEL'];
    $corr = $_SESSION['Correlativo'];
    $lugar = $_SESSION['Lugar'];
    $area = $_SESSION['Idarea'];
    $ROOT_PATH = $_SESSION['ROOT_PATH'];
    $obj = new clsReporteRegistroDiario();
//echo $lugar;
    ?>
    <html>
        <head>
            <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
            <title>Reporte de registro diario</title>
            <script language="JavaScript" type="text/javascript" src="ajax_ReporteRegistroDiario.js"></script>
            <link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
            <link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
            <!--referencias del estilo del calendario-->
            <style>
                /*            .ui-datepicker-calendar {
                               display: none;
                            }*/
            </style>
            <?php include_once $ROOT_PATH . "/public/css.php"; ?>
            <?php include_once $ROOT_PATH . "/public/js.php"; ?>
            <!--llamado al archivo de funciones del calendario-->
            <script language="JavaScript" type="text/javascript">
                function MostrarBusqueda()
                {
                    if ((document.getElementById('txtfechainicio').value == "")
                            && (document.getElementById('txtfechafin').value == ""))
                    {
                        alert("Seleccione un rango de fechas!");
                    }
                    else
                        BuscarDatos();
                }

                function BuscarExamen(idarea) {

                    if (document.getElementById('cmbArea').value == 0) {
                        alert("Debe Seleccionar una Area");

                    }
                    else {
                        LlenarComboExamen(idarea);

                    }
                }
                $(document).ready(function () {
                    $("#cmbExamen").select2({
                        placeholder: "Seleccione examenes",
                        allowClear: true,
                        dropdownAutoWidth: true
                    });
                    $("#cmbArea").select2({
                        placeholder: "Seleccione una Area",
                        allowClear: true,
                        dropdownAutoWidth: true
                    });
                });


                function popup(URL) {
                    myWindow=window.open(URL, '" + "', 'scrollbars=yes, width=1024, height=800, top = 50');
                }


            </script>
            <style type="text/css">
                <!--
                @media print{
                    #boton{display:none;}
                    #divInicial{display:none;}
                    #divInicial{display:none;}
                }

                -->
            </style>
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
            if ($nivel == 5) {
                include_once ('../../../PaginaPrincipal/index_laboratorio52.php');
            }
            if ($nivel == 6) {
                include_once ('../../../PaginaPrincipal/index_laboratorio62.php');
            }
            if ($nivel == 7) {
                include_once ('../../../PaginaPrincipal/index_laboratorio72.php');
            }
            $femu = @pg_fetch_array($obj->fechamuestra());
            $feact = $femu[0];
            $toy = date('Y-m');
            $toy2 = date('Y-m-d');
            ?><br>

            <!-- <form name=" cons_tabulador" onSubmit="return false;" action="excelOrd_x_id.php" method="post" target="_blank">-->
            <form name=" cons_demanda">
                <table align="center" width="100%">

                    <tr>
                        <td align="center">
                    <center>
                        <div class="col-md-6 col-md-offset-3">
                            <div class="panel panel-primary">                        
                                <div class="panel-heading"><h3>Reporte de Registro diario</h3> </div>                        
                                <div class="panel-body" id="pb-primervez">
                                    <table class="table table-white no-v-border table-condensed" border="0" style="border:0px; width: 100%" cellpading="2">
                                        <tr>
                                            <th width="10%">Fecha de recepci&oacute;n:</th>
                                            <td width="30%">   
                                                <input type="text" id="d_fechadesde" name="d_fechadesde" style="width: 50%; text-align: center;  position: fix; top: auto " placeholder="<?php echo $feact; ?>"  class="date form-control height placeholder"  autocomplete="off" />
                                            </td>

                                        </tr>
                                        <tr>
                                            <th width="15%">&Aacute;rea</th>
                                            <td width="85%"  colspan="3"> 
                                                <select id="cmbArea" name="cmbArea"  size="1" onChange="BuscarExamen(this.value)" style="width:100%;" class="height placeholder js-example-basic-single">
                                                    <?php
                                                    echo '<option></option>';
                                                    include('../../../../Laboratorio/LAB/Mantenimientos/Lab_Areas/clsLab_Areas.php');
                                                    $objeareas = new clsLab_Areas;
                                                    $consulta = $objeareas->consultaractivas($lugar);
                                                    while ($row = pg_fetch_array($consulta)) {
                                                        echo "<option value='" . $row['idarea'] . "'>" . htmlentities($row['nombrearea']) . "</option>";
                                                    }
                                                    ?>		  
                                                </select> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" align="center"> 
                                                <br/>
                                                <button type="button" align="right" style="text-align: right" class="btn btn-primary" onclick="MostrarReporteDemanda()"><span class='glyphicon glyphicon-list-alt'></span>&nbsp;Generar Reporte </button></td>
                                        </tr>                  
                                    </table>                        
                                </div>                    
                            </div>
                        </div>

                    </center>    </td>
                    </tr>
                </table>
            </form>
        <center>

            <div id="divBusqueda" class="col-md-8 col-md-offset-2">
            </div><!--Fin de divBusqueda-->
        </center>
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