<?php

session_start();
include_once("clsLab_Examenes.php");
$usuario = $_SESSION['Correlativo'];
$lugar = $_SESSION['Lugar'];
$area = $_SESSION['Idarea'];

$objdatos = new clsLab_Examenes;
$Clases = new clsLabor_Examenes;

//$Pag =$_POST['Pag'];
$opcion = $_POST['opcion'];

//actualiza los datos del empleados
//echo $cond;
//echo $ubicacion;
switch ($opcion) {
   case 1:  //INSERTAR
      $resultado = $_POST['resultado'];
      $resultado_nombre = $_POST['resultado_nombre'];
      $id_resultado = $_POST['id_resultado'];
      $mismo = $_POST['mismo'];
      $idexamen = $_POST['idexamen'];
      $idarea = $_POST['idarea'];
      $nomexamen = $_POST['nomexamen'];
      //  $nota=(empty($_POST['nota'])) ? 'NULL' : "'" . pg_escape_string($_POST['nota']) . "'";
      $idestandar = $_POST['idestandar'];
      //echo $idestandar;
      $plantilla = $_POST['plantilla'];
      //$observacion=(empty($_POST['observacion']))? 'NULL' : "'" . pg_escape_string($_POST['observacion']). "'";
      $ubicacion = $_POST['ubicacion'];
      $Idrio = $_POST['idrio'];
      $IdEstandarResp = $_POST['idestandarRep'];
      //echo $IdEstandarResp." idPlantilla=".$plantilla;
      $etiqueta = $_POST['etiqueta'];
      $Urgente = $_POST['urgente'];
      $sexo = $_POST['sexo'];
      $metodologias_sel = $_POST['metodologias_sel'];
      $text_metodologias_sel = $_POST['text_metodologias_sel'];
      $id_metodologias_sel = $_POST['id_metodologias_sel'];
      $cmbTipoMuestra = $_POST['cmbTipoMuestra'];
      $cmbPerfil = $_POST['cmbPerfil'];
      $cmbEstabReferido = $_POST['cmbEstabReferido'];
      $RepResultado = $_POST['RepResultado'];
      $cmbRealizadopor = $_POST['cmbRealizadopor'];
      //echo $sexo;
      if ($sexo <> 4)
         $idsexo = $sexo;
      else
         $idsexo = 'NULL';
      // echo $idsexo;
      $Hab = $_POST['Hab'];

      $TiempoPrevio = $_POST['tiempoprevio'];
      //Echo $sexo."-".$idestandar."-". $Hab;
      // $cond='H';
      if ($etiqueta == "O") {
         $dato = $objdatos->obtener_letra($idarea);
         $rowletra = pg_fetch_array($dato);
         $rletra = $rowletra[0];
         if (!empty($rletra)) {
            $num = $rletra + 1;
            if ($num == 71) {
               $num = $num + 1;
               $letra = chr($num);
               //echo $letra;
            } else {
               $letra = chr($num);
            }
         } else {
            $num = 65; //"Letra A";
            $letra = chr($num);
         }
      } else {
         $letra = $etiqueta;
      }

      if ($resultado <> "") {
         If ($objdatos->IngExamenxEstablecimiento($idexamen, $nomexamen, $Hab,
                         $usuario, $Idrio, $IdEstandarResp, $plantilla,
                         $letra, $Urgente, $ubicacion, $TiempoPrevio, $idsexo,
                         $idestandar, $lugar, $metodologias_sel,
                         $text_metodologias_sel, $id_metodologias_sel,
                         $resultado, $id_resultado, $cmbTipoMuestra, $cmbPerfil, $cmbEstabReferido,$RepResultado,$cmbRealizadopor) == true) {
            // asignar_resultados($resultado);
            echo "Registro Agregado";
         } else {
            echo "No se pudo Ingresar el Registro";
         }
      } else {
         $resultado = 'NULL';
         $id_resultado = 'NULL';

         If ($objdatos->IngExamenxEstablecimiento($idexamen, $nomexamen, $Hab,
                         $usuario, $Idrio, $IdEstandarResp, $plantilla,
                         $letra, $Urgente, $ubicacion, $TiempoPrevio, $idsexo,
                         $idestandar, $lugar, $metodologias_sel,
                         $text_metodologias_sel, $id_metodologias_sel,
                         $resultado, $id_resultado, $cmbTipoMuestra, $cmbPerfil, $cmbEstabReferido,$RepResultado, $cmbRealizadopor) == true) {
            // asignar_resultados($resultado);
            echo "Registro Agregado";
         } else {
            echo "No se pudo Ingresar el Registro";
         }
      }//fin else
      //  echo $Idrio;



      /*  if ($resultado <>"")
        {

        $objdatos ->posible_resultados($resultado);
        // echo "sii";
        } */
      //  asignar_resultados($resultado);
      break;




   case 2:  //MODIFICAR
      $idexamen = $_POST['idexamen'];
      $idarea = $_POST['idarea'];
      $nomexamen = $_POST['nomexamen'];
      $idestandar = $_POST['idestandar'];
      $plantilla = $_POST['plantilla'];
      //$observacion=$_POST['observacion'];
      $ubicacion = $_POST['ubicacion'];
      $Idrio = $_POST['idformulario'];
      $IdEstandarResp = $_POST['idestandarRep'];
      $etiqueta = $_POST['Etiqueta'];
      $Urgente = $_POST['urgente'];
      $sexo = $_POST['idsexo'];
      $metodologias_sel = $_POST['metodologias_sel'];
      $text_metodologias_sel = $_POST['text_metodologias_sel'];
      $id_metodologias_sel = $_POST['id_metodologias_sel'];
      $resultado = $_POST['resultado'];
      $resultado_nombre = $_POST['resultado_nombre'];
      $id_resultado = $_POST['id_resultado'];
      $cmbTipoMuestra = $_POST['cmbTipoMuestra'];
      $cmbPerfil = $_POST['cmbPerfil'];
      $cmbEstabReferido = $_POST['cmbEstabReferido'];
      $RepResultado = $_POST['RepResultado'];
      $cmbRealizadopor = $_POST['cmbRealizadopor'];
      //  echo $IdEstandarResp." sexo=".$sexo;
      if ($sexo <> 4)
         $idsexo = $sexo;
      else
         $idsexo = 'NULL';
      //echo $idsexo;
      $Hab = $_POST['Hab'];
      $TiempoPrevio = $_POST['Tiempo'];
      $idconf = $_POST['idconf'];
      $ctlidestandar = $_POST['ctlidestandar'];
      $letra = "";

      if ($resultado == "") {
         $resultado = 'NULL';
         $id_resultado = 'NULL';
      }
      if ($etiqueta == "O") {
         $dato = $objdatos->obtener_letra($idarea);
         $rowletra = pg_fetch_array($dato);
         $rletra = $rowletra[0];

         if (!empty($rletra)) {
            $num = $rletra + 1;
            if ($num == 71) {
               $num = $num + 1;
            }
            $letra = chr($num);
         } else {
            $num = 65;
            $letra = chr($num);
         }
      }

      if ($etiqueta == "G") {
         $num = 71;
         $letra = $etiqueta;
      }

      // echo $idexamen."-".$lugar."-".$usuario."-".$Idrio."-".$IdEstandarResp."-".$plantilla."-".$letra."-".$Urgente."-".$ubicacion;
      If ($objdatos->ActExamenxEstablecimiento($idconf, $nomexamen, $lugar,
                      $usuario, $Idrio, $IdEstandarResp, $plantilla,
                      $letra, $Urgente, $ubicacion, $Hab, $TiempoPrevio,
                      $idsexo, $idestandar, $ctlidestandar, $metodologias_sel,
                      $text_metodologias_sel, $id_metodologias_sel, $resultado, $id_resultado, $cmbTipoMuestra, $cmbPerfil, $cmbEstabReferido,$RepResultado, $cmbRealizadopor) == true) {
         /*
          * creando arreglo de elementos seleccionados
          */


         echo "Registro Actualizado";
      } else {
         echo "No se pudo actualizar en Registro";
      }

      break;
   case 3:
      $cond = $_POST['condicion'];
      $idexamen = $_POST['idexamen'];
      //	echo $idexamen."-".$condicion;
      //$resultado=Estado::EstadoCuenta($idexamen,$cond,$lugar);
      $resultado = $objdatos->EstadoCuenta($idexamen, $cond, $lugar);
      break;
   case 4:// PAGINACION
      //para manejo de la paginacion

      $RegistrosAMostrar = 10;
      $RegistrosAEmpezar = ($_POST['Pag'] - 1) * $RegistrosAMostrar;
      $PagAct = $_POST['Pag'];

      /////LAMANDO LA FUNCION DE LA CLASE
      $consulta = $objdatos->consultarpag($lugar, $RegistrosAEmpezar,
              $RegistrosAMostrar);

      //muestra los datos consultados en la tabla
      echo "<center >
               <table border = 1 style='width: 90%;'   class='table table-hover table-bordered table-condensed table-white' >
	           <thead>
                        <tr>
                            <th aling='center' > Modificar</th>
                            <th aling='center' '> Habilitado</th>
                            <th > C&oacute;digo Examen </th>
                            <th > Nombre Examen </th>
                            <th > &Aacute;rea</th>
                            <th >Plantilla</th>
                            <th >C&oacute;digo del Est&aacute;ndar</th>
                            <th >Solicitado en</th>
     			    <th >rio</th>
			    <th >Tabulador</th>
                            <th >Tipo Viñeta</th>
                            <th >Urgente</th>
                            <th >Sexo</th>
                            <th >Tiempo Previo</th>
		     </tr>
                   </thead><tbody>
                    </center>";
      while ($row = pg_fetch_array($consulta)) {
         echo "<tr>
                            <td aling='center'>
				<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\"
				onclick=\"pedirDatos('" . $row['id'] . "')\"> </td>
                            <td style='text-decoration:underline;cursor:pointer;' " .
         "onclick='Estado(\"" . $row['id'] . "\",\"" . $row['condicion'] . "\")'>" . $row['cond'] . "</td>
                            <td>" . $row['idexamen'] . " </td>
                            <td>" . htmlentities($row['nombreexamen']) . " </td>
                            <td>" . htmlentities($row['nombrearea']) . " </td>
                            <td>" . htmlentities($row['idplantilla']) . " </td>
                            <td>" . htmlentities($row['idestandar']) . " </td>
                            <td>" . htmlentities($row['ubicacion']) . "</td>";

         if (!empty($row['nombreformulario']))
            echo"<td>" . htmlentities($row['nombreformulario']) . " </td> ";
         else
            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
         echo "<td>" . htmlentities($row['estandarrep']) . " </td>";
         if ($row['impresion'] == 'G')
            echo"<td>General</td>";
         else
            echo"<td>Especial </td>";
         if ($row['urgente'] == 0)
            echo"<td>NO</td>";
         else
            echo"<td>SI</td>";
         if (!empty($row['nombresexo']))
            echo"<td>" . htmlentities($row['nombresexo']) . " </td>";
         else
            echo "<td>Ambos</td>";
         if (!empty($row['rangotiempoprev']))
            echo"<td>" . $row['rangotiempoprev'] . " </td><tr>";
         else
            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><tr>";
      }
      echo "</table>";

      //determinando el numero de paginas
      $NroRegistros = $objdatos->NumeroDeRegistros($lugar);
      // echo $PagAct;
      $PagAnt = $PagAct - 1;
      $PagSig = $PagAct + 1;

      $PagUlt = $NroRegistros / $RegistrosAMostrar;
      // echo "NumRegistros=".$NroRegistros." Ultima=".$PagUlt." a mostrar ".$RegistrosAMostrar;
      //verificamos residuo para ver si llevar� decimales
      $Res = $NroRegistros % $RegistrosAMostrar;
      //si hay residuo usamos funcion floor para que me
      //devuelva la parte entera, SIN REDONDEAR, y le sumamos
      //una unidad para obtener la ultima pagina
      if ($Res > 0)
         $PagUlt = floor($PagUlt) + 1;

      echo "<table align='center'>
		       <tr>
			   <td colspan=3 align='center'> <strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong> </td>
			   </tr>
			   <tr>
			   <td>
			   <a onclick=\"show_event('1')\">Primero</a> </td>";
      //// desplazamiento

      if ($PagAct > 1)
         echo "<td> <a onclick=\"show_event('$PagAnt')\">Anterior</a> </td>";
      if ($PagAct < $PagUlt)
         echo "<td> <a onclick=\"show_event('$PagSig')\">Siguiente</a> </td>";
      echo "<td> <a onclick=\"show_event('$PagUlt')\">Ultimo</a></td>";
      echo "</tr>
			  </table>";


      echo " <center> <ul class='pagination'>";
      for ($i = 1; $i <= $PagUlt; $i++) {

         echo " <li ><a  href='javascript: show_event(" . $i . ")'>$i</a></li>";
      }
      echo " </ul></center>";
      break;
   case 5:// Se genera el Código del Examenen
      $idarea = $_POST['idarea'];
      // echo $idarea;
      $idarea1 = $objdatos->ObtenerCodigo($idarea);
      $area = $idarea1[0];
      $cod = $objdatos->LeerUltimoCodigo($idarea);
      $dato = substr("$cod", -3);
      $val = (int) $dato;
      //echo $val;
      $consulta = $val + 1;
      if ($consulta >= 0 && $consulta <= 9) {
         $codigo = $area . '00' . $consulta;
      }
      if ($consulta >= 10 && $consulta <= 99) {
         $codigo = $area . '0' . $consulta;
         //document.frmnuevo.txtidexamen.value=idArea+'0'+numero;
      }
      if ($consulta >= 100 && $consulta <= 999) {
         $codigo = $area . $consulta;
         //document.frmnuevo.txtidexamen.value=idArea+numero;
      }
      echo "<input type='text' id='txtidexamen' style='width:250px'  name='txtidexamen' value='" . $codigo . "'  />";

      break;
   case 6:
      $idarea = $_POST['idarea'];

      //echo "combo".$idarea;
      $rslts = '';
      $consultaex = $objdatos->ExamenesPorArea($idarea, $lugar);
      //$dtMed=$obj->LlenarSubServ($proce);

      $rslts = '<select name="cmbEstandar" id="cmbEstandar" size="1" style="width:75%" class="js-example-basic-single" onchange="cargaestablecimientoaref();revisarsiexisten(this.value)">';
      $rslts .='<option value="0">Seleccione un Examen...</option>';

      while ($rows = pg_fetch_array($consultaex)) {
         $rslts.= '<option value="' . $rows[0] . '" >' . $rows['idestandar'] . '-' . htmlentities($rows[1]) . '</option>';
      }

      $rslts .= '</select>';
      echo $rslts;


      break;
   case 7: //BUSQUEDA
      $idexamen = $_POST['idexamen'];
      $idarea = $_POST['idarea'];
      $nomexamen = $_POST['nomexamen'];
      $idestandar = $_POST['idestandar'];
      $plantilla = $_POST['plantilla'];
      //$observacion=$_POST['observacion'];
      $Urgente = $_POST['urgente'];
      $ubicacion = $_POST['ubicacion'];
      $cond = $_POST['condicion'];
      $IdFormulario = $_POST['idformulario'];
      $IdEstandarResp = $_POST['idestandarRep'];
      $etiqueta = $_POST['etiqueta'];
      $sexo = $_POST['sexo'];
      if ($sexo <> 4)
         $idsexo = $sexo;
      else
         $idsexo = 'NULL';
      //  echo $idsexo;
      $Hab = $_POST['Hab'];
      //   echo $idestandar;
      //echo $etiqueta;
      $conExam = $objdatos->BuscarExamen($idexamen, $lugar);

      //print_r ($conExam);
      $ExisExa = pg_fetch_array($conExam);
      //print_r ($ExisExa[0]);
      //echo $ExisExa[0];
      $query = "SELECT lab_conf_examen_estab.id,lab_conf_examen_estab.codigo_examen as idexamen,
                                            lab_conf_examen_estab.nombre_examen as nombreexamen, ctl_area_servicio_diagnostico.nombrearea,lab_plantilla.idplantilla,
                                            ctl_examen_servicio_diagnostico.idestandar,
                                            (CASE WHEN lab_conf_examen_estab.ubicacion=0 THEN 'Todas las Procedencias'
                                            WHEN lab_conf_examen_estab.ubicacion=1 THEN 'Hospitalización y Emergencia'
                                            WHEN lab_conf_examen_estab.ubicacion=3 THEN 'Ninguna'
                                            WHEN lab_conf_examen_estab.ubicacion=4 THEN 'Laboratorio' END ) AS Ubicacion,
                                            (SELECT idestandar FROM ctl_examen_servicio_diagnostico
                                            WHERE lab_conf_examen_estab.idestandarrep=ctl_examen_servicio_diagnostico.id) AS estandarrep,
                                            lab_conf_examen_estab.impresion,urgente, ctl_sexo.nombre AS nombresexo,lab_conf_examen_estab.condicion,
                                            (CASE WHEN lab_conf_examen_estab.condicion='H' THEN 'Habilitado'
                                            WHEN lab_conf_examen_estab.condicion='I' THEN 'Inhabilitado' END) AS cond,cit_programacion_exams.rangotiempoprev,nombreformulario
                                            FROM lab_conf_examen_estab
                                            INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                                            INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
                                            INNER JOIN ctl_examen_servicio_diagnostico ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico=ctl_examen_servicio_diagnostico.id
                                            LEFT JOIN mnt_formulariosxestablecimiento ON mnt_formulariosxestablecimiento.id= lab_conf_examen_estab.idformulario
                                            LEFT JOIN mnt_formularios on mnt_formularios.id=mnt_formulariosxestablecimiento.idformulario
                                            INNER JOIN lab_plantilla ON lab_conf_examen_estab.idplantilla=lab_plantilla.id
                                            LEFT JOIN ctl_sexo ON lab_conf_examen_estab.idsexo= ctl_sexo.id
                                            INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
                                            LEFT JOIN cit_programacion_exams ON lab_conf_examen_estab.id=cit_programacion_exams.id_examen_establecimiento
                                            WHERE lab_areasxestablecimiento.condicion='H' AND ctl_examen_servicio_diagnostico.activo= TRUE
                                            AND mnt_area_examen_establecimiento.activo=TRUE AND lab_areasxestablecimiento.idestablecimiento=$lugar AND ";
      $ban = 0;

      //VERIFICANDO LOS POST ENVIADOS
      // echo $ExisExa[0];
      if ($ExisExa[0] > 0) {//si existe el examen
         if (!empty($_POST['idexamen'])) {
            $query .= " lab_conf_examen_estab.codigo_examen='" . $_POST['idexamen'] . "' AND";
         }
      }
      if (!empty($_POST['nomexamen'])) {
         $query .= " nombre_examen ilike'%" . $_POST['nomexamen'] . "%' AND";
      }

      if (!empty($_POST['idarea'])) {
         $query .= " ctl_area_servicio_diagnostico.id=" . $_POST['idarea'] . "   AND";
      }

      if (!empty($_POST['plantilla'])) {
         $query .= " lab_conf_examen_estab.idplantilla=" . $_POST['plantilla'] . " AND";
      }

      if (!empty($_POST['idestandar'])) {
         $query .= " mnt_area_examen_establecimiento.id=" . $_POST['idestandar'] . " AND";
      }

      if (!empty($_POST['idestandarRep'])) {
         $query .= " lab_conf_examen_estab.idestandarrep=" . $_POST['idestandarRep'] . " AND";
      }

      if (!empty($_POST['idformulario'])) {
         $query .= " lab_conf_examen_estab.idformulario='" . $_POST['idformulario'] . "' AND";
      }

      if (!empty($_POST['ubicacion'])) {
         $query .= " lab_conf_examen_estab.ubicacion='" . $_POST['ubicacion'] . "' AND";
      }

//      if (!empty($_POST['etiqueta'])) {
//         if ($_POST['etiqueta'] == 'G') {
//            $query .= "  lab_conf_examen_estab.impresion='G' AND";
//         } else {
//            $query .= "  lab_conf_examen_estab.impresion<>'G' AND";
//         }
//      }
      if (!empty($_POST['urgente'])) {
         $query .= " lab_conf_examen_estab.urgente='" . $_POST['urgente'] . "' AND";
      }
//
//      if ($_POST['sexo'] <> 0) {
//         if ($_POST['sexo'] <> 4)
//            $query .= "  lab_conf_examen_estab.idsexo =" . $_POST['sexo'] . " AND";
//         else
//            $query .= "  lab_conf_examen_estab.idsexo IS NULL AND";
//      }


//      if (!empty($_POST['Hab'])) {
//         if ($_POST['Hab'] == 'H') {
//            $query .= "  lab_conf_examen_estab.condicion='H' AND";
//         } else {
//            $query .= "  lab_conf_examen_estab.condicion='I' AND";
//         }
//      } else {
//         $ban = 1;
//      }


      if ($ban == 0) {
         $query = substr($query, 0, strlen($query) - 4);
         $query_search = $query . " ORDER BY ctl_area_servicio_diagnostico.idarea,lab_conf_examen_estab.nombre_examen";
      } else {
         $query = substr($query, 0, strlen($query) - 4);
         $query_search = $query . " ORDER BY ctl_area_servicio_diagnostico.idarea,lab_conf_examen_estab.nombre_examen";
      }

    // echo  $query_search;
      // echo $query_search;
      //para manejo de la paginacion
      $RegistrosAMostrar = 10;
      $RegistrosAEmpezar = ($_POST['Pag'] - 1) * $RegistrosAMostrar;
      $PagAct = $_POST['Pag'];

      //ENVIANDO A EJECUTAR LA BUSQUEDA!!
      /////LAMANDO LA FUNCION DE LA CLASE
      $consulta = $objdatos->consultarpagbus($query_search, $RegistrosAEmpezar,
              $RegistrosAMostrar);
      //echo $query_search;
      //muestra los datos consultados en la tabla
     // echo $row[0];
      echo "<center >
               <table border = 1 style='width: 90%;'   class='table table-hover table-bordered table-condensed table-white' >
	           <thead>
                   <tr><td colspan='14'><h4><center><b>Resultado de Coincidencias...<b></center></h4></td></tr>
                        <tr>
		      	    <th aling='center' > Modificar</td>
			    <td aling='center' > Habilitado</td>
			    <td > C&oacute;digo Examen </td>
			    <td > Nombre Examen </td>
			    <td > &Aacute;rea</td>
                            <td >Plantilla</td>
                            <td >C&oacute;digo del Est&aacute;ndar</td>
			    <td >Solicitado en</td>
                            <td >Formulario</td>
                            <td >Tabulador</td>
                            <td >Tipo Viñeta</td>
                            <td >Urgente</td>
                            <td >Sexo</td>
                            <td >Tiempo Previo</td>
		     </tr>
                   </thead><tbody>
                    </center>";
      while ($row = pg_fetch_array($consulta)) {
         echo "<tr>
                            <td aling='center'>
				<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\"
				onclick=\"pedirDatos('" . $row[0] . "')\"></td>
                            <td  style='text-decoration:underline;cursor:pointer;' " .
         "onclick='Estado(\"" . $row['id'] . "\",\"" . $row['condicion'] . "\")'>" . $row['cond'] . "</td>
                            <td>" . $row['idexamen'] . " </td>
                            <td>" . htmlentities($row['nombreexamen']) . "</td>
                            <td>" . htmlentities($row['nombrearea']) . "</td>
                            <td>" . htmlentities($row['idplantilla']) . "</td>
                            <td>" . htmlentities($row['idestandar']) . "</td>
                            <td>" . htmlentities($row['ubicacion']) . "</td>";

         //if ($row['Ubicacion']=='0')
         //   echo"<td>SI</td>";
         // else
         //  echo"<td>NO</td>";*/
         if (!empty($row['nombreformulario']))
            echo"<td>" . htmlentities($row['nombreformulario']) . " </td> ";
         else
            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
         echo "<td>" . htmlentities($row['estandarrep']) . " </td>";

         if ($row['impresion'] == 'G')
            echo"<td>General</td>";
         else
            echo"<td>Especial </td>";
         if ($row['urgente'] == 0)
            echo"<td>NO</td>";
         else
            echo"<td>SI</td>";

         if (!empty($row['nombresexo']))
            echo"<td>" . htmlentities($row['nombresexo']) . " </td>";
         else
            echo "<td>Ambos</td>";
         if (!empty($row['rangotiempoprev']))
            echo"<td>" . $row['rangotiempoprev'] . " </td><tr>";
         else
            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><tr>
                    </tr>";
      }
      echo "</table>";

      //determinando el numero de paginas
      $NroRegistros = $objdatos->NumeroDeRegistrosbus($query_search);
      $PagAnt = $PagAct - 1;
      $PagSig = $PagAct + 1;

      $PagUlt = $NroRegistros / $RegistrosAMostrar;

      //verificamos residuo para ver si llevar� decimales
      $Res = $NroRegistros % $RegistrosAMostrar;
      //si hay residuo usamos funcion floor para que me
      //devuelva la parte entera, SIN REDONDEAR, y le sumamos
      //una unidad para obtener la ultima pagina
      if ($Res > 0)
         $PagUlt = floor($PagUlt) + 1;

      echo "<table align='center'>
		       <tr>
			   <td colspan=3 align='center'> <strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong> </td>
                       </tr>
                       <tr>
			   <td>
			   <a onclick=\"show_event_search('1')\">Primero</a> </td>";
      //// desplazamiento

      if ($PagAct > 1)
         echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
      if ($PagAct < $PagUlt)
         echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
      echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
      echo "</tr>
			  </table>";

      echo " <center> <ul class='pagination'>";
      for ($i = 1; $i <= $PagUlt; $i++) {

         echo " <li ><a  href='javascript: show_event_search(" . $i . ")'>$i</a></li>";
      }
      echo " </ul></center>";
      break;

   case 8://PAGINACION DE BUSQUEDA
      $idexamen = $_POST['idexamen'];
      $idarea = $_POST['idarea'];
      $nomexamen = $_POST['nomexamen'];
      $idestandar = $_POST['idestandar'];
      $plantilla = $_POST['plantilla'];
      //$observacion=$_POST['observacion'];
      $Urgente = $_POST['urgente'];
      $ubicacion = $_POST['ubicacion'];
      $cond = $_POST['condicion'];
      $IdFormulario = $_POST['idformulario'];
      $IdEstandarResp = $_POST['idestandarRep'];
      $etiqueta = $_POST['etiqueta'];
      $sexo = $_POST['sexo'];
      if ($sexo <> 4)
         $idsexo = $sexo;
      else
         $idsexo = 'NULL';
      // echo $idsexo;
      //echo $IdFormulario."--".$IdEstandarResp;
      $conExam = $objdatos->BuscarExamen($idexamen, $lugar);

      //print_r ($conExam);
      $ExisExa = pg_fetch_array($conExam);
      //print_r ($ExisExa[0]);


       $query = "SELECT lab_conf_examen_estab.id,lab_conf_examen_estab.codigo_examen as idexamen,
                                            lab_conf_examen_estab.nombre_examen as nombreexamen, ctl_area_servicio_diagnostico.nombrearea,lab_plantilla.idplantilla,
                                            ctl_examen_servicio_diagnostico.idestandar,
                                            (CASE WHEN lab_conf_examen_estab.ubicacion=0 THEN 'Todas las Procedencias'
                                            WHEN lab_conf_examen_estab.ubicacion=1 THEN 'Hospitalización y Emergencia'
                                            WHEN lab_conf_examen_estab.ubicacion=3 THEN 'Ninguna'
                                            WHEN lab_conf_examen_estab.ubicacion=4 THEN 'Laboratorio' END ) AS Ubicacion,
                                            (SELECT idestandar FROM ctl_examen_servicio_diagnostico
                                            WHERE lab_conf_examen_estab.idestandarrep=ctl_examen_servicio_diagnostico.id) AS estandarrep,
                                            lab_conf_examen_estab.impresion,urgente, ctl_sexo.nombre AS nombresexo,lab_conf_examen_estab.condicion,
                                            (CASE WHEN lab_conf_examen_estab.condicion='H' THEN 'Habilitado'
                                            WHEN lab_conf_examen_estab.condicion='I' THEN 'Inhabilitado' END) AS cond,cit_programacion_exams.rangotiempoprev,
                                             (SELECT nombreformulario FROM mnt_formularios WHERE mnt_formularios.id=lab_conf_examen_estab.idformulario) AS nombreformulario
                                            FROM lab_conf_examen_estab
                                            INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                                            INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
                                            INNER JOIN ctl_examen_servicio_diagnostico ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico=ctl_examen_servicio_diagnostico.id
                                            LEFT JOIN mnt_formularios ON mnt_formularios.id=lab_conf_examen_estab.idformulario
                                            INNER JOIN lab_plantilla ON lab_conf_examen_estab.idplantilla=lab_plantilla.id
                                            LEFT JOIN ctl_sexo ON lab_conf_examen_estab.idsexo= ctl_sexo.id
                                            INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
                                            LEFT JOIN cit_programacion_exams ON lab_conf_examen_estab.id=cit_programacion_exams.id_examen_establecimiento
                                            WHERE lab_areasxestablecimiento.condicion='H' AND ctl_examen_servicio_diagnostico.activo= TRUE AND mnt_area_examen_establecimiento.activo=TRUE
                                            AND lab_areasxestablecimiento.idestablecimiento=$lugar AND";

      $ban = 0;

      //VERIFICANDO LOS POST ENVIADOS
      // echo $ExisExa[0];
      if ($ExisExa[0] > 0) {//si existe el examen
         if (!empty($_POST['idexamen'])) {
            $query .= " lab_conf_examen_estab.codigo_examen='" . $_POST['idexamen'] . "' AND";
         }
      }
      if (!empty($_POST['nomexamen'])) {
         $query .= " nombre_examen ilike'%" . $_POST['nomexamen'] . "%' AND";
      }

      if (!empty($_POST['idarea'])) {
         $query .= " ctl_area_servicio_diagnostico.id=" . $_POST['idarea'] . "   AND";
      }

      if (!empty($_POST['plantilla'])) {
         $query .= " lab_conf_examen_estab.idplantilla=" . $_POST['plantilla'] . " AND";
      }

       if (!empty($_POST['idestandar'])) {
         $query .= " mnt_area_examen_establecimiento.id=" . $_POST['idestandar'] . " AND";
      }

      if (!empty($_POST['idestandarRep'])) {
         $query .= " lab_conf_examen_estab.idestandarrep=" . $_POST['idestandarRep'] . " AND";
      }

      if (!empty($_POST['idformulario'])) {
         $query .= " lab_conf_examen_estab.idformulario='" . $_POST['idformulario'] . "' AND";
      }

      if (!empty($_POST['ubicacion'])) {
         $query .= " lab_conf_examen_estab.ubicacion='" . $_POST['ubicacion'] . "' AND";
      }

    /*  if (!empty($_POST['etiqueta'])) {
         if ($_POST['etiqueta'] == 'G') {
            $query .= "  lab_conf_examen_estab.impresion='G' AND";
         } else {
            $query .= "  lab_conf_examen_estab.impresion<>'G' AND";
         }
      }*/

      if (!empty($_POST['urgente'])) {
         $query .= " lab_conf_examen_estab.urgente='" . $_POST['urgente'] . "' AND";
      }


   /*   if ($_POST['sexo'] <> 0) {
         if ($_POST['sexo'] <> 4)
            $query .= "  lab_conf_examen_estab.idsexo =" . $_POST['sexo'] . " AND";
         else
            $query .= "  lab_conf_examen_estab.idsexo IS NULL AND";
      }*/
      /* if ($_POST['sexo']<>3)
        { if($_POST['sexo']<>0)
        $query .= "  lab_conf_examen_estab.idsexo =".$_POST['sexo']." AND";
        else
        $query .= "  lab_conf_examen_estab.idsexo IS NULL AND";

        } */


     /* if (!empty($_POST['Hab'])) {
         if ($_POST['Hab'] == 'H') {
            $query .= "  lab_conf_examen_estab.condicion='H' AND";
         } else {
            $query .= "  lab_conf_examen_estab.condicion='I' AND";
         }
      } else {
         $ban = 1;
      }*/


     if ($ban == 0) {
         $query = substr($query, 0, strlen($query) - 4);
         $query_search = $query . " ORDER BY ctl_area_servicio_diagnostico.idarea,lab_conf_examen_estab.nombre_examen";
      } else {
         $query = substr($query, 0, strlen($query) - 4);
         $query_search = $query . " ORDER BY ctl_area_servicio_diagnostico.idarea,lab_conf_examen_estab.nombre_examen";
      }

      // echo $query_search;
      //require_once("clsLab_Examenes.php");
      ////para manejo de la paginacion
      $RegistrosAMostrar = 10;
      $RegistrosAEmpezar = ($_POST['Pag'] - 1) * $RegistrosAMostrar;
      $PagAct = $_POST['Pag'];

      /////LAMANDO LA FUNCION DE LA CLASE
      $consulta = $objdatos->consultarpagbus($query_search, $RegistrosAEmpezar,
              $RegistrosAMostrar);

      //muestra los datos consultados en la tabla
      echo "<center >
               <table border = 1 style='width: 90%;'   class='table table-hover table-bordered table-condensed table-white' >
	           <thead>
                        <tr>
		      	    <th aling='center' > Modificar</th>
                            <th aling='center' > Habilitado</th>
                            <th aling='center'> C&oacute;digo Examen </th>
                            <th aling='center' > Nombre Examen </th>
                            <th aling='center' > &Aacute;rea</th>
                            <th aling='center' >Plantilla</th>
                            <th aling='center' >C&oacute;digo del Est&aacute;ndar</th>
                            <th aling='center' >Solicitado en</th>
                            <th aling='center' >Formulario</th>
                            <th aling='center' >Tabulador</th>
                            <th aling='center' >Tipo Viñeta</th>
                            <th aling='center' >Urgente</th>
                            <th aling='center' >Sexo</th>
                            <th aling='center' >Tiempo Previo</th>
		      </tr>
                   </thead><tbody>
                    </center>";
      while ($row = pg_fetch_array($consulta)) {
         echo "<tr>
                            <td aling='center'>
				<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\"
				onclick=\"pedirDatos('" . $row[0] . "')\"></td>
                            <td  style='text-decoration:underline;cursor:pointer;' " .
         "onclick='Estado(\"" . $row['id'] . "\",\"" . $row['condicion'] . "\")'>" . $row['cond'] . "</td>
                            <td>" . $row['idexamen'] . " </td>
                            <td>" . htmlentities($row['nombreexamen']) . "</td>
                            <td>" . htmlentities($row['nombrearea']) . "</td>
                            <td>" . htmlentities($row['idplantilla']) . "</td>
                            <td>" . htmlentities($row['idestandar']) . "</td>
                            <td>" . htmlentities($row['ubicacion']) . "</td>";

         //if ($row['Ubicacion']=='0')
         //   echo"<td>SI</td>";
         // else
         //  echo"<td>NO</td>";*/
         if (!empty($row['nombreformulario']))
            echo"<td>" . htmlentities($row['nombreformulario']) . " </td> ";
         else
            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
         echo "<td>" . htmlentities($row['estandarrep']) . " </td>";

         if ($row['impresion'] == 'G')
            echo"<td>General</td>";
         else
            echo"<td>Especial </td>";
         if ($row['urgente'] == 0)
            echo"<td>NO</td>";
         else
            echo"<td>SI</td>";

         if (!empty($row['nombresexo']))
            echo"<td>" . htmlentities($row['nombresexo']) . " </td>";
         else
            echo "<td>Ambos</td>";
         if (!empty($row['rangotiempoprev']))
            echo"<td>" . $row['rangotiempoprev'] . " </td><tr>";
         else
            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><tr>
                    </tr>";
      }
      echo "</table>";
      //determinando el numero de paginas
      $NroRegistros = $objdatos->NumeroDeRegistrosbus($query_search);
      $PagAnt = $PagAct - 1;
      $PagSig = $PagAct + 1;

      $PagUlt = $NroRegistros / $RegistrosAMostrar;

      //verificamos residuo para ver si llevar� decimales
      $Res = $NroRegistros % $RegistrosAMostrar;
      //si hay residuo usamos funcion floor para que me
      //devuelva la parte entera, SIN REDONDEAR, y le sumamos
      //una unidad para obtener la ultima pagina
      if ($Res > 0)
         $PagUlt = floor($PagUlt) + 1;

      echo "<table align='center'>
		       <tr>
			   <td colspan=3 align='center'> <strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong> </td>
			   </tr>
			   <tr>
			   <td>
			   <a onclick=\"show_event_search('1')\">Primero</a> </td>";
      //// desplazamiento
      if ($PagAct > 1)
         echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
      if ($PagAct < $PagUlt)
         echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
      echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
      echo "</tr>
			  </table>";

      echo " <center> <ul class='pagination'>";
      for ($i = 1; $i <= $PagUlt; $i++) {

         echo " <li ><a  href='javascript: show_event_search(" . $i . ")'>$i</a></li>";
      }
      echo " </ul></center>";


      break;
   case 9://Muestra los formularios para cada programa
      $IdPrograma = $_POST['idprograma'];
      //echo $IdPrograma;
      $rslts = '';
      $consulta = $objdatos->consultar_formularios($lugar);

      $rslts = '<select name="cmbConForm" id="cmbConForm"   size"1">';
      $rslts .='<option value="0">--Seleccione--</option>';

      while ($rows = pg_fetch_array($consulta)) {
         $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
      }

      $rslts .= '</select>';
      echo $rslts;

      break;

    case 10://Muestra los formularios para cada programa
         $idexa = $_POST['idexa'];
         //echo $IdPrograma;
         $rslts = '';
         $consulta = $objdatos->establecimiento_referido($idexa);
         if (@pg_num_rows($consulta)==0){
             $rslts.= '<select id="cmbEstabReferido" disabled name="cmbEstabReferido[]" size="1" style="width:75%"  class="height js-example-placeholder-multiple" multiple="multiple">
             <option value="0" selected> No hay establecimientos a refererir asociados al nivel de donde se realiza el examen.</option>
             </select>';
         }
         else{
             $rslts = '<select id="cmbEstabReferido" name="cmbEstabReferido[]" size="1" style="width:75%"  class="height js-example-placeholder-multiple" multiple="multiple">';

             while ($rows = pg_fetch_array($consulta)) {
                $rslts.= '<option value="' . $rows[0] . '|'.$rows[1].'" >' . htmlentities($rows[2]) . '</option>';
             }

             $rslts .= '</select>';
        }
         echo $rslts;

         break;


     case 11://Muestra los formularios para cada programa
            $idexa = $_POST['idexa'];
            $idarea = $_POST['idarea'];
            //echo $IdPrograma;
            $rslts = '';
            $consulta = $objdatos->examenes_configurados($idexa, $idarea);
            if (@pg_num_rows($consulta)>=1){
                $rslts.= 'Y';
            }
            else{
                $rslts = 'N';
           }
            echo $rslts;

            break;
}

function asignar_resultados($resultado) {
   $objdatos = new clsLab_Examenes;
   $objdatos->posible_resultados($resultado);
}

?>
