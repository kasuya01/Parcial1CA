<?php session_start();
include_once("clsLab_DatosFijosExamen.php");
include('../Lab_Areas/clsLab_Areas.php');
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

//variables POST

$opcion=$_POST['opcion'];

$objdatos = new clsLab_DatosFijosExamen;
$objeareas=new clsLab_Areas;
$Clases = new clsLabor_DatosFijosExamen;

switch ($opcion)
{
	case 1:  //INSERTAR

		$idexamen=$_POST['idexamen'];
		$idarea=$_POST['idarea'];
		$unidades=(empty($_POST['unidades'])) ? 'NULL' : "'" . pg_escape_string(utf8_encode($_POST['unidades'])) . "'";
                $nota=(empty($_POST['nota'])) ? 'NULL' : "'" . pg_escape_string(utf8_encode($_POST['nota'])) . "'";
                $sexo=$_POST['sexo'];
                if ($sexo==3)
                    $sexo='NULL';
                $redad=$_POST['redad'];
                $rangoinicio=(empty($_POST['rangoinicio'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangoinicio']) . "'";
                $rangofin=(empty($_POST['rangofin'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangofin']) . "'";
                $Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
		$Fechafin=(empty($_POST['Fechafin'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechafin']) . "'";

        //echo $unidades;

		if ($objdatos->insertar($idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)==true)
                     /*   &&
		    ($Clases->insertar_labo($idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)==true))*/
                {
		   	echo "Registro Agregado";
	   	}
		else{
			echo "No se pudo Agregar";
		}

	break;
    	case 2:  //MODIFICAR
			$idexamen=$_POST['idexamen'];
			$idarea=$_POST['idarea'];
			$iddatosfijosresultado=$_POST['iddatosfijosexamen'];
		        $redad=(empty($_POST['redad'])) ? 'NULL' : "'" . pg_escape_string($_POST['redad']) . "'";
                        $sexo=(empty($_POST['sexo'])) ? 'NULL' : "'" . pg_escape_string($_POST['sexo']) . "'";
                        $unidades=(empty($_POST['unidades'])) ? 'NULL' : "'" . pg_escape_string(utf8_encode($_POST['unidades'])) . "'";
                        $rangoinicio=(empty($_POST['rangoinicio'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangoinicio']) . "'";
                        $rangofin=(empty($_POST['rangofin'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangofin']) . "'";
                        $nota=(empty($_POST['nota'])) ? 'NULL' : "'" . pg_escape_string(utf8_encode($_POST['nota'])) . "'";
                        $Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
                        $Fechafin=(empty($_POST['Fechafin'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechafin']) . "'";
                       // echo "ctr ".$nota;
			if ($objdatos->actualizar($iddatosfijosresultado,$idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)==true)
                           /* && $Clases->actualizar_labo($iddatosfijosresultado,$idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)==true)*/
			{
				echo "Registro Actualizado"	;
			}
			else{
				echo "No se pudo actualizar";
			}

	break;
	case 3:  //ELIMINAR
		 //Vefificando Integridad de los datos
		$iddatosfijosresultado=$_POST['iddatosfijosresultado'];
			//echo $iddatosfijosresultado;
		if ($objdatos->eliminar($iddatosfijosresultado,$lugar)==true){
                         /*&& $Clases->eliminar_labo($iddatosfijosresultado,$lugar)){		*/
			echo "Registro Eliminado" ;

                }
                else{
                            echo "El registro no pudo ser eliminado ";
                    }


	break;

	case 4:// PAGINACION

		////para manejo de la paginacion
		$RegistrosAMostrar=10;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];

		 /////LAMANDO LA FUNCION DE LA CLASE
		$consulta= $objdatos->consultarpag($lugar,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
		  echo "<center >
               <table border = 1 style='width: 80%;'  class='table table-hover table-bordered table-condensed table-white'>
	           <thead>
                        <tr>
				<th aling='center' > Modificar</td>
				<!-- <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td> -->
				<th > Habilitado              </th>
                                <th > Código              </th>
				<th > Examen                </th>
				<th > Unidades              </th>
				<th > Valores Normales      </th>
				<th > Observacion           </th>
                                <th > Sexo                  </th>
                                <th > Rango de Edad         </th>
				<th > Fecha Inicio          </th>
				<th > Fecha Finalización    </th>
                        </tr>
                    </thead><tbody>
                    </center>";
		while($row = pg_fetch_array($consulta)){
                    $idatofijo=$row['idatofijo'];
                    $habilitado= $row['habilitado'];

                  if ($habilitado=='Habilitado'){

                      echo "<tr>
				<td aling='center'>
                                    <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\"
                                    onclick=\"pedirDatos('".$row['id']."')\"> </td>
				<!--<td aling ='center'>
                                            <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
                                            onclick=\"eliminarDato('".$row['id']."')\">
                                   </td> -->

                                    <td width='6%'><span style='color: #0101DF;'>
                   	 <a style ='text-decoration:underline;cursor:pointer;' title='Al dar Click inhabilitará el dato fijo' onclick='Estado(\"".$row['idatofijo']."\",\"".$row['habilitado']."\")'>".$row['habilitado']."</a></td>


				<td>".$row['idestandar']."</td>
				<td>".htmlentities($row['nombre_examen'])."</td>";
			if (empty($row['unidades']))
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
				echo"<td>".htmlentities($row['unidades'])."</td>";

                        if ((empty($row['rangoInicio'])) && (empty($row['rangofin'])))
                                echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
                               echo "<td>".$row['rangoinicio']." - ".$row['rangofin']."</td>";

			if (empty($row['nota']))
                            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
                            echo "<td>".htmlentities($row['nota'])."</td>";

                        if (empty($row['sexo']))
                            echo "<td> Ambos </td>";
                        else
                            echo "<td>".$row['sexo']."</td>";

                            echo "<td>".$row['redad']."</td>";
			//echo $row[7];
			if((empty($row[7])) || ($row[7]=="NULL") || ($row[7]=="00-00-0000"))
				     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
				else
					 echo"<td>".$row[7]."</td>";
			if((empty($row[8])) || ($row[8]=="(NULL)") || ($row[8]=="00/00/0000"))
			     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
			else
					echo"<td>".$row[8]."</td></tr>";
            echo "</tr>";


                  }ELSE{

		  echo "<tr>
				<td aling='center'>
                                    <img src='../../../Imagenes/Search.png' style=\"text-decoration:underline;cursor:pointer;\"
                                    onclick=\"pedirDatos('".$row['id']."')\"height='30' width='50'> </td>
				<!--<td aling ='center'>
                                            <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
                                            onclick=\"eliminarDato('".$row['id']."')\">
                                   </td> -->
                                   <td>". $row['habilitado'] ."</td>
				<td>".$row['idestandar']."</td>
				<td>".htmlentities($row['nombre_examen'])."</td>";
			if (empty($row['unidades']))
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
				echo"<td>".utf8_decode($row['unidades'])."</td>";

                        if ((empty($row['rangoInicio'])) && (empty($row['rangofin'])))
                                echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
                               echo "<td>".$row['rangoinicio']." - ".$row['rangofin']."</td>";

			if (empty($row['nota']))
                            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
                            echo "<td>".utf8_decode($row['nota'])."</td>";

                        if (empty($row['sexo']))
                            echo "<td> Ambos </td>";
                        else
                            echo "<td>".$row['sexo']."</td>";

                            echo "<td>".$row['redad']."</td>";
			//echo $row[7];
			if((empty($row[7])) || ($row[7]=="NULL") || ($row[7]=="00-00-0000"))
				     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
				else
					 echo"<td>".$row[7]."</td>";
			if((empty($row[8])) || ($row[8]=="(NULL)") || ($row[8]=="00/00/0000"))
			     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
			else
					echo"<td>".$row[8]."</td></tr>";
            echo "</tr>";
                  }
		}


	echo "</table>";
		//determinando el numero de paginas
		 $NroRegistros= $objdatos->NumeroDeRegistros($lugar);
		 $PagAnt=$PagAct-1;
		 $PagSig=$PagAct+1;

		 $PagUlt=$NroRegistros/$RegistrosAMostrar;

		 //verificamos residuo para ver si llevar� decimales
		 $Res=$NroRegistros%$RegistrosAMostrar;
		 //si hay residuo usamos funcion floor para que me
		 //devuelva la parte entera, SIN REDONDEAR, y le sumamos
		 //una unidad para obtener la ultima pagina
		 if($Res>0) $PagUlt=floor($PagUlt)+1;

		 echo "<table align='center'>
		       <tr>
			      <td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
		       </tr>
		       <tr>
			      <td><a onclick=\"show_event('1')\">Primero</a> </td>";
		//// desplazamiento

		 if($PagAct>1)
			 echo "<td> <a onclick=\"show_event('$PagAnt')\">Anterior</a> </td>";
		 if($PagAct<$PagUlt)
			 echo "<td> <a onclick=\"show_event('$PagSig')\">Siguiente</a> </td>";
			 echo "<td> <a onclick=\"show_event('$PagUlt')\">Ultimo</a></td>";
			 echo "</tr>
			</table>";


                                echo " <center> <ul class='pagination'>";
                          for ($i=1 ; $i<=$PagUlt; $i++)
                                    {

					 echo " <li ><a  href='javascript: show_event(".$i.")'>$i</a></li>";
                                     }
                    echo " </ul></center>";

	break;
	case 5:
		$idarea=$_POST['idarea'];

           	//echo $idarea." estab=".$lugar;
	  	$rslts='';
		$consultaex= $objdatos->ExamenesPorArea($idarea,$lugar);
		//$dtMed=$obj->LlenarSubServ($proce);

		$rslts = '<select name="cmbExamen" id="cmbExamen" size="1" class="form-control height" style="width:75%">';
		$rslts .='<option value="0">--Seleccione un Examen--</option>';

		while ($rows =pg_fetch_array($consultaex)){
			$rslts.= '<option value="' .$rows[0].'" >'.$rows[2]." - ".htmlentities($rows[1]).'</option>';
		}

		$rslts .= '</select>';
		echo $rslts;


	break;

	case 6:

	  break;
	case 7: //BUSQUEDA
		$idexamen=$_POST['idexamen'];
		$idarea=$_POST['idarea'];
		// echo   $idexamen;
                $unidades=(empty($_POST['unidades'])) ? 'NULL' : "'" . pg_escape_string($_POST['unidades']) . "'";
                $nota=(empty($_POST['nota'])) ? 'NULL' : "'" . pg_escape_string($_POST['nota']) . "'";
                $sexo=(empty($_POST['sexo'])) ? 'NULL' : "'" . pg_escape_string($_POST['sexo']) . "'";
                $redad=(empty($_POST['redad'])) ? 'NULL' : "'" . pg_escape_string($_POST['redad']) . "'";
                $rangoinicio=(empty($_POST['rangoinicio'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangoinicio']) . "'";
                $rangofin=(empty($_POST['rangofin'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangofin']) . "'";
                $Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
		$Fechafin=(empty($_POST['Fechafin'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechafin']) . "'";

	  	//echo $lugar;
		$query = "SELECT lab_datosfijosresultado.id,
                                lab_conf_examen_estab.codigo_examen,
                                lab_conf_examen_estab.nombre_examen,
                                lab_datosfijosresultado.unidades,
                                lab_datosfijosresultado.rangoinicio,
                                rangofin, lab_datosfijosresultado.nota,
                                to_char(lab_datosfijosresultado.fechaini,'dd/mm/YYYY') AS FechaIni,
                                to_char(lab_datosfijosresultado.fechafin,'dd/mm/YYYY') AS FechaFin,
                                ctl_sexo.nombre as sexo,
                                ctl_rango_edad.nombre as redad,
                                CASE lab_datosfijosresultado.fechafin
                                    WHEN lab_datosfijosresultado.fechafin THEN 'Inhabilitado'
                                    ELSE 'Habilitado' END AS habilitado,
                                    lab_datosfijosresultado.id as idatofijo,
                                    ctl_examen_servicio_diagnostico.idestandar
                         FROM lab_datosfijosresultado
                         INNER JOIN lab_conf_examen_estab           ON lab_datosfijosresultado.id_conf_examen_estab=lab_conf_examen_estab.id
                         INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                         INNER JOIN ctl_area_servicio_diagnostico   ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
                         INNER JOIN ctl_examen_servicio_diagnostico ON ctl_examen_servicio_diagnostico.id = mnt_area_examen_establecimiento.id_examen_servicio_diagnostico
                         INNER JOIN lab_areasxestablecimiento       ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
                         LEFT JOIN ctl_sexo                         ON lab_datosfijosresultado.idsexo = ctl_sexo.id
                         INNER JOIN ctl_rango_edad                  ON lab_datosfijosresultado.idedad = ctl_rango_edad.id
                         WHERE lab_conf_examen_estab.idplantilla=1
                         AND lab_conf_examen_estab.condicion='H'
                         AND lab_areasxestablecimiento.condicion='H'
                         AND mnt_area_examen_establecimiento.activo= TRUE
                         AND ctl_examen_servicio_diagnostico.activo= TRUE
                         AND lab_datosfijosresultado.idestablecimiento=$lugar  ";
                        $ban=0;
                        //VERIFICANDO LOS POST ENVIADOS
                        if (!empty($_POST['idarea']))
                        { $query .= " AND  mnt_area_examen_establecimiento.id_area_servicio_diagnostico=".$_POST['idarea']."        "; }

                        if (!empty($_POST['idexamen']))
                        { $query .= " AND lab_conf_examen_estab.id=".$_POST['idexamen']."     "; }

                       if (!empty($_POST['unidades']))
                        { $query .= " AND unidades='".$_POST['unidades']."'     "; }

                        if (!empty($_POST['rangoinicio']))
                        { $query .= "AND  rangoinicio='".$_POST['rangoinicio']."'    "; }

                        if (!empty($_POST['rangofin']))
                        { $query .= " AND  rangofin='".$_POST['rangofin']."'     "; }

                         if (!empty($_POST['nota']))
                        { $query .= " AND nota ILIKE '%".$_POST['nota']."%'     "; }

                      /*  if (!empty($_POST['sexo'])){
                            if ($_POST['sexo']<>3)
                              $query .= " AND ctl_sexo.id=".$_POST['sexo']."  ";
                        }
                        else
                        { $query .= " AND ctl_sexo.id is null   "; }*/

                        if ( !empty( $_POST['sexo'] ) && $_POST['sexo'] !== '0' )
                            {
                              //  $query .= "  AND CASE WHEN ctl_sexo.id IS NULL THEN 'NULL' ELSE ctl_sexo.id::text END = '".$_POST['sexo']."'   ";

                              if ( $_POST['sexo']==3){

                                            $query .= "AND ((ctl_sexo.id IS NULL) or (ctl_sexo.id=".$_POST['sexo']."))        ";
                             } else{
                                    $query .="AND ctl_sexo.id=".$_POST['sexo']."         ";

                                    }
                            }



                        if (!empty($_POST['redad']))
                        { $query .= " AND ctl_rango_edad.id=".$_POST['redad']."      "; }

                        if (!empty($_POST['Fechaini']))
                        { 	/*$FechaI=explode('/',$_POST['Fechaini']);
                                $Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];*/

                                $query .= " AND  lab_datosfijosresultado.fechaini='".$_POST['Fechaini']."'      "; }

                        if (!empty($_POST['Fechafin'])){
                                /*$FechaF=explode('/',$_POST['Fechafin']);
                                $Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];*/
                                $query .= " AND  fechafin='".$_POST['Fechafin']."'   "; }



                        if((empty($_POST['idexamen'])) and (empty($_POST['idarea'])) and (empty($_POST['unidades'])) and
                                (empty($_POST['rangoinicio'])) and (empty($_POST['rangofin'])) and (empty($_POST['nota'])) and
                                (empty($_POST['Fechafin'])) and (empty($_POST['Fechaini'])) and (empty($_POST['sexo']))
                                and (empty($_POST['redad'])))
                        {
                                $ban=1;
                        }
                        if ($ban==0)
                        {   $query = substr($query ,0,strlen($query)-3);
                            $query_search = $query. "  ORDER BY mnt_area_examen_establecimiento.id_area_servicio_diagnostico,lab_conf_examen_estab.id";
                        }

		  $query_search;
		//ENVIANDO A EJECUTAR LA BUSQUEDA!!
		//para manejo de la paginacion
		$RegistrosAMostrar=10;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];

		 /////LAMANDO LA FUNCION DE LA CLASE
		//$obje=new clsLab_DatosFijosExamen;
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
			 echo "<center >
               <table border = 1 style='width: 80%;'  class='table table-hover table-bordered table-condensed table-white'>
	           <thead>
                        <tr>
				<th aling='center' > Modificar</td>
				<!-- <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td> -->
				<th > Habilitado              </th>
                                <th > Código              </th>
				<th > Examen                </th>
				<th > Unidades              </th>
				<th > Valores Normales      </th>
				<th > Observacion           </th>
                                <th > Sexo                  </th>
                                <th > Rango de Edad         </th>
				<th > Fecha Inicio          </th>
				<th > Fecha Finalización    </th>
                        </tr>
                    </thead><tbody>
                    </center>";
				while($row = @pg_fetch_array($consulta)){
                    $idatofijo=$row['idatofijo'];
                    $habilitado= $row['habilitado'];

                  if ($habilitado=='Habilitado'){

                      echo "<tr>
				<td aling='center'>
                                    <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\"
                                    onclick=\"pedirDatos('".$row['id']."')\"> </td>
				<!--<td aling ='center'>
                                            <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
                                            onclick=\"eliminarDato('".$row['id']."')\">
                                   </td> -->

                                    <td width='6%'><span style='color: #0101DF;'>
                   	 <a style ='text-decoration:underline;cursor:pointer;' onclick='Estado(\"".$row['idatofijo']."\",\"".$row['habilitado']."\")'>".$row['habilitado']."</a></td>

                                <td>".$row['idestandar']."</td>
				<td>".htmlentities($row['nombre_examen'])."</td>";
			if (empty($row['unidades']))
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
				echo"<td>".htmlentities($row['unidades'])."</td>";

                        if ((empty($row['rangoInicio'])) && (empty($row['rangofin'])))
                                echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
                               echo "<td>".$row['rangoinicio']." - ".$row['rangofin']."</td>";

			if (empty($row['nota']))
                            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
                            echo "<td>".htmlentities($row['nota'])."</td>";

                        if (empty($row['sexo']))
                            echo "<td> Ambos </td>";
                        else
                            echo "<td>".$row['sexo']."</td>";

                            echo "<td>".$row['redad']."</td>";
			//echo $row[7];
			if((empty($row[7])) || ($row[7]=="NULL") || ($row[7]=="00-00-0000"))
				     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
				else
					 echo"<td>".$row[7]."</td>";
			if((empty($row[8])) || ($row[8]=="(NULL)") || ($row[8]=="00/00/0000"))
			     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
			else
					echo"<td>".$row[8]."</td></tr>";
            echo "</tr>";


                  }ELSE{

		  echo "<tr>
				<td aling='center'>
                                    <img src='../../../Imagenes/Search.png' style=\"text-decoration:underline;cursor:pointer;\"
                                    onclick=\"pedirDatos('".$row['id']."')\"height='40' width='50'> </td>
				<!--<td aling ='center'>
                                            <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
                                            onclick=\"eliminarDato('".$row['id']."')\">
                                   </td> -->
                                   <td>". $row['habilitado'] ."</td>
                                   <td>".$row['idestandar']."</td>
				<td>".htmlentities($row['nombre_examen'])."</td>";
			if (empty($row['unidades']))
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
				echo"<td>".htmlentities($row['unidades'])."</td>";

                        if ((empty($row['rangoInicio'])) && (empty($row['rangofin'])))
                                echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
                               echo "<td>".$row['rangoinicio']."-".$row['rangofin']."</td>";

			if (empty($row['nota']))
                            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
                            echo "<td>".htmlentities($row['nota'])."</td>";

                        if (empty($row['sexo']))
                            echo "<td> Ambos </td>";
                        else
                            echo "<td>".$row['sexo']."</td>";

                            echo "<td>".$row['redad']."</td>";
			//echo $row[7];
			if((empty($row[7])) || ($row[7]=="NULL") || ($row[7]=="00-00-0000"))
				     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
				else
					 echo"<td>".$row[7]."</td>";
			if((empty($row[8])) || ($row[8]=="(NULL)") || ($row[8]=="00/00/0000"))
			     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
			else
					echo"<td>".$row[8]."</td></tr>";
            echo "</tr>";
                  }


		}
		          echo "</table>";
		//determinando el numero de paginas
		 $NroRegistros= $objdatos->NumeroDeRegistrosbus($query_search);
		 $PagAnt=$PagAct-1;
		 $PagSig=$PagAct+1;

		 $PagUlt=$NroRegistros/$RegistrosAMostrar;

		 //verificamos residuo para ver si llevar� decimales
		 $Res=$NroRegistros%$RegistrosAMostrar;
		 //si hay residuo usamos funcion floor para que me
		 //devuelva la parte entera, SIN REDONDEAR, y le sumamos
		 //una unidad para obtener la ultima pagina
		 if($Res>0) $PagUlt=floor($PagUlt)+1;

		 echo "<table align='center'>
		       <tr>
			   <td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
			   </tr>
			   <tr>
			   <td>
			   <a onclick=\"show_event_search('1')\">Primero</a> </td>";
		//// desplazamiento

		 if($PagAct>1)
			 echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
		 if($PagAct<$PagUlt)
			 echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
			 if($PagUlt > 0)
				echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
			 echo "</tr>
			  </table>";

                            echo " <center> <ul class='pagination'>";
                          for ($i=1 ; $i<=$PagUlt; $i++)
                                    {

					 echo " <li ><a  href='javascript: show_event_search(".$i.")'>$i</a></li>";
                                     }
                    echo " </ul></center>";
	  break;
	case 8://PAGINACION DE BUSQUEDA
		$idexamen=$_POST['idexamen'];
		$idarea=$_POST['idarea'];
		$unidades=(empty($_POST['unidades'])) ? 'NULL' : "'" . pg_escape_string($_POST['unidades']) . "'";
                $nota=(empty($_POST['nota'])) ? 'NULL' : "'" . pg_escape_string($_POST['nota']) . "'";
                $sexo=(empty($_POST['sexo'])) ? 'NULL' : "'" . pg_escape_string($_POST['sexo']) . "'";
                $redad=(empty($_POST['redad'])) ? 'NULL' : "'" . pg_escape_string($_POST['redad']) . "'";
                $rangoinicio=(empty($_POST['rangoinicio'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangoinicio']) . "'";
                $rangofin=(empty($_POST['rangofin'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangofin']) . "'";
                $Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
		$Fechafin=(empty($_POST['Fechafin'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechafin']) . "'";


		$query = "SELECT lab_datosfijosresultado.id,
                                lab_conf_examen_estab.codigo_examen,
                                lab_conf_examen_estab.nombre_examen,
                                lab_datosfijosresultado.unidades,
                                lab_datosfijosresultado.rangoinicio,
                                rangofin, lab_datosfijosresultado.nota,
                                to_char(lab_datosfijosresultado.fechaini,'dd/mm/YYYY') AS FechaIni,
                                to_char(lab_datosfijosresultado.fechafin,'dd/mm/YYYY') AS FechaFin,
                                ctl_sexo.nombre as sexo,
                                ctl_rango_edad.nombre as redad,
                                CASE lab_datosfijosresultado.fechafin
                                    WHEN lab_datosfijosresultado.fechafin THEN 'Inhabilitado'
                                    ELSE 'Habilitado' END AS habilitado,
                                    lab_datosfijosresultado.id as idatofijo,
                                    ctl_examen_servicio_diagnostico.idestandar
                         FROM lab_datosfijosresultado
                         INNER JOIN lab_conf_examen_estab           ON lab_datosfijosresultado.id_conf_examen_estab=lab_conf_examen_estab.id
                         INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                         INNER JOIN ctl_area_servicio_diagnostico   ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
                         INNER JOIN ctl_examen_servicio_diagnostico ON ctl_examen_servicio_diagnostico.id = mnt_area_examen_establecimiento.id_examen_servicio_diagnostico
                         INNER JOIN lab_areasxestablecimiento       ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
                         LEFT JOIN ctl_sexo                         ON lab_datosfijosresultado.idsexo = ctl_sexo.id
                         INNER JOIN ctl_rango_edad                  ON lab_datosfijosresultado.idedad = ctl_rango_edad.id
                         WHERE lab_conf_examen_estab.idplantilla=1
                         AND lab_conf_examen_estab.condicion='H'
                         AND lab_areasxestablecimiento.condicion='H'
                         AND mnt_area_examen_establecimiento.activo= TRUE
                         AND ctl_examen_servicio_diagnostico.activo= TRUE
                         AND lab_datosfijosresultado.idestablecimiento=$lugar   ";
		$ban=0;

		//VERIFICANDO LOS POST ENVIADOS
		$ban=0;
                        //VERIFICANDO LOS POST ENVIADOS
                        if (!empty($_POST['idarea']))
                        { $query .= " AND  mnt_area_examen_establecimiento.id_area_servicio_diagnostico=".$_POST['idarea']."        "; }

                        if (!empty($_POST['idexamen']))
                        { $query .= " AND lab_conf_examen_estab.id=".$_POST['idexamen']."     "; }

                       if (!empty($_POST['unidades']))
                        { $query .= " AND unidades='".$_POST['unidades']."'     "; }

                        if (!empty($_POST['rangoinicio']))
                        { $query .= "AND  rangoinicio='".$_POST['rangoinicio']."'    "; }

                        if (!empty($_POST['rangofin']))
                        { $query .= " AND  rangofin='".$_POST['rangofin']."'     "; }

                         if (!empty($_POST['nota']))
                        { $query .= " AND nota ILIKE '%".$_POST['nota']."%'     "; }

                      /*  if (!empty($_POST['sexo'])){
                            if ($_POST['sexo']<>3)
                              $query .= " AND ctl_sexo.id=".$_POST['sexo']."  ";
                        }
                        else
                        { $query .= " AND ctl_sexo.id is null   "; }*/

                        if ( !empty( $_POST['sexo'] ) && $_POST['sexo'] !== '0' )
                            {
                              //  $query .= "  AND CASE WHEN ctl_sexo.id IS NULL THEN 'NULL' ELSE ctl_sexo.id::text END = '".$_POST['sexo']."'   ";

                              if ( $_POST['sexo']==3){

                                            $query .= "AND ((ctl_sexo.id IS NULL) or (ctl_sexo.id=".$_POST['sexo']."))        ";
                             } else{
                                    $query .="AND ctl_sexo.id=".$_POST['sexo']."         ";

                                    }
                            }



                        if (!empty($_POST['redad']))
                        { $query .= " AND ctl_rango_edad.id=".$_POST['redad']."      "; }

                        if (!empty($_POST['Fechaini']))
                        { 	/*$FechaI=explode('/',$_POST['Fechaini']);
                                $Fechaini=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];*/

                                $query .= " AND  lab_datosfijosresultado.fechaini='".$_POST['Fechaini']."'      "; }

                        if (!empty($_POST['Fechafin'])){
                                /*$FechaF=explode('/',$_POST['Fechafin']);
                                $Fechafin=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];*/
                                $query .= " AND  fechafin='".$_POST['Fechafin']."'   "; }


	if((empty($_POST['cargo'])) and (empty($_POST['idarea'])) and (empty($_POST['nomempleado']))
         and (empty($_POST['idempleado'])) and (empty($_POST['sexo'])) and (empty($_POST['redad'])))
	{
		$ban=1;
	}

	if ($ban==0)
	{   $query = substr($query ,0,strlen($query)-3);
	    $query_search = $query. " ORDER BY mnt_area_examen_establecimiento.id_area_servicio_diagnostico,lab_conf_examen_estab.id";
	}
      //echo $query_search;

		//ENVIANDO A EJECUTAR LA BUSQUEDA!!
		//para manejo de la paginacion
		$RegistrosAMostrar=10;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];

		 //LAMANDO LA FUNCION DE LA CLASE
		//$obje=new clsLab_DatosFijosExamen;
		$consulta= $objdatos->consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
		  echo "<center >
               <table border = 1 style='width: 80%;'  class='table table-hover table-bordered table-condensed table-white'>
	           <thead>
                        <tr>
				<th aling='center' > Modificar</td>
				<!-- <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td> -->
				<th > Habilitado              </th>
                                <th > Código                </th>
				<th > Examen                </th>
				<th > Unidades              </th>
				<th > Valores Normales      </th>
				<th > Observacion           </th>
                                <th > Sexo                  </th>
                                <th > Rango de Edad         </th>
				<th > Fecha Inicio          </th>
				<th > Fecha Finalización    </th>
                        </tr>
                    </thead><tbody>
                    </center>";
		while($row = @pg_fetch_array($consulta)){
		   $idatofijo=$row['idatofijo'];
                    $habilitado= $row['habilitado'];

                  if ($habilitado=='Habilitado'){

                      echo "<tr>
				<td aling='center'>
                                    <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\"
                                    onclick=\"pedirDatos('".$row['id']."')\"> </td>
				<!--<td aling ='center'>
                                            <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
                                            onclick=\"eliminarDato('".$row['id']."')\">
                                   </td> -->

                                    <td width='6%'><span style='color: #0101DF;'>
                   	 <a style ='text-decoration:underline;cursor:pointer;' onclick='Estado(\"".$row['idatofijo']."\",\"".$row['habilitado']."\")'>".$row['habilitado']."</a></td>


				<td>".$row['idestandar']."</td>
				<td>".htmlentities($row['nombre_examen'])."</td>";
			if (empty($row['unidades']))
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
				echo"<td>".htmlentities($row['unidades'])."</td>";

                        if ((empty($row['rangoInicio'])) && (empty($row['rangofin'])))
                                echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
                               echo "<td>".$row['rangoinicio']."-".$row['rangofin']."</td>";

			if (empty($row['nota']))
                            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
                            echo "<td>".htmlentities($row['nota'])."</td>";

                        if (empty($row['sexo']))
                            echo "<td> Ambos </td>";
                        else
                            echo "<td>".$row['sexo']."</td>";

                            echo "<td>".$row['redad']."</td>";
			//echo $row[7];
			if((empty($row[7])) || ($row[7]=="NULL") || ($row[7]=="00-00-0000"))
				     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
				else
					 echo"<td>".$row[7]."</td>";
			if((empty($row[8])) || ($row[8]=="(NULL)") || ($row[8]=="00/00/0000"))
			     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
			else
					echo"<td>".$row[8]."</td></tr>";
            echo "</tr>";


                  }ELSE{

		  echo "<tr>
				<td aling='center'>
                                    <img src='../../../Imagenes/Search.png' style=\"text-decoration:underline;cursor:pointer;\"
                                    onclick=\"pedirDatos('".$row['id']."')\"height='40' width='50'> </td>
				<!--<td aling ='center'>
                                            <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
                                            onclick=\"eliminarDato('".$row['id']."')\">
                                   </td> -->
                                   <td>". $row['habilitado'] ."</td>
				<td>".$row['idestandar']."</td>
				<td>".htmlentities($row['nombre_examen'])."</td>";
			if (empty($row['unidades']))
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
				echo"<td>".htmlentities($row['unidades'])."</td>";

                        if ((empty($row['rangoInicio'])) && (empty($row['rangofin'])))
                                echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
                               echo "<td>".$row['rangoinicio']."-".$row['rangofin']."</td>";

			if (empty($row['nota']))
                            echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
                            echo "<td>".htmlentities($row['nota'])."</td>";

                        if (empty($row['sexo']))
                            echo "<td> Ambos </td>";
                        else
                            echo "<td>".$row['sexo']."</td>";

                            echo "<td>".$row['redad']."</td>";
			//echo $row[7];
			if((empty($row[7])) || ($row[7]=="NULL") || ($row[7]=="00-00-0000"))
				     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
				else
					 echo"<td>".$row[7]."</td>";
			if((empty($row[8])) || ($row[8]=="(NULL)") || ($row[8]=="00/00/0000"))
			     echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
			else
					echo"<td>".$row[8]."</td></tr>";
            echo "</tr>";
                  }
		}
	       	echo "</table>";
		//determinando el numero de paginas
		 $NroRegistros= $objdatos->NumeroDeRegistrosbus($query_search);
		 $PagAnt=$PagAct-1;
		 $PagSig=$PagAct+1;

		 $PagUlt=$NroRegistros/$RegistrosAMostrar;

		 //verificamos residuo para ver si llevar� decimales
		 $Res=$NroRegistros%$RegistrosAMostrar;
		 //si hay residuo usamos funcion floor para que me
		 //devuelva la parte entera, SIN REDONDEAR, y le sumamos
		 //una unidad para obtener la ultima pagina
		 if($Res>0) $PagUlt=floor($PagUlt)+1;

		 echo "<table align='center'>
		       <tr>
			   <td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
                       </tr>
                       <tr>
			   <td><a onclick=\"show_event_search('1')\">Primero</a></td>";
		//// desplazamiento

		 if($PagAct>1)
                     echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
		 if($PagAct<$PagUlt)
                     echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
                 if($PagUlt > 0)
                     echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
		 echo "</tr>
			  </table>";

                   echo " <center> <ul class='pagination'>";
                          for ($i=1 ; $i<=$PagUlt; $i++)
                                    {

					 echo " <li ><a  href='javascript: show_event_search(".$i.")'>$i</a></li>";
                                     }
                    echo " </ul></center>";

		//echo $query_search;

	break;

        case 9:  //habilitado


		$idatofijo=$_POST['idatofijo'];
                //$fechafinhabilitado="NULL";
             //	echo $idexamen."-".$condicion;
		//$resultado=Estado::EstadoCuenta($idexamen,$cond,$lugar);
		if($objdatos->Estadohabilitado($idatofijo,$usuario)==true )
                {
                   // echo "cambio";
                }else{
                   // echo "no cambio";
                }
	break;

        case 10:
           $idexamen=$_POST['idexamen'];
           $sexo=$_POST['sexo'];
           $edad=$_POST['redad'];
           $hay=$objdatos->buscardatosfijo($sexo,$edad, $idexamen);

            $cuantos=pg_num_rows($hay);
            if ($cuantos>0){
               $imprimir = 1;
            }
            else {
               $imprimir = 0;
            }
           echo $imprimir;

        break;
}

?>
