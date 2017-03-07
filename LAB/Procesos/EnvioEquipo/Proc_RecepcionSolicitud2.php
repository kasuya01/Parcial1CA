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
            <script language="JavaScript" >

            </script>
            <script type="text/javascript">

            </script>
        </head>
        <body  style="background-color:#fff; color:#194775 ; font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
            <br/>
            <div class="btn-group">
              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Nueva Solicitud <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">Separated link</a></li>
              </ul>
            </div>

              <br/>

            <form role="form">
            <div class="row">
                <div class='col-md-1'></div>
                <div class='col-md-10'>
                    <div class="box box-primary">
                        <div class="box-header with-border" style="background-color:#367FA9; border-color:#163f69; ">
                            <h3 class="box-title" style="color:#fff;text-align:center; width: 100%; font-weight:bold;">Datos Nueva Solicitud</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->

                            <div class="box-body" style="background-color:#ECF0F5">
                                <br>
                                <div class="form-group">
                                  <label for="inputEmail3" class="col-sm-4 control-label" style="text-align:right">Motivo de Análisis</label>
                                  <div class="col-sm-4">
                                      <select class="form-control" placeholder="Selecciona una opcion">
                                          <option selected>Sospecha Diagnóstica</option>
                                      </select>
                                  </div>
                                  <br>
                                </div>
                                <br>
                                <!-- <div class="form-group">
                                    <label for="exampleInputEmail1">Motivo de Análisis</label>
                                    <select class="form-control" placeholder="Selecciona una opcion">
                                        <option selected>Sospecha Diagnóstica</option>
                                    </select>
                                </div> -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Nombre de la Institución</label>
                                            <select class="form-control" placeholder="Selecciona una opcion">
                                                <option selected>Seleccionar institución</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Nombre de Establecimiento </label>
                                            <select class="form-control" placeholder="Selecciona una opcion">
                                                <option selected>Seleccionar Establecimiento</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">No. Expediente/No. Afiliación</label>
                                            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="99999-99">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Sexo</label>
                                            <select class="form-control" placeholder="Selecciona una opcion">
                                                <option selected>Seleccionar Sexo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Edad (Años- Meses- Dias)</label>
                                            <div class="row">
                                                <div class="col-xs-4">
                                                    <input type="text" class="form-control" placeholder="Años">
                                                </div>
                                                <div class="col-xs-4">
                                                    <input type="text" class="form-control" placeholder="Meses">
                                                </div>
                                                <div class="col-xs-4">
                                                    <input type="text" class="form-control" placeholder="Días">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Fecha de Nacimiento</label>
                                            <!-- <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"> -->
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon1">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                                <input type="text" class="form-control" placeholder="dd-mm-aaaa" aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Apellidos</label>
                                            <div class="row">
                                                <div class="col-xs-4">
                                                    <input type="text" class="form-control" placeholder="Primer Apellido">
                                                </div>
                                                <div class="col-xs-4">
                                                    <input type="text" class="form-control" placeholder="Segundo Apellido">
                                                </div>
                                                <div class="col-xs-4">
                                                    <input type="text" class="form-control" placeholder="Apellido de Casada">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Nombres</label>
                                            <div class="row">
                                                <div class="col-xs-4">
                                                    <input type="text" class="form-control" placeholder="Primer Nombre">
                                                </div>
                                                <div class="col-xs-4">
                                                    <input type="text" class="form-control" placeholder="Segundo Nombre">
                                                </div>
                                                <div class="col-xs-4">
                                                    <input type="text" class="form-control" placeholder="Tercer Nombre">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Sección de destino</label>
                                            <select class="form-control" placeholder="Selecciona una opcion">
                                                <option selected>Seleccionar sección destino</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Sospecha Diagnóstica</label>
                                            <!-- <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"> -->
                                            <select class="form-control" placeholder="Selecciona una opcion">
                                                <option selected>Seleccionar Sospecha diagnostica</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Examen Solicitado</label>
                                    <!-- <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"> -->
                                    <select class="form-control" placeholder="Selecciona una opcion">
                                        <option selected>Seleccionar Examen</option>
                                    </select>
                                </div>

                            </div>
                            <!-- /.box-body -->



                    </div>
                </div>
            </div>
            <div class="row">
                <div class='col-md-1'></div>
                <div class='col-md-10'>
                    <div class="box box-info">
                        <div class="box-header with-border" style="background-color:#367FA9; border-color:#a0afbb; ">
                            <h3 class="box-title" style="color:#fff;text-align:center; width: 100%; font-weight:bold;">Seleccionar Muestra</h3>
                        </div>
                        <div class="box-body" style="background-color:#ECF0F5">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Tipo de Muestra</label>
                                        <select class="form-control" placeholder="Selecciona una opcion">
                                            <option selected>Seleccionar Tipo de Muestra</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Sitio Anátomico Origen de Muestra</label>
                                        <select class="form-control" placeholder="Selecciona una opcion">
                                            <option selected>Seleccionar Sitio Anátomico</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Descripción</label>
                                <textarea class="form-control" rows="3" placeholder="Descripción..."></textarea>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Validar Muestra</label>
                                        <select class="form-control" placeholder="Selecciona una opcion">
                                            <option selected>Rechazar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Motivo de Rechazo</label>
                                        <select class="form-control" placeholder="Selecciona una opcion">
                                            <option selected>Otros</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputFile">Otros</label>
                                        <textarea class="form-control" rows="1" placeholder="Descripción..."></textarea>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-plus"></i> Agregar </button>
                            </div>
                            <div class="row" style="margin-top:80px;">
                                <div class="col-md-12">
                                    <table class="table table-white">
                                        <thead>
                                            <tr>
                                                <th>#</th> <th>Tipo de Muestra</th> <th>Sitio Anátomico</th> <th>Descripción</th> <th>Validación</th> <th>Motivo Rechazo</th><th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">1</th> <td>Secreción</td> <td>Uretra</td> <td>-</td> <td>Aceptada</td> <td>-</td><td style="font-size:20px"><i class="fa fa-trash-o"></i></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">1</th> <td>Liquido</td> <td>Cefalorraquideo</td> <td>-</td> <td>Rechazada</td> <td>Otros</td><td  style="font-size:20px"><i class="fa fa-trash-o"></i></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="box-footer">
                            <div class="form-group pull-right">
                                <button type="submit" class="btn btn-success" name="btn_create_and_list"><i class="fa fa-save"></i> <i class="fa fa-list"></i> Crear y Regresar al Menú</button>
                                <button class="btn btn-success" type="submit" name="btn_create_and_create"><i class="fa fa-plus-circle"></i> Crear y agregar otro</button>
                                <button type="submit" class="btn btn-default" name="btn_create_and_list"> Cancelar</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

                        </form>
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
