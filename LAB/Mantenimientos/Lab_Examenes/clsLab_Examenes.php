<?php

include_once("../../../Conexion/ConexionBD.php");

class clsLab_Examenes {

   //constructor
   function clsLab_Examenes() {

   }

   //INSERTA UN REGISTRO
   function IngExamenxEstablecimiento($idexamen, $nomexamen, $Hab, $usuario,
           $IdFormulario, $IdEstandarResp, $plantilla, $letra, $Urgente,
           $ubicacion, $TiempoPrevio, $sexo, $idestandar, $lugar,
           $metodologias_sel, $text_metodologias_sel, $id_metodologias_sel,
           $resultado, $id_resultado, $cmbTipoMuestra, $cmbPerfil, $cmbEstabReferido) {

               //echo '<br>LLegando al cls: '.$cmbEstabReferido.'<br>';
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         if ($IdFormulario == '0')
            $IdFormulario = 'NULL';
         $nextid="select nextval('lab_conf_examen_estab_id_seq')";
                    $sql=  pg_query($nextid);
                    $nextseq=  pg_fetch_array($sql);
                    $ultimo=$nextseq[0];
         $query = "INSERT INTO lab_conf_examen_estab
                (id, condicion,idformulario,urgente,impresion,ubicacion,codigosumi,
                 idusuarioreg,fechahorareg,idusuariomod,fechahoramod,idexamen,idestandarrep,idplantilla,nombre_examen,
                 idsexo,codigo_examen)
                 VALUES
                 ($ultimo,'$Hab', $IdFormulario,$Urgente,'$letra',$ubicacion,NULL,$usuario,NOW(),$usuario,NOW(),$idestandar,
                   $IdEstandarResp,$plantilla,'$nomexamen',$sexo,'$idexamen') ";
         // echo $query;
         $result = pg_query($query);


         //ingresar resultados
         /*
          *

          */


//         $query2 = "select COALESCE(max(id),1) from lab_conf_examen_estab";
//         $result2 = pg_query($query2);
//         $row2 = pg_fetch_array($result2);
//         $ultimo = $row2[0];

         $aresultados = explode(',', $resultado);
         $aidresultados = explode(',', $id_resultado);
         for ($i = 0; $i < (count($aresultados) - 1); $i++) {

            $query = "UPDATE lab_examen_posible_resultado
                        SET habilitado = true,
                            fechafin = null,
                            id_user_mod = $usuario,
                            fecha_mod = now()
                        WHERE id_posible_resultado = '$aresultados[$i]' AND id_conf_examen_estab=$ultimo";
            $result = pg_query($query);
            if (pg_affected_rows($result) == 0) {
               $query = "INSERT INTO lab_examen_posible_resultado(
                            id_conf_examen_estab, id_posible_resultado, fechainicio, fechafin,
                            habilitado, id_user, fecha_registro, id_user_mod, fecha_mod)
                    VALUES ('$ultimo', '$aresultados[$i]', date_trunc('seconds',NOW()), null,
                            true, $usuario, date_trunc('seconds',NOW()), null, null)";
               $result = pg_query($query);
            }
         }

         // fin ingresar resultados
//
//
//
//         $query2 = "select COALESCE(max(id),1) from lab_conf_examen_estab";
//         $result2 = pg_query($query2);
//         $row2 = pg_fetch_array($result2);
//         $ultimo = $row2[0];
         //echo 'met_sel:'.$metodologias_sel;

         /*
          * crear examen - metodologias
          */
        // echo 'MetodologiasSel:'.$metodologias_sel.'---';
         $aMetodologias = explode(',', $metodologias_sel);
         $aMetodologias_text = explode(',', $text_metodologias_sel);
         $aMetodologias_id = explode(',', $id_metodologias_sel);
         //echo 'amet:'.count($aMetodologias). ' /met: '. $aMetodologias[0];
         /*
          * actualizar o crear examen metodología
          */
         $i = 0;
         if ($aMetodologias[0] != "") {
            for ($i = 0; $i < (count($aMetodologias) - 1); $i++) {
               $sql = "INSERT INTO lab_examen_metodologia(id_conf_exa_estab,id_metodologia,activo,fecha_inicio,fecha_fin,nombre_reporta) VALUES ($ultimo, $aMetodologias[$i], true, NOW(), NULL, '$aMetodologias_id[$i]')";
               pg_query($sql);
            }
         }

         if ($i == 0) { // si no se han seleccionado metodologias
            $sql = "INSERT INTO lab_examen_metodologia(id_conf_exa_estab,id_metodologia,activo,fecha_inicio,fecha_fin, nombre_reporta) VALUES ($ultimo, NULL, true, NOW(), NULL, '$nomexamen') returning id";
            $qry = pg_query($sql);
            if ($resultado != 'NULL') {
               $idmetodo = pg_fetch_array($qry);
               $idmet = $idmetodo['id'];
               for ($i = 0; $i < (count($aresultados) - 1); $i++) {
                  $insert = "insert into lab_examen_metodo_pos_resultado (id_examen_metodologia, id_posible_resultado, fechainicio,  habilitado, id_user, fecha_registro, id_codigoresultado)
values ($idmet, $aresultados[$i], current_date, true,$usuario, date_trunc('seconds', NOW()), $aidresultados[$i])";
                  $metpos = pg_query($insert);
               }
            }
         }


         $sqlText = "INSERT INTO cit_programacion_exams (id_examen_establecimiento,rangotiempoprev,id_atencion,id_establecimiento,idusuarioreg,fechahorareg)
                    VALUES ($ultimo,$TiempoPrevio,98,$lugar,$usuario,date_trunc('seconds', NOW()))";

         // echo $query2." - ".$ultimo." - ".$sqlText;
         $dtSub = pg_query($sqlText);

         // echo $sqlText;

         //asignar posibles resultados

         //Asignar posibles tipos de muestra

         $aTipoMuestra = explode(',', $cmbTipoMuestra);
        // echo 'cmbTipomuestra'.$cmbTipoMuestra.'-----'.count($aTipoMuestra);
         //echo 'amet:'.count($aMetodologias). ' /met: '. $aMetodologias[0];
         /*
          * actualizar o crear examen metodología
          */
         $i = 0;
         if ($aTipoMuestra[0] != "") {
            for ($i = 0; $i < (count(array_filter($aTipoMuestra))); $i++) {
               $sql = "INSERT INTO lab_tipomuestraporexamen(idtipomuestra,idusuarioreg, fechahorareg, idexamen) VALUES ($aTipoMuestra[$i], $usuario,  date_trunc('seconds', NOW()), $ultimo)";
               pg_query($sql);
            }
         }

         //********Asignar a perfil
          //Asignar posibles tipos de muestra
         $aPerfil = explode(',', $cmbPerfil);
        // echo 'aperfil:'.$aPerfil.'----0'.count($aPerfil);

         $i = 0;
         if ($aPerfil[0] != "") {
            for ($i = 0; $i < (count(array_filter($aPerfil)) ); $i++) {
               $sql = "INSERT INTO lab_perfil_prueba(id_perfil,id_conf_examen_estab, id_establecimiento, fecha_inicio, fechahorareg, idusuarioreg) VALUES ($aPerfil[$i], $ultimo, $lugar, current_date,  date_trunc('seconds', NOW()), $usuario)";
               pg_query($sql);
            }
         }

         //********Asignar establecimientos a referir
          //Asignar posibles tipos de muestra
         $aEstabReferido = explode(',', $cmbEstabReferido);
        //echo '$cmbEstabReferido: '.$cmbEstabReferido.'   estabreferido:'.$aEstabReferido.'----'.count(array_filter($aEstabReferido)).' a1:'.$aEstabReferido[0];

         $i = 0;
         if ($aEstabReferido[0] != "") {
            for ($i = 0; $i < (count(array_filter($aEstabReferido)) ); $i++) {
                $aConfexaTipolab= explode('|', $aEstabReferido[$i]);
                //echo 'aConfexaTipolab:'.$aConfexaTipolab.'----0'.count($aConfexaTipolab);
               $sql6 = "INSERT INTO lab_conf_examen_tipo_laboratorio (id_establecimiento, id_examen_tipo_laboratorio, fecha_inicio, id_conf_examen_estab) values ($aConfexaTipolab[1], $aConfexaTipolab[0], current_date, $ultimo);";
               pg_query($sql6);
            }
         }
           if (!$result) {
            return false;
         } else {
            return true;
         }
      }
   }

   function IngExamenxEstablecimiento2($idexamen, $nomexamen, $Hab, $usuario,
           $IdFormulario, $IdEstandarResp, $plantilla, $letra, $Urgente,
           $ubicacion, $TiempoPrevio, $sexo, $idestandar, $lugar,
           $metodologias_sel, $text_metodologias_sel, $id_metodologias_sel) {

      $con = new ConexionBD;
      if ($con->conectar() == true) {
         if ($IdFormulario == '0')
            $IdFormulario = 'NULL';
         $query = "INSERT INTO lab_conf_examen_estab
                (condicion,idformulario,urgente,impresion,ubicacion,codigosumi,
                 idusuarioreg,fechahorareg,idusuariomod,fechahoramod,idexamen,idestandarrep,idplantilla,nombre_examen,
                 idsexo,codigo_examen)
                 VALUES
                 ('$Hab',$IdFormulario,$Urgente,'$letra',$ubicacion,NULL,$usuario,NOW(),$usuario,NOW(),$idestandar,
                   $IdEstandarResp,$plantilla,'$nomexamen',$sexo,'$idexamen') ";
         // echo $query;
         $result = pg_query($query);






         $query2 = "select COALESCE(max(id),1) from lab_conf_examen_estab";
         $result2 = pg_query($query2);
         $row2 = pg_fetch_array($result2);
         $ultimo = $row2[0];
         //echo 'met_sel:'.$metodologias_sel;

         /*
          * crear examen - metodologias
          */
         $aMetodologias = explode(',', $metodologias_sel);
         $aMetodologias_text = explode(',', $text_metodologias_sel);
         $aMetodologias_id = explode(',', $id_metodologias_sel);
         //echo 'amet:'.count($aMetodologias). ' /met: '. $aMetodologias[0];
         /*
          * actualizar o crear examen metodología
          */
         $i = 0;
         if ($aMetodologias[0] != "") {
            for ($i = 0; $i < (count($aMetodologias) - 1); $i++) {
               $sql = "INSERT INTO lab_examen_metodologia(id_conf_exa_estab,id_metodologia,activo,fecha_inicio,fecha_fin,nombre_reporta) VALUES ($ultimo, $aMetodologias[$i], true, NOW(), NULL, '$aMetodologias_id[$i]')";
               pg_query($sql);
            }
         }
         if ($i == 0) { // si no se han seleccionado metodologias
            $sql = "INSERT INTO lab_examen_metodologia(id_conf_exa_estab,id_metodologia,activo,fecha_inicio,fecha_fin, nombre_reporta) VALUES ($ultimo, NULL, true, NOW(), NULL, '$nomexamen')";
            pg_query($sql);
         }


         $sqlText = "INSERT INTO cit_programacion_exams (id_examen_establecimiento,rangotiempoprev,id_atencion,id_establecimiento,idusuarioreg,fechahorareg)
                    VALUES ($ultimo,$TiempoPrevio,98,$lugar,$usuario,NOW())";

         // echo $query2." - ".$ultimo." - ".$sqlText;
         $dtSub = pg_query($sqlText);

         // echo $sqlText;
         if (!$result) {
            return false;
         } else {
            return true;
         }
         //asignar posibles resultados
      }
   }

   // ultimo id de lab_conf_examen_estab
   function ultimoexa() {

      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "select COALESCE(max(id),1) from lab_conf_examen_estab";
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   // INSERTAR LOS RESULTADOS
   function posible_resultados($resultado) {

      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {

         $query2 = "select COALESCE(max(id),1) from lab_conf_examen_estab";
         $result2 = pg_query($query2);
         $row2 = pg_fetch_array($result2);
         $ultimo = $row2[0];

         $aresultados = explode(',', $resultado);
         for ($i = 0; $i < (count($aresultados) - 1); $i++) {

            $query = "UPDATE lab_examen_posible_resultado
                        SET habilitado = true,
                            fechafin = null,
                            id_user_mod = 8,
                            fecha_mod = now()
                        WHERE id_posible_resultado = '$aresultados[$i]' AND id_conf_examen_estab='$ultimo'";
            $result = pg_query($query);
            if (pg_affected_rows($result) == 0) {
               $query = "INSERT INTO lab_examen_posible_resultado(
                            id_conf_examen_estab, id_posible_resultado, fechainicio, fechafin,
                            habilitado, id_user, fecha_registro, id_user_mod, fecha_mod)
                    VALUES ('$ultimo', '$aresultados[$i]', now(), null,
                            true, 8, now(), null, null)";
               $result = pg_query($query);
            }
         }
      }
   }

   //ACTUALIZA UN REGISTRO
   function ActExamenxEstablecimiento($idconf, $nomexamen, $lugar, $usuario,
           $IdFormulario, $IdEstandarResp, $plantilla, $letra, $Urgente,
           $ubicacion, $Hab, $TiempoPrevio, $idsexo, $idestandar,
           $ctlidestandar, $metodologias_sel, $text_metodologias_sel,
           $id_metodologias_sel, $resultado, $id_resultado, $cmbTipoMuestra, $cmbPerfil, $cmbEstabReferido ) {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         if ($IdFormulario == '0')
            $IdFormulario = 'NULL';

         $query = "UPDATE lab_conf_examen_estab
                              SET idusuariomod=$usuario,fechahoramod=NOW(),idformulario=$IdFormulario,
                              idestandarrep=$IdEstandarResp,IdPlantilla=$plantilla,impresion='$letra',urgente=$Urgente,ubicacion=$ubicacion,condicion='$Hab',nombre_examen='$nomexamen',idsexo=$idsexo
                              WHERE lab_conf_examen_estab.id=$idconf";
         //echo $query;
         $result = pg_query($query);

         $aMetodologias = explode(',', $metodologias_sel);

         $aMetodologias_text = explode(',', $text_metodologias_sel);
         $aMetodologias_id = explode(',', $id_metodologias_sel);


         $aresultados = explode(',', $resultado);
         $aidresultados = explode(',', $id_resultado);
         $cantaresultados = count(array_filter($aresultados));
         /*
          * En caso de no haber ingresado ningún resultado
          */
         $actualizaposresexa="update lab_examen_posible_resultado "
                 . "set fechafin =current_date, "
                 . "habilitado=false, "
                 . "id_user_mod=$usuario, "
                 . "fecha_mod=date_trunc('seconds', NOW()) "
                 . "where id_conf_examen_estab=$idconf  "
                 . "and habilitado=true;";

         $actualizaposresexamet = "update lab_examen_metodo_pos_resultado t01
                           set habilitado=false,
                           fechafin=current_date,
                           id_user_mod=$usuario,
                           fecha_mod=date_trunc('seconds', NOW())
                           from lab_examen_metodologia t02
                           where t01.id_examen_metodologia=t02.id
                           and id_conf_exa_estab=$idconf
                              and habilitado=true ;";

         /*
          * actualizar las metodoligas del examen
          */

         if ($aMetodologias[0] == "") { // cuando no hay metodologias seleccionadas
            /*
             * verificar si hay registro con NULL en metodologia
             */
            $sql = "SELECT * FROM lab_examen_metodologia WHERE id_conf_exa_estab=$idconf and id_metodologia is NULL";
            $con = pg_query($sql);
            /*
             * evaluar si hay metodologias guardadas
             */
            if (pg_num_rows($con) > 0) {
               $sql = "UPDATE lab_examen_metodologia SET activo=true,fecha_inicio=NOW(),fecha_fin=NULL WHERE id_conf_exa_estab=$idconf AND id_metodologia is null returning id";
               $upd = pg_query($sql);
               $idexametodologia=  pg_fetch_array($upd);
               $idexameto=$idexametodologia['id'];

               $sql = "UPDATE lab_examen_metodologia SET activo=false,fecha_fin=NOW() WHERE id_conf_exa_estab=$idconf AND id_metodologia is not null";
               $upd = pg_query($sql);
            } else { // si no existen registro con metodologia NULL
               $sql = "INSERT INTO lab_examen_metodologia(id_conf_exa_estab,id_metodologia,activo,fecha_inicio,fecha_fin, nombre_reporta) VALUES ($idconf, null, true, NOW(), NULL, '$nomexamen') returning id";
               $resultexme=pg_query($sql);
               $idexametodologia=  pg_fetch_array($resultexme);
               $idexameto=$idexametodologia['id'];
               /*
                * Desactivar todas los examen metodologias menos el que solo tiene examen.
                */
               $sql = "UPDATE lab_examen_metodologia SET activo=false WHERE id_conf_exa_estab=$idconf AND id_metodologia is not null";
               $upd = pg_query($sql);
            }

         //Actualizar los posibles resultados de acuerdo a los ingresados.
         //Ejecutar la query que actualiza la tabla de lab_examen_posible_resultado
         $queryposres1=  pg_query($actualizaposresexa);
         //Ejecutar la query que actualiza la tabla de lab_examen_metodo_pos_resultado
         $queryposres2=  pg_query($actualizaposresexamet);
           if ($cantaresultados>0){
             //--1.Actualizar tabla lab_examen_posible_resultado
              $queryposres1=  pg_query($actualizaposresexa);
            $posres="select * from lab_examen_posible_resultado where id_conf_examen_estab=$idconf;";
            $sql3="select * from lab_examen_metodo_pos_resultado where id_examen_metodologia=$idexameto;";
            //$resultpr=  pg_query($posres);
             for ($j = 0; $j < count(array_filter($aresultados)); $j++) {
                $bandera=0;
                $bandexamet=0;
                $query1 = pg_query($posres);
                  while ($mpr = @pg_fetch_array($query1)) {
                     if (($mpr['id_posible_resultado'] == $aresultados[$j])) {
                         $sql2="update lab_examen_posible_resultado "
                                . "set fechafin=null, "
                                . "habilitado=true, "
                                . "id_user_mod=$usuario, "
                                . "fecha_mod=date_trunc('seconds', NOW())"
                                . " where id_conf_examen_estab=$idconf "
                                . "and id_posible_resultado=$aresultados[$j];";
                        $query2=  pg_query($sql2);
                        $bandera=1;
                     }

                  }
                  if ($bandera==0){
                     $sql2="insert into lab_examen_posible_resultado(id_conf_examen_estab, id_posible_resultado, fechainicio, habilitado, id_user, fecha_registro)
values ($idconf,$aresultados[$j], current_date, true, $usuario, date_trunc('seconds', NOW()))";
                     $query2=pg_query($sql2);
                  }
                $result3=pg_query($sql3);
                while ($metpor=@pg_fetch_array($result3)){
                   if ($metpor['id_posible_resultado']==$aresultados[$j]){
                      $sql4="update lab_examen_metodo_pos_resultado "
                              . "set fechafin=NULL, "
                              . "habilitado=true, "
                              . "id_user_mod=$usuario, "
                              . "fecha_mod=date_trunc('seconds', NOW())";

                      if ($aidresultados[$j]!="")
                        $sql4.=",id_codigoresultado=$aidresultados[$j]";
                      $sql4.=" where id_examen_metodologia=$idexameto and id_posible_resultado=$aresultados[$j];
";
                      $query4=pg_query($sql4);
                      $bandexamet=1;

                   }
                }
                if ($bandexamet==0){
                   $sql5="insert into lab_examen_metodo_pos_resultado (id_examen_metodologia, id_posible_resultado, fechainicio, habilitado, id_user, fecha_registro, id_codigoresultado)
                     values ($idexameto, $aresultados[$j], current_date, true, $usuario, date_trunc('seconds', now()), $aidresultados[$j]);";
                   $query5=  pg_query($sql5);
                }

             }
            //**1.FIN DE Actualizar tabla lab_examen_posible_resultado
            //--2.Actualizar tabla lab_examen_metodo_pos_resultado

            //**2.FIN DE Actualizar tabla lab_examen_metodo_pos_resultado
           }//fin if cantresultados>0

            //Fin Actualizar los posibles resultados de acuerdo a los ingresados.


         } else { //cuando se seleccionan las metodologias
            /*
             * inactivar la opcion default de lab_examen_metodologia
             */
            $sql = "UPDATE lab_examen_metodologia SET activo=false,fecha_fin=NOW() WHERE id_conf_exa_estab=$idconf AND fecha_fin is null";
            $upd = pg_query($sql);
            //echo $sql;
            //Ejecutar la query que actualiza la tabla de lab_examen_posible_resultado
            $queryposres1=  pg_query($actualizaposresexa);
            //Ejecutar la query que actualiza la tabla de lab_examen_metodo_pos_resultado
         //   $queryposres2=  pg_query($actualizaposresexamet);
            /*
             *
             */

            $band = 0;
            $bandera=0;
            $bandexamet=0;
            $sql1 = "SELECT * FROM lab_examen_metodologia WHERE id_conf_exa_estab=$idconf and id_metodologia is not NULL";
            $metodologiassel="";
            for ($i = 0; $i < count(array_filter($aMetodologias)) ; $i++) {
               // echo $band=0;
              // echo 'i:'.$i.'---';
               $con = pg_query($sql1);
               while ($rome = pg_fetch_array($con)) {
                  if ($rome['id_metodologia'] == $aMetodologias[$i]) {
                     $sql = "UPDATE lab_examen_metodologia SET activo=true,fecha_inicio=NOW(),fecha_fin=NULL,nombre_reporta='$aMetodologias_id[$i]' WHERE id_conf_exa_estab=$idconf AND id_metodologia=$aMetodologias[$i] returning id;";
                     $upd = pg_query($sql);
                     $idexametodologia=  pg_fetch_array($upd);
                     $idexameto=$idexametodologia['id'];
                     //$metodologiassel=$metodologiassel.','.$idexameto;
                     $band = 1;
                  }
                  // echo 'i:'.$i.' band:'.$band.' idmet:'.$rome['id_metodologia'].' amet:'.$aMetodologias[$i].'<br>****';
               }
               if ($band == 0) {
                  // echo 'entro aqui';
                  $sql = "INSERT INTO lab_examen_metodologia(id_conf_exa_estab,id_metodologia,activo,fecha_inicio,fecha_fin,nombre_reporta) VALUES ($idconf, $aMetodologias[$i], true, NOW(), NULL,'$aMetodologias_id[$i]') returning id;";
                  // echo 'sql:'.$sql;
                  $upd = pg_query($sql);
                  $idexametodologia=  pg_fetch_array($upd);
                  $idexameto=$idexametodologia['id'];
                  //$metodologiassel=$metodologiassel.','.$idexameto;
               }
               if ($metodologiassel==""){
                  $metodologiassel=$idexameto;
               }
               else{
                  $metodologiassel=$metodologiassel.','.$idexameto;
               }
            //  echo  'metodologiassel: '.$metodologiassel.'<br/>';

               if ($cantaresultados>0){
                     //--1.Actualizar tabla lab_examen_posible_resultado
                    $posres="select * from lab_examen_posible_resultado where id_conf_examen_estab=$idconf;";
                    //$sql3="select * from lab_examen_metodo_pos_resultado where id_examen_metodologia=$idexameto;";
                    //$resultpr=  pg_query($posres);
                     for ($j = 0; $j < count(array_filter($aresultados)); $j++) {                                  $bandera=0;
                        $bandexamet=0;
                        $query1 = pg_query($posres);
                          while ($mpr = @pg_fetch_array($query1)) {
                             if (($mpr['id_posible_resultado'] == $aresultados[$j])) {
                                 $sql2="update lab_examen_posible_resultado "
                                . "set fechafin=null, "
                                . "habilitado=true, "
                                . "id_user_mod=$usuario, "
                                . "fecha_mod=date_trunc('seconds', NOW())"
                                . " where id_conf_examen_estab=$idconf "
                                . "and id_posible_resultado=$aresultados[$j];";
                                $query2=  pg_query($sql2);
                                $bandera=1;
                             }

                          }
                          if ($bandera==0){
                             $sql2="insert into lab_examen_posible_resultado(id_conf_examen_estab, id_posible_resultado, fechainicio, habilitado, id_user, fecha_registro)
        values ($idconf,$aresultados[$j], current_date, true, $usuario, date_trunc('seconds', NOW()))";
                             $query2=pg_query($sql2);
                          }
                           //$result3=pg_query($sql3);


                     }
                    //**1.FIN DE Actualizar tabla lab_examen_posible_resultado
                    //--2.Actualizar tabla lab_examen_metodo_pos_resultado

                    //**2.FIN DE Actualizar tabla lab_examen_metodo_pos_resultado
                   }//fin if cantresultados>0
               $band = 0;
               $bandera=0;
               $bandexamet=0;

            }
          //  echo  'metodologiassel: '.$metodologiassel.'   -cantaresultados:'.$cantaresultados.'<br/>';
//            var_dump($metodologiassel);
            //actualizar la de lab_examen_metodo_pos_resultados, poner habilitado=false las metodologias que no fueron ingresadas ahorita

             $upexapr="update lab_examen_metodo_pos_resultado t01
               set fechafin=current_date,
               habilitado=false,
               id_user_mod=$usuario,
               fecha_mod=date_trunc('seconds', NOW())
               from lab_examen_metodologia t02
               where t02.id=t01.id_examen_metodologia
               and id_conf_exa_estab=$idconf
               and id_examen_metodologia not in ($metodologiassel)";
            $query6=  pg_query($upexapr);
         }

         $query_tiempo = "SELECT * FROM cit_programacion_exams
                                    WHERE id_examen_establecimiento=$idconf ";
         $tot = pg_num_rows(pg_query($query_tiempo));
         // $tot=$result_tiempo[0];
         //echo $tot;
         if ($tot > 0) {
            $sqlText = "UPDATE cit_programacion_exams
                                     SET rangotiempoprev=$TiempoPrevio
                                     WHERE id_examen_establecimiento=$idconf";
            // $dtSub = pg_query($sqlText);
         } else {
            $sqlText = "INSERT INTO cit_programacion_exams (id_examen_establecimiento,rangotiempoprev,id_atencion,id_establecimiento,idusuarioreg,fechahorareg)
                                     VALUES ($idconf,$TiempoPrevio,98,$lugar,$usuario,NOW())";
         }
         //echo $sqlText;
         $dtSub = pg_query($sqlText);

         //**Actualizar los tipos de muestra


         $atipomuestra = explode(',', $cmbTipoMuestra);
         $cantipomuestra=count(array_filter($atipomuestra));

         $sql6="update lab_tipomuestraporexamen
               set habilitado=false,
               idusuariomod=$usuario,
               fechahoramod=date_trunc('seconds', NOW())
               where idexamen=$idconf
               and habilitado=true;";

         $queryposres3=  pg_query($sql6);
         if ($cantipomuestra>0){
            $sql7="select * from lab_tipomuestraporexamen where idexamen =$idconf";
            for ($k=0; $k< $cantipomuestra; $k++){
               $bande=0;
               $query7= pg_query($sql7);
               while ($tmu=@pg_fetch_array($query7)){
                  if ($tmu['idtipomuestra']==$atipomuestra[$k]){
                     $sql8="update lab_tipomuestraporexamen
                           set habilitado=true,
                           idusuariomod=$usuario,
                           fechahoramod=date_trunc('seconds', NOW())
                           where idexamen=$idconf
                           and idtipomuestra=$atipomuestra[$k];";
                     $query8=  pg_query($sql8);
                     $bande=1;
                  }
               }
               if ($bande==0){
                  $sql9="insert into lab_tipomuestraporexamen (idtipomuestra, idusuarioreg, fechahorareg, idexamen, habilitado) values ($atipomuestra[$k], $usuario, date_trunc('seconds', NOW()), $idconf, true)";
                  $query9= pg_query($sql9);
               }

            }
         }
         //-***Fin actualizar tipos de muestra
         //**Actualizar los perfiles
         $aperfil = explode(',', $cmbPerfil);
         $cantperfil=count(array_filter($aperfil));

         $sqlA="update lab_perfil_prueba
            set habilitado=false,
             idusuariomod=$usuario,
            fechahoramod=date_trunc('seconds', NOW())
            where id_conf_examen_estab=$idconf
            and habilitado=true;";
         $queryA=pg_query($sqlA);
         if ($cantperfil>0){
            $sqlB="select * from lab_perfil_prueba where id_conf_examen_estab=$idconf;";
            for($l=0; $l<$cantperfil; $l++){
               $bandl=0;
               $queryB=  pg_query($sqlB);
               while ($per=@pg_fetch_array($queryB)){
                  if($per['id_perfil']==$aperfil[$l]){
                     $sqlC="update lab_perfil_prueba
                           set habilitado=true,
                            idusuariomod=$usuario,
                           fechahoramod=date_trunc('seconds', NOW())
                           where id_conf_examen_estab=$idconf
                           and id_perfil=$aperfil[$l];";
                     $queryC=  pg_query($sqlC);
                     $bandl=1;
                  }
               }
               if ($bandl==0){
                  $sqlD="insert into lab_perfil_prueba (id_perfil, id_conf_examen_estab, id_establecimiento,  habilitado, fechahorareg, idusuarioreg) values($aperfil[$l], $idconf, $lugar, true,date_trunc('seconds', NOW()),$usuario );";
                  $queryD=  pg_query($sqlD);

               }
            }
         }

         //-***Fin actualizar perfiles


         //**Actualizar los establecimientos a referir
         $acmbEstabReferido = explode(',', $cmbEstabReferido);
         $cancmbEstabReferido=count(array_filter($acmbEstabReferido));
         $sqlA="UPDATE lab_conf_examen_tipo_laboratorio
                set activo= false,
                fecha_fin= current_date
                where id_conf_examen_estab =$idconf
                and activo=true;";
         $queryA=pg_query($sqlA);
         if ($cancmbEstabReferido>0){
            $sqlB="SELECT * from lab_conf_examen_tipo_laboratorio where id_conf_examen_estab=$idconf;";
            for($m=0; $m<$cancmbEstabReferido; $m++){
                $sepestabref = explode('|', $acmbEstabReferido[$m]);
                //var_dump($sepestabref[0]);
               $bandl=0;
               $queryB=  pg_query($sqlB);
               while ($estref=@pg_fetch_array($queryB)){
                  if($estref['id_establecimiento']==$sepestabref[1] && $estref['id_examen_tipo_laboratorio']==$sepestabref[0]){
                     $sqlC="UPDATE lab_conf_examen_tipo_laboratorio
                            set activo=true,
                            fecha_fin=null
                            where id_conf_examen_estab=$idconf
                            and id_establecimiento=$sepestabref[1]
                            and id_examen_tipo_laboratorio=$sepestabref[0];";
                     $queryC=  pg_query($sqlC);
                     $bandl=1;
                  }
               }
               if ($bandl==0){
                  $sqlD="INSERT INTO lab_conf_examen_tipo_laboratorio (id_establecimiento, id_examen_tipo_laboratorio, fecha_inicio, id_conf_examen_estab) values ($sepestabref[1], $sepestabref[0], current_date, $idconf);";
                  $queryD=  pg_query($sqlD);

               }
            }
         }

         //-***Fin actualizar establecimientos a referir


         if (!$result && !$dtSub)
            return false;
         else {

            return true;
         }
      }
   }

   /* function AgregarDatosFijos($idexamen,$idarea,$usuario,$lugar){
     $con = new ConexionBD;
     if($con->conectar()==true)
     {
     $query = "INSERT INTO lab_datosfijosresultado (IdArea,IdExamen,IdUsuarioReg,FechaHoraReg,IdEstablecimiento,FechaIni,FechaFin)
     VALUES ('$idarea','$idexamen',$usuario,NOW(),$lugar,CURDATE(),NULL)";
     // echo $query;
     $result = pg_query($query);



     if (!$result)
     return false;
     else
     return true;
     }

     } */

   //ACTUALIZA UN REGISTRO
   /* function actualizar($idexamen,$idarea,$nomexamen,$idestandar,$observacion,$usuario,$sexo)
     {
     $con = new ConexionBD;
     if($con->conectar()==true)
     {
     $query = "UPDATE lab_examenes SET nombreExamen='$nomexamen',idestandar='$idestandar',idarea='$idarea',Observacion='$observacion',
     IdUsuarioMod='$usuario',FechaHoraMod=NOW(),IdSexo=$sexo WHERE idexamen='$idexamen'";
     $result = pg_query($query);
     if (!$result)
     return false;
     else
     //echo $query;
     return true;
     }
     } */



   //CONSULTA LOS PROGRAMAS
   function consultar_programas() {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $query = "SELECT * FROM mnt_programas ORDER BY IdPrograma";
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function consultar_codigospruebas() {
      //creamos el objeto $con a partir de la clase ConexionBD
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "SELECT id,idestandar,descripcion FROM ctl_examen_servicio_diagnostico ORDER BY idEstandar ";
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   //CONSULTA el catalogo de sexo
   function catalogo_sexo() {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $query = "SELECT id,nombre FROM ctl_sexo where id <>3";
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   //CONSULTA LOS FORMULARIOS POR PROGRAMA
   function consultar_formularios($lugar) {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $query = "SELECT mnt_formularios.id,nombreformulario
				  FROM mnt_formularios
				  INNER JOIN mnt_formulariosxestablecimiento
				  ON mnt_formularios.id=mnt_formulariosxestablecimiento.idformulario
		                  WHERE idestablecimiento=$lugar and mnt_formularios.activo=true";
         // echo $query;
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   //CONSULTA LOS REGISTROS
   function consultar($lugar) {
      //creamos el objeto $con a partir de la clase ConexionBD
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "SELECT lab_examenes.idexamen,lab_examenes.idestandar,lab_examenes.idarea,nombreexamen,descripcion,nombreArea,
                lab_examenesxestablecimiento.idplantilla,observacion,lab_examenes.habilitado,lab_examenesxestablecimiento.condicion,
                lab_examenes.ubicacion,urgente
                FROM lab_examenes
                INNER JOIN lab_areas  on lab_examenes.idarea=lab_areas.id
                INNER JOIN lab_codigosestandar ON lab_examenes.idestandar=lab_codigosestandar.id
                INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id=lab_examenesxestablecimiento.idexamen
                WHERE lab_examenesxestablecimiento.condicion='H' AND lab_examenesxestablecimiento.idestablecimiento=$lugar
                ORDER BY lab_examenes.idarea,lab_examenes.idexamen";
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function Obtener_NombreEstandar($IdEstandar) {
      $con = new ConexionBD;
      if ($con->conectar() == true) {

         $query = "SELECT Descripcion FROM lab_codigosestandar WHERE  IdEstandar='$IdEstandar'";
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

//CONSULTA EXAMEN POR EL CODIGO
   function consultarid($idexamen, $lugar) {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         //$query = "SELECT * FROM lab_examenes WHERE idexamen='$idexamen'";
        $query = "SELECT lab_conf_examen_estab.id,lab_conf_examen_estab.codigo_examen as idexamen,
                    lab_conf_examen_estab.nombre_examen as nombreexamen, ctl_area_servicio_diagnostico.nombrearea,
                    lab_plantilla.id as idplantilla,ctl_examen_servicio_diagnostico.idestandar,
                    (CASE WHEN lab_conf_examen_estab.ubicacion=0 THEN 'Todas las Procedencias'
                    WHEN lab_conf_examen_estab.ubicacion=1 THEN 'Hospitalización y Emergencia'
                    WHEN lab_conf_examen_estab.ubicacion=4 THEN 'Laboratorio' END ) AS Ubicacion, lab_conf_examen_estab.ubicacion as idubicacion,
                    (SELECT id FROM ctl_examen_servicio_diagnostico
                    WHERE lab_conf_examen_estab.idestandarrep=ctl_examen_servicio_diagnostico.id) AS ctlidestandarrep,
                    (SELECT idestandar
                    FROM ctl_examen_servicio_diagnostico
                    WHERE lab_conf_examen_estab.idestandarrep=ctl_examen_servicio_diagnostico.id) AS estandarrep,
                    (SELECT descripcion FROM ctl_examen_servicio_diagnostico
                    WHERE lab_conf_examen_estab.idestandarrep=ctl_examen_servicio_diagnostico.id) AS descestandarrep,
                    lab_conf_examen_estab.impresion,urgente, ctl_sexo.nombre AS nombresexo,lab_conf_examen_estab.condicion,
                    (CASE WHEN lab_conf_examen_estab.condicion='H' THEN 'Habilitado'
                    WHEN lab_conf_examen_estab.condicion='I' THEN 'Inhabilitado' END) AS cond,cit_programacion_exams.rangotiempoprev,
                    mnt_formularios.nombreformulario,mnt_formularios.id as idformulario,lab_plantilla.plantilla,
                    ctl_examen_servicio_diagnostico.descripcion,ctl_sexo.id as idsexo,ctl_sexo.nombre as sexo,mnt_area_examen_establecimiento.id as mntid,
                    id_area_servicio_diagnostico as idarea,
                    (SELECT ARRAY_AGG(m.id) metodologia
                        FROM lab_examen_metodologia em
                        LEFT JOIN lab_metodologia m ON m.id = em.id_metodologia
                        WHERE em.id_conf_exa_estab = $idexamen AND em.activo=true AND em.id_metodologia is not null
                        GROUP BY em.id_conf_exa_estab) as metodologias,
                    (SELECT ARRAY_AGG(m.nombre_metodologia) metodologia
                        FROM lab_examen_metodologia em
                        JOIN lab_metodologia m ON m.id = em.id_metodologia
                        WHERE em.id_conf_exa_estab = $idexamen AND em.activo=true AND em.id_metodologia is not null
                        GROUP BY em.id_conf_exa_estab) as metodologias_text,
                    (SELECT ARRAY_AGG(em.nombre_reporta) metodologia
                        FROM lab_examen_metodologia em
                        LEFT JOIN lab_metodologia m ON m.id = em.id_metodologia
                        WHERE em.id_conf_exa_estab = $idexamen AND em.activo=true AND em.id_metodologia is not null
                        GROUP BY em.id_conf_exa_estab) as id_metodologias_text,
                     (SELECT array_to_string(array_agg(t02.id order by t02.posible_resultado), ',') as posibleresultado
                        from lab_examen_posible_resultado t01
                        join lab_posible_resultado t02 			on (t02.id=t01.id_posible_resultado)
                        where id_conf_examen_estab=$idexamen and t01.habilitado=true)as id_posible_resultado,
                     (SELECT array_to_string(array_agg(posible_resultado order by t02.posible_resultado), ',') as posibleresultado
                        from lab_examen_posible_resultado t01
                        join lab_posible_resultado t02 			on (t02.id=t01.id_posible_resultado)
                        where id_conf_examen_estab=$idexamen and t01.habilitado=true) as posible_resultado,
                     (SELECT array_to_string(array_agg(t02.id order by t02.tipomuestra), ',') as posibleresultado from lab_tipomuestraporexamen t01 join lab_tipomuestra t02 on (t02.id=t01.idtipomuestra) where idexamen=$idexamen and t01.habilitado=true) as id_tipo_muestra,
                     (select array_to_string(array_agg(t02.id order by t02.nombre), ',') from lab_perfil_prueba t01 join lab_perfil  t02 on (t02.id=t01.id_perfil) where id_conf_examen_estab=$idexamen and t01.habilitado=true) as id_perfil,
                    (case when ctl_area_servicio_diagnostico.id=14
                			then (select array_to_string(array_agg((t2.id ||'|'|| t2.id_establecimiento||'|'|| t2.id_examen_tipo_laboratorio ) order by t2.id), ',') as conf_examen_tipo_laboratorio
                            from lab_conf_examen_estab	t1
                            join lab_conf_examen_tipo_laboratorio	t2 on (t1.id=t2.id_conf_examen_estab)
                            where t1.id=$idexamen
                            and (t2.activo=true or fecha_fin >= current_date))
                			else null end) as id_idestab_idexatipolab
                    FROM lab_conf_examen_estab
                    INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                    INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
                    INNER JOIN ctl_examen_servicio_diagnostico ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico=ctl_examen_servicio_diagnostico.id
                    LEFT JOIN mnt_formularios ON lab_conf_examen_estab.idformulario=mnt_formularios.id
                    INNER JOIN lab_plantilla ON lab_conf_examen_estab.idplantilla=lab_plantilla.id
                    LEFT JOIN ctl_sexo ON lab_conf_examen_estab.idsexo= ctl_sexo.id
                    INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
                    LEFT JOIN cit_programacion_exams ON lab_conf_examen_estab.id=cit_programacion_exams.id_examen_establecimiento
                    WHERE lab_areasxestablecimiento.condicion='H' AND lab_areasxestablecimiento.idestablecimiento=$lugar
                    AND lab_conf_examen_estab.id=$idexamen";

         // echo $query;
         $result = pg_query($query);
         if (!$result)
            return false;
         else
         //echo $query;
            return $result;
      }
   }

//OBTENER PLANTILLAS
   function LeerPlantilla() {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $query = "SELECT id,plantilla from lab_plantilla";
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

//FUNCION PARA LEER EL ULTIMO CODIGO INSERTADO
   function LeerUltimoCodigo($idarea) {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $query = "SELECT codigo_examen FROM lab_conf_examen_estab
               INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
               INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico = ctl_area_servicio_diagnostico.id
               WHERE ctl_area_servicio_diagnostico.id=$idarea
               ORDER BY lab_conf_examen_estab.codigo_examen DESC LIMIT 1";
         //  echo $query;
         $result = pg_query($query);

         if (!$result)
            return false;
         else
            $row = pg_fetch_array($result);
         return $row[0];
      }
   }

   /*    * ******************************************FUNCIONES PARA MANEJO DE PAGINACION****************************** */

   //consultando el numero de registros de la tabla
   function NumeroDeRegistros($lugar) {
      //creamos el objeto $con a partir de la clase ConexionBD
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "SELECT *
                FROM lab_conf_examen_estab
                INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
                INNER JOIN ctl_examen_servicio_diagnostico ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico=ctl_examen_servicio_diagnostico.id
                LEFT JOIN mnt_formularios ON lab_conf_examen_estab.idformulario=mnt_formularios.id
                INNER JOIN lab_plantilla ON lab_conf_examen_estab.idplantilla=lab_plantilla.id
                LEFT JOIN ctl_sexo ON lab_conf_examen_estab.idsexo= ctl_sexo.id
                INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
                LEFT JOIN cit_programacion_exams ON lab_conf_examen_estab.id=cit_programacion_exams.id_examen_establecimiento
                WHERE lab_areasxestablecimiento.condicion='H' AND ctl_examen_servicio_diagnostico.activo= TRUE AND mnt_area_examen_establecimiento.activo=TRUE AND mnt_area_examen_establecimiento.id_establecimiento=$lugar
                AND lab_areasxestablecimiento.idestablecimiento=$lugar
                ORDER BY ctl_area_servicio_diagnostico.idarea,lab_conf_examen_estab.nombre_examen";

         // echo $query;

         $numreg = pg_num_rows(pg_query($query));
         if (!$numreg)
            return false;
         else
            return $numreg;
      }
   }

   function NumeroDeRegistrosbus($query_search) {
      //creamos el objeto $con a partir de la clase ConexionBD
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = $query_search;
         $numreg = pg_num_rows(pg_query($query));
         if (!$numreg)
            return false;
         else
            return $numreg;
      }
   }

   function consultarpagbus($query_search, $RegistrosAEmpezar,
           $RegistrosAMostrar) {
      //creamos el objeto $con a partir de la clase ConexionBD
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = $query_search . " LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function ObtenerCodigo($idarea) {
      //creamos el objeto $con a partir de la clase ConexionBD
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "SELECT idarea FROM ctl_area_servicio_diagnostico WHERE id=$idarea";
         // echo $query;
         $result = pg_fetch_array(pg_query($query));
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function BuscarExamen($idexamen, $lugar) {
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "SELECT count( * ) FROM ctl_examen_servicio_diagnostico
                 INNER JOIN mnt_area_examen_establecimiento
                 ON ctl_examen_servicio_diagnostico.id=mnt_area_examen_establecimiento.id_examen_servicio_diagnostico
                 INNER JOIN lab_conf_examen_estab
                 ON mnt_area_examen_establecimiento.id = lab_conf_examen_estab.idexamen
                 WHERE lab_conf_examen_estab.codigo_examen ='$idexamen'
                 AND mnt_area_examen_establecimiento.id_establecimiento =$lugar";

         // echo $query;
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function consultar_estandar() {
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "SELECT ctl_examen_servicio_diagnostico.id,idestandar,descripcion
                  FROM ctl_examen_servicio_diagnostico
                  INNER JOIN lab_estandarxgrupo ON lab_estandarxgrupo.id=ctl_examen_servicio_diagnostico.idgrupo
                  WHERE id_atencion=98 AND ctl_examen_servicio_diagnostico.activo=TRUE AND lab_estandarxgrupo.activo=TRUE
                  ORDER BY SUBSTRING(idestandar FROM '[a-zA-Z]+'), TO_NUMBER(SUBSTRING(idestandar FROM '[0-9]+'), '99')";
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function consultarpag($lugar, $RegistrosAEmpezar, $RegistrosAMostrar) {
      //creamos el objeto $con a partir de la clase ConexionBD
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
        $query = "SELECT lab_conf_examen_estab.id,lab_conf_examen_estab.codigo_examen as idexamen, lab_conf_examen_estab.nombre_examen as nombreexamen, ctl_area_servicio_diagnostico.nombrearea,lab_plantilla.idplantilla, ctl_examen_servicio_diagnostico.idestandar, (CASE WHEN lab_conf_examen_estab.ubicacion=0 THEN 'Todas las Procedencias' WHEN lab_conf_examen_estab.ubicacion=1 THEN 'Hospitalización y Emergencia' WHEN lab_conf_examen_estab.ubicacion=3 THEN 'Ninguna' WHEN lab_conf_examen_estab.ubicacion=4 THEN 'Laboratorio' END ) AS Ubicacion,
        (SELECT idestandar FROM ctl_examen_servicio_diagnostico WHERE lab_conf_examen_estab.idestandarrep=ctl_examen_servicio_diagnostico.id) AS estandarrep, (SELECT descripcion FROM ctl_examen_servicio_diagnostico WHERE lab_conf_examen_estab.idestandarrep=ctl_examen_servicio_diagnostico.id) AS descestandarrep, lab_conf_examen_estab.impresion,urgente, ctl_sexo.nombre AS nombresexo,lab_conf_examen_estab.condicion, (CASE WHEN lab_conf_examen_estab.condicion='H' THEN 'Habilitado' WHEN lab_conf_examen_estab.condicion='I' THEN 'Inhabilitado' END) AS cond,cit_programacion_exams.rangotiempoprev, ctl_examen_servicio_diagnostico.descripcion,mnt_formularios.id as idformulario, (SELECT nombreformulario FROM mnt_formularios WHERE mnt_formularios.id=lab_conf_examen_estab.idformulario) AS nombreformulario
        FROM lab_conf_examen_estab INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
        INNER JOIN ctl_examen_servicio_diagnostico ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico=ctl_examen_servicio_diagnostico.id
        LEFT JOIN mnt_formularios ON mnt_formularios.id=lab_conf_examen_estab.idformulario
        INNER JOIN lab_plantilla ON lab_conf_examen_estab.idplantilla=lab_plantilla.id
        LEFT JOIN ctl_sexo ON lab_conf_examen_estab.idsexo= ctl_sexo.id
        INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
        LEFT JOIN cit_programacion_exams ON lab_conf_examen_estab.id=cit_programacion_exams.id_examen_establecimiento
        WHERE lab_areasxestablecimiento.condicion='H' AND ctl_examen_servicio_diagnostico.activo= TRUE
        AND mnt_area_examen_establecimiento.activo=TRUE AND mnt_area_examen_establecimiento.id_establecimiento=$lugar
        ORDER BY ctl_area_servicio_diagnostico.idarea,lab_conf_examen_estab.nombre_examen
        LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";

         // echo $query;
         $result = pg_query($query);

         if (!$result)
            return false;
         else
            return $result;
         // echo $query;
      }
   }

   function EstadoCuenta($idexamen, $cond, $lugar) {
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         if ($cond == 'H') {
            $query = "UPDATE lab_conf_examen_estab SET condicion='I' WHERE id=$idexamen";
            $result = pg_query($query);
            //  $query1= "UPDATE lab_examenes SET Habilitado='N' WHERE IdExamen='$idexamen'" ;
            // $result1 = pg_query($query1);
         }
         if ($cond == 'I') {
            $query = "UPDATE lab_conf_examen_estab SET condicion='H' WHERE id=$idexamen";
            $result = pg_query($query);
            // $query1= "UPDATE lab_examenes SET Habilitado='S' WHERE IdExamen='$idexamen'";
            //$result1 = pg_query($query1);
         }
      }
      $con->desconectar();
   }

   function obtener_letra($idarea) {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $query = "SELECT ASCII(impresion)
                          FROM lab_conf_examen_estab
                          INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                          INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
                          WHERE ctl_area_servicio_diagnostico.id=$idarea AND impresion<>'G'
                          ORDER BY Impresion DESC LIMIT 1";
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function ExamenesPorArea($idarea, $lugar) {
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "SELECT mnt_area_examen_establecimiento.id,ctl_examen_servicio_diagnostico.descripcion,ctl_examen_servicio_diagnostico.idestandar
                        FROM  mnt_area_examen_establecimiento
                        INNER JOIN ctl_examen_servicio_diagnostico ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico=ctl_examen_servicio_diagnostico.id
                        WHERE  mnt_area_examen_establecimiento.activo=TRUE AND mnt_area_examen_establecimiento.id_area_servicio_diagnostico=$idarea AND id_establecimiento=$lugar
                        ORDER BY SUBSTRING(idestandar FROM '[a-zA-Z]+'), TO_NUMBER(SUBSTRING(idestandar FROM '[0-9]+'), '99')";

         //  echo $query;
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

//*******************************************FIN FUNCIONES PARA MANEJO DE PAGINACION************************************************/


   function metodologias() {
      /*
       * Julio Castillo
       */
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "SELECT m.id as id_metodologia,
                                m.nombre_metodologia metodologias
                        FROM lab_metodologia m
                        WHERE m.activa=true
                        ORDER BY m.nombre_metodologia";

         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function examen_metodologia($id_examen) {
      /*
       * Julio Castillo
       */
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "SELECT m.id as id_metodologia,
                                (CASE WHEN (em.id_metodologia IS NULL) then m.nombre_metodologia ELSE '' END) metodologias,
                                (SELECT nombre_metodologia FROM lab_metodologia WHERE id=em.id_metodologia) metodologias_sel,
                                (SELECT CONCAT(idestandar,'-',descripcion)
                                    FROM ctl_examen_servicio_diagnostico t01, lab_conf_examen_estab t02
                                    WHERE t01.id=t02.idestandarrep AND t02.id=$id_examen) nombre_prueba,
                                '$id_examen' AS idexamen
                        FROM lab_metodologia m
                        LEFT JOIN (SELECT id_metodologia from lab_examen_metodologia
                                    WHERE id_conf_exa_estab =$id_examen) em ON em.id_metodologia = m.id
                        WHERE m.activa IS TRUE
                        GROUP BY m.id, em.id_metodologia
                        ORDER BY m.nombre_metodologia";

         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function prueba_lab($id_examen) {

      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "SELECT nombre_examen as nombre_prueba FROM lab_conf_examen_estab WHERE id=$id_examen";

         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function examen_metodologia_add($id_examen, $id_metodologia) {
      /*
       * Julio Castillo
       */
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "INSERT INTO lab_examen_metodologia(id_conf_exa_estab,id_metodologia,activo,fecha_inicio,fecha_fin) VALUES ($id_examen, $id_metodologia, true, NOW(), NULL)";

         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function examen_metodologia_del($id_examen, $id_metodologia) {
      /*
       * Julio Castillo
       */
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "DELETE FROM lab_examen_metodologia WHERE id_conf_exa_estab = $id_examen AND id_metodologia = $id_metodologia";

         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   /*
    * Moy
    */

   function cambiar_estadolab_exaposible_resultado($idconf) {

      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "update lab_examen_posible_resultado set habilitado = false where id_conf_examen_estab= $idconf";
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function cambiar_estado_idlab_posibleresultado($id_posible_resultado, $idconf) {

      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "UPDATE lab_examen_posible_resultado
                        SET habilitado = true,
                            fechafin = null,
                            id_user_mod = 8,
                            fecha_mod = now()
                        WHERE id_posible_resultado = '$id_posible_resultado' AND id_conf_examen_estab='$idconf'";
         $result = pg_query($query);
         if (pg_affected_rows($result) == 0) {
            $query = "INSERT INTO lab_examen_posible_resultado(
                            id_conf_examen_estab,
                            id_posible_resultado,
                            fechainicio, fechafin,
                            habilitado,
                            id_user,
                            fecha_registro,
                            id_user_mod,
                            fecha_mod)
                    VALUES ('$idconf', '$id_posible_resultado', now(), null,
                            true, 8, date_trunc('seconds',NOW()), null, null)";
            $result = pg_query($query);
         }
      }
   }

   function resultados1($idconf) {

      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "select 	t01.id,
                  t01.posible_resultado resultado
                  from lab_posible_resultado t01
                  left join  (select  id,
                                  id_conf_examen_estab,
                                  id_posible_resultado,
                                  habilitado from lab_examen_posible_resultado t02
                                  where t02.id_conf_examen_estab=$idconf and t02.habilitado= true )


                                  t02 on t02.id_posible_resultado=t01.id
                                                                  WHERE t02.id is null
						ORDER BY t01.posible_resultado";


         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function resultados() {

      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = /* "select 	t01.id,
                   t01.posible_resultado resultado
                   from lab_posible_resultado t01
                   left join  (select  id,
                   id_conf_examen_estab,
                   id_posible_resultado,
                   habilitado from lab_examen_posible_resultado t02
                   where t02.id_conf_examen_estab=$idproce and t02.habilitado= true )


                   t02 on t02.id_posible_resultado=t01.id
                   WHERE t02.id is null
                   ORDER BY t01.posible_resultado"; */
                 "select * from lab_posible_resultado lpr
where habilitado=true
and fechafin is null or date(fechafin)>=current_date
order by posible_resultado;";

         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   function resultados_seleccionados($idconf) {

      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = /* "select t02.id, t02.posible_resultado resultado, t03.nombreprocedimiento  from lab_procedimiento_posible_resultado t01
                   inner join lab_posible_resultado 	t02 on (t02.id=t01.id_posible_resultado)
                   inner join lab_procedimientosporexamen t03 on (t03.id=t01.id_procedimientoporexamen)
                   where t03.id=$idproce";
                    */



                 "select t02.id, t02.posible_resultado resultado,
                t03.nombre_examen
                from lab_examen_posible_resultado   t01
                inner join lab_posible_resultado    t02 on (t02.id=t01.id_posible_resultado)
                inner join lab_conf_examen_estab    t03 on (t03.id=t01.id_conf_examen_estab)
                where t01.id_conf_examen_estab=$idconf AND t01.habilitado is true ";

         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

   //fn pg
   function buscarcodigores() {
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if ($con->conectar() == true) {
         $query = "select * from lab_codigosresultados order by id;";

         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

    //Funcion utilizada para seleccionar los tipos de muestras
   function tipo_muestra() {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $query = "select * from lab_tipomuestra where habilitado=true order by tipomuestra";
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

    //Funcion utilizada para seleccionar los perfiles
   function perfil() {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $query = "select * from lab_perfil";
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

    //Funcion utilizada para seleccionar los perfiles
   function establecimiento_referido($idmntareaexest) {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
        $query = "select t2.id as id_examen_tipo_laboratorio, t7.id as id_establecimiento, (t7.nombre||' ('||t4.nombre||')') as nombre_estab
                from ctl_examen_servicio_diagnostico	 	t1
                join lab_examen_tipo_laboratorio	 	t2 on (t1.id=t2.id_examen_servicio_diagnostico)
                join lab_tipo_estab_tipo_lab		 	t3 on (t3.id=t2.id_tipo_estab_tipo_lab)
                join ctl_tipo_laboratorio 		 	t4 on (t4.id=t3.id_tipo_laboratorio)
                join lab_establecimiento_tipo_laboratorio	t5 on (t4.id=t5.id_tipo_laboratorio)
                join lab_conf_establecimiento_tipo_laboratorio	t6 on (t5.id=t6.id_establecimiento_tipo_laboratorio)
                join ctl_establecimiento 			t7 on (t7.id=t6.id_establecimiento and t7.id_tipo_establecimiento=t3.id_tipo_establecimiento)
                where t1.id= (select id_examen_servicio_diagnostico
                            from mnt_area_examen_establecimiento
                            where id=$idmntareaexest)
                and t6.activo=true
                order by 3;";

         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
      }
   }

}

//CLASE

class clsLabor_Examenes {

   //INSERTA UN REGISTRO
   function insertar_labo($idexamen, $idarea, $nomexamen, $idestandar,
           $plantilla, $observacion, $activo, $ubicacion, $usuario) {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $query = "INSERT INTO laboratorio.lab_examenes(idexamen,IdArea,nombreExamen,IdEstandar,Observacion,IdPlantilla,Habilitado,Ubicacion,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) VALUES('$idexamen','$idarea','$nomexamen','$idestandar','$observacion','$plantilla','$activo',$ubicacion,$usuario,NOW(),$usuario,NOW())";
         $result = pg_query($query);

         if (!$result)
            return false;
         else
            return true;
      }
   }

   //ACTUALIZA UN REGISTRO
   function actualizar_labo($idexamen, $idarea, $nomexamen, $idestandar,
           $observacion, $plantilla, $ubicacion, $usuario) {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $query = "UPDATE laboratorio.lab_examenes SET nombreExamen='$nomexamen' , idestandar='$idestandar', idarea='$idarea', IdPlantilla='$plantilla',Observacion='$observacion',Ubicacion=$ubicacion,IdUsuarioMod='$usuario', FechaHoraMod=NOW() WHERE idexamen='$idexamen'";
         $result = pg_query($query);
         if (!$result)
            return false;
         else
            return true;
      }
   }

}

?>
