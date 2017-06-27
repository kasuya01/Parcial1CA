<?php
session_start();
include_once("clsRecepcionSolicitud.php");
$usuario = $_SESSION['Correlativo'];
$lugar   = $_SESSION['Lugar'];
$area    = $_SESSION['Idarea'];
$nestab  = $_SESSION['nombre_estab'];




?>
<script language="JavaScript" type="text/javascript" src="ajax_RecepcinSolicitud.js"></script>
<script language="JavaScript" >
$(document).ready(function () {

    $('#myModal').dialog({
        modal: true,
        autoOpen: false
    });

    $('#estabarealiza2').change(function () {
        if ($(this).val() == "1") {
            $('#myModal8').dialog('open');
        }
    });

});
</script>
<?php

//variables POST
$idexpediente = $_POST['idexpediente'];
 $fechacita    = $_POST['fechacita'];
$Nfecha       = explode("/", $fechacita);
$Nfechacita = $Nfecha[2] . "-" . $Nfecha[1] . "-" . $Nfecha[0];
$estado     = 'D';
 $idEstablecimiento = $_POST['idEstablecimiento'];
 $idsolicitud = $_POST['idsolicitud'];
 $idestablecimientoext = $_POST['idestablecimientoext'];
$arraysolic  = array();
$arraypiloto = array();
$i   = 0;
$j   = 0;
$ban = 0;
$pos = 0;
//actualiza los datos del empleados
$objdatos = new clsRecepcionSolicitud;
$procref=$objdatos->BuscarProcReferido();
while ($row_pl=@pg_fetch_array($procref)){
    if($row_pl['id_proceso_laboratorio']==10)
        $refext=$row_pl['activo'];
    if ($row_pl['id_proceso_laboratorio']==11)
        $hl7=$row_pl['activo'];
    if ($row_pl['id_proceso_laboratorio']==12)
        $bdi=$row_pl['activo'];
}
$configuracion=0;

$consulta = $objdatos->BuscarSolicitudes($idexpediente,  $fechacita, $lugar, $idEstablecimiento, $idsolicitud, $idestablecimientoext);
//echo "Nfecha= ".$Nfechacita;
$NroRegistros = $objdatos->NumeroDeRegistros($idexpediente,  $fechacita, $lugar, $idEstablecimiento, $idsolicitud,$idestablecimientoext);
$pil = $objdatos->Piloto($idexpediente,  $fechacita, $lugar, $idEstablecimiento, $idsolicitud ,$idestablecimientoext);
//echo "NUM ".$NroRegistros ;
while ($piloto = pg_fetch_array($pil)) {
  $arraypiloto[$j] = $piloto[0];
    $j++;
}

while ($rowsolic = pg_fetch_array($consulta)) {
    $arraysolic[$i] = $rowsolic[0];
    
  //  echo $rowsolic[0];
    $i++;
}
//echo "antes del for".$idestablecimientoext;
for ($i = 0; $i < $NroRegistros; $i++) {
   // echo $i;
    echo "<div class='row'>
    <div class='col-md-1'></div>";

        echo "<div class='col-md-10'>";
            echo "<div class='box box-primary'>";
//echo "despues del for ".$idestablecimientoext;

    $ConsultaDatos = $objdatos->BuscarDatosSolicitudes($idexpediente, $fechacita, $arraysolic[$i], $lugar,$idestablecimientoext);

    while ($row = pg_fetch_array($ConsultaDatos)) {
        echo '<div class="box-header with-border">
              <div class="col-md-8"><h3 class="box-title"><i class="fa fa-file margin-r-5"></i> Solicitud de Estudios</h3></div>
              <div class="col-md-4 text-right">
                <strong><i class="fa fa-tag margin-r-5"></i> Tipo Solicitud:</strong>
                <label> ' . htmlentities($row['tiposolicitud']) . '</label>

              </div>
            </div>';
        echo "<div class='box-body'>
                  <strong><i class='fa fa-user round-icon bg-blue'></i><font color='#0073B7'> Datos de Paciente</font></strong><br/>


                  <table style='width:90%; background-color:#ecf5ff' border='0' align='center' class='table tableinfo table-hover'>
                          <tr>
                            <th class='th-info'>Establecimiento Solicitante</th>
                            <td class='td-blue' colspan='3'>" . htmlentities($row['nombre']) . "</td>
                          </tr>
                          <tr>
                                <th class='th-info' width='20%'>Paciente</th>
                          <td colspan='1' class='td-blue' width='35%'>" . htmlentities($row['nombrepaciente']) . "
                              <input name='txtpaciente[" . $i . "]' id='txtpaciente[" . $i . "]' type='hidden' value='" . htmlentities($row['nombrepaciente']) . "' disabled='disabled' />
                          </td>
                              <th class='th-info'  width='20%'>Expediente</th>
                              <td colspan='1' class='td-blue' width='35%'><h4 style='margin-top: 0px;'>" . htmlentities($row['idnumeroexp']) . "<input type='hidden' id='idhistorial' name='idhistorial' value='".$row['idhistorial']."'/> <input type='hidden' id='referido' name='referido' value='".$row['referido']."'/></h3></td>
                          </tr>
                          <tr>
                              <th class='th-info'>Conocido Por</th>
                              <td colspan='3' class='td-blue'>" . $row['conocidopor'] . "</td>
                          </tr>
                          <tr>
                              <th class='th-info'>Edad</th>
                              <td class='td-blue'>".$row['edad']."</td>
                              <th class='th-info'>Sexo:</th>
                              <td class='td-blue'>" . $row['sexo'] . "</td>
                          </tr>
                          <tr>
                              <th class='th-info'>Peso</th>
                              <td class='td-blue'>" . $row['peso'] . "&nbsp;&nbsp;Kg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                              <th class='th-info'>Talla:</th>
                              <td class='td-blue'>" . $row['talla'] . "&nbsp;&nbsp;cm.</td>
                  		</tr>
                        <tr>
                            <th class='th-info'>Procedencia</th>
                            <td colspan='1' class='td-blue'>" . htmlentities($row['precedencia']) . "
                                <input name='txtprecedencia[" . $i . "]' id='txtprecedencia[" . $i . "]' type='hidden'  value='" . $row['precedencia'] . "'/>
                                <input name='txtidsolicitud[" . $i . "]' id='txtidsolicitud[" . $i . "]' type='hidden' value='" . $arraysolic[$i] . "'/>
                                <input name='txtfecha[" . $i . "]' id='txtfecha[" . $i . "]' type='hidden' value='" . $row['fecha'] . "'/>
                                <input name='fecha_solicitud[" . $i . "]' id='fecha_solicitud' type='hidden' value='" . $row['fecha_solicitud'] . "'/>
                                <input name='suEdad[" . $i . "]'' id='suEdad[" . $i . "]' type='hidden' value='" . $row['fechanacimiento'] . "'/>
                            </td>
                            <th class='th-info'>Origen</th>
                            <td class='td-blue' colspan='1'>" . $row['origen'] . "</td>
                        </tr>
                        <tr>
                            <th class='th-info'>M&eacute;dico</th>
                            <td colspan='3' class='td-blue'>" . htmlentities($row['nombremedico']) . "</td>
                        </tr>
                        <tr>
                            <th class='th-info'>Diagnostico </th>
                            <td colspan='3' class='td-blue'>" . htmlentities($row['sct_name_es']) . "</td>
                        </tr>
                  </table>





                  <hr>
                  <strong><i class='fa fa-file-text round-icon bg-blue'></i><font color='#0073B7'> Detalle Solicitud</font></strong><br/><br/>";
                  echo "<div class='table-responsive' style='width: 100%;' style='align:center;'>
                     <table border = 0 align='center' class='table table-hover table-bordered table-condensed table-white' cellspacing='0' style='width:90%'><thead>
          		        <tr>
                              <td colspan='8' align='center' class='CobaltFieldCaptionTD'>ESTUDIOS SOLICITADOS <a href='http://siaps.localhost/app_dev.php/laboratorio/solicitudexterna/send?id=12' target='_blank'>soap</a></td>
          		        </tr>
          		        <tr class='prueba'>
                              <th>Código Prueba</th>
                              <th>Cód. Area</th>
                              <th> Examen </th>
                              <th> Indicaciones </th>
                              <th >Fecha Toma Mx.<br/><input type='text' placeholder='aaaa-mm-dd HH:MM' class='datepicker form-control height' title='Seleccione la Fecha de toma de muestra igual para actualizar la de todas las pruebas.' id='fgentomamxgen'  name='fgentomamx' style='width:150px' value='" . $fecha . "' onchange= \"valfechasolicita(this.value, 'fgentomamxgen'), updatealldates(), changefechatomamx()\"  ></th>
                              <th>A Realizar</th>
                              <th> Validar Muestra</th>
                              <th id='colnewdate_' class='hide_me newdate'>Nueva Cita</th>";
//echo "solicitud ".$row['idsolicitudestudio'];
                    if ($refext=='t')
                              echo "<th > Lugar de Realización</th>";
          		        echo "</tr></thead><tbody>";
                                
                                
                  $detalle = $objdatos->BuscarDetalleSolicitud($idexpediente, $fechacita, $row['idsolicitudestudio'], $idEstablecimiento);
                  $k=1;
                  while ($rows = pg_fetch_array($detalle)) {
                    echo "<tr id='rowdetalle_".$k."'>
                              <td>" . $rows['idestandar'] . " </td>
                              <td>" . $rows['idarea'] . " </td>
                              <td style='width:300px'>" . htmlentities($rows['nombreexamen']) . "</td>";
                      if (!empty($rows['indicacion'])) {
                          echo "<td>" . htmlentities($rows['indicacion']) . "</td>";
                      } else
                          echo "<td>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                          $fecha= date('Y-m-d H:i');
                          $iddetalle=$rows['iddetalle'];
                          $i_idexamen=$rows['i_idexamen'];

                      echo " <td style='width:150px'><input type='text' class='datepicker form-control height'  id='f_tomamuestra_".$k."' name='f_tomamuestra_'  value='". date('Y-m-d H:i')."' onchange=\"valfechasolicita(this.value, 'f_tomamuestra_".$k."'); changenewdate('".$k."')\" style='width:150px' />"
                              . "<input type='hidden' id='iddetalle_".$k."' name='iddetalle_' value='".$iddetalle."'/>"
                              . "<input type='hidden' id='i_idexamen_".$k."' name='i_idexamen_' value='".$i_idexamen."'/>"
                              . "<input type='hidden' id='hdn_numexOrd".$k."' name='hdn_numexOrd' value='".$k."'/>"
                              . "</td>";
                      //***************** bandera ************************
                      if (($rows['idexamen'] == 'COA001')or ( $rows['idexamen'] == 'COA002') or ( $rows['idexamen'] == 'COA016')) {
                          $ban = 1;
                      }
                      //***Buscar las formas de realizar este examen
                      $formas = $objdatos->buscarrealizar($i_idexamen);
                      $cantformas = pg_num_rows($formas);
                      if ($cantformas>=1){
                          echo '<td style="width:150px"><select id="arealizar_'.$k.'" name="arealizar_"  class="form-control height" style="width:150px">';
                          if ($cantformas>1)
                          echo '<option value="0" selected>Seleccione una opción..</option>';
                      }
                      else{
                          if ($cantformas==0){
                              echo '<td style="width:150px" class="bg-danger">';
                              echo '<input type="hidden" id="arealizar_'.$k.'" name="arealizar_" value="X"/> ';
                              echo 'No puede continuar, debe de configurar primero este examen..';
                              $configuracion=1;
                          }
                      }
                      while ($fr = @pg_fetch_array($formas)){
                          //echo '<option value="'.$fr["id_suministrante"].'">'. $fr["suministrante"].'</option>';
                        /*  if ($hl7=='f' && $fr['id_tipo_conexion']==2){
                              echo '<p>Debe habilitar la opción de permitir el proceso Conexión con Equipos Automatizados HL7 </p>'
                          }*/
                          echo '<option value = "'.$fr["id_suministrante"].'">'.$fr["suministrante"].'</option>';
                      }
                      echo '</select></td>';
                      //*** Fin Forma de realizar este examen
                      echo '<td style="width:200px"><div id="divopcionvalidar_'.$k.'"  style="display:block; width:100%">';
                      echo '<input type="hidden" id="idk_'.$k.'" name="idk_'.$k.'" value="'.$k.'" />';

                      echo '<select id="validarmuestra_'.$k.'" name="validarmuestra_" onchange="OpcionRechazo(this.value, '.$k.')" class="form-control height" style="width:200px">';
                     // echo '<option value="0">Validada</option>';
                      $rechazo=$objdatos->opcionrechazo();
                      while ($rec=@pg_fetch_array($rechazo)){
                         echo '<option value="'.$rec["id"].'">'.$rec["estado"].'</option>';

                      }
                      echo '</select>';
                      echo '</div>'
                      . '<div id="divopcionrechazo_'.$k.'" style="width:100%;display:none"></div>'
                              . '</td>';
                      echo '<td  id="colnewdate_" class="hide_me newdate"  style="width:100px"> <div id="divnewdate_'.$k.'" style="display:none"></div></td>';


                      if ($refext=='t'){

                          if ($rows['id_area']!=14){
                              echo "<td class='th-info' style='text-align:left !important;'>".$nestab."</td>";
                          }
                          else{
                         //     echo "<td>";
                              $ber=$objdatos->BuscarEstabRealiza($i_idexamen);
                              if (pg_num_rows($ber)>=1){
                                  echo '<td><select id="estabarealiza'.$k.'" name="estabarealiza_" class="form-control height" style="width:300px" onchange="abrirmodal2('.$k.');"  data-toggle="modal" data-target="#myModal2">';
                                        echo '<option value="0" selected>Seleccione una opción</option>';
                                  while ($rows2= pg_fetch_array($ber)){
                                     echo '<option value="'.$rows2["id_establecimiento"].'">'.$rows2["nombre"].'</option>';
                                  }
                              }
                              else {
                                  echo "<td class='bg-danger'> Se debe de hacer la respectiva configuración del examen referido antes de continuar";
                              }
                              echo "</td>";

                          }
                      }
                      echo "</tr>";

                      $k++;
                  }// while detalle
          // <input type='text' class='datepicker' name="fecha_realizacion" id="fecha_realizacion" size="60"  placeholder="aaaa-mm-dd" onchange="valfechasolicita(this, 'fecha_realizacion')"/>
          //          <input type='text' class='datepicker' name='fecha_reporte' id='fecha_reporte' size='15'  value='".date('Y-m-d H:i')."' onchange=\'valfechasolicita(this, 'fecha_reporte')\'/><br>           <input type='button' name='btnImprimir[" . $i . "]' id='btnImprimir[" . $i . "]' value='Imprimir Vi&ntilde;etas' onClick='ImprimirExamenes(" . $i . ");'/>   <input type='button' name='btnImpSolicitud[" . $i . "]' id='btnImpSolicitud[" . $i . "]' value='Imprimir Solicitud' onClick='ImprimirSolicitud(" . $i . ");'/>

                  echo "</tbody></table></div>";
                  echo "<input type='hidden' id='configurado' name='configurado' value='".$configuracion."'/>";

            echo "<hr>
            <table align='center' class='table table-condensed table-white' style='width:45%;border:0'><br>
                    <tr>
                        <td align='center'>";
                        if ($configuracion==0){
            echo "
                            <button type='button'  name='btnActualizar[" . $i . "]' id='btnActualizar[" . $i . "]' align='right' style='text-align: right' class='btn btn-primary' onclick='AsignarNumeroMuestra(" . $i . ");'><span class='glyphicon glyphicon-check'></span>&nbsp;Procesar Solicitud </button>&nbsp;
                            <button type='button'  name='btnRechazar[" . $i . "]' id='btnRechazar[" . $i . "]' align='right' style='text-align: right' class='btn btn-primary' data-toggle='modal' data-target='#myModal' onclick='setfecharechazo();fecharechazosol();'><span class='glyphicon glyphicon-remove'></span>&nbsp;Rechazar Solicitud </button>&nbsp;";
                        }
                else{
                    echo '<p class="bg-danger">No se puede procesar esta solicitud debido a que requiere revisión de las configuraciones de todos los examenes solicitados....</p>';
                }
            echo '<button type="button"  name="btnOtra" id="btnOtra" align="right" style="text-align: right" class="btn btn-primary" onclick="window.location.replace(\'Proc_RecepcionSolicitud.php\');"><span class="glyphicon glyphicon-refresh"></span>&nbsp;Ingresar otra solicitud </button>';
    //        echo
    //                        <input type='hidden' name='oculto' id='text' value='" . $i . "' />
    //                        <button type='button'  name='btnOtra' id='btnOtra' align='right' style='text-align: right' class='btn btn-primary' onclick='window.location.replace(\'Proc_RecepcionSolicitud.php\);'><span class='glyphicon glyphicon-refresh'></span>&nbsp;Ingresar otra solicitud </button>
               echo "</td>
                        </tr>
                        <tr>
                        <td id='divoculto[" . $i . "]' style='display:none'><center>

                             <button type='button'  name='btnImprimir[" . $i . "]' id='btnImprimir[" . $i . "]' align='right' style='text-align: right' class='btn btn-primary' onclick='ImprimirExamenes(" . $i . ");'><span class='glyphicon glyphicon-barcode'></span>&nbsp;Imprimir Viñetas </button>
                              <button type='button'  name='btnImpSolicitud[" . $i . "]' id='btnImpSolicitud[" . $i . "]' align='right' style='text-align: right' class='btn btn-primary' onclick='ImprimirSolicitud(" . $i . ");'><span class='glyphicon glyphicon-list-alt'></span>&nbsp;Imprimir Solicitud </button>
                             </center>
                        </td></tr></table>
              </div>";
              
    }//del while
    echo "<input type='hidden' name='topei' id='topei' value='" . $NroRegistros . "' /> ";
        echo "</div>";//fin div class box box-primary
    echo "</div>";//fin div class col-md-8
echo "</div>";//fin div class row
}// for
?>

<!--    <table align="center">
        <tr>-->
<!--            <td>
               <button type='button'  name='btnOtra' id='btnOtra' align='right' style='text-align: right' class='btn btn-primary' onclick='window.location.replace("Proc_RecepcionSolicitud.php");'><span class='glyphicon glyphicon-refresh'></span>&nbsp;Ingresar otra solicitud </button></td>
 <button type="button"  name="btnOtra" id="btnOtra" align="right" style="text-align: right" class="btn btn-primary" onclick="window.location.replace('Proc_RecepcionSolicitud.php');"><span class="glyphicon glyphicon-refresh"></span>&nbsp;Ingresar otra solicitud </button></td>

            </td>-->
<!--               <input type="button" name="btnOtra" id="btnOtra" value="Ingresar otra solicitud" onClick="window.location.replace('Proc_RecepcionSolicitud.php')"></td>-->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Rechazar Solicitud</h4>
      </div>
      <div class="modal-body">
          *<label>Fecha de rechazo:</label>
         <div class="input-group">
            <span class="input-group-addon" id="basic-addon1" style="width:inherit">
                <i class="fa fa-calendar"></i>
            </span>
              <input type="text" class="date form-control" id="fecharechazo" name="fecharechazo" style="width:105px" value="<?php echo date("Y-m-d");?>" aria-describedby="basic-addon1" onchange="valdatesolicita(this.value, 'fecharechazo'); fecharechazosol();">
         </div>

         <br>
         *<label>Tipo de Rechazo:</label>
         <select style="width: 90%" class="form-control height" id="cmbrechazoest" name="cmbrechazoest" onclick="rechazosolicitud(this.value)">
            <option value="0">Seleccione una opción</option>
            <?php
            $cmbrechazo1=$objdatos->opcionrechazo();
            while ($cmb1=@pg_fetch_array($cmbrechazo1)){
               if ($cmb1['id']!=1)
               echo '<option value='.$cmb1["id"].'>'.$cmb1["estado"].'</option>';
            }
            ?>
         </select>
         <br>

         <label>*Elija razón de rechazo de solicitud:</label>
         <div id="newreasonsol" style="display: block; width: 100%" >
             <select style="width: 90%" class="form-control height" id="cmbrechazosol" name="cmbrechazosol">
                <option value="0">Seleccione una opción</option>
                <?php
                /*$cmbrechazo=$objdatos->obteneropcionesrechazo();
                while ($cmb=@pg_fetch_array($cmbrechazo)){

                   echo '<option value='.$cmb["id"].'>'.$cmb["posible_observacion"].'</option>';
               }*/
                ?>
             </select>
         </div>

         <br>
         <div id="newdatesol" style="display: none; width: 100%" >
            <label>*Fecha de nueva cita:</label>
            <div class="input-group">
            <span class="input-group-addon" id="basic-addon1" style="width:inherit">
                <i class="fa fa-calendar"></i>
            </span>
              <input type="text" class="date form-control" id="fechanewcitasol" name="fechanewcitasol" style="width:105px">
         </div>

            <br>
         </div>
         <label>Observación:</label>
         <textarea cols="35" rows="5" style="width: 90%" id="observacionrechazo" name="observacionrechazo"></textarea>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cancelarechazo()">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="cancelarsolicitud()">Rechazar Solicitud</button>
      </div>
    </div>
  </div>
</div>

</form>
