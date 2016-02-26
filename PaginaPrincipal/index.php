<?php
session_start();
include_once("../Conexion/ConexionBD.php"); //Agregamos el Archivo con las clases y funciones a utilizar
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

    <div class="col-md-3"></div>
    <div class="col-md-6 ">
        <div class="info-box bg-navy">
            <span class="info-box-icon"><i class="fa  fa-tachometer"></i></span>

            <div class="info-box-content" style="text-align:left">
                <div class="row">
                    <div class="col-md-5">
                        <span class="info-box-text"><b>Estado de Solicitudes pendientes.</b></span>
                        <?php
                        echo '<span class="info-box-number"><table  style="color:white;">';
                        $num=pg_num_rows($result);
                        while ($row= pg_fetch_array($result)){
                            echo '<tr><td width="5px"></td><td align="right"> <b>'.$row[0].'</b>&nbsp;&nbsp;&nbsp;Solicitud(es)</td>';
                            echo '<td> &nbsp;'.$row["descripcion"].'</td></tr>';
                        }
                        echo '</table></span>';
                        ?>
                    </div>
                    <div class="col-md-5">
                        <span class="info-box-text"><b>Estado de solicitudes registradas en el mes</b></span>
                        <?php
                        echo '<table class="progress-description" style="color:white;">';
                        $num2=pg_num_rows($result2);
                         while ($row2= pg_fetch_array($result2)){
                            echo '<tr><td width="5px"><td align="right"> <span class="progress-description"> '.$row2[0].'&nbsp;&nbsp;&nbsp;Solicitud(es)</span></td>';
                            echo '<td><span class="progress-description"> &nbsp;'.$row2["descripcion"].'</span></td></tr>';
                         }
                        echo '</table>';
                        ?>
                    </div>
                    <div class="col-md-1 text-right"   >
                        <?php
                        $tooltipexp="DIGITADA: Solicitudes de médicos, pendientes de recepcionar en el área. &#010;

                             RECIBIDA: Solicitud pendiente de recepcionar en las secciones.  &#010;

                             EN PROCESO: Solicitudes pendientes de ingresarles resultados de examenes. ";
                            echo '<a href="#" style="html: true" title="'.$tooltipexp.'"><span class="glyphicon glyphicon-info-sign"  ></span></a>';
                        ?>
                    </div>
                </div>
                <div class="progress">
                    <div title="" class="progress-bar" style="width: <?php echo $percent[0]; ?>%"></div>
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
