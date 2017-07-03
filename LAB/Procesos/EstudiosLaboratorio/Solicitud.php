<?php
include_once("../../../Conexion/ConexionBD.php");
include_once("ClaseSolicitud.php"); //Agregamos el Archivo con las clases y funciones a utilizar

@session_start();
$ROOT_PATH = $_SESSION['ROOT_PATH'];
// Creamos un objeto Conexion, Paciente, Laboratorio
$Conexion = new ConexionBD;
//Abrimos la Conexion
$Conectar = $Conexion->conectar();
$IdNumeroExp = $_GET["IdNumeroExp"];
$IdEstablecimiento = $_GET["IdEstablecimiento"]; //IdEstablecimiento solicitante
$lugar = $_GET["lugar"]; //IdEstablecimiento local
//echo 'Idestab:'.$IdEstablecimiento.'<br/>Lugar:'.$lugar.'<br\>';
//$IdSubServicio = $_GET["IdSubServicio"];
$IdSubServicio = isset($_GET['IdSubServicio']) ? $_GET['IdSubServicio'] : null;
//$IdEmpleado = $_GET["IdEmpleado"];
$IdEmpleado = isset($_GET['IdEmpleado']) ? $_GET['IdEmpleado'] : null;
$IdUsuarioReg = $_SESSION['Correlativo'];
$FechaConsulta = $_GET["FechaConsulta"];
$FechaRecepcion = $_GET["FechaRecepcion"];
$IdCitaServApoyo = $_GET["IdCitaServApoyo"];
$sexo = $_GET["Sexo"];
$idexpediente = $_GET["idexpediente"];
//echo '<br\>.Idexpediente: '.$idexpediente.' IdNumeroExp:'.$IdNumeroExp. '  numhistorial: '.$_GET["IdHistorialClinico"].'  issethist: '.isset( $_GET["IdHistorialClinico"]);
//IdHistorialClinico = $_GET["IdHistorialClinico"];
$IdHistorialClinico = isset($_GET['IdHistorialClinico']) ? $_GET['IdHistorialClinico']: null;
$prioridad= isset($_GET['prioridad']) ? $_GET['prioridad']: 2;
$addexam= isset($_GET['addexam']) ? $_GET['addexam']: 0;
$FechaSolicitud = $FechaConsulta;


/* PARA OBTENER LA IP REAL DE LA PC QUE SE CONECTA */
if (!empty($_SERVER['HTTP_CLIENT_IP']))
   $ippc = $_SERVER['HTTP_CLIENT_IP'];
else
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
   $ippc = $_SERVER['HTTP_X_FORWARDED_FOR'];
else
   $ippc = $_SERVER['REMOTE_ADDR'];


/* * *************************************************************** */
$Historial = new CrearHistorialClinico;
if (!isset($_GET["IdHistorialClinico"]) || $IdHistorialClinico == '') {
    //echo 'aqui no  existe: ';
   $IdHistorialClinico = $Historial->HistorialClinico($IdNumeroExp,
           $IdEstablecimiento, $IdSubServicio, $IdEmpleado, $FechaConsulta,
           $_SESSION['Correlativo'], $ippc, $idexpediente, $lugar);
}
$_SESSION["IdNumeroExp"] = $IdNumeroExp;
$_SESSION["idexpediente"] = $idexpediente;
$_SESSION["IdHistorialClinico"] = $IdHistorialClinico;
$_SESSION["Fecha"] = $FechaSolicitud;
$_SESSION["FechaConsulta"] = $FechaConsulta;
$_SESSION["FechaRecepcion"] = $FechaRecepcion;
$_SESSION["IdUsuarioReg"] = $IdUsuarioReg;
$_SESSION["IdEstablecimiento"] = $IdEstablecimiento;
$_SESSION["lugar"] = $lugar;
//echo 'Ĺugar'.$lugar.'  idhistclin'.$IdHistorialClinico.'<br/>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Solicitud de Estudios Para Laboratorio Clínico</title>
      <link rel="stylesheet" type="text/css" href="./Estilo.css">
<?php include_once $ROOT_PATH . "/public/css.php"; ?>
<?php include_once $ROOT_PATH . "/public/js.php"; ?>
         <script languaje="javascript">
            var band = 0;
            jQuery(document).ready(function ($) {
                $('#tabla_examenes').DataTable();
               /*Habilitar todos los registros*/
               $('button[id^="select_all"]').on("click", function (e) {
                  $('input[id^="Examenes"]').each(function () {
                     this.checked = true;
                     valors=$(this).val()
                     data=($(this).attr('id')).split('Examenes');
              // console.log(data[1]+' --- '+val.id_conf_examen_estab);
              MostrarLista2(valors, data[1]);
                  });
                  $('button[id^="btnperfil"]').addClass("disabled");
               });

               /*Deshabilitar todos los registros*/
               $('button[id^="unselect_all"]').on("click", function (e) {
                  $('input[id^="Examenes"]').each(function () {
                     this.checked = false;
                     id=$(this).val()
                     $('#'+id).html('');
                     $('#O'+id).html('');
                  });
                  $('button[id^="btnperfil"]').removeClass("disabled");
               });




            });
            //  var id_solicitud=null;
         </script>
         <script language="javascript" src="./ajax.js"></script>
   </head>


   <body>

      <div class="panel panel-primary">
         <table  cellspacing="1" cellpadding="2" border="1" align="justify" width="100%"  class="table table-bordered table-condensed table-white no-v-border" id="tabla_examenes">
            <thead><tr>

<!--<td colspan='7'color='white' nowrap><strong><font color="white">P R U E B A S &nbsp;&nbsp;&nbsp; I N D I V I D U A L E S  </font></strong>-->
                  <td colspan='6'color='white' style="background-color: #428bca" nowrap>
                     <img src="../../../Imagenes/paisanito_white.png" class="pull-left" style="padding: 5px; height: 80px;" />
                     <strong><font color="white"> <div class="panel-heading">
                              <center>
                                 <h2><strong>SOLICITUD A LABORATORIO CLINICO</strong></h2>
                                 P R U E B A S &nbsp;&nbsp;&nbsp; I N D I V I D U A L E S
                              </center></div>     </font></strong>
                  </td>
               </tr>

            </thead>

            <?php
            $perf = $Historial->buscarperfil($sexo);
            $j = 0;

            $rnum = pg_num_rows($perf);
            if ($rnum > 0) {
               echo "<thead><tr><th colspan='5' valign='middle' style='vertical-align: middle;' ><h4><b>
                        <span class='glyphicon glyphicon-list-alt'></span>  &nbsp;&nbsp;PERFILES </b></h4></th>
                         <td>
                           <button type='button' id='select_all_1' class='btn btn-link'>
                              <span class='glyphicon glyphicon-check' style='font-size: 10px;'>Seleccionar Todos</span>
                           </button>
                           <button type='button' id='unselect_all_1' class='btn btn-link'>
                              <span class='glyphicon glyphicon-unchecked' style='font-size: 10px;'>Deseleccionar Todos</span>
                           </button>
                        </td>
                       </tr>
                     </thead><tbody>";
            }


            echo '<tr><td colspan="6"><div class="container-fluid"><div class="row"> ';
            while ($rowp = @pg_fetch_array($perf)) {

               echo "<div class='col-md-2'><button type='button' class='btn btn-info btn-lg btn-block bg-info' onclick='seleccionarpruebas(this.value);' value ='" . $rowp['id'] . "' style='background: #d9edf7; border-color:#bce8f1; color:#31708f!important' id='btnperfil" . $rowp['id'] . "'><input type='hidden' id='idperfil" . $j . "' name='idperfil' value ='" . $rowp['id'] . "' >" . $rowp['nombre'] . "</button></div>";

               $j++;
            }

            echo '</div></div></td></tr>';
            echo '</tbody>';

            echo "<tr class='info' style='display: none'>
            <td colspan='6'>
                <div class='row' >
                    <div class='col-md-11' style='text-align:right; vertical-align: top;'>
                    <h4><b>Solicitud Urgente: </b></h4></div>
                    <div class='col-md-1' style='vertical-align: middle;'>";
            if ($prioridad==1){
                echo "<input type='checkbox' id='tiposolgen' name='tiposolgen' checked data-switch-enabled='true' class='form height'>";
            }
            else {
                echo "<input type='checkbox' id='tiposolgen' name='tiposolgen' data-switch-enabled='true' class='form height'>";
            }

            echo "</div>
                </div>
            </td>

            </tr>";

            echo "<tr><td colspan='6' align='right' >

            <span class='glyphicon glyphicon-chevron-right'></span>
            <span class='glyphicon glyphicon-chevron-right'></span>
            <span class='glyphicon glyphicon-chevron-right'></span>
            <span class='glyphicon glyphicon-chevron-right'></span>
            <button type='button' class='btn btn-primary' id='Enviar' onclick='GuardarSolicitud(); '><span class='glyphicon glyphicon-share-alt'></span> Enviar Solicitud</button>
            <button type='button' class='btn btn-primary'  onclick='window.close();'><span class='glyphicon glyphicon-remove-circle'></span> Cancelar Solicitud</button>";
            if ($addexam==1){
                echo "  <button type='button' class='btn btn-primary'  onclick='ListaExamenes(".$IdHistorialClinico.",".$IdCitaServApoyo.", 0)'><span class='glyphicon glyphicon-repeat'></span> Regresar listado</button>";
            }



          echo "</td></tr>";



//Llamar a funcion para buscar las areas que tiene configurado el establecimiento
            $areas = $Historial->buscarareas($lugar);
            // echo 'num areas: '.pg_num_rows($areas).' areas:'.$areas.'<br/>';
            echo "<form  method='post' name='Solicitud'>";
            $i = 0;

            while ($rowar = @pg_fetch_array($areas)) {

               $examen = $Historial->busca_mnt_area_exa_est($lugar,
                       $rowar['id'], $sexo, $IdHistorialClinico,
                       $IdEstablecimiento);
               if (pg_num_rows($examen) > 0) {
                  echo "<thead><tr><td colspan='6'><b>" . ($rowar['nombrearea']) . "</b></td></tr></thead><tbody>";
                  while ($ResultadoExamenes = pg_fetch_array($examen)) {
                     //Primera Columna

                     echo "<tr>
                                <td style='width:3%'>
                                <div class=''><label>
                                    <input type='checkbox' name='Examenes'  Id='Examenes" . $i . "' value='" . $ResultadoExamenes['idconf'] . "' onclick=\"MostrarLista2(" . $ResultadoExamenes['idconf'] . ",$i)\" /><b>" . $ResultadoExamenes['nombre_examen'] . "</b>
                                </label></div><input type='hidden' id='Nombre" . $ResultadoExamenes['idconf'] . "' value='" . $ResultadoExamenes['nombre_examen'] . "'></td>";

                     echo "<td style='width:26%'><div id='" . $ResultadoExamenes['idconf'] . "'>";
                /*     if ($ResultadoExamenes['urgente']==1){
                         echo '<div class="onoffswitch" style="width:100px"><strong><font color="#428bca">Urgente :&nbsp;&nbsp;&nbsp;&nbsp;  </font></strong>
                                                    <input type="checkbox" id="tiposolgen'.$ResultadoExamenes['idconf'].'" name="tiposolgen" data-switch-enabled="true" class="form height">
                                                </div>';
                     }
                     else {

                     }*/
                     echo " </div>";
                     echo "<div id='O" . $ResultadoExamenes['idconf'] . "'></div></td>";
                     $i++;
                     // SEGUNDA COLUMNA
                     if ($ResultadoExamenes = pg_fetch_array($examen)) {
                        echo "<td colspan='2' style='width:23%; vertical-align: top;' >
                        <div  class='checkbox'><label>
                        <input type='checkbox' name='Examenes'  Id='Examenes" . $i . "' value='" . $ResultadoExamenes['idconf'] . "' onclick=\"MostrarLista2(" . $ResultadoExamenes['idconf'] . ",$i)\" /><b>" . $ResultadoExamenes['nombre_examen'] . "</b></label></div><input type='hidden' id='Nombre" . $ResultadoExamenes['idconf'] . "' value='" . $ResultadoExamenes['nombre_examen'] . "'></td>";

                        echo "<td style='width:23%'><div  id='" . $ResultadoExamenes['idconf'] . "'></div>";
                        echo "<div  id='O" . $ResultadoExamenes['idconf'] . "'></div>";
                        echo "</td></tr>";
                        $i++;
                     } else
                        echo "<td colspan='3'></td></tr>";
                  }
                  echo '</tbody>';
               }//fin while examenes
            }//Fin while areas
            echo "</table>
              <input type='hidden' name='IdNumeroExp' Id='IdNumeroExp' value='" . $IdNumeroExp . "'>
              <input type='hidden' name='idexpediente' Id='idexpediente' value='" . $idexpediente . "'>
              <input type='hidden' name='IdHistorialClinico' Id='IdHistorialClinico' value='" . $IdHistorialClinico . "'>
              <input type='hidden' name='FechaSolicitud' Id='FechaSolicitud' value='" . $FechaSolicitud . "'>
              <input type='hidden' name='FechaRecepcion' Id='FechaRecepcion' value='" . $FechaRecepcion . "'>
              <input type='hidden' name='IdUsuarioReg' Id='IdUsuarioReg' value='" . $IdUsuarioReg . "'>
              <input type='hidden' name='IdCitaServApoyo' Id='IdCitaServApoyo' value='" . $IdCitaServApoyo . "'>
              <input type='hidden' name='IdEstablecimiento' Id='IdEstablecimiento' value='" . $IdEstablecimiento . "'>
              <input type='hidden' name='lugar' Id='lugar' value='" . $lugar . "'>
              <input type='hidden' name='TipoSexo' Id='TipoSexo' value='" . $sexo . "'>";

            echo "
              <br>
              <table  class='table table-bordered table-condensed table-white'>
              <tr><td colspan='6'><div id='Resultados'></div></td></tr>
              <tr><td colspan='6' align='right' >

              <span class='glyphicon glyphicon-chevron-right'></span>
              <span class='glyphicon glyphicon-chevron-right'></span>
              <span class='glyphicon glyphicon-chevron-right'></span>
              <span class='glyphicon glyphicon-chevron-right'></span>
              <button type='button' class='btn btn-primary' id='Enviar' onclick='GuardarSolicitud(); '><span class='glyphicon glyphicon-share-alt'></span> Enviar Solicitud</button>
              <button type='button' class='btn btn-primary'  onclick='window.close();'><span class='glyphicon glyphicon-remove-circle'></span> Cancelar Solicitud</button>";
              if ($addexam==1){
                  echo "  <button type='button' class='btn btn-primary'  onclick='ListaExamenes(".$IdHistorialClinico.",".$IdCitaServApoyo.", 0)'><span class='glyphicon glyphicon-repeat'></span> Regresar listado</button>";
              }



            echo "</td></tr></form>";
            ?>

         </table>
      </div>
   </body>
</html>
