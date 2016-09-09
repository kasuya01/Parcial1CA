<?php
session_start();
include_once("ClaseSolicitud.php"); //Agregamos el Archivo con las clases y funciones a utilizar
$Paciente = new Paciente;
$Laboratorio = new SolicitudLaboratorio;
$ROOT_PATH = $_SESSION['ROOT_PATH'];
//$_SESSION["conectar"]=$conectar;
$conectar = 1;
$IdHistorialClinico = $_GET["IdHistorialClinico"];
$IdCitaServApoyo = $_GET["IdCitaServApoyo"];
$band = isset($_GET["band"]) ? $_GET["band"] : 0;

// echo '<br/>idcitaservapoyo: '.$IdCitaServApoyo;
$IdEstablecimiento = $_SESSION["IdEstablecimiento"]; //Elegido en el combo
$IdNumeroExp = $_SESSION["IdNumeroExp"];
$idexpediente = $_SESSION["idexpediente"];
$iduser = $_SESSION["IdUsuarioReg"];
$LugardeAtencion = $_SESSION["lugar"]; //Lugar de donde proviene
//$NombrePaciente=$Paciente->RecuperarNombre($IdEstablecimiento,$IdNumeroExp);
$NombrePa = $Paciente->RecuperarNombre($IdEstablecimiento, $idexpediente,
        $IdNumeroExp);
$rowpa = pg_fetch_array($NombrePa);
$NombrePaciente = $rowpa['nombre'];
$sexo = $rowpa['id_sexo'];
//echo 'IdHistorialClinico:'.$IdHistorialClinico .' IdCitaServApoyo:  '.$IdCitaServApoyo.'  IdNumeroExp: '.$IdNumeroExp. '</br>';
//exit;
// recuperar el IdSolicituEstudio
$IdSolicitudEstudio = $Paciente->RecuperarIdSolicituEstudio($idexpediente,
        $IdHistorialClinico, $IdEstablecimiento);

//$sol=$Paciente->verificarestado($IdSolicitudEstudio);
/* HACER AKI EL IF DE VERIFICACION DE IdCitaServApoyo Y ASI HACER EL INSERT O EL UPDATE */
// echo 'idsol' .$IdSolicitudEstudio;
// echo '$IdCitaServApoyo'.$IdCitaServApoyo.'<br/>';
if ($IdCitaServApoyo == "") {
   $IdCitaServApoyo = $Paciente->IdCitaServApoyoInsertUpdate($IdSolicitudEstudio,
           $iduser, $IdNumeroExp, $LugardeAtencion, $IdCitaServApoyo, 1);
} else {
   $IdCitaServApoyo = $Paciente->IdCitaServApoyoInsertUpdate($IdSolicitudEstudio,
           $iduser, $IdNumeroExp, $LugardeAtencion, $IdCitaServApoyo, 0);
}
?>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Examenes Solicitados a Laboratorio</title>
      <link rel="stylesheet" type="text/css" href="./Estilo.css"></link>
      <?php include_once $ROOT_PATH . "/public/css.php"; ?>
      <?php include_once $ROOT_PATH . "/public/js.php"; ?>
      <script languaje="javascript">
         var band=<?php echo $band; ?>;
         var id_solicitud=<?php echo $IdSolicitudEstudio;?>;
      </script>
      <script language="javascript" src="./ajax.js"></script>
   </head>

<!--<body onload="MostrarDetalle(<?php //echo $IdHistorialClinico; ?>);">-->
   <body >
      <br>
      <div class='panel panel-primary'>
         <table  class='table table-bordered table-condensed table-white no-v-border'  >
            <thead><tr><td colspan='2' style='background-color: #428bca; color:#ffffff; text-align:left' >
                     <h4>Datos Paciente</h4>
                  </td></tr></thead><tbody>
               <tr>
                  <td style="width: 10%"><b>Expediente:</b> </td>
                  <td style="width: 90%; alignment-adjust: left"><?php echo $IdNumeroExp; ?>
                        <input type="hidden" id="id_solicitudest" name="id_solicitudest" value="<?php echo $IdSolicitudEstudio ?>"
                  </td></tr>
               <tr>
                  <td><font color='black'><b>Paciente:</b></td>
                  <td><font color='black'><?php echo $NombrePaciente; ?></td></tr>
            </tbody></table>
      </div>
      <br>
      <div id='RespuestaAjax'>
         <?php
         $result = $Laboratorio->DetalleEstudiosLaboratorio($conectar,
                 $IdHistorialClinico, $IdEstablecimiento);
         $Bandera = 'S';
         $fecha = date('Y-m-d H:i');
         if ($result != false) {
            echo " <form  method='post' name='Editar' id='Editar'>
                 <div class='panel panel-primary'>
                    <table  class='table table-hover table-bordered table-condensed table-white no-v-border'>
                    <thead><tr><td colspan='8' style='background-color: #428bca; color:#ffffff; text-align:left' ><h4>Examenes Solicitados a Laboratorio</h4> </td></tr>
                    <tr class='info'><td colspan='6' align='right' style=' vertical-align: middle'><h4><b>Solicitud Urgente:</b></h4></td><td style='vertical-align: middle'><input type='checkbox' id='tiposolgen' name='tiposolgen' data-switch-enabled='true' class='form height' onclick='SolicitudUrgente(" . $IdHistorialClinico . ", " . $IdSolicitudEstudio . ");'></td></tr>";


            $Ejecutar2 = $Laboratorio->impresionessoli($IdHistorialClinico,
                    $IdEstablecimiento);
            $Respuesta2 = pg_fetch_array($Ejecutar2);
            if ($Respuesta2["impresiones"] == 1) {
               $check = "<input id='Imprimir' data-switch-enabled='true' type='checkbox'onclick='ImprimirResultados(" . $IdHistorialClinico . ", " . $IdSolicitudEstudio . ");' checked='checked'>";
            } else {
               $check = "<input id='Imprimir' data-switch-enabled='true' type='checkbox'onclick='ImprimirResultados(" . $IdHistorialClinico . ", " . $IdSolicitudEstudio . ");'>";
            }
            echo "<tr class='info'><td colspan='6' align='right' style=' vertical-align: middle'>  <h4><b>Resultado de Examenes Impresos [Pre-Operatorios]</b></h4> </td><td>".$check."</td></tr>";

            echo "<tr><th style='vertical-align: middle !important'>C&oacute;digo</th>
                        <th style='vertical-align: middle'>Nombre Examen</th>
                        <th style='vertical-align: middle'>Tipo Muestra</th>
                        <th style='vertical-align: middle'>Origen Muestra</th>
                        <th style='vertical-align: middle'>Indicaci&oacute;n</th>
                        <th title='Seleccione la Fecha de toma de muestra igual para actualizar la de todas las pruebas.'>F. Toma Mx.<br/><input type='text' class='datepicker' id='fgentomamxgen' class='form-control height' name='fgentomamx'value='" . $fecha . "' onchange= \"valfechasolicita(this.value, 'fgentomamxgen".$k."'), updatealldates()\"  ></th>
                        <th style='vertical-align: middle'>Borrar</th>
                    </tr></thead><tbody>";
            $i = 0;
            $t = 0;
            $s = 0;

            while ($Respuesta = pg_fetch_array($result)) {
               echo "<tr><td>" . $Respuesta["codigo_examen"] . "</td>";
               echo "<td>" . ($Respuesta["nombre_examen"]) . "</td>";
               echo "<td><font color='#084B8A'><b>" . ($Respuesta["tipomuestra"]) . "</b></font></td>";
               echo "<td><font color='#084B8A'><b>" . ($Respuesta["origenmuestra"]) . "</b></font></td>";
               echo "<td><input type='text' id='Indicacion" . $Respuesta['idexamen'] . "' name='Indicacion" . $Respuesta['idexamen'] . "'value='" . ($Respuesta["indicacion"]) . "'>";
               if ($Respuesta['f_tomamuestra']!=''){
                 $fecha= $Respuesta['f_tomamuestra'];
               }
               echo "<td><input type='text' class='datepicker' id='ftomamx" . $Respuesta['idexamen'] . "' name='ftomamx" . $Respuesta['idexamen'] . "'value='" . $fecha . "' class='form-control height' onchange=\"valfechasolicita(this.value, 'ftomamx".$Respuesta['idexamen']."')\"></td>";
//              if ($Respuesta['urgente']==1){
//               echo "<td><input type='checkbox' id='tiposol" . $Respuesta['idexamen'] . "' name='tiposol" . $Respuesta['idexamen'] . "' data-switch-enabled='true' ></td>";
//              }
//              else{
//                  echo "<td><div style='display:none'><input type='checkbox' id='tiposol" . $Respuesta['idexamen'] . "' name='tiposol" . $Respuesta['idexamen'] . "' data-switch-enabled='true' ><div></td>";
//              }
               echo "<input type='hidden' name='IdDetalle" . $Respuesta['idexamen'] . "' Id='IdDetalle" . $Respuesta['idexamen'] . "'value='" . $Respuesta['iddetallesolicitud'] . "'></td>";
               echo "<td><input type='checkbox' id='ExamenesLab' name='ExamenesLab' value='" . $Respuesta['idexamen'] . "'/></td>";
               /*  $Resp       uesta3=$Laboratorio->RecuperarData($IdHistorialClinico,$IdEstablecimiento,$Bandera);
                 if ($Respuesta3!=NULL || $Respuesta3!=''){
                 $buscadetsol=$Laboratorio->buscardetsol($Respuesta3);
                 while ($Respuesta4=  pg_fetch_array($buscadetsol)){
                 if($Respuesta4["urgente"]==1 && $Respuesta['iddetallesolicitud']==$Respuesta4['iddetallesolicitud']){
                 echo "<td><input type='checkbox' id='Detalle".$Respuesta['idexamen']."' checked='checked' value='".$Respuesta4['iddetallesolicitud']."'/></td> ";
                 $t++;
                 }
                 }//fin while respuesta4
                 if($Respuesta["urgente"]==1 && $Respuesta3 != $Respuesta['idsolicitudestudio']){
                 echo "<td><input type='checkbox' id='Detalle".$Respuesta['idexamen']."' value='".$Respuesta['iddetallesolicitud']."'/></td>";
                 $t++;
                 } //if fin respuestaurgente=1
                 }//fin if respuesta3

                 if($Respuesta3 == NULL OR $Respuesta3 ==''){
                 if($Respuesta["urgente"]==1){
                 echo "<td><input type='checkbox' id='Detalle".$Respuesta['idexamen']."' value='".$Respuesta['iddetallesolicitud']."'/></td>";
                 $t++;
                 }//fin if respuesta urgente=1
                 }//fin if Respuesta3=null
                */
               echo "</tr>";
               $i++;
               //aqui me quede
            }//fin while respuesta

//
//            $Ejecutar2 = $Laboratorio->impresionessoli($IdHistorialClinico,
//                    $IdEstablecimiento);
//            $Respuesta2 = pg_fetch_array($Ejecutar2);
//            if ($Respuesta2["impresiones"] == 1) {
//               $check = "<input id='Imprimir' type='checkbox'onclick='ImprimirResultados(" . $IdHistorialClinico . ", " . $IdSolicitudEstudio . ");' checked='checked'>";
//            } else {
//               $check = "<input id='Imprimir' type='checkbox'onclick='ImprimirResultados(" . $IdHistorialClinico . ", " . $IdSolicitudEstudio . ");'>";
//            }
            //echo "<tr><td colspan='6' align='right'><br><h3><b>".$check."Resultado de Examenes Impresos [Pre-Operatorios]</b></h3> </td></tr></table><br>";
            echo "<tr><td colspan='6'><br><b>Total de Examenes Solicitados:" . $i . "</b> </td></tr>"
            . ""
            . "</tbody></table></div><br>";

            echo "<table border=0 style='width:100%'>
                            <tr><td align='right'>
                            <button type='button' class='btn btn-primary' id='Enviar' onclick='GuardarCambios(0); '>
                                <span class='glyphicon glyphicon-floppy-disk'></span>
                                Guardar Cambios
                             </button>
                            <button type='button' class='btn btn-primary' id='Agregar' onclick='AgregarExamen(); '>
                                <span class='glyphicon glyphicon-plus'></span>
                                Agregar Examen
                             </button>
                            <button type='button' class='btn btn-primary' id='Terminar' onclick='GuardarCambios($IdSolicitudEstudio); '>
                                <span class='glyphicon glyphicon-ok'></span>
                                Terminar Solitud
                             </button>
                             </td></tr></table>
                            <input type='hidden' name='total' id='total' value='" . $i . "'>
                            <input type='hidden' id='totalurgente' value='" . $t . "'>
                            <input type='hidden' id='IdHistorialClinico' name= 'IdHistorialClinico' value='" . $IdHistorialClinico . "'>
                            </form>";
         }   //fin result=false
         ?>
      </div>

      <div id='Cambios'></div>
      <div id='Cambios'></div>
      <form name="Parametros" method="post">
         <input type="hidden" id="IdHistorialClinico" value="<?php echo $_GET["IdHistorialClinico"]; ?>">
         <input type="hidden" id="IdNumeroExp"		 value="<?php echo $_SESSION["IdNumeroExp"]; ?>">
         <input type="hidden" id="FechaSolicitud" 	 value="<?php echo $_SESSION["Fecha"]; ?>">
         <input type="hidden" id="IdUsuarioReg" 		 value="<?php echo $_SESSION["IdUsuarioReg"]; ?>">
         <input type="hidden" id="FechaHoraReg" 		 value="<?php echo $_SESSION["FechaHoraReg"]; ?>">
         <input type="hidden" id="idexpediente" 		 value="<?php echo $_SESSION["idexpediente"]; ?>">
         <input type="hidden" id="FechaConsulta" 		 value="<?php echo $_SESSION["FechaConsulta"]; ?>">
         <input type="hidden" id="IdCitaServApoyo" 		 value="<?php echo $IdCitaServApoyo; ?>">
         <input type="hidden" id="lugar" 		 value="<?php echo $LugardeAtencion; ?>">
         <input type="hidden" id="IdEstablecimiento" 		 value="<?php echo $IdEstablecimiento; ?>">
         <input type="hidden" id="Sexo" 		 value="<?php echo $sexo; ?>">

      </form>
   </body>
</html>
