<?php
    session_start();
    include_once("cls_Mnt_confestipolab.php");
    $mntconfestipolab = new cls_Mnt_confestipolab;
    $nivel = $_SESSION['NIVEL'];
    $corr  = $_SESSION['Correlativo'];
    $lugar = $_SESSION['Lugar'];
    $area  = $_SESSION['Idarea'];
    $ROOT_PATH = $_SESSION['ROOT_PATH'];
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Mantenimiento de establecimientos a referir</title>
        <?php include_once $ROOT_PATH."/public/css.php";?>
        <?php include_once $ROOT_PATH."/public/js.php";?>
        <script language="JavaScript" type="text/javascript" src="ajax_Mnt_confestipolab.js"></script>
        <script type="text/javascript">
        function iniciarselects2(){
            $("[id^=cmbEstablecimiento]").select2({
               placeholder: "Seleccione un Establecimiento si aplica...",
               allowClear: true
            });
        }
        jQuery(document).ready(function($) {
          iniciarselects2();
        $("[id^=cmbEstablecimiento]").on("select2-removing", function(e) {
                  console.log(e.val);
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
        if ($nivel == 5) {
            include_once ('../../../PaginaPrincipal/index_laboratorio52.php');}
        if ($nivel == 6) {
            include_once ('../../../PaginaPrincipal/index_laboratorio62.php');}
        if ($nivel == 7) {
            include_once ('../../../PaginaPrincipal/index_laboratorio72.php'); }
        ?>
    </center>
        <br />
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-12" style="padding-top: 30px;margin-bottom: 30px;">
                    <form role="form" action="" onSubmit="insertRegisters(); return false;" id="lab-form">
                        <div class="row">
                            <div class="col-xs-12 col-md-12"  style="padding-top:10px;padding-bottom:10px;">
                                <div class="panel panel-info">
                                    <div class="panel panel-heading">
                                    <h3 class="box-title">Establecimientos a referir, por niveles de atención</h3>
                                </div>


                                <div class="box-body">

                                    <!-- <div class="col-xs-5 col-md-5">

                                    </div> -->

                                    <!-- <div class="col-xs-10 col-md-10 text-right"> -->
                                        <table class="table table-striped" style="border-bottom: 1px solid #31708f;">
                                            <?php
                                            $tiposest=$mntconfestipolab->getconfestipolab();
                                            while ($row = pg_fetch_array($tiposest)) {
                                                echo "<tr><td class='ridge' style='width:25%'>
                                                <span class='glyphicon glyphicon-home' style='color:#31708f'></span> &nbsp;" .$row['nombre']."</td>";

                                                echo '<td><select id="cmbEstablecimiento'.$row["id"].'" name="cmbEstablecimiento'.$row["id"].'[]" size="1" style="width:100%"  class="height js-example-placeholder-multiple  select2-lightblue" multiple="multiple" >';

                                                        $tipomuestra=$mntconfestipolab->getconfestab($row['id']);
                                                        while($row2 = pg_fetch_array($tipomuestra)){
                                                            $b=0;
                                                            $estconf=$mntconfestipolab->getconfestipolabconf($row['id']);
                                                            while($row3 = pg_fetch_array($estconf)){
                                                                if ($row2['idestab']==$row3['idestab']){
                                                                    echo "<option value='" . $row2['idestab']. "_".$row2['idestipolab']."' selected>" .$row2['nombrestab'] . "</option>";
                                                                    $b=1;
                                                                }
                                                            }
                                                            if ($b==0){
                                                                echo "<option value='" . $row2['idestab']. "_".$row2['idestipolab']."'>" .$row2['nombrestab'] . "</option>";
                                                            }
                                                        }
                				                echo '</select><br /></td>';
                                            }
                                             ?>
                                        </table>
                                        <br />
                                        <center>
                                            <input type="submit" name="bt-submit_1" id="bt-submit_1" value="Aceptar" class="btn btn-primary">
                                            <input type="button" name="bt-submit_2" id="bt-submit_2" value="Limpiar" onclick="LimpiarCampos();" class="btn btn-primary">
                                        </center>
                                    <!-- </div> -->
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

                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
