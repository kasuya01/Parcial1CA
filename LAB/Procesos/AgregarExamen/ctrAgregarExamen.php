<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("../AgregarExamen/clsAgregarExamen.php");

//variables POST
$opcion=$_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsAgregarExamen;
//echo $idexpediente;
switch ($opcion)
{
  	case 1:

        $pag=$_POST['pag'];
	$registros = 20;
	$pag =$_POST['pag'];
	$inicio = ($pag-1) * $registros;

        $ban = 0;
        $IdEstab        = $_POST['IdEstab'];
       echo $IdServ         = $_POST['IdServ'];
        $IdSubServ      = $_POST['IdSubServ'];
       // $idarea         = $_POST['idarea'];
       // $idexamen       = $_POST['idexamen'];
        $idexpediente   = $_POST['idexpediente'];
       // $fechasolicitud = $_POST['fechasolicitud'];
        $fecharecepcion = $_POST['fecharecep'];
        $PNombre        = $_POST['primernombre'];
        $SNomre         = $_POST['segundonombre'];
        $PApellido      = $_POST['primerapellido'];
        $SApellido      = $_POST['segundoapellido'];
       // $TipoSolic      = $_POST['TipoSolic'];
        $cond1="";
        $cond2="";
        $query="";
        $query2="";
        $where_with="";
        $IdServ1="";
         $idexpediente="'".$idexpediente."'";
         $cond0="and";


    
        if ($_POST['IdEstab']<>0) {
            if ($_POST['IdEstab']<>$lugar){
                $cond1 .= "t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " AND";
                $cond2 .= "t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " AND";
            }
          
            
        }

        if ($_POST['IdServ'] <> 0) {
            $cond1 .= " t12.id  = " . $_POST['IdServ'] . " AND";
            $cond2 .= " t12.id  = " . $_POST['IdServ'] . " AND";
            $where_with = "t03.id = $IdServ AND ";
        }

        if (!empty($_POST['IdSubServ'])) {
            $cond1 .= " t10.id = " . $_POST['IdSubServ'] . " AND";
            $cond2 .= " t10.id = " . $_POST['IdSubServ'] . " AND";
        }


        if (!empty($_POST['idarea'])) {
            $cond1 .= "t08.id = " . $_POST['idarea'] . "AND";
            $cond2 .= "t08.id = " . $_POST['idarea'] . "AND";
        }

        if ($_POST['idexpediente'] <> 0) {
            $cond1 .= " t06.numero = '" . $_POST['idexpediente'] . "' AND";
            $cond2 .= " t06.numero = '" . $_POST['idexpediente'] . "' AND";
        }

        if (!empty($_POST['idexamen'])) {
             $cond1 .= "t04.id = " . $_POST['idexamen'] . "AND";
             $cond2 .= "t04.id = " . $_POST['idexamen'] . "AND";
        }

        if (!empty($_POST['fechasolicitud'])) {
            $cond1 .= " t02.fecha_solicitud = '" . $_POST['fechasolicitud'] . "' AND";
            $cond2 .= " t02.fecha_solicitud = '" . $_POST['fechasolicitud'] . "' AND";
        }


        if (!empty($_POST['fecharecep'])) {
             $cond1 .= " t03.fecharecepcion = '" . $_POST['fecharecep'] . "' AND";
             $cond2 .= " t03.fecharecepcion = '" . $_POST['fecharecep'] . "' AND";
        }

        if (!empty($_POST['primernombre'])) {

            $cond1 .= " t07.primer_nombre ILIKE  '%".$_POST['primernombre']."%' AND";
            $cond2 .= " t07.primer_nombre ILIKE  '%".$_POST['primernombre']."%' AND";
        }

        if (!empty($_POST['segundonombre'])) {
             $cond1 .= " t07.segundo_nombre  ILIKE '%". $_POST['segundonombre'] ."%' AND";
             $cond2 .= " t07.segundo_nombre  ILIKE '%". $_POST['segundonombre'] ."%' AND";
        }

        if (!empty($_POST['primerapellido'])) {
            $cond1 .= " t07.primer_apellido ILIKE '%".$_POST['primerapellido']."%' AND";
            $cond2 .= " t07.primer_apellido ILIKE '%".$_POST['primerapellido']."%' AND";
        }

        if (!empty($_POST['segundoapellido'])) {
            $cond1 .=" t07.segundo_apellido ILIKE '%".$_POST['segundoapellido']."%' AND";
            $cond2 .=" t07.segundo_apellido ILIKE '%".$_POST['segundoapellido']."%' AND";
        }

        if (!empty($_POST['TipoSolic'])) {
            $cond1 .= "t17.idtiposolicitud = '".$_POST['TipoSolic']."' AND";
            $cond2 .= "t17.idtiposolicitud = '".$_POST['TipoSolic']."' AND";
        }

        if ((empty($_POST['idexpediente'])) AND ( empty($_POST['idarea'])) AND ( empty($_POST['fecha']))
                AND ( empty($_POST['IdEstab'])) AND ( empty($_POST['IdServ'])) AND ( empty($_POST['IdSubServ']))
                AND ( empty($_POST['primernombre'])) AND ( empty($_POST['segundonombre'])) AND ( empty($_POST['primerapellido']))
                AND ( empty($_POST['segundoapellido'])) AND ( empty($_POST['idexamen'])) AND ( empty($_POST['TipoSolic']))) {
            $ban = 1;
        }

        if ($ban == 0) {
            if ((!empty($cond1)) && (!empty($cond2))){
                $cond1 = substr($cond1, 0, strlen($query) - 3);
                $cond2 = substr($cond2, 0, strlen($query) - 3);
                $var1 = $lugar." AND ".$cond1;
                $var2 = $lugar." AND ".$cond2;
                
              
            }
            else{
                $var1 = $lugar;
                $var2 = $lugar;
            }
        }

     $query= "WITH tbl_servicio as (SELECT mnt_3.id, CASE WHEN id_servicio_externo_estab IS NOT NULL
                       THEN mnt_ser.abreviatura ||'--'  || a.nombre
                       ELSE       cmo.nombre ||'--' || a.nombre
                       END as procedencia,

                       CASE WHEN mnt_3.nombre_ambiente IS NOT NULL THEN mnt_3.nombre_ambiente 
                       ELSE cmo.nombre ||'--' ||cat.nombre END AS servicio 

                    FROM ctl_atencion cat 
                    JOIN mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion) 
                    JOIN mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id) 
                    JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion in (1,4)) 
                    LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id 
                    LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id 
                    JOIN mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab) 
                    JOIN ctl_modalidad cmo on (cmo.id=mme.id_modalidad) 
                    WHERE mnt_3.nombre_ambiente IS NOT NULL AND mnt_3.id_establecimiento=$lugar 

                    UNION 

                    SELECT mnt_3.id,CASE WHEN id_servicio_externo_estab IS NOT NULL
                                           THEN mnt_ser.abreviatura ||'--'  || a.nombre
                                           ELSE       cmo.nombre ||'--' || a.nombre
                                           END as procedencia,
                                           cat.nombre AS servicio
                    FROM ctl_atencion cat JOIN mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion) 
                    JOIN mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id) 
                    JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion in (1,4)) 
                    LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id 
                    LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id 
                    JOIN mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab) 
                    JOIN ctl_modalidad cmo on (cmo.id=mme.id_modalidad) 
                    WHERE mnt_3.id_establecimiento=$lugar 
                    AND mnt_3.id_atencion ||'-'|| mnt_3.id_area_mod_estab ||'-'||mnt_3.id_establecimiento NOT IN (SELECT id_atencion ||'-'|| id_area_mod_estab ||'-'||id_establecimiento 
                    FROM mnt_aten_area_mod_estab WHERE nombre_ambiente IS NOT NULL))
                SELECT ordenar.* FROM (
                SELECT t02.id,
                TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                t06.numero AS idnumeroexp,
                CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,
                t07.segundo_apellido,t07.apellido_casada) AS paciente,
                t20.servicio AS nombresubservicio,
                t20.procedencia AS nombreservicio,
                t14.nombre,
                TO_CHAR(t02.fecha_solicitud, 'dd/mm/yyyy HH12:MI') AS fechasolicitud,
                (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                CASE t02.estado
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado,
            TO_CHAR(t15.fechahorareg, 'DD/MM/YYYY HH12:MI') as fecchaconsulta
            FROM sec_solicitudestudios t02
            INNER JOIN lab_recepcionmuestra t03                 ON (t02.id = t03.idsolicitudestudio)
	    INNER JOIN mnt_expediente t06                       ON (t06.id = t02.id_expediente)
            INNER JOIN mnt_paciente t07                         ON (t07.id = t06.id_paciente)
	    INNER JOIN sec_historial_clinico t09                ON (t09.id = t02.id_historial_clinico)
            INNER JOIN mnt_aten_area_mod_estab t10              ON (t10.id = t09.idsubservicio)
            INNER JOIN ctl_atencion t11                         ON (t11.id = t10.id_atencion)
            INNER JOIN mnt_area_mod_estab t12                   ON (t12.id = t10.id_area_mod_estab)
            INNER JOIN ctl_area_atencion t13                    ON (t13.id = t12.id_area_atencion)
            INNER JOIN ctl_establecimiento t14                  ON (t14.id = t09.idestablecimiento)
            INNER JOIN cit_citas_serviciodeapoyo t15            ON (t15.id_solicitudestudios=t02.id)
            INNER JOIN lab_tiposolicitud t17                    ON (t17.id = t02.idtiposolicitud)
            INNER JOIN tbl_servicio t20                         ON (t20.id = t10.id AND t20.servicio IS NOT NULL)
            WHERE (t02.id_atencion=(SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
            AND t02.id_establecimiento = $var1

            UNION

            SELECT
            t02.id,
            TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
            t06.numero AS idnumeroexp,
            CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,
            t07.apellido_casada) AS paciente,
            t20.servicio AS nombresubservicio,
            t20.procedencia AS nombreservicio,
            t14.nombre,
           TO_CHAR(t02.fecha_solicitud, 'dd/mm/yyyy HH12:MI') AS fechasolicitud,
            (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
            CASE t02.estado
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado,
            TO_CHAR(t15.fechahorareg, 'DD/MM/YYYY HH12:MI') as fecchaconsulta
            FROM sec_solicitudestudios t02
            INNER JOIN lab_recepcionmuestra t03                     ON (t03.idsolicitudestudio=t02.id)
            INNER JOIN mnt_dato_referencia t09                      ON t09.id=t02.id_dato_referencia
            INNER JOIN mnt_expediente_referido t06                  ON (t06.id = t09.id_expediente_referido)
            INNER JOIN mnt_paciente_referido t07                    ON (t07.id = t06.id_referido)
            INNER JOIN mnt_aten_area_mod_estab t10                  ON (t10.id = t09.id_aten_area_mod_estab)
            INNER JOIN ctl_atencion t11                             ON (t11.id = t10.id_atencion)
            INNER JOIN mnt_area_mod_estab t12                       ON (t12.id = t10.id_area_mod_estab)
            INNER JOIN ctl_area_atencion t13                        ON (t13.id = t12.id_area_atencion)
            INNER JOIN ctl_establecimiento t14                      ON (t14.id = t09.id_establecimiento)
            INNER JOIN cit_citas_serviciodeapoyo t15                ON (t15.id_solicitudestudios=t02.id)
            INNER JOIN lab_tiposolicitud t17 			    ON (t17.id = t02.idtiposolicitud)
            INNER JOIN tbl_servicio t20                         ON (t20.id = t10.id AND t20.servicio IS NOT NULL)
            WHERE (t02.id_atencion=(SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
            AND t02.id_establecimiento = $var2 ) ordenar
                ORDER BY to_date(ordenar.fecharecepcion, 'DD/MM/YYYY') DESC";

  // echo $query;
        $consulta=$objdatos->BuscarSolicitudesPaciente($query);

        $RegistrosAMostrar=12;
	$RegistrosAEmpezar=($_POST['pag']-1)*$RegistrosAMostrar;
	$PagAct=$_POST['pag'];

	$consulta=$objdatos->consultarpag($query,$RegistrosAEmpezar,$RegistrosAMostrar);
	$NroRegistros= $objdatos->NumeroDeRegistros($query);
	$NroRegistros= $objdatos->NumeroDeRegistros($query);

        if ($NroRegistros=="")
        {
            $NroRegistros=0;

             echo "<table width='35%' border='0'  align='center'>
                    <center>
                        <tr>
                            <td colspan='11' align='center'><span style='color: #0101DF;'> <h3> TOTAL DE SOLICITUDES: ".$NroRegistros."</h3></span>
                            </td>
                        </tr>
                    </center>
                </table> ";

        }else {
                       echo "<table width='35%' border='0'  align='center'>
                    <center>
                        <tr>
                            <td colspan='11' align='center'><span style='color: #0101DF;'> <h3> TOTAL DE SOLICITUDES: ".$NroRegistros."</h3></span>
                            </td>
                        </tr>
                    </center>
                </table> ";

                  }





     echo "<center> <div class='table-responsive' style='width: 80%;'>
         <table width='80%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
                <thead><tr>
			<th>Fecha Recepci&oacute;n</th>
				<th>NEC </th>
				<th>Nombre Paciente</th>
					<th>Procedencia</th>
                                        <th>Servicio</th>
					<th>Establecimiento</th>
					<th>Estado Solicitud</th>
					<th>Fecha Consulta</th>
                    </tr></thead><tbody>";
        if(pg_num_rows($consulta)){
            $pos = 0;

            while ($row = pg_fetch_array($consulta)) {
                echo "<tr>
				   <td width='5%' >".$row['fecharecepcion']."</td>
				   <td ><span style='color: #0101DF;'>
					   <a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(".$pos.");'>".
					   $row['idnumeroexp']."</a>".
					   "</td>".
                                           "<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row[0]."' />".
                                           "<input name='idsoli[".$pos."]' id='idsoli[".$pos."]' type='hidden' size='60' value='".$row[1]."' />".
					   "<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row['idnumeroexp']."' />".
					   "<input name='idestablecimiento[".$pos."]' id='idestablecimiento[".$pos."]' type='hidden' size='60' value='".$IdEstab."' />".
                                           "<input name='fecharecepcion[".$pos."]' id='fecharecepcion[".$pos."]' type='hidden' size='60' value='".$fecharecepcion."' />".
				  "<td>".$row['paciente']."</td>
				   <td>".htmlentities($row['nombreservicio'])."</td>
                                   <td>".htmlentities($row['nombresubservicio'])."</td>    
                                   <td>".htmlentities($row['estabext'])."</td>
				   <td>".$row['estado']."</td>
                                   <td width='9%'>".$row['fecchaconsulta']."</td>


				 </tr>";


                $pos = $pos + 1;

                //



            }
            pg_free_result($consulta);
            echo "<input type='hidden' name='oculto' id='text' value='" . $pos . "' />
                </tbody></table></div></center>";
        } else {
            echo "<tr><td colspan='11'><span style='color: #575757;'>No se han encontrado resultados...</span></td></tr></tbody></table></div></center>";
        }


                    //determinando el numero de paginas
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
				<td><a onclick=\"BuscarDatos('1')\">Primero</a> </td>";
				//// desplazamiento

	if($PagAct>1)
	 		 echo "<td> <a onclick=\"BuscarDatos('$PagAnt')\">Anterior</a> </td>";
	 if($PagAct<$PagUlt)
			 echo "<td> <a onclick=\"BuscarDatos('$PagSig')\">Siguiente</a> </td>";
		 	 echo "<td> <a onclick=\"BuscarDatos('$PagUlt')\">Ultimo</a></td></tr>
                 </table>";


                    /*     echo "<table align='center'>
			<tr align='center'><td  colspan='2' width='25%'>";
		 $numPags ='';
			 for ($i=1 ; $i<=$PagUlt; $i++){
				 if ($pag == $i)
					 $numPags .= "<a >$pag</a>";

				 else
					 $numPags .= "<a  href='javascript: BuscarDatos(".$i.")'>$i</a>&nbsp;";
			 }
				 echo $numPags."</td></tr>
		</table>";*/

                         echo " <center> <ul class='pagination'>";
                          for ($i=1 ; $i<=$PagUlt; $i++)
                                    {

					 echo " <li ><a  href='javascript: BuscarDatos(".$i.")'>$i</a></li>";
                                     }
                    echo " </ul></center>";





	break;

	case 2: 

                $idexpediente       =$_POST['idexpediente'];
	        $idsolicitud        =$_POST['idsolicitud'];
                $fecharecepcion     =$_POST['fecharecepcion'];
              //  echo $fecharecepcion;
		//$IdEstablecimiento  =$POST['idestablecimiento'];
		//$IdEstablecimiento;
		//include_once("clsSolicitudesPorPaciente.php");
		//recuperando los valores generales de la solicitud

		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
		$row = pg_fetch_array($consulta);
		//obteniedo los datos generales de la solicitud
		//valores de las consultas
                $idsolicitudPadre=$row[0];
		$medico=$row['medico'];
		$idmedico=$row[1];
		$paciente=$row['paciente'];
		$edad=$row['edad'];
		$sexo=$row['sexo'];
		$precedencia=$row['nombreservicio'];
		$origen=$row['nombresubservicio'];
                $expediente=$row['expediente'];
                $fechaConsulta=$row['fecha_solicitud'];
		//$DatosClinicos=$row['DatosClinicos'];

		$DatosClinicos=$DatosClinicos=isset($row['DatosClinicos']) ? $row['DatosClinicos'] : null ;
		$Estado=$row['estado'];
                $ConocidoPor=isset($row['conocodidox']) ? $row['conocodidox'] : null ;
                $idarea=isset($row['idarea']) ? $row['idarea'] : null ;
//		$fechasolicitud=$row['FechaSolicitud'];
//                $FechaNac=$row['FechaNacimiento'];
		$fechasolicitud=$DatosClinicos=isset($row['FechaSolicitud']) ? $row['FechaSolicitud'] : null ;
		$Diagnostico=$DatosClinicos=isset($row['diagnostico']) ? $row['diagnostico'] : null ;
                $FechaNac=isset($row['FechaNacimiento']) ? $row['FechaNacimiento'] : null ;
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);

		//$estadosolicitud=$objdatos->EstadoSolicitud($idexpediente,$idsolicitud);
		//$estado=pg_fetch_array($estadosolicitud);



		$imprimir="<form name='frmDatos'>
		<table width='55%' border='0' align='center'>
			<tr>
				<td  colspan='4'>&nbsp;&nbsp;&nbsp;&nbsp</td>
			</tr>
			<tr>
				<td colspan='4' align='center' class='CobaltFieldCaptionTD'>
					<h3><strong>DATOS SOLICITUD</strong></h3>
			</tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Establecimiento</td>
                                <td class='StormyWeatherDataTD' colspan='3'>".$row['estabext']."</td>
			</tr>
		        <tr>    <td class='StormyWeatherFieldCaptionTD'>Expediente</td>
                                <td class='StormyWeatherDataTD'>".$row['expediente']."</td>
				<td class='StormyWeatherFieldCaptionTD'>Paciente</td>
				<td colspan='1' class='StormyWeatherDataTD'>".htmlentities($paciente)."
                        	     <input name='txtpaciente' id='txtpaciente' type='hidden' size='' value=$paciente disabled='disabled' /></td>
                        </tr>
                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Conocido por</td>
				<td colspan='3' class='StormyWeatherDataTD'>".htmlentities($ConocidoPor)."
			     		<input name='txtpaciente' id='txtpaciente' type='hidden' size='70' value='".$paciente."' disabled='disabled' /></td>
		   	</tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Edad</td>
				<td class='StormyWeatherDataTD'>".htmlentities($edad)."
			     		<input name='txtpaciente' id='txtpaciente1' type='hidden' size='35' value='".$edad."' disabled='disabled' /></td>


                                </td>
				<td class='StormyWeatherFieldCaptionTD'>Sexo</td>
				<td class='StormyWeatherDataTD'>$sexo<input type='hidden' name='txtsexo' value='".$sexo."' disabled='disabled' /></td>
                        </tr>
                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Procedencia</td>
				<td class='StormyWeatherDataTD'>$precedencia
                                    <input name='txtprecedencia' id='txtprecedencia' type='hidden' size='35' value='".$precedencia."' disabled='disabled' />
                                </td>
				<td class='StormyWeatherFieldCaptionTD'>Servicio</td>
				<td class='StormyWeatherDataTD'>".htmlentities($origen)."
					<input name='txtorigen' id='txtorigen'  type='hidden' size='35' value='".$origen."' disabled='disabled' />
                                        <input name='idsolicitudPadre' id='idsolicitudPadre'  type='hidden' size='40' value='".$idsolicitudPadre."' disabled='disabled' />
					<input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='".$idsolicitud."' disabled='disabled' />
					<input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='".$idexpediente."' disabled='disabled' />
                                        <input name='fecharecepcion' id='fecharecepcion'  type='hidden' size='40' value='".$fecharecepcion."' disabled='disabled' />
				</td>
                        </tr>
                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>M&eacute;dico</td>
				<td colspan='1' class='StormyWeatherDataTD'>".htmlentities($medico)."
				   <input name='txtmedico' id='txtmedico'  type='hidden' size='70' value='".$medico."' disabled='disabled' /></td>
                                <td class='StormyWeatherFieldCaptionTD'>Fecha Consulta</td> 
                                <td colspan='3' class='StormyWeatherDataTD'>". $fechaConsulta."</td>
                        </tr>
                        <tr>
                                <td class='StormyWeatherFieldCaptionTD'>Diagnostico</td>
                                <td colspan='3' class='StormyWeatherDataTD'>". $Diagnostico."</td>
                        </tr>
                        <tr>

</table>
    <br><br>
                <table style='width:55%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>

                    <thead> <tr>

                        <th colspan='5'   class='CobaltFieldCaptionTD'>
					<h3><strong><center>ESTUDIOS SOLICITADO</center></strong></h3> </th>
                            </tr>
                                <tr>
                                    <th><center>    IdExamen    <center></th>
                                    <th><center>    Examen      <center></th>
                                    <th><center>    IdArea      <center></th>
                                    <th><center>    Indicacion Medica <center></th>
                                    <th><center>    Estado            <center></th>
			   	 </tr>
                    </thead><tbody>";
			$pos=0;
	while($fila = pg_fetch_array($consultadetalle)){
                    $imprimir .= "<tr>
                                    <td width='10%'>".$fila['idestandar']."</td>
                                    <td width='39%'>".htmlentities($fila['nombre_examen'])."</td>
                                    <td width='7%'>".$fila['codigo_area']."</td>";
            	if (!empty($fila['indicacion'])){
		       $imprimir .="<td width='20%'>".htmlentities($fila['indicacion'])."</td>";
		       $imprimir .="<td colspan='2' >".htmlentities($fila['estado'])."</td>
                                </tr>";
				}else{
                       $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td>
		                    <td colspan='2'>".$fila['estado']."</td>
                    </tr>";
				}
   	}
	 $imprimir .="</table> </center>";

			pg_free_result($consultadetalle);

 	//$imprimir .= "<i/nput type='hidden' name='oculto' id='oculto' value='".$pos."' />
	 $imprimir .="</table>
             <center>
	<div id='divImpresion' style='display:block' >

		<table>
			<tr >
				<p></p>
			</tr>
			<tr >
				<td align='center'>
					<!--<input type='button' name='btnImprimirSol' class='btn btn-primary' id='btnImprimirSol' value='Agregar Examen' onClick='ImprimirSolicitud()' <span class='glyphicon glyphicon-search'>-->
					 <button type='button' align='center' class='btn btn-primary' id='buscarsolicitud' onclick='ImprimirSolicitud(); '><span class='glyphicon glyphicon-plus-sign'></span> Agregar Examen</button>";
				$imprimir .="</td>
			</tr>
		</table>
	</div></center>
</form>";
     echo $imprimir;


   	break;
   	case 3:
   		$idexpediente=$_POST['idexpediente'];
		$idsolicitud=$_POST['idsolicitud'];
		$idestablecimiento=$_POST['idestablecimiento'];
		include_once("clsAgregarExamen.php");
		//recuperando los valores generales de la solicitud
		//echo $idestablecimiento;





 $imprimir .= "<input type='hidden' name='oculto' id='oculto' value='".$pos."' />
			</table>
			<div id='divImpresion' style='display:block' >
				<table aling='center'>
				<tr >
				<p></p>
				</tr>
				<td aling='center'><input type='button' name='btnCerrar' id='btnCerrar' value='Cerrar' onClick='Cerrar()'></td>
				<td ><input type='button' name='btnImprimir' id='btnImprimir'  value='Imprimir' onClick='Imprimir()'></td>
				</tr>
				</table>
			</div>
			</form>";
     echo $imprimir;

   break;
	case 5://LLENANDO COMBO DE Examenes
		$rslts='';

		$idarea=$_POST['idarea'];
		//echo $IdSubEsp;
		$dtExam=$objdatos->ExamenesPorArea($idarea,$lugar);
		$rslts = '<select name="cmbExamen" id="cmbExamen"  onchange="LlenarComboMuestra1(this.value);" class="form-control height"  style="width:405px !important" size="1">';
		$rslts .='<option value="0"> Seleccione Examen </option>';

		while ($rows =pg_fetch_array($dtExam)){
			$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
		}

		$rslts .= '</select>';
		echo $rslts;


   	break;

	case 6:// Llenar Combo Establecimiento
		$rslts='';
		$Idtipoesta=$_POST['idtipoesta'];

                if ($Idtipoesta<>0){
                    $dtIdEstab=$objdatos->LlenarCmbEstablecimiento($Idtipoesta);
                    $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:500px" class="js-example-basic-single">';
                    //$rslts .='<option value="0"> Seleccione Establecimiento </option>';
                    while ($rows =pg_fetch_array( $dtIdEstab)){
                        $rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
                    }
		}else{
                    $dtIdEstab = $objdatos->LlenarTodosEstablecimientos();
                    $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:500px" class="js-example-basic-single">';
                    $rslts .='<option value="0"> Seleccione Establecimiento </option>';
                    while ($rows = pg_fetch_array($dtIdEstab)) {
                        $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
                    }
                }

		$rslts .= '</select>';
		echo $rslts;
   	break;
	case 7:// Llenar combo Subservicio
   	     $rslts='';
            $IdServ=$_POST['IdServicio'];
            $IdAreaAtencion=$objdatos->ObtenerAreaAtencion($IdServ, $lugar);
	   //  echo $IdServ;
            if ($IdAreaAtencion==6){
                //echo $IdServ;
                $dtserv=$objdatos->LlenarReferido($IdServ,$lugar);
                $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:500px" class="form-control height" >';
                while ($rows =pg_fetch_array($dtserv)){
                    $rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
                }
                
            }else{
                
	     $dtserv=$objdatos->LlenarCmbServ($IdServ,$lugar,$IdAreaAtencion);
	     $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:500px" class="form-control height" >';
			$rslts .='<option value="0"> Seleccione Subespecialidad </option>';
			while ($rows =pg_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
            }   		}

	      $rslts .='</select>';
	      echo $rslts;



        break;
	case 8://Llenar tipo muestra
		 $rslts='';
         $IdExamen=$_POST['IdExamen'];
	   //  echo $IdServ;
	     $dtserv=$objdatos->LlenarCmbTipoMuestra($IdExamen);
	     $rslts = '<select name="cmbMuestra" id="cmbMuestra" class="form-control height" style="width:405px" onchange="LlenarComboOrigen1(this.value)">';
		 $rslts .='<option value="0">--Seleccione Tipo de Muestra--</option>';
		 while ($rows =pg_fetch_array($dtserv)){
				$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	     }

	      $rslts .='</select>';
	      echo $rslts;
	break;
	case 9://Llenar origen muestra
		 $rslts='';
        $IdTipo=$_POST['IdTipo'];
                $idexpediente=$_POST['idexpediente'];
                $idsolicitud=$_POST['idsolicitud'];

	     $dtTipo=$objdatos->LlenarCmbOrigenMuestra($IdTipo);

             $rslts = '<select name="cmbOrigen" id="cmbOrigen" class="form-control height" style="width:405px">';
             if (@pg_num_rows($dtTipo)>0){
	     $rslts .='<option value="0">--Seleccione Origen de Muestra--</option>';
//
//		 $rslts='';
//        $IdTipo=$_POST['IdTipo'];
//
//	     $dtTipo=$objdatos->LlenarCmbOrigenMuestra($IdTipo);
//		 $rslts = '<select name="cmbOrigen" id="cmbOrigen" class="form-control height" style="width:405px">';
//
//		$rslts='';
//                $IdTipo=$_POST['IdTipo'];
//                $idexpediente=$_POST['idexpediente'];
//                $idsolicitud=$_POST['idsolicitud'];

               // echo  $IdTipo;


//
//
//               $dtTipo=$objdatos->LlenarCmbOrigenMuestra($IdTipo);
//
//		 $rslts = '<select name="cmbOrigen" id="cmbOrigen" style="width:375px">';
///		 $rslts .='<option value="0">--Seleccione Origen de Muestra--</option>';

		 while ($rows =pg_fetch_array($dtTipo)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	     }
             }
             else{
                $rslts .='<option value="0">--No aplica--</option>';
             }

	      $rslts .='</select>';



	      echo $rslts;
	break;
	case 10:/*    Agregar Examen   */
		$idsolicitud=$_POST['idsolicitud'];
                $idsolicitudPa=$_POST['idsoli']; //si!!
		$IdExamen=$_POST['idExamen'];       // si!!
		$indicacion=$_POST['indicacion'];  // si!!
		$IdTipo=$_POST['tipoMuestra'];    // si
		$Observa=$_POST['Observacion'];    //si
		$Empleado=$_POST['IdEmpleado'];  // si!!
		$IdEstab=$_POST['IdEstab'];     //si!!
                $origen=$_POST['OrigenMuestra'];        //si
                $fechatomamuestra=$_POST['fechatomamuestra'];
                $idarea=$_POST['idarea'];
                $idsuministrante=$_POST['idsuministrante'];
                //echo $idarea;

              //echo 'idtipo='.$IdTipo."**".$origen.'**'.$idsolicitud."%%".$IdEmpleado."%%".$idsolicitudPa.'//'.$IdExamen.'**'.$IdEstab;
                $consulta=$objdatos->opteneridexamen($IdExamen);
		$row = pg_fetch_array($consulta);
		$idexamen1=$row['idexa'];

		$conExam=$objdatos->BuscarExamen($idsolicitudPa,$IdExamen,$lugar);
		$ExisExa=pg_fetch_array($conExam);

		if($ExisExa[0]>=1){
			echo "El examen ya existe en la solicitud";
		}
		else{

                    if ($idarea==14)
                        $estado=7;
                    else
                        $estado=5;

                    if ($origen==0){
                        //$var=$objdatos->insertar_Examensin($idsolicitudPa,$idexamen1,$IdExamen,$indicacion,$IdTipo,$Observa,$lugar,$Empleado,$usuario,$IdEstab,$fechatomamuestra);
                      //echo $var;
                            if (($objdatos->insertar_Examensin($idsolicitudPa,$idexamen1,$IdExamen,$indicacion,$IdTipo,$Observa,$lugar,$Empleado,$usuario,$IdEstab,$fechatomamuestra,$estado, $idsuministrante)==1) && ($objdatos-> CambiarEstadoSolicitud($idsolicitudPa)==TRUE)){
				echo "Examen Agregado!!";
                            }
                            else{
				echo "No se pudo Agregar el Examen!!".$idsolicitudPa.'-'.$idexamen1.'-'.$IdExamen.'-'.$indicacion.'-'.$IdTipo.'-'.$Observa.'-'.$lugar.'-'.$Empleado.'-'.$usuario.'-'.$IdEstab.'-'.$fechatomamuestra;
			}

                    }
                    else{

                        if (($objdatos->insertar_Examen($idsolicitudPa,$idexamen1,$IdExamen,$indicacion,$IdTipo,$Observa,$lugar,$Empleado,$usuario,$IdEstab,$origen,$fechatomamuestra,$estado, $idsuministrante)==1) && ($objdatos-> CambiarEstadoSolicitud($idsolicitudPa)==TRUE)){
				echo "Examen Agregado!!";
			}
			else{
				echo "No se pudo Agregar el Examen 2!!".$idsolicitudPa.'-'.$idexamen1.'-'.$IdExamen.'-'.$indicacion.'-'.$IdTipo.'-'.$Observa.'-'.$lugar.'-'.$Empleado.'-'.$usuario.'-'.$IdEstab.'-'.$fechatomamuestra.'-'.$origen;
			}

                    }

		}

	break;
        case 11:
             $rslts='';
                $IdTipo=$_POST['IdTipo'];
                $idexpediente=$_POST['idexpediente'];
                $idsolicitud=$_POST['idsolicitud'];
                $fecharecep=$_POST['fecharecep'];
                $consultadetalle=$objdatos->obtener_fecha_tomamuestra($idexpediente,$idsolicitud,$lugar,$IdTipo);
                $row_detalle = pg_fetch_array($consultadetalle);
                if (!empty($row_detalle[0]))
                    $rslts .='<input type="text" style="width:405px" class="datepicker form-control height" name="txttomamuestra" id="txttomamuestra" size="15"  value="'. $row_detalle['fechatomamuestra'].'"/>';
                else {
                     $consrecep=$objdatos->BuscarFechaRecepcion($idsolicitud,$lugar);
                     $row_recep = pg_fetch_array($consrecep);
                     $rslts .='<input type="text" style="width:405px" class="datepicker form-control height" name="txttomamuestra" id="txttomamuestra" size="15"  value="'. $row_recep['fecharecepcion'].'"/>';
                }
                echo $rslts;
        break;

        case 12://Llenar a realizar
    		 $rslts='';
             $IdExamen=$_POST['IdExamen'];
    	     $dtserv=$objdatos->llenararealizar($IdExamen);
    	     $rslts = '<select name="cmbSuministrante" id="cmbSuministrante" class="form-control height" style="width:405px">';
             $cantisumi = pg_num_rows($dtserv);
             if ($cantisumi==1){
                 $rows=pg_fetch_array($dtserv);
                 $rslts.='<option selected value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
             }
             else{
                 $rslts .='<option value="0">--Seleccione una opción--</option>';
                 while ($rows =pg_fetch_array($dtserv)){
        		 $rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
        	     }
             }
    	      $rslts .='</select>';
    	      echo $rslts;
    	break;


}//switch

?>
