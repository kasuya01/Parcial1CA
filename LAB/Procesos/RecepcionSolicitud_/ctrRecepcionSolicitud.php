<?php

session_start();
include_once("Clases.php");
include ("clsRecepcionSolicitud.php");
include_once("../EstudiosLaboratorio/ClaseSolicitud.php");
include_once("../EstudiosLaboratorio/envioSolicitudWS.php");
$usuario = $_SESSION['Correlativo'];
$lugar = $_SESSION['Lugar'];
$area = $_SESSION['Idarea'];
$Clases = new cls_Clases;

//variables POST
$opcion = $_POST['opcion'];
$object = new clsRecepcionSolicitud;
$Paciente = new Paciente;
//include_once $ROOT_PATH."/public/css.php";
//include_once $ROOT_PATH."/public/js.php";
//creando los objetos de las clases
$con = new ConexionBD;
$con2 = new ConexionBDLab;
switch ($opcion) {
   case 1:  //ACTUALIZA ESTADO DE LA SOLICITUD
      $idexpediente = $_POST['idexpediente'];
      $fechacita = $_POST['fechacita'];
      $estado = $_POST['estado'];
      $idsolicitud = $_POST['idsolicitud'];
      $cantidadnum = $_POST['cantidadnum'];
      $idhistorial = $_POST['idhistorial'];
      $referido = $_POST['referido'];
      $return_='';
      $enviohl7= $Paciente->ConsultaEnvioHL7($lugar);

      if ($con->conectar() == true) {
         $query = "UPDATE sec_solicitudestudios "
                 . "SET estado = (SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado = '$estado' AND id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                        WHERE id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB') AND
                        id_establecimiento = $lugar AND
                         id = $idsolicitud";

         $result = @pg_query($query);

         $aux_query = "SELECT COUNT(id) AS numero FROM lab_proceso_establecimiento WHERE id_proceso_laboratorio = 3 and activo=true";
         $aux_result = @pg_query($aux_query);
         $sirecep = pg_fetch_array($aux_result);
         $numero = $sirecep['numero'];
         $hl7conexion=0;
         if ($numero == '0') {
            //if($sirecep[0] === 0) {

            if ($cantidadnum > 0) {
               for ($i = 1; $i <= $cantidadnum; $i++) {
                  $hdniddeatalle_ = $_POST['iddetalle_' . $i];
                  $hdni_idexamen_ = $_POST['i_idexamen_' . $i];
                  $hdnfechatmx_ = $_POST['f_tomamuestra_' . $i];
                  $hdnid_suministrante_ = $_POST['id_suministrante_' . $i];

                  $hdnvalidarmuestra_ = $_POST['validarmuestra_' . $i];
                  //$hdncmbrechazo_ = $_POST['cmbrechazo_'.$i];
                  $hdncmbrechazo_ = isset($_POST['cmbrechazo_' . $i]) ? $_POST['cmbrechazo_' . $i]
                             : null;

                 ///Buscar si el idsuministrante tiene conexion tipo hl7
                 $tipo_conexion="SELECT id_tipo_conexion from lab_suministrante where id= $hdnid_suministrante";
                 $return_.=$tipo_conexion;
                 $resulthl7=pg_query($tipo_conexion);
                 if (pg_fetch_array($resulthl7)==2){
                     $hl7conexion=1;
                 }
                  if ($hdncmbrechazo_ == 0)
                     $hdncmbrechazo_ = 'NULL';
                  //$hdnf_newdate_ = $_POST['f_newdate_'.$i];
                  $hdnf_newdate_ = isset($_POST['f_newdate_' . $i]) ? $_POST['f_newdate_' . $i]
                             : null;
                  if ($hdnvalidarmuestra_ != 1 && $hdnvalidarmuestra_ !=4) {
                     $idestado = 'RM';
                     if ($hdnvalidarmuestra_ == 2) {
                        $query0 = "select lab_reprogramarnuevacita($idhistorial, '$hdnf_newdate_', $hdniddeatalle_, $idsolicitud, $hdni_idexamen_, $usuario,$referido )";
                        $result0 = pg_query($query0);
                     }
                  } else {
                      if ($hdnvalidarmuestra_ == 4){
                         $idestado = 'E';
                      }
                      else{
                          $sql3="select t3.id_area_servicio_diagnostico
                          from  lab_conf_examen_estab t2
                          join mnt_area_examen_establecimiento t3 on (t3.id=t2.idexamen)
                          where t2.id=$hdni_idexamen_;";
     //                     $sql3="select t4.id
     //                     from  lab_conf_examen_estab t2
     //                     join ctl_examen_servicio_diagnostico t3 on (t3.id=t2.idestandarrep)
     //                     join lab_estandarxgrupo t4 on (t4.id=t3.idgrupo)
     //                     where t2.id=$hdni_idexamen_";
                          $result3=pg_query($sql3);
                          $rowtmu=  pg_fetch_array($result3);
                          //14 es examen referido
                          if ($rowtmu[0]==14)
                               $idestado='RC';
                          else
                             $idestado = 'PM';
                      }

                  }
                  //  (a_idhistorial integer, a_newfechacita date, a_iddetallesolicitud integer, a_idsolicitudestudio integer, a_idexamen integer,  a_idusuarioreg integer, referido integer)
                  // echo 'idestado:'.$idestado.'    hdni_idexamen:'.$hdni_idexamen_.'<br\>';

                  $query1 = "UPDATE sec_detallesolicitudestudios
                      SET estadodetalle = (SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado = '$idestado' AND id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')),
                      f_tomamuestra= '$hdnfechatmx_',
                      id_estado_rechazo=$hdnvalidarmuestra_,
                      id_posible_observacion=$hdncmbrechazo_,
                      id_suministrante = $hdnid_suministrante_,
                         f_estado=current_date
                                 WHERE idsolicitudestudio = $idsolicitud "
                          . "AND idestablecimiento = $lugar
                           and id= $hdniddeatalle_";

                  $result1 = @pg_query($query1);
               }
            }
         } else {
            if ($cantidadnum > 0) {
               for ($i = 1; $i <= $cantidadnum; $i++) {
                  $hdniddeatalle_ = $_POST['iddetalle_' . $i];
                  $hdnfechatmx_ = $_POST['f_tomamuestra_' . $i];
                  $hdnid_suministrante_ = $_POST['id_suministrante_' . $i];
                  $hdnvalidarmuestra_ = $_POST['validarmuestra_' . $i];
                  $hdni_idexamen_ = $_POST['i_idexamen_' . $i];
                  $hdncmbrechazo_ = isset($_POST['cmbrechazo_' . $i]) ? $_POST['cmbrechazo_' . $i]
                             : null;
                  //$hdnf_newdate_ = $_POST['f_newdate_'.$i];
                  $hdnf_newdate_ = isset($_POST['f_newdate_' . $i]) ? $_POST['f_newdate_' . $i]
                             : null;
                 $tipo_conexion="SELECT id_tipo_conexion from lab_suministrante where id= $hdnid_suministrante";
                 $resulthl7=pg_query($tipo_conexion);
                 if (pg_fetch_array($resulthl7)==2){
                     $hl7conexion=1;
                 }
                  if ($hdnvalidarmuestra_ != 1 && $hdnvalidarmuestra_ !=4) {
                     $idestado = 'RM';
                     if ($hdnvalidarmuestra_ == 2) {
                        $query0 = "select lab_reprogramarnuevacita($idhistorial, '$hdnf_newdate_', $hdniddeatalle_, $idsolicitud, $hdni_idexamen_, $usuario,$referido)";
                        $result0 = pg_query($query0);
                     }
                  } else {
                      if ($hdnvalidarmuestra_ == 4){
                         $idestado = 'E';
                      }
                      else{

                     $sql3="select t3.id_area_servicio_diagnostico
                     from  lab_conf_examen_estab t2
                     join mnt_area_examen_establecimiento t3 on (t3.id=t2.idexamen)
                     where t2.id=$hdni_idexamen_;";
//                     $sql3="select t4.id
//                     from  lab_conf_examen_estab t2
//                     join ctl_examen_servicio_diagnostico t3 on (t3.id=t2.idestandarrep)
//                     join lab_estandarxgrupo t4 on (t4.id=t3.idgrupo)
//                     where t2.id=$hdni_idexamen_";
                     $result3=pg_query($sql3);
                     $rowtmu=  pg_fetch_array($result3);
                     //14 es examen referido
                     if ($rowtmu[0]==14)
                          $idestado='RC';
                     else
                          $idestado = 'D';
                      }
                  }

                      $query1 = "UPDATE sec_detallesolicitudestudios
                      SET estadodetalle = (SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado = '$idestado' AND id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')), "
                          . "f_tomamuestra= '$hdnfechatmx_',"
                          . "id_estado_rechazo=$hdnvalidarmuestra_,
                             id_posible_observacion=$hdncmbrechazo_,  "
                          . "f_estado=current_date"
                          . "WHERE idsolicitudestudio = $idsolicitud "
                          . "AND idestablecimiento = $lugar
                           and id= $hdniddeatalle_";
                  $result1 = @pg_query($query1);
               }
            }
         }
         //Si hay conexiones de tipo hl7 debe invocar el envio de la SolicitudLab
         echo $return_.='enviohl7: '.$enviohl7.' $hl7conexo: '.$hl7conexion;
         if ($enviohl7!=0 && $hl7conexion==1){
              $retorno =enviarSolicitudWS($IdSolicitudEstudio);
              var_dump($retorno); exit();
              $return_.=$retorno;
         if ($retorno=='false'){
              $return_.= ' Error Envío a Equipos Automatizados, favor intentar enviar esta solicitud más tarde....';

          }
          else{
              $return_.=  'Solicitud Enviada a equipos automatizados correctamente...';
              $envio='Y';
          }
          /*$envio=enviarSolicitudWS($IdSolicitudEstudio);
          echo 'Envio: '.$envio;*/
        }
         $estadosol= "select count(*) as canti
                from sec_detallesolicitudestudios
                where idsolicitudestudio =$idsolicitud
                and estadodetalle in (1,2,3,5);";
            $resultes = @pg_query($estadosol);
            $rowtcant=  @pg_fetch_array($resultes);
            //14 es examen referido
            if ($rowtcant['canti']==0){
            $querysol = "UPDATE sec_solicitudestudios
                      set estado = 4
                      WHERE id = $idsolicitud";

            $resultsol = @pg_query($querysol);
             }


         if (!$result)
            $return_.=  "N";
         else
            $return_.=  "Y";

        echo $return_;
      } else {
         echo "No se conecta a la base de datos";
      }
      break;
   case 2:  //verificar existencia de datos para los parametros de una solicitud
      $idexpediente = $_POST['idexpediente'];
      $fechacita = $_POST['fechacita'];
      $idEstablecimiento = $_POST['idEstablecimiento'];
      $idsolicitud = $_POST['idsolicitud'];
      // echo 'fechaCita:'.$fechacita;
      //$Nfecha     = explode("/", $fechacita);
      //$Nfechacita = $Nfecha[2] . "-" . $Nfecha[1] . "-" . $Nfecha[0];
      $Nfechacita = $fechacita;

      if ($con->conectar() == true) {
         $query = "SELECT COUNT(t01.id_solicitudestudios) AS numreg, (CASE WHEN t10.id is null THEN false ELSE true END) referido
                      FROM cit_citas_serviciodeapoyo        t01
                      INNER JOIN sec_solicitudestudios      t02 ON (t02.id = t01.id_solicitudestudios)
                      LEFT JOIN sec_historial_clinico       t03 ON (t03.id = t02.id_historial_clinico)
                      LEFT JOIN mnt_expediente              t04 ON (t04.id = t02.id_expediente)
                      LEFT JOIN mnt_dato_referencia	    t10 ON (t10.id = t02.id_dato_referencia)
                      LEFT JOIN mnt_expediente_referido     t11 ON (t11.id = t10.id_expediente_referido)

                      WHERE t01.fecha = '$Nfechacita' AND (t04.numero = '$idexpediente' OR t11.numero = '$idexpediente') AND t02.id_establecimiento = $lugar";
         $query .=' GROUP BY (CASE WHEN t10.id is null THEN false ELSE true END)';
         $numreg = pg_fetch_array(pg_query($query));
         // echo $query;

         if ($numreg[0] == 1) {//verificando existencia de datos para los parametros de la busqueda no referido
            if ($numreg['referido'] == 'f') {
               $query_estado = "SELECT CASE t04.idestado
                                                WHEN 'D' THEN 'Digitada'
                                                WHEN 'R' then 'Recibida'
                                                WHEN 'P' then 'En Proceso'
                                                WHEN 'C' then 'Completa'
                                                WHEN 'RM' then 'Muestra Rechazada'
                                            END AS estado
                                     FROM sec_solicitudestudios                 t01
                                     INNER JOIN cit_citas_serviciodeapoyo       t02 ON (t01.id = t02.id_solicitudestudios)
                                     INNER JOIN sec_historial_clinico           t03 ON (t03.id = t01.id_historial_clinico)
                                     INNER JOIN ctl_estado_servicio_diagnostico t04 ON (t04.id = t01.estado AND t04.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                                     INNER JOIN mnt_expediente                  t05 ON (t05.id = t01.id_expediente)
                                     INNER JOIN ctl_atencion                    t06 ON (t06.id = t01.id_atencion)
                                     WHERE t05.numero = '$idexpediente' AND t02.fecha = '$Nfechacita' AND  t01.id_establecimiento = $lugar
                                           AND t03.idestablecimiento = $idEstablecimiento AND t06.codigo_busqueda = 'DCOLAB' ";
//echo $query_estado;
            } else { // verificar existencia para datos de referencia
               $query_estado = "SELECT CASE t04.idestado
                                            WHEN 'D' THEN 'Digitada'
                                            WHEN 'R' then 'Recibida'
                                            WHEN 'P' then 'En Proceso'
                                            WHEN 'C' then 'Completa'
                                            WHEN 'RM' then 'Muestra Rechazada'
                                        END AS estado
				 FROM sec_solicitudestudios                 t01
				 INNER JOIN cit_citas_serviciodeapoyo       t02 ON (t01.id = t02.id_solicitudestudios)
                                 INNER JOIN ctl_estado_servicio_diagnostico t04 ON (t04.id = t01.estado AND t04.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                                 LEFT JOIN mnt_expediente                  t05 ON (t05.id = t01.id_expediente)
                                 INNER JOIN ctl_atencion                    t06 ON (t06.id = t01.id_atencion)
                                 LEFT JOIN mnt_dato_referencia	    t10 ON (t10.id = t01.id_dato_referencia)
                                 LEFT JOIN mnt_expediente_referido     t11 ON (t11.id = t10.id_expediente_referido)
				 WHERE (t05.numero = '$idexpediente' OR t11.numero = '$idexpediente') AND t02.fecha = '$Nfechacita' AND  t01.id_establecimiento = $lugar
                                       AND t10.id_establecimiento = $idEstablecimiento AND t06.codigo_busqueda = 'DCOLAB'";
            }
            //echo $query_estado;
            $result = @pg_query($query_estado);
            $row = pg_fetch_array($result);
            $estadosolicitud = $row[0];

            if ($estadosolicitud == "Digitada") { //Mostrar datos de la solicitud
               echo "D";
            } else {
               if ($estadosolicitud == "Recibida" or $estadosolicitud == "En Proceso" or $estadosolicitud
                       == "Completa" or $estadosolicitud == "Muestra Rechazada") {
                  echo "La Solicitud esta: " . $estadosolicitud;
               }
            }
         }
         // echo 'numreg: '.$numreg[0];

         if ($numreg[0] > 1) {
            $query_estado = "SELECT CASE t04.idestado
                                            WHEN 'D' THEN 'Digitada'
                                            WHEN 'R' then 'Recibida'
                                            WHEN 'P' then 'En Proceso'
                                            WHEN 'C' then 'Completa'
                                            WHEN 'RM' then 'Muestra Rechazada'
        				                END AS estado
                                 FROM sec_solicitudestudios                 t01
				 INNER JOIN cit_citas_serviciodeapoyo       t02 ON (t01.id = t02.id_solicitudestudios)
				 INNER JOIN sec_historial_clinico           t03 ON (t03.id = t01.id_historial_clinico)
                                 INNER JOIN ctl_estado_servicio_diagnostico t04 ON (t04.id = t01.estado AND t04.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                                 INNER JOIN mnt_expediente                  t05 ON (t05.id = t01.id_expediente)
                                 INNER JOIN ctl_atencion                    t06 ON (t06.id = t01.id_atencion)
				                 WHERE t05.numero = '$idexpediente' AND t02.fecha = '$Nfechacita' AND t04.idestado = 'D' AND t01.id_establecimiento = $lugar AND t03.idestablecimiento = $idEstablecimiento AND t06.codigo_busqueda = 'DCOLAB'";

            $result = @pg_query($query_estado);
            $row = pg_fetch_array($result);
            $estadosolicitud = $row[0];
            if ($estadosolicitud != '') {
               if ($estadosolicitud == "Digitada") { //Mostrar datos de la solicitud
                  echo 'D';
               } else {
                  if ($estadosolicitud == "Recibida" or $estadosolicitud == "En Proceso" or $estadosolicitud
                          == "Completa" or $estadosolicitud == "Muestra Rechazada") { //echo "N";
                     echo "La Solicitud esta: " . $estadosolicitud;
                  }
               }
            } else {
               echo "No hay solicitudes que procesar de este paciente";
            }
         }
         if ($numreg[0] == '0') {
            echo "La Solicitud no Existe";
         }
      } else
         echo "No se conecta a la base de datos";
      break;
   case 4://REGISTRANDO NUEVO NUMERO DE LA MUESTRA
      $idexpediente = $_POST['idexpediente'];
      $fechacita = $_POST['fechacita'];
      $idsolicitud = $_POST['idsolicitud'];

      //Asignando el Numero de Muestra y Registrando la recepcion
     // if ($con->conectar() == true) {
       /*  $query = "SELECT coalesce(MAX(t01.numeromuestra),0) + 1 AS numeromuestra
                      FROM lab_recepcionmuestra        t01
		      INNER JOIN sec_solicitudestudios t02 ON (t02.id = t01.idsolicitudestudio)
		      WHERE  date(t01.fecharecepcion) = current_date
                      AND t02.id_establecimiento = $lugar";*/
         $result=$object->maxrecepcionmuestra($lugar);
         //$result = @pg_query($query);
         if ($result==false)
            echo "N";
         else {
            $row = pg_fetch_array($result);
            $numero = $row['numeromuestra'];
            if ($numero == "") {
               $numero = 1;
            }
            //Registro de la recepcion
            $result_insert=$object->insertrecepcionmuestra($idsolicitud, $numero, $fechacita, $lugar, $usuario);
            /*$query_insert = "INSERT INTO lab_recepcionmuestra(idsolicitudestudio, numeromuestra, fechacita, fecharecepcion, idusuarioreg, fechahorareg, idestablecimiento)
                                 VALUES($idsolicitud, $numero, '$fechacita', TO_DATE(NOW()::text, 'YYYY-MM-DD'), $usuario ,date_trunc('seconds', now()), $lugar)";
            $result_insert = @pg_query($query_insert);*/
            if ($result_insert==false) {
               echo "NN";
            } else {
               //Asignando el Numero de la muestra
               echo "El Numero de Muestra asignado es: " . $numero;
            }
         //}
      }
      break;
   case 5:
      $idexpediente = $_POST['idexpediente'];
      $fechacita = $_POST['fechacita'];
      $estado = $_POST['estado'];
      $idsolicitud = $_POST['idsolicitud'];

      //CAMBIO DEL ESTADO DE LA SOLICITUD
      if ($con->conectar() == true) {
         $query = "UPDATE sec_detallesolicitudestudios SET estadodetalle = (SELECT id FROM lab_estadosdetallesolicitud WHERE idestadodetalle = '$estado')
                      WHERE idsolicitudestudio = $idsolicitud AND IdServicio='DCOLAB'";
         $result = @pg_query($query);
         if (!$result)
            echo "OK";
         else
            echo "FF";
      }
      break;
   case 7:
      $NEC = $_POST['NEC'];
      $Solicitud = $_POST['Solicitud'];
      $Fecha = date("Y-m-d");
      //echo $Solicitud;
      if ($con->conectar() == true) {

         $query = "SELECT DISTINCT lab_recepcionmuestra.FechaRecepcion,mnt_subservicio.IdServicio,mnt_subservicio.IdSubServicio,mnt_empleados.IdEmpleado,
			mnt_expediente.IdNumeroExp, CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre) as Nombres,
			PrimerApellido,IF(SegundoApellido is not null,SegundoApellido,'-') as APELL2,FechaNacimiento,IF(Sexo=1,'M','F') AS Sexo,
			sec_detallesolicitudestudios.IdExamen, lab_examenes.CODIGOSUMI,sec_solicitudestudios.Impresiones,sec_detallesolicitudestudios.IdDetalleSolicitud,sec_detallesolicitudestudios.Indicacion,
			sec_detallesolicitudestudios.IdTipoMuestra,sec_detallesolicitudestudios.IdOrigenMuestra, lab_examenes.IdPlantilla,sec_detallesolicitudestudios.Observacion,
			sec_detallesolicitudestudios.EstadoDetalle,lab_recepcionmuestra.IdRecepcionMuestra,lab_recepcionmuestra.NumeroMuestra,sec_solicitudestudios.CAMA
			FROM sec_solicitudestudios
			INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
			INNER JOIN sec_detallesolicitudestudios ON sec_solicitudestudios.IdSolicitudEstudio=sec_detallesolicitudestudios.IdSolicitudEstudio
			INNER JOIN mnt_empleados ON sec_historial_clinico.IdEmpleado=mnt_empleados.IdEmpleado
			INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp=mnt_expediente.IdNumeroExp
			INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente
			INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
			INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio=mnt_servicio.IdServicio
			INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
			INNER JOIN lab_recepcionmuestra ON sec_solicitudestudios.IdSolicitudEstudio=lab_recepcionmuestra.IdSolicitudEstudio
			WHERE sec_solicitudestudios.IdSolicitudEstudio='$Solicitud'";

         echo $query;
         $Cla = "";
         $result = pg_query($query);
         while ($row = pg_fetch_array($result)) {

            $con2->conectarT();
            $Cla.= $Clases->Query($Solicitud, $row['FechaRecepcion'],
                    $row['IdServicio'], $row['IdSubServicio'],
                    $row['IdEmpleado'], $row['IdNumeroExp'], $row['Nombres'],
                    $row['PrimerApellido'], $row['APELL2'],
                    $row['FechaNacimiento'], $row['Sexo'], $row['IdExamen'],
                    $row['CODIGOSUMI'], $row['Impresiones'], $row['CAMA'],
                    $Fecha, 0, $row['IdDetalleSolicitud'], $row['Indicacion'],
                    $row['IdTipoMuestra'], $row['IdOrigenMuestra'],
                    $row['IdPlantilla'], $row['Observacion'],
                    $row['EstadoDetalle'], $row['IdRecepcionMuestra'],
                    $row['numeromuestra']);
            $Cla.='\n';
         }
         echo $Cla;
         $con2->desconectarT();
      }

      break;
   case 8: //llenar combo estableciminetos
      $rslts = '';
      $Idtipo = $_POST['IdTipoEstab'];

      if ($con->conectar() == true) {
         $query = "SELECT id AS idestablecimiento, nombre FROM ctl_establecimiento WHERE id_tipo_establecimiento = $Idtipo ORDER BY Nombre";
         $result = pg_query($query);

         $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" class="height js-example-basic-single" style="width:400px">';
         $rslts .='<option value="0">--Seleccione un Establecimiento--</option>';

         while ($rows = pg_fetch_array($result)) {
            $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
         }
      }
      $rslts .= '</select>';
      echo $rslts;
      break;
   case 9: //Mostrar todos las solicitudes
      $idexpediente = $_POST['idexpediente'];
      $fechacita = $_POST['fechacita'];
      $idEstablecimiento = $_POST['idEstablecimiento'];

      $query = $object->buscarTodasSolicitudes($idexpediente, $fechacita,
              $lugar, $idEstablecimiento);

      if ($query !== false) {
         $jsonresponse['status'] = true;
         $jsonresponse['num_rows'] = pg_num_rows($query);
//            $jsonresponse['consuls'] = $resulti;

         if (pg_num_rows($query) > 0)
            $jsonresponse['data'] = pg_fetch_all($query);
      } else {
         $jsonresponse['status'] = false;
      }
      echo json_encode($jsonresponse);
      break;
   case 10:
      $query = $object->obtenerEstado($lugar);

      if ($query !== false) {
         $jsonresponse['status'] = true;
         $jsonresponse['num_rows'] = pg_num_rows($query);

         if (pg_num_rows($query) > 0)
            $jsonresponse['data'] = pg_fetch_all($query);
      } else {
         $jsonresponse['status'] = false;
      }
      echo json_encode($jsonresponse);
      break;
   case 11:
      $idrechazo = $_POST['idrechazo'];
      $k = $_POST['idk'];
      $rslts = "";
      if ($idrechazo != 1) {
         $query = $object->obteneropcionesrechazo($idrechazo);
         $rslts = '<select name="cmbrechazo_' . $k . '" id="cmbrechazo_' . $k . '" class="form-control height" style="width:100%" onclick="cancelrechazo(this.value, ' . $k . ')">';
         $rslts = "<select name='cmbrechazo_" . $k . "' id='cmbrechazo_" . $k . "' class='form-control height' style='width:100%' onclick=\"cancelrechazo(this.value, '" . $k . "')\">";
         $rslts .='<option value="0" selected>--Seleccione una opción--</option>';
         while ($rows = pg_fetch_array($query)) {
            $rslts.= '<option value="' . $rows["id_posible_observacion"] . '" >' . htmlentities($rows["posible_observacion"]) . '</option>';
         }
         $rslts .='<option value="xyz">*Validar nuevamente</option>';

         $rslts .= '</select>';
      }
      echo $rslts;
      break;

   case 12:
      $idrechazo = $_POST['cmbrechazo'];
      $k = $_POST['idk'];
      $temporal = $_POST['temporal'];
      $date = date_create(date('Y-m-d'));
      date_modify($date, '+1 day');
      $rslts = "";
      if ($temporal == 2) {
         if ($idrechazo != 0 && $idrechazo != 'xyz') {
            //  $query = $object->obteneropcionesrechazo();
            $rslts = "<input type='text' class='date form-control height placeholder'  id='f_newdate_" . $k . "' name='f_newdate_'  placeholder='aaaa-mm-dd' onchange=\"valfechasolicita(this.value, 'f_newdate_" . $k . "')\"  style='width:105px' />";
         }
      } else {
          if ($temporal== 3){
              $rslts = "<p>Definitivo</p><input type='hidden' class='date form-control height'  id='f_newdate_" . $k . "' name='f_newdate_'  value='" . date('Y-m-d') . "' onchange=\"valfechasolicita(this.value, 'f_newdate_" . $k . "')\" style='width:100px' />";
           }
          else {
              $rslts = "<p>Cancelado</p><input type='hidden' class='date form-control height'  id='f_newdate_" . $k . "' name='f_newdate_'  value='" . date('Y-m-d') . "' onchange=\"valfechasolicita(this.value, 'f_newdate_" . $k . "')\" style='width:100px' />";
          }
      }
      echo $rslts;
      break;

   case 13:
      //Ingresando a lab_recepcionmuestra
      $jsonresponse['status'] = true;

      $cmbrechazoest = $_POST['cmbrechazoest'];
      $cmbrechazosol = $_POST['cmbrechazosol'];
      $idsolicitud = $_POST['idsolicitud'];
      $fecharechazo = $_POST['fecharechazo'];
      $var = $_POST['fechacita'];
      $date = str_replace('/', '-', $var);
      $fechacita=date('Y-m-d', strtotime($date));

    //  $fecpure=  new DateTime($_POST['fechacita']);
    //   $fechacita=$fecpure->format('Y-m-d');
     // $idsolicitud = $_POST['idsolicitud'];

      //$fechanewcitasol = isset($_POST['fechanewcitasol']) ? $_POST['fechanewcitasol']
      $fechanewcitasol=(empty($_POST['fechanewcitasol'])) ? 'NULL' : "'" . pg_escape_string(trim($_POST['fechanewcitasol'])) . "'";
      $fechacita=(empty($fechacita)) ? 'NULL' : "'" . pg_escape_string(trim($fechacita)) . "'";
      $fecharechazo=(empty($fecharechazo)) ? 'NULL' : "'" . pg_escape_string(trim($fecharechazo)) . "'";
//      $observacionrechazo = isset($_POST['observacionrechazo']) ? $_POST['observacionrechazo']
      $observacionrechazo = (empty($_POST['observacionrechazo'])) ? 'NULL' : "'" . pg_escape_string(trim($_POST['observacionrechazo'])) . "'";

      $rslts = "";
      $cancelarsol = $object->cancelarsolicitud($cmbrechazoest, $cmbrechazosol,
              $fechanewcitasol, $observacionrechazo, $idsolicitud, $usuario, $fechacita, $lugar, $fecharechazo);

     // var_dump($cancelarsol);
     // exit();
      if ($cancelarsol == true) {
         $jsonresponse['status'] = true;
      } else
         $jsonresponse['status'] = FALSE;


      echo json_encode($jsonresponse);

      break;

      case 14:
         $idrechazo = $_POST['idrechazo'];
         $rslts = "";
         if ($idrechazo!=0){
             $query = $object->obteneropcionesrechazo($idrechazo);
             $rslts = "<select name='cmbrechazosol' id='cmbrechazosol' class='form-control height' style='width:90%' >";
             $rslts .='<option value="0" selected>--Seleccione una opción--</option>';
             while ($rows = pg_fetch_array($query)) {
                $rslts.= '<option value="' . $rows["id_posible_observacion"] . '" >' . htmlentities($rows["posible_observacion"]) . '</option>';
             }
             $rslts .= '</select>';
         }
         else{
             $rslts = "<select name='cmbrechazosol' id='cmbrechazosol' class='form-control height' style='width:90%' >";
             $rslts .='<option value="0" selected>--Seleccione una opción--</option>';
             $rslts .= '</select>';
         }


         echo $rslts;
         break;
}
