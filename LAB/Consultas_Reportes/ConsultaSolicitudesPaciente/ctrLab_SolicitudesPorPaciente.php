<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
include ("clsSolicitudesPorPaciente.php");

//variables POST
$opcion= $_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsSolicitudesPorPaciente;
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
        $IdServ         = $_POST['IdServ'];
        $IdSubServ      = $_POST['IdSubServ'];
        //$idarea         = $_POST['idarea'];
        //$idexamen       = $_POST['idexamen'];
        $idexpediente   = $_POST['idexpediente'];
       //$fechasolicitud = $_POST['fechasolicitud'];
       $fecharecepcion = $_POST['fechaconsulta'];
        $PNombre        = $_POST['primernombre'];
        $SNomre         = $_POST['segundonombre'];
        $PApellido      = $_POST['primerapellido'];
        $SApellido      = $_POST['segundoapellido'];
        //$TipoSolic      = $_POST['TipoSolic'];
        $cond1="";
        $cond2="";
        $query="";
        $query2="";
        $where_with="";
        
         $idexpediente="'".$idexpediente."'";
         $cond0="and";
        
        
        
        
      //  echo $IdEstab." - ".$lugar;
        if (!empty($_POST['IdEstab'])) {
           if ($_POST['IdEstab']<>$lugar){
               $cond1 .=$cond0. "  t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " ";
               $cond2 .=$cond0. "  t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " ";
           }
          
        }
        
        if (!empty($_POST['IdSubServ'])) {
            $cond1 .= $cond0." t10.id = " . $_POST['IdSubServ'] . "    ";
            $cond2 .= $cond0." t10.id = " . $_POST['IdSubServ'] . "   ";
        }

        if (!empty($_POST['IdServ'])) {
            $cond1 .=$cond0 ."  t13.id  = " . $_POST['IdServ'] . "     ";
            $cond2 .=$cond0 ."  t13.id  = " . $_POST['IdServ'] . "     ";
            $where_with = "id_area_atencion = $IdServ AND ";
        }
  
        if (!empty($_POST['idarea'])) {
            $cond1 .= " and t08.id = " . $_POST['idarea'] . " ";
            $cond2 .= " and t08.id = " . $_POST['idarea'] . " ";
        }

        if (!empty($_POST['idexpediente'])) {
          $idexpediente="'".$idexpediente."'";
            
            $cond1 .= "and t06.numero = '".$_POST['idexpediente'] ."'    ";
            $cond2 .= "and t06.numero = '".$_POST['idexpediente'] ."'   ";
        }

        if (!empty($_POST['idexamen'])) {
             $cond1 .= " and t04.id = " . $_POST['idexamen'] . " ";
             $cond2 .= " and t04.id = " . $_POST['idexamen'] . " ";
        }

        if (!empty($_POST['fechasolicitud'])) {
             $cond1 .= " and t02.fecha_solicitud = '" . $_POST['fechasolicitud'] . "' ";
             $cond2 .= " and  t02.fecha_solicitud = '" . $_POST['fechasolicitud'] . "' ";
        }

        if (!empty($_POST['fechaconsulta'])) {
             $cond1 .= " and t03.fecharecepcion = '".$_POST['fechaconsulta']."'       ";
             $cond2 .= " and t03.fecharecepcion = '".$_POST['fechaconsulta']."'       ";
        }

        if (!empty($_POST['primernombre'])) {
          
            $cond1 .= " and t07.primer_nombre  ILIKE  '".$_POST['primernombre']."%'      ";
            $cond2 .= " and  t07.primer_nombre ILIKE  '".$_POST['primernombre']."%'      ";
        }

        if (!empty($_POST['segundonombre'])) {
             $cond1 .= " and t07.segundo_nombre  ILIKE '". $_POST['segundonombre'] ."%'       ";
             $cond2 .= " and t07.segundo_nombre  ILIKE '". $_POST['segundonombre'] ."%'       ";
        }

        if (!empty($_POST['primerapellido'])) {
            $cond1 .= " and  t07.primer_apellido ILIKE '".$_POST['primerapellido']."%'         ";
            $cond2 .="  and  t07.primer_apellido ILIKE '".$_POST['primerapellido']."%'         ";
        }

        if (!empty($_POST['segundoapellido'])) {
            $cond1 .=" and t07.segundo_apellido ILIKE '".$_POST['segundoapellido']."%'       ";
            $cond2 .=" and t07.segundo_apellido ILIKE '".$_POST['segundoapellido']."%'       ";
        }

        if (!empty($_POST['TipoSolic'])) {
            $cond1 .= " and t17.idtiposolicitud = '".$_POST['TipoSolic']."'  ";
            $cond2 .= " and t17.idtiposolicitud = '".$_POST['TipoSolic']."'  ";
        }

        if ((empty($_POST['idexpediente'])) AND ( empty($_POST['idarea'])) AND ( empty($_POST['fecha']))
                AND ( empty($_POST['IdEstab'])) AND ( empty($_POST['IdServ'])) AND ( empty($_POST['IdSubServ']))
                AND ( empty($_POST['primernombre'])) AND ( empty($_POST['segundonombre'])) AND ( empty($_POST['primerapellido']))
                AND ( empty($_POST['segundoapellido'])) AND ( empty($_POST['idexamen'])) AND ( empty($_POST['TipoSolic']))) {
            $ban = 1;
        }
        
        if ($ban == 0) {

            $cond1 = substr($cond1, 0, strlen($query) - 3);
            $cond2 = substr($cond2, 0, strlen($query) - 3);
            
          //  echo $query1;
           // $query_search = 
          // echo $cond1;
          // echo $cond2;
        }     
       // echo $cond2;
         $query="WITH tbl_servicio AS (SELECT t02.id,
                CASE WHEN t02.nombre_ambiente IS NOT NULL THEN
                    t02.nombre_ambiente
                    ELSE
                            CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura  ||'   -   ' ||  t01.nombre
                                 WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=t01.nombre)
                                   
                                    THEN t01.nombre
                    END

                END AS servicio,
               (CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'-->'  || t06.nombre
                    ELSE   t07.nombre ||'-->' || t06.nombre
                END) as procedencia 
            FROM ctl_atencion t01 
            INNER JOIN mnt_aten_area_mod_estab t02 ON (t01.id = t02.id_atencion) 
            INNER JOIN mnt_area_mod_estab t03 ON (t03.id = t02.id_area_mod_estab) 
            LEFT JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab) 
            LEFT JOIN mnt_servicio_externo t05 ON (t05.id = t04.id_servicio_externo) 
            INNER JOIN ctl_area_atencion t06 on t06.id = t03.id_area_atencion 
            INNER JOIN ctl_modalidad t07 ON t07.id = t03.id_modalidad_estab WHERE t02.id_establecimiento = $lugar ORDER BY 2)
                     SELECT ordenar.* FROM (
                 SELECT 
                t02.id, 
                TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                t06.numero AS idnumeroexp, 
                CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,
                t07.segundo_apellido,t07.apellido_casada) AS paciente,
                t20.servicio AS nombresubservicio,
                t20.procedencia AS nombreservicio,
                -- t13.nombre AS nombreservicio, 
                t14.nombre, 
                TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud, 
                (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                CASE t02.estado 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso'    
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo'
                        WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='E') THEN 'Cancelado(a)' END AS estado,
            TO_CHAR(t15.fechahorareg, 'DD/MM/YYYY') as fecchaconsulta,
            t02.estado as idestado
            FROM sec_solicitudestudios              t02                
            INNER JOIN lab_recepcionmuestra         t03      ON (t03.idsolicitudestudio=t02.id) 
	    INNER JOIN mnt_expediente               t06      ON (t06.id = t02.id_expediente) 
            INNER JOIN mnt_paciente                 t07      ON (t07.id = t06.id_paciente) 
	    INNER JOIN sec_historial_clinico        t09      ON (t09.id = t02.id_historial_clinico) 
            INNER JOIN mnt_aten_area_mod_estab      t10      ON (t10.id = t09.idsubservicio) 
            INNER JOIN ctl_atencion                 t11      ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab           t12      ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion            t13      ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento          t14      ON (t14.id = t09.idestablecimiento) 
            INNER JOIN cit_citas_serviciodeapoyo    t15      ON (t15.id_solicitudestudios=t02.id) 
            INNER JOIN lab_tiposolicitud            t17      ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN tbl_servicio                 t20      ON (t20.id = t10.id AND t20.servicio IS NOT NULL)
            WHERE (t02.id_atencion=(SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
            AND t02.id_establecimiento = $lugar $cond1
            
        
           UNION

            SELECT 
            t02.id, 
            TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
            t06.numero AS idnumeroexp,
            CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,
            t07.apellido_casada) AS paciente, 
            t11.nombre AS nombresubservicio, 
            t13.nombre AS nombreservicio, 
            t14.nombre,
            TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud, 
            (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
            CASE t02.estado 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso'    
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' 
                        WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='CA') THEN 'Cancelad0(a)' 
                        END AS estado,
            TO_CHAR(t15.fechahorareg, 'DD/MM/YYYY') as fecchaconsulta,
            t02.estado as idestado
            FROM sec_solicitudestudios              t02                    	   
            INNER JOIN lab_recepcionmuestra         t03         ON (t03.idsolicitudestudio=t02.id) 
            INNER JOIN mnt_dato_referencia          t09         ON t09.id=t02.id_dato_referencia 
            INNER JOIN mnt_expediente_referido      t06         ON (t06.id = t09.id_expediente_referido) 
            INNER JOIN mnt_paciente_referido        t07         ON (t07.id = t06.id_referido) 
            INNER JOIN mnt_aten_area_mod_estab      t10         ON (t10.id = t09.id_aten_area_mod_estab) 
            INNER JOIN ctl_atencion                 t11         ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab           t12         ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion            t13         ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento          t14         ON (t14.id = t09.id_establecimiento)
            INNER JOIN cit_citas_serviciodeapoyo    t15         ON (t15.id_solicitudestudios=t02.id) 
            INNER JOIN lab_tiposolicitud            t17 	ON (t17.id = t02.idtiposolicitud) 
            WHERE (t02.id_atencion=(SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
            AND t02.id_establecimiento =$lugar $cond2  ) ordenar
                ORDER BY to_date(ordenar.fecharecepcion, 'DD/MM/YYYY') DESC"; 
     // echo $query;             
                 
     $consulta=$objdatos->BuscarSolicitudesPaciente($query); 
         
         $RegistrosAMostrar=10;
	$RegistrosAEmpezar=($_POST['pag']-1)*$RegistrosAMostrar;
	$PagAct=$_POST['pag'];
				
	$consulta=$objdatos->consultarpag($query,$RegistrosAEmpezar,$RegistrosAMostrar);
	$NroRegistros= $objdatos->NumeroDeRegistros($query);
				
    		
		$NroRegistros= $objdatos->NumeroDeRegistros($query);				
     
    if (empty($NroRegistros)){
         $NroRegistros=0;
       echo"<table width='90%' border='0'  align='center'>
                <center>
                    <tr>
                        <td colspan='8' align='center'><span style='color: #0101DF;'> <h3> TOTAL DE SOLICITUDES:".$NroRegistros."</h3></span></td>
                    </tr>
                </center>
            </table> ";
    }else {
       echo"<table width='90%' border='0'  align='center'>
                <center>
                    <tr>
                        <td colspan='8' align='center'><span style='color: #0101DF;'> <h3> TOTAL DE SOLICITUDES:".$NroRegistros."</h3></span></td>
                    </tr>
                </center>
	    </table> ";
    }               
                                                    
     echo "<center>
            <div class='table-responsive' style='width: 95%;'>
                <table width='95%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
                    <thead>
                        <tr>
                            <td>NEC                 </th>
                                <th>Nombre Paciente     </th>
                                <th>Origen              </th>
                                <th>Procedencia         </th>
                                <th>Establecimiento     </th>
                                <th>Estado Solicitud    </th>
                                <th>Fecha Consulta      </th>
                                <th>Fecha Recepci&oacute;n      </th>
                        </tr>
                    </thead><tbody>"; 
        if(@pg_num_rows($consulta)){
            $pos = 0;

            while ($row = pg_fetch_array($consulta)) {
               // $esta=$row['estado'];
                  echo "<tr>";
                    if($row['idestado'] <> 8){ 
                       echo"<td width='5%'><span style='color: #0101DF;'>
			        <a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(".$pos.");'>".
					   $row['idnumeroexp']."</a>". 
					   "</td>"; 
                    } else {
                        echo "<td width='5%'>".$row['idnumeroexp']."</a></td>"; 
                    }            
                       echo "<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row[0]."' />".
                               "<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row['idnumeroexp']."' />".
                               "<input name='idestablecimiento[".$pos."]' id='idestablecimiento[".$pos."]' type='hidden' size='60' value='".$IdEstab."' />".
                               
		           "<td width='20%'>".$row['paciente']."</td>
			    <td width='12%'>".htmlentities($row['nombresubservicio'])."</td>
			    <td width='10%'>".htmlentities($row['nombreservicio'])."</td>
                            <td width='22%'>".htmlentities($row['estabext'])."</td>
			    <td width='9%'>".$row['estado']."</td>
                            <td width='8%'>".$row['fecchaconsulta']."</td>
                            <td width='8%'>".$row['fecharecepcion']."</td>    
			</tr>";
            
                $pos = $pos + 1;
        }
        
            pg_free_result($consulta);
            echo "<input type='hidden' name='oculto' id='text' value='" . $pos . "' />
                </table>";
        } else {
            echo "<tr><td colspan='11'><span style='color: #575757;'>No se han encontrado resultados...</span></td></tr>"
            . "</table>";
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
	 		 echo "<td> <a onclick=\"BuscarDatospaciente('$PagAnt')\">Anterior</a> </td>";
	 if($PagAct<$PagUlt)  
			 echo "<td> <a onclick=\"BuscarDatospaciente('$PagSig')\">Siguiente</a> </td>";
		 	 echo "<td> <a onclick=\"BuscarDatospaciente('$PagUlt')\">Ultimo</a></td></tr>
                 </table>";
	                                    
                        echo " <center> <ul class='pagination'>";
                          for ($i=1 ; $i<=$PagUlt; $i++)
                           {
                              echo " <li ><a  href='javascript: BuscarDatospaciente(".$i.")'>$i</a></li>";
                           }
                    echo " </ul></center>";
	break;
    	
	case 2:  // solicitud estudios
		$idexpediente       =$_POST['idexpediente'];
		$idsolicitud        =$_POST['idsolicitud'];
                
		//$IdEstablecimiento  =$POST['idestablecimiento'];
		//$IdEstablecimiento;
		include_once("clsSolicitudesPorPaciente.php");
		//recuperando los valores generales de la solicitud
		
		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
		$row = pg_fetch_array($consulta);
                //$Estado1=$row['estado1'];
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
		//$DatosClinicos=$row['DatosClinicos'];
                $Estado=$row['estado'];
                $ConocidoPor=$row['conocodidox'];
                $Diagnostico=$row['diagnostico'];
		//$fechasolicitud=$row['FechaSolicitud'];
                //$FechaNac=$row['FechaNacimiento'];
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
		//$estadosolicitud=$objdatos->EstadoSolicitud($idexpediente,$idsolicitud);
		//$estado=pg_fetch_array($estadosolicitud);
		
		
		$imprimir="<form name='frmDatos'>
                    <table width='45%' border='0' align='center'>
			<tr>
                            <td  colspan='4'>&nbsp;&nbsp;&nbsp;&nbsp</td>
			</tr>
			<tr>
                            <td colspan='4' align='center' class='CobaltFieldCaptionTD'>
				<h3><strong>DATOS SOLICITUD</strong></h3>
			</tr>
			<tr>
                            <td class='StormyWeatherFieldCaptionTD'>Establecimiento Solicitante</td>
                            <td class='StormyWeatherDataTD' colspan='3'>".$row['estabext']."</td>
			</tr>
		        <tr>
                            <td class='StormyWeatherFieldCaptionTD'width='25%'>Paciente</td>
                            <td class='StormyWeatherDataTD'>".htmlentities($paciente)." 
                                <input name='txtpaciente' id='txtpaciente' type='hidden' size='' value=$paciente disabled='disabled' /></td>
                            <td class='StormyWeatherFieldCaptionTD'>Expediente</td>
                            <td class='StormyWeatherDataTD'>".$idexpediente."</td>         
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
                            <td class='StormyWeatherDataTD'>$precedencia <input name='txtprecedencia' id='txtprecedencia' 
				type='hidden' size='35' value='".$precedencia."' disabled='disabled' />
                            </td>
                            <td class='StormyWeatherFieldCaptionTD'>Origen</td>
                            <td class='StormyWeatherDataTD'>".htmlentities($origen)."
                            	<input name='txtorigen' id='txtorigen'  type='hidden' size='35' value='".$origen."' disabled='disabled' />
                                <input name='idsolicitudPadre' id='idsolicitudPadre'  type='hidden' size='40' value='".$idsolicitudPadre."' disabled='disabled' />
				<input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='".$idsolicitud."' disabled='disabled' />
				<input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='".$idexpediente."' disabled='disabled' />
                            </td>
			</tr>
                        <tr>
                            <td class='StormyWeatherFieldCaptionTD'>M&eacute;dico</td>
                            <td colspan='3' class='StormyWeatherDataTD'>".htmlentities($medico)."
				<input name='txtmedico' id='txtmedico'  type='hidden' size='70' value='".$medico."' disabled='disabled' /></td>
                        </tr>
                        <tr>
                            <td class='StormyWeatherFieldCaptionTD'>Diagnostico</td>
                            <td colspan='3' class='StormyWeatherDataTD'>". $Diagnostico."</td>
                        </tr>
                        <tr><td colspan='4'>
                            <table width='50%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
                                <thead>
                                    <tr>
                                        <th colspan='5'  class='CobaltFieldCaptionTD' ><center>ESTUDIOS SOLICITADO</center></th>
                                    </tr>
                                    <tr>
                                        <th > IdExamen</th>
                                        <th > Examen </th>
                                        <th > IdArea </th>
                                        <th > Indicacion Medica </th>
                                        <th  > Estado </th>
                                   </tr>
                                   </thead><tbody>"; 
                                $pos=0;
                                while($fila = pg_fetch_array($consultadetalle)){

                      $imprimir .= "<tr>
                                        <td width='10%'>".$fila['codigo_examen']."</td>
                                        <td width='39%'>".htmlentities($fila['nombre_examen'])."</td>	
                                        <td width='7%'>".$fila['codigo_area']."</td>";	
                                    if (!empty($fila['indicacion'])){     				
                           $imprimir .="<td width='20%'>".htmlentities($fila['indicacion'])."</td>";
                           $imprimir .="<td width='21%'>".$fila['estado']."</td>	
                                   </tr>";
                                    }else{
                           $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td>
                                        <td>".$fila['estado']."</td>
                                    </tr>";	
                                    }
                                }

                                pg_free_result($consultadetalle);

                //$imprimir .= "<i/nput type='hidden' name='oculto' id='oculto' value='".$pos."' />


                                $consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
                        $row = pg_fetch_array($consulta);
                         $Estado1=$row['estado1'];

                 $imprimir .="</table></center></td></tr>
	<center><div id='divImpresion' style='display:block' >
		<table>
			<tr >
				<p></p>
			</tr>
			<tr >
				<td align='center'>
					
                                    <button type='button' align='center' class='btn btn-primary'  onclick='ImprimirSolicitud(); '><span class='glyphicon glyphicon-print'></span> Imprimir Solicitud </button>
                                
                
                                        <!--<input type='button' name='btnImprimirSol' id='btnImprimirSol' value='Imprimir Solicitud' onClick='ImprimirSolicitud()'>-->";
			//	echo"---> estado1--->". $estado['estado1'];
         
                         $Estado1;
                        //'R'  'P'
                            if(($Estado1=='Recibida') OR ($Estado1 =='En Proceso')){
                                            
                                                    //echo " si esta entrando ";
						$imprimir .=" <!--<input type='button' name='btnImprimir'  id=btnImprimir' value='Imprimir Vi&ntilde;etas' onClick='ImprimirExamenes()'/>-->
                                                        
                                                                <button type='button' align='center' class='btn btn-primary'  onclick='ImprimirExamenes(); '><span class='glyphicon glyphicon-print'></span> Imprimir Vi&ntilde;etas </button>
                                                                    ";}
                                                
                                                else{
                                               // echo "no esta entrando ";
						$imprimir .="<!--<input type='button' name='btnImprimir' disabled='enabled' id=btnImprimir' value='Imprimir Vi&ntilde;etas' onClick='ImprimirExamenes();'/>-->
                                                        <button type='button' align='center' class='btn btn-primary'  onclick='ImprimirExamenes(); '><span class='glyphicon glyphicon-print'></span> Imprimir Vi&ntilde;etas </button>
                                                        ";
					}
				$imprimir .="</td>
			</tr>
		</table>
	</div>	</center>

</form>";
     echo $imprimir;
	 
	 
   	break;
   	case 3:
   		/*$idexpediente   =$_POST['idexpediente'];
		$idsolicitud    =$_POST['idsolicitud'];
		
  	 include_once("clsSolicitudesPorPaciente.php");
		//recuperando los valores generales de la solicitud
		
		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
		$row = pg_fetch_array($consulta);
		//obteniedo los datos generales de la solicitud
		//valores de las consultas
                $idsolicitudPadre=$row[0];
		$medico         =$row['medico'];
		$idmedico       =$row[1];
		$paciente       =$row['paciente'];
		$edad           =$row['edad'];
		$sexo           =$row['sexo'];
		$precedencia    =$row['nombreservicio'];
		$origen         =$row['nombresubservicio'];
		//$DatosClinicos  =$row['DatosClinicos'];
		//$Estado         =$row['Estado'];
		//$fechasolicitud =$row['FechaSolicitud'];
                //$FechaNac       =$row['FechaNacimiento'];
                $ConocidoPor    =$row['conocodidox'];
                $Diagnostico    =$row['diagnostico'];
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
		//recuperando los valores del detalle de la solicitud
		//$consultadetalle=$objdatos->DatosDetalleSolicitud($idexpediente,$idsolicitud);
		$imprimir="<form name='frmDatos'>
                    <table width='100%' border='0' align='center'>
			<tr>
                            <td  colspan='4'>&nbsp;&nbsp;&nbsp;&nbsp</td>
			</tr>
			<tr>
                            <td colspan='4' align='center' >
					<h3><strong>DATOS SOLICITUD</strong></h3>
			</tr>
			<tr>
                            <td >Establecimiento</td>
                            <td  colspan='3'>".$row['estabext']."</td>
			</tr>
		        <tr>
                            <td >Paciente</td>
                            <td >".htmlentities($paciente)." 
                                 <input name='txtpaciente' id='txtpaciente' type='hidden' size='' value=$paciente disabled='disabled' /></td>
                            <td >Expedinte</td> 
                            <td '>".$idexpediente."</td>         
                        </tr>
                        <tr>
                            <td >Conocido por</td>
                            <td colspan='3' >".htmlentities($ConocidoPor)." 
                                <input name='txtpaciente' id='txtpaciente' type='hidden' size='70' value='".$paciente."' disabled='disabled' /></td>
		   	</tr>
			<tr>
                            <td >Edad</td>
                            <td>".htmlentities($edad)." 
			    	<input name='txtpaciente' id='txtpaciente1' type='hidden' size='35' value='".$edad."' disabled='disabled' /></td>
				
                                    </div>
                                </td>
				<td >Sexo</td>
				<td >$sexo<input type='hidden' name='txtsexo' value='".$sexo."' disabled='disabled' /></td>
                        </tr>
                        <tr>
			    <td >Procedencia</td>
			    <td >$precedencia <input name='txtprecedencia' id='txtprecedencia' type='hidden' size='35' value='".$precedencia."' disabled='disabled' /></td>
			    <td >Origen</td>
			    <td >".htmlentities($origen)."
				<input name='txtorigen' id='txtorigen'  type='hidden' size='35' value='".$origen."' disabled='disabled' />
                                <input name='idsolicitudPadre' id='idsolicitudPadre'  type='hidden' size='40' value='".$idsolicitudPadre."' disabled='disabled' />
			        <input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='".$idsolicitud."' disabled='disabled' />
				<input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='".$idexpediente."' disabled='disabled' />
			</tr>
                        <tr>
                            <td >M&eacute;dico</td>
                            <td colspan='3'>".htmlentities($medico)."
				<input name='txtmedico' id='txtmedico'  type='hidden' size='70' value='".$medico."' disabled='disabled' /></td>
                        </tr>
                        <tr>
                            <td >Diagnostico</td>
                            <td colspan='3'>". $Diagnostico."</td>
                        </tr>
                        <tr> 
                            <td colspan='4'>&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp</td>
                        </tr>
                           
                        <tr>
                        <center>
                            <div' style='width: 100%;'>
                                <table width='100%' border='1' align='center'   >
                               
                                    <tr>
                                        <td colspan='5'  ><center>ESTUDIOS SOLICITADO</center></td>
                                    </tr>
                                    <tr>
                                        <td class='Estilo6'> Código</td>
                                        <td class='Estilo6'> Examen </td>
                                        <td class='Estilo6'> Código Área </td>
                                        <td class='Estilo6'> Indicacion Medica </td>
                                        <td class='Estilo6'> Estado </td>
                                    </tr>"; 
		$pos=0;
		while($fila = pg_fetch_array($consultadetalle)){
		      $imprimir .= "<tr>
			    		<td class='Estilo6'>".$fila['codigo_examen']."</td>
			    		<td class='Estilo6'>".htmlentities($fila['nombre_examen'])."</td>	
					<td class='Estilo6'>".$fila['codigo_area']."</td>";	
                	if (!empty($fila['indicacion'])){     				
		   	   $imprimir .="<td class='Estilo6'>".htmlentities($fila['indicacion'])."</td>";
		           $imprimir .="<td class='Estilo6'>".$fila['estado']."</td>	
	      			    </tr>";
                	}else{
			   $imprimir .="<td class='Estilo6'>&nbsp;&nbsp;&nbsp;&nbsp</td>
					<td class='Estilo6'>".$fila['estado']."</td>
                     		    </tr>";	
				}
                     $pos=$pos + 1;
}

pg_free_result($consultadetalle);

 $imprimir .= "<input type='hidden' name='oculto' id='oculto' value='".$pos."' />
			</table>
	<div id='boton'>
        <table align='center'>
                    <tr>
	                <td  aling='center'>
                                <button type='button' align='center' class='btn btn-primary'  onclick='window.print(); '><span class='glyphicon glyphicon-print'></span> Imprimir </button>
                                         <button type='button' align='center' class='btn btn-primary'  onClick='window.close();'><span class='glyphicon glyphicon-arrow-left'></span> Regresar </button>
                        </td>
                    </tr>
		</table>
        </div>";
                                         
     echo $imprimir;*/
   
   break;
	case 5://LLENANDO COMBO DE Examenes
		$rslts='';
		
		$idarea=$_POST['idarea'];
		//echo $IdSubEsp;
		$dtExam=$objdatos->ExamenesPorArea($idarea,$lugar);	
		$rslts = '<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:250px">';
		$rslts .='<option value="0"> Seleccione Examen </option>';
			
		while ($rows =pg_fetch_array($dtExam)){
			$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
		}
				
		$rslts .= '</select>';
		echo $rslts;	
   	break;
	case 6:// Llenar Combo Establecimiento
            $rslts = '';
                $Idtipoesta = $_POST['idtipoesta'];
        
      //   echo $Idtipoesta;
        if ($Idtipoesta<>0){
          //   echo "tipo=".$Idtipoesta;
            $dtIdEstab = $objdatos->LlenarCmbEstablecimiento($Idtipoesta);
            $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:500px" class="form-control height">';
           // $rslts .='<option value="0"> Seleccione Establecimiento </option>';
            while ($rows = pg_fetch_array($dtIdEstab)) {
                $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
            }
        }else{
             $dtIdEstab = $objdatos->LlenarTodosEstablecimientos();
              $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:500px" class="form-control height">';
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
	   //  echo $IdServ;
	     $dtserv=$objdatos->LlenarCmbServ($IdServ,$lugar);
	     $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:500px" onChange="BuscarMedicos(this.value)" class="form-control height">';
            			$rslts .='<option value="0"> Seleccione Subespecialidad </option>';
			while ($rows =pg_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       		}
				
	      $rslts .='</select>';
	      echo $rslts;
        break;	
		 
}//switch

?>