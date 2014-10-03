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
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Mantenimiento de Areas y Examenes de Laboratorio</title>
        <?php include_once $ROOT_PATH."/public/css.php";?>
        <?php include_once $ROOT_PATH."/public/js.php";?>
        <script language="JavaScript" type="text/javascript" src="ajax_Mnt_AreaExamenEstablecimiento.js"></script>
        <script type="text/javascript">
            function loadContent() {
                getAreaExamenEstablecimiento(function(object) {
                    var estandar = [];

                    if(object.status) {
                        var nav_header = "";
                        var nav_content = "";
                        var index = jQuery( "#cmb-area option:selected" ).val();
                        var grp_idx = 0;

                        jQuery('#nav-header').empty();
                        jQuery('#nav-content').empty();

                        jQuery.each(object.data[index]['grupos'], function(gidx,gval) {
                            var exa_idx = 0;

                            if(grp_idx === 0) {
                                nav_header = nav_header + '<li class="active">';
                                nav_content = nav_content + '<div class="tab-pane fade in active" id="pane_'+gval.codigo+'">';
                            } else {
                                nav_header = nav_header + '<li>'
                                nav_content = nav_content + '<div class="tab-pane fade" id="pane_'+gval.codigo+'">';
                            }
                            
                            nav_header = nav_header + '<a href="#pane_'+gval.codigo+'" role="tab" data-toggle="pill">'+gval.nombre+'</a></li>';
                            grp_idx++;

                            nav_content = nav_content + '<div class="table-responsive" style="max-height: 748px;overflow: hidden;overflow-y: auto;overflow-x: auto;"><table class="table table-hover table-bordered table-condensed table-white no-v-border" style="border: 1px solid #fff !important;"><tbody>';

                            jQuery.each(object.data[index]['grupos'][gidx]['examenes'], function(eidx,eval) {
                                var checked = '';
                                if(eval.activo === 'true')
                                    checked = 'checked';
                                
                                if(exa_idx % 2 === 0 || exa_idx === 0)
                                    nav_content = nav_content + '<tr>';

                                nav_content = nav_content + '<td>\
                                                                <div class="checkbox">\
                                                                    <label>\
                                                                        <input type="checkbox" id="exam_'+gval.codigo+'_'+eval.id+'" name="exam_'+gval.codigo+'_'+eval.id+'" value="'+eval.id+'_'+eval.activo+'" '+checked+'>\
                                                                        '+eval.nombre+'\
                                                                    </label>\
                                                                </div>\
                                                            </td>';

                                if(exa_idx % 2 !== 0 && exa_idx !== 0)
                                    nav_content = nav_content + '</tr>';

                                exa_idx++;
                            });

                            nav_content = nav_content + '</tbody></table></div></div>'
                        });

                        jQuery('#nav-header').append(nav_header);
                        jQuery('#nav-content').append(nav_content);
                    }
                });
            }


            jQuery(document).ready(function($) {


                getLabAreas(function(object) {
                    if(object.status) {
                        $('#cmb-area').children().remove();
                        $.each(object.data, function(idx,val) {
                            $('#cmb-area').append($("<option></option>").attr("value",val.idarea).text(val.nombrearea));
                        });
                        
                        $("#cmb-area option:first").attr('selected','selected');
                        $('#cmb-area').trigger("change");
                    }
                });

                $('#cmb-area').on('change', function(e) {
                    loadContent();
                });

                /*Habilitar todos los registros*/
                $('button[id^="select_all"]').on("click", function(e) {
                    $('input[id^="exam_"]').each(function() {
                        this.checked = true;
                    });
                });

                /*Deshabilitar todos los registros*/
                $('button[id^="unselect_all"]').on("click", function(e) {
                    $('input[id^="exam_"]').each(function() {
                        this.checked = false;
                    });
                });

                /*Habilitar todos los registros de un determinado grupo*/
                $('button[id^="select_group"]').on("click", function(e) {
                    var group_code = $('li.active a').attr('href');
                    var toRemove = '#pane_';
                    group_code = group_code.replace(toRemove,'');

                    $('input[id^="exam_'+group_code+'"]').each(function() {
                        this.checked = true;
                    });
                });

                /*Deshabilitar todos los registros de un determinado grupo*/
                $('button[id^="unselect_group"]').on("click", function(e) {
                    var group_code = $('li.active a').attr('href');
                    var toRemove = '#pane_';
                    group_code = group_code.replace(toRemove,'');

                    $('input[id^="exam_'+group_code+'"]').each(function() {
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
                <div class="col-xs-12 col-md-12" style="background-color:#FAFAFA;padding-top: 30px;margin-bottom: 30px;">
                    <form role="form" action="" onSubmit="updateRegisters(); loadContent(); return false;" id="lab-form">
                        <div class="row">
                            <div class="col-xs-12 col-md-12" style="padding-top:10px;padding-bottom:10px;">
                                <div class="row">
                                    <div class="col-xs-5 col-md-5">
                                        <label style="padding-left: 15px; min-width:80px;">&Aacute;rea:</label>
                                        <select id="cmb-area">
                                        </select>
                                    </div>
                                    <div class="col-xs-7 col-md-7 text-right">
                                        <button type='button' id='select_all_1' class='btn btn-link'>
                                            <span class='glyphicon glyphicon-check' style='font-size: 10px;'>Seleccionar Todos</span>
                                        </button>
                                        <button type='button' id='select_group_1' class='btn btn-link'>
                                            <span class='glyphicon glyphicon-check' style='font-size: 10px;'>Seleccionar Grupo</span>
                                        </button>
                                        <button type='button' id='unselect_group_1' class='btn btn-link'>
                                            <span class='glyphicon glyphicon-unchecked' style='font-size: 10px;'>Deseleccionar Grupo</span>
                                        </button>
                                        <button type='button' id='unselect_all_1' class='btn btn-link'>
                                            <span class='glyphicon glyphicon-unchecked' style='font-size: 10px;'>Deseleccionar Todos</span>
                                        </button>
                                        <input type="submit" name="bt-submit_1" id="bt-submit_1" value="Aceptar" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3 col-md-3">
                                <ul class="nav nav-pills nav-stacked" id="nav-header">
                                </ul>
                            </div>
                            <div class="col-xs-9 col-md-9">
                                <div class="tab-content" id="nav-content">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12 text-right">
                                <button type='button' id='select_all_2' class='btn btn-link'>
                                    <span class='glyphicon glyphicon-check' style='font-size: 10px;'>Seleccionar Todos</span>
                                </button>
                                <button type='button' id='select_group_2' class='btn btn-link'>
                                    <span class='glyphicon glyphicon-check' style='font-size: 10px;'>Seleccionar Grupo</span>
                                </button>
                                <button type='button' id='unselect_group_2' class='btn btn-link'>
                                    <span class='glyphicon glyphicon-unchecked' style='font-size: 10px;'>Deseleccionar Grupo</span>
                                </button>
                                <button type='button' id='unselect_all_2' class='btn btn-link'>
                                    <span class='glyphicon glyphicon-unchecked' style='font-size: 10px;'>Deseleccionar Todos</span>
                                </button>
                                <input type="submit" name="bt-submit_2" id="bt-submit_2" value="Aceptar" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
