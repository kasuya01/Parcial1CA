<?php
    session_start();
    $nivel = $_SESSION['NIVEL'];
    $corr  = $_SESSION['Correlativo'];
    $lugar = $_SESSION['Lugar'];
    $area  = $_SESSION['Idarea'];
    $ROOT_PATH = $_SESSION['ROOT_PATH'];
?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
        <title>Mantenimiento de Areas de Laboratorio</title>
        <?php include_once $ROOT_PATH."/public/css.php";?>
        <?php include_once $ROOT_PATH."/public/js.php";?>
        <script language="JavaScript" type="text/javascript" src="ajax_Mnt_AreaExamenEstablecimiento.js"></script>
        <script type="text/javascript">
            function loadContent() {
               /* getLabAreas(function(object) {
                    if(object.status) {

                    } else {
                        
                    }
                });*/
            }

            jQuery(document).ready(function($) {
                loadContent();

                getLabAreas(function(object) {
                    if(object.status) {
                        $.each(object.data, function(idx,val) {
                            $('#cmb-area').append($("<option></option>").attr("value",val.idarea).text(val.nombrearea));
                        });
                    }
                });

                getAreaExamenEstablecimiento(function(object) {
                    var estandar = [];

                    if(object.status) {
                        console.log(object);
                        /*$.each(object.data, function(idx,val) {
                            if (isValidCode(val.nombre_grupo, estandar)) {
                                estandar[] = val.nombre_grupo;
                            }
                            console.log(estandar);
                            //$('#cmb-area').append($("<option></option>").attr("value",val.idarea).text(val.nombrearea));
                        });*/
                    }
                });

                function isValidCode(code, arrayElement){
                    return ($.inArray(code, arrayElement) > -1);
                }


                /*Habilitar todos los registros*/
                $('body').on("click", "#select_all", function(e) {
                    $('input[id^="area_"]').each(function() {
                        this.checked = true;
                    });
                });

                /*Deshabilitar todos los registros*/
                $('body').on("click", "#unselect_all", function(e) {
                    $('input[id^="area_"]').each(function() {
                        this.checked = false;
                    });
                });
            });
        </script>
    </head>
    <body>

        <?php
        //echo $nivel;
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
        ?>
    </center>
        <br />
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-12" style="background-color:#FAFAFA;">
                    <form role="form" action="" onSubmit="updateRegistros(); buildAreas(); return false;" id="lab-form">
                        <div class="row">
                            <div class="col-xs-12 col-md-12" style="padding-top:10px;padding-bottom:10px;">
                                <label style="padding-left: 15px; min-width:80px;">&Aacute;rea:</label>
                                <select id="cmb-area"></select>
                            </div>
                        </div>
                        <div class="table-responsive" style="padding: 15px 0">
                            <div class="row">
                                <div class="col-xs-3 col-md-3">
                                    <ul class="nav nav-pills nav-stacked" id="nav-header">
                                        <li class="active"><a href="#pane1" role="tab" data-toggle="pill">Home</a></li>
                                        <li><a href="#pane2" role="tab" data-toggle="pill">Profile</a></li>
                                        <li><a href="#pane3" role="tab" data-toggle="pill">Messages</a></li>
                                        <li><a href="#pane4" role="tab" data-toggle="pill">Other</a></li>
                                    </ul>
                                </div>
                                <div class="col-xs-9 col-md-9">
                                    <div class="tab-content" id="nav-content">
                                        <div class="tab-pane fade in active" id="pane1">pane 1</div>
                                        <div class="tab-pane fade" id="pane2">pane 2</div>
                                        <div class="tab-pane fade" id="pane3">pane 3</div>
                                        <div class="tab-pane fade" id="pane4">pane 4</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
