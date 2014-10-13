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
        <script language="JavaScript" type="text/javascript" src="ajax_Lab_Areas.js"></script>
        <script type="text/javascript">
            function buildAreas() {
                jQuery("#tbody-area").empty();

                var html = "";
                getLabAreas(function(object){
                    if(object.status) {
                        jQuery.each(object.data, function(idx,val) {
                            var checked = '';
                            if(val.estado === 'H')
                                checked = 'checked';

                            if(idx % 2 === 0 || idx === 0)
                                html = html + '<tr>';

                            html = html +'<td>\
                                        <div class="checkbox">\
                                            <label>\
                                                <input type="checkbox" id="area_'+val.id+'" name="area_'+val.id+'" value="'+val.id+'" '+checked+'>\
                                                '+val.nombrearea+'\
                                            </label>\
                                        </div>\
                                    </td>';

                            if(idx % 2 !== 0 && idx !== 0)
                                html = html + '</tr>';
                        });

                        html = html + "<tr>\
                                        <td>\
                                            <button type='button' id='select_all' class='btn btn-link'>\
                                                <span class='glyphicon glyphicon-check' style='font-size: 10px;'>Seleccionar Todos</span>\
                                            </button>\
                                        </td>\
                                        <td>\
                                            <button type='button' id='unselect_all' class='btn btn-link'>\
                                                <span class='glyphicon glyphicon-unchecked' style='font-size: 10px;'>Deseleccionar Todos</span>\
                                            </button>\
                                        </td>\
                                    </tr>";
                    } else {
                        html = html + "<tr><td colspan='2'>Error al procesar los datos..!!!</td></tr>";
                    }

                    jQuery("#tbody-area").append(html);
                });
            }

            jQuery(document).ready(function($) {
                buildAreas();

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
        <label style="padding-left: 15px;">Por favor seleccione las areas del establecimiento:</label>
        <center>
            <div style="background-color:#FAFAFA;padding: 20px 0 20px 0;">
                <form role="form" action="" onSubmit="updateRegistros(); buildAreas(); return false;" id="lab-form">
                    <div class="table-responsive" style="width: 80%;">
                        <table class="table table-hover table-bordered table-condensed table-white no-v-border">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-area">
                            </tbody>
                        </table>
                    </div>
                    <input type="submit" name="bt-submit" id="bt-submit" value="Aceptar" class="btn btn-primary">
                </form>
            </div>
        </center>
    </body>
</html>
