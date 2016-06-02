<?php
session_start();
include_once("../Conexion/ConexionBD.php"); //Agregamos el Archivo con las clases y funciones a utilizar
setlocale(LC_ALL,"es_SV.UTF-8");
//include ("../encabezado.php");
$nivel = $_SESSION['NIVEL'];
$lugar = $_SESSION['Lugar'];
$area  = $_SESSION['Idarea'];
$corr  = $_SESSION['Correlativo'];
$cod   = $_SESSION['IdEmpleado'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];

include_once $ROOT_PATH."/public/css.php";
include_once $ROOT_PATH."/public/js.php";
echo '<h4><i>Fecha actual: '.strftime("%A, %d de %B de %Y").'<br/></i></h4>';

   $con = new ConexionBD;
   if($con->conectar()==true){
       	$query="SELECT count(*) as total, idestado, descripcion
                from sec_solicitudestudios t1
                join ctl_estado_servicio_diagnostico t2 on (t2.id=t1.estado)
                where idestado not in ('C', 'RM')
                group by idestado, descripcion, t2.id
                order by t2.id; ";
	 $result = @pg_query($query);

     $query2="SELECT count(*) as total, idestado, descripcion
                from sec_solicitudestudios t1
                join ctl_estado_servicio_diagnostico t2 on (t2.id=t1.estado)
                where extract (month from fechahorareg)= extract (month from current_date)
                and extract (year from fechahorareg)= extract(year from fechahorareg)
                group by idestado, descripcion, t2.id
                order by t2.id;  ";
	$result2 = @pg_query($query2);

    $query21="SELECT count(*) as total, idestado, descripcion, extract(year from current_date)
                from sec_solicitudestudios t1
                join ctl_estado_servicio_diagnostico t2 on (t2.id=t1.estado)
                where extract (year from fechahorareg)= extract(year from current_date)
                group by idestado, descripcion, t2.id
                order by t2.id;  ";
	$result21 = @pg_query($query21);

    $query3="WITH tbl as (
            select count(*)  as total, idestado, descripcion, t2.id as idest
            from sec_solicitudestudios t1
            join ctl_estado_servicio_diagnostico t2 on (t2.id=t1.estado)
            --where t2.id not in (4,7,8,6)
            group by idestado, descripcion, t2.id
            )
            select (round( ((select sum(total) from tbl where idest in (4,7,8,6))/sum(total)::numeric)*100 ,1))::text  as porcentaje from tbl";
    $result3 = @pg_query($query3);
    $percent = pg_fetch_array($result3);


?>

<div class="row" style="margin-top: 90px;">

    <div class="col-md-3">

  </div>
    <div class="col-md-5 ">
        <div class="box"  style="text-align: right;">
            <div class="box-header">
            <a href="../LAB/Procesos/Recepcion/RecepcionLab.php" class="btn btn-app" >
                <i class="fa fa-file-o" ></i> Nueva Solicitud
            </a>
            <a href="../LAB/Procesos/RecepcionSolicitud/Proc_RecepcionSolicitud.php" class="btn btn-app" >
                <i class="fa fa-check-square-o" ></i> Recepción muestra
            </a>
            <a href="../LAB/Procesos/IngresoResultados/Proc_SolicitudesProcesadas.php" class="btn btn-app" >
                <i class="fa fa-edit" ></i> Ingreso de Resultados
            </a>
              <!-- <a href="../LAB/Procesos/Recepcion/RecepcionLab.php" class="btn btn-link" style="color:#c8f1ff !important;"><button class="btn btn-block btn-primary btn-xs" >Nueva Solicitud</button></a>
              <a href="../LAB/Procesos/RecepcionSolicitud/Proc_RecepcionSolicitud.php" class="btn btn-link" style="color:#c8f1ff !important;">Recepción muestra</a>
              <a href="../LAB/Procesos/IngresoResultados/Proc_SolicitudesProcesadas.php" class="btn btn-link" style="color:#c8f1ff !important;">Ingreso de Resultados</a> -->
          </div>
        </div>

        <div class="callout bg-navy disabled color-palette"  style="text-align: left;">
            <span class="info-box-icon"><i class="fa fa-calendar-check-o"></i></span>


            <div class="info-box-content">
              <span class="info-box-number">Estado de solicitudes registradas en el mes actual (<?php echo strftime('%B') ?>) </span>

              <?php
                  $num2=pg_num_rows($result2);
                  if ($num2 >0){
                  echo '<table class="progress-description" style="color:white;">';

                      while ($row2= pg_fetch_array($result2)){
                         echo '<tr><td width="5px"><td align="right"> <span class="progress-description"> '.$row2[0].'&nbsp;&nbsp;&nbsp;Solicitud(es)</span></td>';
                         echo '<td><span class="progress-description"> &nbsp;'.$row2["descripcion"].'</span></td></tr>';

                      }


                  echo '</table>';
                  }
                  else{
                      echo '<p><i>&emsp;No hay registro de solicitudes en este período....</i></p>';
                  }
                  echo '<hr style="border-top: 1px dotted #8c8b8b;">';
              ?>
            </div>
            <!-- /.info-box-content -->
          </div>
          <div class="callout bg-navy color-palette" style="text-align: left;">
              <span class="info-box-icon"><i class="fa fa-calendar-o"></i></span>
              <div class="info-box-content">
                <span class="info-box-number">Estado de solicitudes registradas en el año  actual (<?php echo date('Y') ?>)</span>
                <?php
                    $num21=pg_num_rows($result21);
                    if ($num21 >0){
                    echo '<table class="progress-description" style="color:white;">';
                     while ($row21= pg_fetch_array($result21)){
                        echo '<tr><td width="5px"><td align="right"> <span class="progress-description"> '.$row21[0].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Solicitud(es)</span></td>';
                        echo '<td><span class="progress-description"> &nbsp;'.$row21["descripcion"].'</span></td></tr>';

                     }


                    echo '</table>';
                    }
                    else{
                        echo '<p><i>&emsp;No hay registro de solicitudes en este período....</i></p>';
                    }
                    echo '<hr style="border-top: 1px dotted #8c8b8b;">';
                ?>
              </div>
              </div>
              <!-- /.info-box-content -->
              <div class="callout bg-navy color-palette" style="text-align: left;">
                  <span class="info-box-icon"><i class="fa fa-tachometer"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-number">Estado de solicitudes registradas en total</span>
                    <?php
                        $num=pg_num_rows($result);
                        if ($num >0){
                        echo '<table class="progress-description" style="color:white;">';
                         while ($row= pg_fetch_array($result)){
                            echo '<tr><td width="5px"><td align="right"> <span class="progress-description"> '.$row[0].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Solicitud(es)</span></td>';
                            echo '<td><span class="progress-description"> &nbsp;'.$row["descripcion"].'</span></td></tr>';

                         }


                        echo '</table>';
                        }
                        else{
                            echo '<p><i>&emsp;No hay registro de solicitudes en este período....</i></p>';
                        }
                        echo '<hr style="border-top: 1px dotted #8c8b8b;">';
                    ?>
                  </div>
                  <!-- /.info-box-content -->
            </div>


        <!-- <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa fa-tachometer"></i></span>

            <div class="info-box-content" style="text-align:left">
                <div class="row">
                    <div class="col-md-9">
                        <span class="info-box-text"><b>Estado de solicitudes registradas en el meso</b></span>
                        <?php
                        // $num2=pg_num_rows($result2);
                        // if ($num2 >0){
                        // echo '<table class="progress-description" style="color:white;">';
                        //
                        //     while ($row2= pg_fetch_array($result2)){
                        //        echo '<tr><td width="5px"><td align="right"> <span class="progress-description"> '.$row2[0].'&nbsp;&nbsp;&nbsp;Solicitud(es)</span></td>';
                        //        echo '<td><span class="progress-description"> &nbsp;'.$row2["descripcion"].'</span></td></tr>';
                        //
                        //     }
                        //
                        //
                        // echo '</table>';
                        // echo '<hr style="border-top: 1px dotted #8c8b8b;">';
                        // }
                        // else{
                        //     echo '<p>No hay registro de solicitudes en este período....</p>';
                        // }
                        ?>
                    </div>
                    <div class="col-md-9">
                        <span class="info-box-text"><b>Estado de solicitudes registradas durante el presente año</b></span>
                        <?php
                        // $num21=pg_num_rows($result21);
                        // if ($num21 >0){
                        // echo '<table class="progress-description" style="color:white;">';
                        //  while ($row21= pg_fetch_array($result21)){
                        //     echo '<tr><td width="5px"><td align="right"> <span class="progress-description"> '.$row21[0].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Solicitud(es)</span></td>';
                        //     echo '<td><span class="progress-description"> &nbsp;'.$row21["descripcion"].'</span></td></tr>';
                        //
                        //  }
                        // echo '</table>';
                        // }
                        // else{
                        //     echo '<p><i>No hay registro de solicitudes en este período....</i></p>';
                        // }
                        // echo '<hr style="border-top: 1px dotted #8c8b8b;">';
                        ?>
                    </div>
                    <div class="col-md-9">
                        <span class="info-box-text"><b>Estado de Solicitudes pendientes totales.</b></span>
                        <?php
                        // echo '<span class="info-box-number"><table  style="color:white;">';
                        // $num=pg_num_rows($result);
                        // while ($row= pg_fetch_array($result)){
                        //     echo '<tr><td width="5px"></td><td align="right"> <b>'.$row[0].'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Solicitud(es)</td>';
                        //     echo '<td> &nbsp;'.$row["descripcion"].'</td></tr>';
                        // }
                        // echo '</table></span>';
                        ?>
                    </div>
                    <div class="col-md-5">
                        <span class="info-box-text"><b>Estado de solicitudes registradas en el mes</b></span>
                        <?php
                        // echo '<table class="progress-description" style="color:white;">';
                        // $num2=pg_num_rows($result2);
                        //  while ($row2= pg_fetch_array($result2)){
                        //     echo '<tr><td width="5px"><td align="right"> <span class="progress-description"> '.$row2[0].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Solicitud(es)</span></td>';
                        //     echo '<td><span class="progress-description"> &nbsp;'.$row2["descripcion"].'</span></td></tr>';
                        //
                        //  }
                        // echo '</table>';
                        ?>
                    </div>
                    <div class="col-md-1 text-right"   >
                        <?php
                        // $tooltipexp="DIGITADA: Solicitudes de médicos, pendientes de recepcionar en el área. &#010;
                        //
                        //      RECIBIDA: Solicitud pendiente de recepcionar en las secciones.  &#010;
                        //
                        //      EN PROCESO: Solicitudes pendientes de ingresarles resultados de examenes. ";
                        //     echo '<a href="#" style="html: true" title="'.$tooltipexp.'"><span class="glyphicon glyphicon-info-sign"  ></span></a>';
                        ?>
                    </div>
                </div>
                <div class="progress">
                    <div title="<?php echo $percent[0]; ?>%" class="progress-bar" style="width: <?php echo $percent[0]; ?>%"><?php echo $percent[0]; ?></div>
                </div>
                <div class="row">
                  <div class="col-md-5">
                      <span class="progress-description" style="align=left">
                          Porcentaje de Solicitudes finalizadas: <?php echo $percent[0] ?> %
                      </span>
                  </div>
                  <div class="col-md-7 text-right">
                    <a href="../LAB/Procesos/Recepcion/RecepcionLab.php" class="btn btn-link" style="color:#c8f1ff !important;">Nueva Solicitud</a>
                    <a href="../LAB/Procesos/RecepcionSolicitud/Proc_RecepcionSolicitud.php" class="btn btn-link" style="color:#c8f1ff !important;">Recepción muestra</a>
                    <a href="../LAB/Procesos/IngresoResultados/Proc_SolicitudesProcesadas.php" class="btn btn-link" style="color:#c8f1ff !important;">Ingreso de Resultados</a>
                  </div>
                </div>

            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-md-3"></div>
</div>
<br><br>
<?php
 }
 ?>
