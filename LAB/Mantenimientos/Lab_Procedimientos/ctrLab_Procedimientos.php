<?php session_start();
include_once "clsLab_Procedimientos.php";
//include_once("Clases_labo.php");//lkillm

$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//variables POST


//$Pag =$_POST['Pag'];
$opcion=$_POST['opcion'];
//echo $opcion;
$objdatos = new clsLab_Procedimientos;
$Clases = new clsLabor_Procedimientos;



switch ( $opcion ) {
case 1:  //INSERTAR
	$idexamen=$_POST['idexamen'];
	$proce=$_POST['proce'];
	$idarea=$_POST['idarea'];
	$unidades=$_POST['unidades'];
	$sexo=$_POST['sexo'];
	$redad=$_POST['redad'];

	if ( empty( $_POST['rangoini'] ) ) {
		$rangoini="NULL";
	} else {
		$rangoini=$_POST['rangoini'];
	}

	if ( empty( $_POST['rangofin'] ) ) {
		$rangofin="NULL";
	} else {
		$rangofin=$_POST['rangofin'];
	}

	if ( empty( $_POST['Fechaini'] ) ) {
		$Fechaini="NULL";
	} else {
		$FechaI=explode( '/', $_POST['Fechaini'] );
		$Fechaini='\''.$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0].'\'';
	}

	if ( empty( $_POST['Fechafin'] ) ) {
		$Fechafin="NULL";
	} else {
		$FechaF=explode( '/', $_POST['Fechafin'] );
		$Fechafin='\''.$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0].'\'';
	}

	//echo $Fechaini."-".$Fechafin;

	if ( $objdatos->insertar( $proce, $idarea, $idexamen, $unidades, $rangoini, $rangofin, $usuario, $lugar, $Fechaini, $Fechafin, $sexo, $redad ) == true ) {
		echo "Registro Agregado";
	}
	else {
		echo "No se pudo Agregar";
	}

	break;

case 2:  //MODIFICAR
	$idexamen=$_POST['idexamen'];
	$proce=$_POST['proce'];
	$idarea=$_POST['idarea'];
	$unidades=$_POST['unidades'];
	$idproce=$_POST['idproce'];

	$sexo=$_POST['sexo'];
	$redad=$_POST['redad'];
	if ( empty( $_POST['rangoini'] ) ) {
		$rangoini="NULL";
	}
	else {
		$rangoini=$_POST['rangoini'];
	}

	if ( empty( $_POST['rangofin'] ) ) {
		$rangofin="NULL";
	}else {
		$rangofin=$_POST['rangofin'];
	}

	if ( empty( $_POST['Fechaini'] ) ) {
		$Fechaini="NULL";
	}else {
		$FechaI=explode( '/', $_POST['Fechaini'] );
		$Fechaini='\''.$FechaI[2].'-'.$FechaI[1].'-'.$FechaI[0].'\'';
	}

	if ( empty( $_POST['Fechafin'] ) ) {
		$Fechafin="NULL";
	}else {
		$FechaF=explode( '/', $_POST['Fechafin'] );
		$Fechafin='\''.$FechaF[2].'-'.$FechaF[1].'-'.$FechaF[0].'\'';
	}

	if ( $objdatos->actualizar( $idproce, $proce, $idarea, $idexamen, $unidades, $rangoini, $rangofin, $usuario, $lugar, $Fechaini, $Fechafin, $sexo, $redad )==true ) {
		echo "Registro Actualizado";
	} else {
		echo "No se pudo actualizar";
	}

	break;
case 3:  //ELIMINAR
	//Vefificando Integridad de los datos
	$idproce =$_POST['idproce'];
	//echo $idproce."**".$lugar;
	if ( $objdatos->eliminar( $idproce, $lugar )==true ) {
		//&& ($Clases->eliminar_labo($idproce,$lugar))){
		echo "Registro Eliminado" ;

	}
	else {
		echo "El registro no pudo ser eliminado ";
	}
	break;

case 4:// PAGINACION
	//require_once("clsLab_Procedimientos.php");
	////para manejo de la paginacion
	$RegistrosAMostrar=4;
	$RegistrosAEmpezar=( $_POST['Pag']-1 )*$RegistrosAMostrar;
	$PagAct=$_POST['Pag'];

	/////LAMANDO LA FUNCION DE LA CLASE
	$consulta= $objdatos->consultarpag( $lugar, $RegistrosAEmpezar, $RegistrosAMostrar );

	//muestra los datos consultados en la tabla
	echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
                    <tr>
                        <td  class='CobaltFieldCaptionTD' aling='center'> Modificar</td>
			<!-- <td  class='CobaltFieldCaptionTD' aling='center'> Eliminar</td> -->
			<td class='CobaltFieldCaptionTD'> IdExamen </td>
			<td class='CobaltFieldCaptionTD'> Examen </td>
			<td class='CobaltFieldCaptionTD'> Procedimiento </td>
			<td class='CobaltFieldCaptionTD'> Unidades </td>
			<td class='CobaltFieldCaptionTD'> Valores Normales </td>
                        <td class='CobaltFieldCaptionTD'> Sexo</td>
                        <td class='CobaltFieldCaptionTD'> Rango de Edad </td>
			<td class='CobaltFieldCaptionTD'> Fecha Inicio </td>
			<td class='CobaltFieldCaptionTD'> Fecha Finalización </td>
                    </tr>";

	while ( $row = pg_fetch_array( $consulta ) ) {


		echo "<tr>
                    <td aling='center'>
                        <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\"
			onclick=\"pedirDatos('".$row['idprocedimientoporexamen']."')\"> </td>
                   <!-- <td aling ='center'>
			 <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
			onclick=\"eliminarDato('".$row['idprocedimientoporexamen']."',$lugar)\"> </td> -->
                 	<td>".$row['idexamen']."</td>
                    <td>".htmlentities( $row['nombreexamen'] )."</td>
                    <td>".htmlentities( $row['nombreprocedimiento'] )."</td>
                    <td>".htmlentities( $row['unidades'] )."</td>
                    <td>".$row['rangoinicio']."-".$row['rangofin']."</td>";
		echo "<td>".$row['sexovn']."</td>
                    <td>".$row['nombregrupoedad']."</td>";
		if ( ( $row['fechaini']=="NULL" ) || ( $row['fechaini']=="00/00/0000" ) ||( empty( $row['fechaini'] ) ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechaini']."</td>";

		if ( ( empty( $row['fechafin'] ) ) || ( $row['fechafin']=="NULL" ) || ( $row['fechafin']=="00/00/0000" ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechafin']."</td>
			          ";
		echo"</tr>";
	}
	echo "</table>";
	//determinando el numero de paginas
	$NroRegistros= $objdatos->NumeroDeRegistros( $lugar );
	$PagAnt=$PagAct-1;
	$PagSig=$PagAct+1;

	$PagUlt=$NroRegistros/$RegistrosAMostrar;

	//verificamos residuo para ver si llevar� decimales
	$Res=$NroRegistros%$RegistrosAMostrar;
	//si hay residuo usamos funcion floor para que me
	//devuelva la parte entera, SIN REDONDEAR, y le sumamos
	//una unidad para obtener la ultima pagina
	if ( $Res>0 ) $PagUlt=floor( $PagUlt )+1;
	echo "<table align='center'>
               <tr>
                    <td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
               </tr>
               <tr>
		    <td><a onclick=\"show_event('1')\">Primero</a> </td>";
	//// desplazamiento

	if ( $PagAct>1 )
		echo "<td> <a onclick=\"show_event('$PagAnt')\">Anterior</a> </td>";
	if ( $PagAct<$PagUlt )
		echo "<td> <a onclick=\"show_event('$PagSig')\">Siguiente</a> </td>";
	echo "<td> <a onclick=\"show_event('$PagUlt')\">Ultimo</a></td>";
	echo "</tr>
	      </table>";
	break;
case 5:  //LLENAR COMBO DE EXAMENES
	$idarea=$_POST['idarea'];
	$rslts='';
	$consultaex= $objdatos->ExamenesPorArea( $idarea, $lugar );
	//$dtMed=$obj->LlenarSubServ($proce);

	$rslts = '<select name="cmbExamen" id="cmbExamen" size="1" >';
	$rslts .='<option value="0">--Seleccione un Examen--</option>';

	while ( $rows =pg_fetch_array( $consultaex ) ) {
		$rslts.= '<option value="' .$rows['idexamen'].'" >'.htmlentities( $rows['nombreexamen'] ).'</option>';
	}
	$rslts .= '</select>';
	echo $rslts;


	break;

case 6: //DIBUJANDO EL FORMULARIO DE NUEVO

	break;
case 7: //BUSQUEDA
	$idexamen = $_POST['idexamen'];
	$proce 	  = $_POST['proce'];
	$idarea   = $_POST['idarea'];
	$unidades = $_POST['unidades'];
	$sexo 	  = $_POST['sexo'];
	$redad 	  = $_POST['redad'];

	if ( empty( $_POST['rangoini'] ) ) {
		$rangoini="NULL";
	} else {
		$rangoini = '\''.$_POST['rangoini'].'\'';
	}
	if ( empty( $_POST['rangofin'] ) ) {
		$rangofin="NULL";
	} else {
		$rangofin = '\''.$_POST['rangofin'].'\'';
	}
	if ( empty( $_POST['Fechaini'] ) ) {
		$Fechaini="NULL";
	} else {
		$FechaI=explode( '/', $_POST['Fechaini'] );
		$Fechaini='\''.$FechaI[2].'-'.$FechaI[1].'-'.$FechaI[0].'\'';
	}
	if ( empty( $_POST['Fechafin'] ) ) {
		$Fechafin="NULL";
	} else {
		$FechaF=explode( '/', $_POST['Fechafin'] );
		$Fechafin='\''.$FechaF[2].'-'.$FechaF[1].'-'.$FechaF[0].'\'';
	}

	$query = "SELECT lppe.id AS idprocedimientoporexamen,
					lcee.codigo_examen AS idexamen,
					lcee.nombre_examen AS nombreexamen,
					casd.id AS idarea,
				 	casd.nombrearea,
				 	lppe.nombreprocedimiento,
				 	lppe.unidades,
				 	lppe.rangoinicio,
				 	lppe.rangofin,
				 	TO_CHAR(lppe.fechaini::timestamp, 'DD/MM/YYYY') AS fechaini,
				 	TO_CHAR(lppe.fechafin::timestamp, 'DD/MM/YYYY')AS fechafin,
				 	CASE WHEN cex.id IS NULL THEN 'NULL'
				         ELSE cex.id::text
				    END AS idsexo,
				 	CASE WHEN cex.nombre IS NULL THEN 'Ambos'
				         ELSE cex.nombre
				    END AS sexovn,
				 	cre.id AS idedad,
				 	cre.nombre AS nombregrupoedad
			  FROM lab_procedimientosporexamen			 lppe
			  INNER JOIN lab_conf_examen_estab 			 lcee ON (lcee.id = lppe.id_conf_examen_estab)
			  INNER JOIN lab_plantilla					 lpla ON (lpla.id = lcee.idplantilla)
			  INNER JOIN mnt_area_examen_establecimiento mnt4 ON (mnt4.id = lcee.idexamen)
			  INNER JOIN ctl_area_servicio_diagnostico	 casd ON (casd.id = mnt4.id_area_servicio_diagnostico)
			  LEFT OUTER JOIN ctl_sexo 					 cex  ON (cex.id  = lppe.idsexo AND cex.abreviatura != 'I')
			  LEFT OUTER JOIN ctl_rango_edad 			 cre  ON (cre.id  = lppe.idrangoedad)
			  WHERE lpla.idplantilla = 'E' AND lcee.condicion = 'H' AND lppe.idestablecimiento = $lugar AND";

	$ban=0;
	//VERIFICANDO LOS POST ENVIADOS

	if ( !empty( $_POST['idarea'] ) && $_POST['idarea'] !== '0' ) {
		$query .= " casd.id = ".$_POST['idarea']." AND";
	}

	if ( !empty( $_POST['idexamen'] ) && $_POST['idexamen'] !== '0' ) {
		$query .= " lcee.id = ".$_POST['idexamen']." AND";
    }

	if ( !empty( $_POST['proce'] ) ) {
		$query .= " lppe.nombreprocedimiento ILIKE '%".$_POST['proce']."%' AND";
	}

	if ( !empty( $_POST['unidades'] ) ) {
		$query .= " lppe.unidades = '".$_POST['unidades']."' AND";
	}

	if ( !empty( $_POST['rangoini'] ) ) {
		$query .= " lppe.rangoinicio = ".$rangoini." AND";
	}

	if ( !empty( $_POST['rangofin'] ) ) {
		$query .= " lppe.rangofin = ".$rangofin." AND";
	}

	if ( !empty( $_POST['sexo'] ) && $_POST['sexo'] !== '0' ) {
		$query .= " CASE WHEN cex.id IS NULL THEN 'NULL' ELSE cex.id::text END = '".$_POST['sexo']."' AND";
	}

	if ( !empty( $_POST['redad'] ) && $_POST['redad'] !== '0' ) {
		$query .= " lppe.idrangoedad = ".$_POST['redad']." AND";
	}

	if ( !empty( $_POST['Fechaini'] ) ) { 
		$query .= " fechaini = ".$Fechaini." AND"; }

	if ( !empty( $_POST['Fechafin'] ) ) {
		$query .= " fechafin = ".$Fechafin." AND"; }

	if ( ( empty( $_POST['idarea'] ) || $_POST['idarea'] === '0') and ( empty( $_POST['idexamen'] ) || $_POST['idexamen'] === '0' ) and ( empty( $_POST['proce'] ) ) and ( empty( $_POST['unidades'] ) ) and ( empty( $_POST['rangoini'] ) )
		and ( empty( $_POST['rangofin'] ) ) and ( empty( $_POST['Fechafin'] ) ) and ( empty( $_POST['Fechaini'] ) ) and ( empty( $_POST['sexo'] ) || $_POST['sexo'] === '0') and ( empty( $_POST['redad'] ) || $_POST['redad'] === '0' ) ) {
		$ban=1;
	}

	if ( $ban==0 ) {
		$query = substr( $query , 0, strlen( $query )-3 );
	} else {
		$query = substr( $query , 0, strlen( $query )-6 );
	}

	$query_search = $query. " ORDER BY lcee.codigo_examen";
	//echo $query_search;
	//ENVIANDO A EJECUTAR LA BUSQUEDA!!
	//require_once("clsLab_Procedimientos.php");
	////para manejo de la paginacion
	$RegistrosAMostrar=4;
	$RegistrosAEmpezar=( $_POST['Pag']-1 )*$RegistrosAMostrar;
	$PagAct=$_POST['Pag'];

	/////LAMANDO LA FUNCION DE LA CLASE
	//$obje=new clsLab_Procedimientos;
	$consulta= $objdatos->consultarpagbus( $query_search, $RegistrosAEmpezar, $RegistrosAMostrar );

	//muestra los datos consultados en la tabla
	echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
           <tr>
                <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
              	<!--  <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td> -->
             	<td class='CobaltFieldCaptionTD'> IdExamen </td>
                <td class='CobaltFieldCaptionTD'> Examen </td>
                <td class='CobaltFieldCaptionTD'> Procedimiento </td>
                <td class='CobaltFieldCaptionTD'> Unidades </td>
                <td class='CobaltFieldCaptionTD'> Rangos </td>
                <td class='CobaltFieldCaptionTD'> Sexo</td>
                <td class='CobaltFieldCaptionTD'> Rango de Edad </td>
                <td class='CobaltFieldCaptionTD'> Fecha Inicio </td>
                <td class='CobaltFieldCaptionTD'> Fecha Finalización </td>
	</tr>";
	while ( $row = pg_fetch_array( $consulta ) ) {
		echo "<tr>
                <td aling='center'><img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" onclick=\"pedirDatos('".$row['idprocedimientoporexamen']."')\"></td>
               	<!-- <td aling ='center'><img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" onclick=\"eliminarDato('".$row['idprocedimientoporexamen']."',$lugar)\"></td> -->
                <td>".$row['idexamen']."</td> 
                <td>".htmlentities( $row['nombreexamen'] )."</td>
                <td>".htmlentities( $row['nombreprocedimiento'] )."</td>
                <td>".htmlentities( $row['unidades'] )."</td>
                <td>".$row['rangoinicio']."-".$row['rangofin']."</td>
                <td>".$row['sexovn']."</td>
                <td>".$row['nombregrupoedad']."</td>";
		if ( ( $row['fechaini']=="NULL" ) || ( $row['fechaini']=="00/00/0000" ) ||( empty( $row['fechaini'] ) ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechaini']."</td>";

		if ( ( empty( $row['fechafin'] ) ) || ( $row['fechafin']=="NULL" ) || ( $row['fechafin']=="00/00/0000" ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechafin']."</td>
		          ";
		echo"</tr>";
	}
	echo "</table>";
	//determinando el numero de paginas

	$NroRegistros= $objdatos->NumeroDeRegistrosbus( $query_search );
	$PagAnt=$PagAct-1;
	$PagSig=$PagAct+1;

	$PagUlt=$NroRegistros/$RegistrosAMostrar;

	//verificamos residuo para ver si llevar� decimales
	$Res=$NroRegistros%$RegistrosAMostrar;
	//si hay residuo usamos funcion floor para que me
	//devuelva la parte entera, SIN REDONDEAR, y le sumamos
	//una unidad para obtener la ultima pagina
	if ( $Res>0 ) $PagUlt=floor( $PagUlt )+1;

	echo "<table align='center'>
	    	<tr>
		   		<td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
		   	</tr>
		   	<tr>
		   		<td><a onclick=\"show_event_search('1')\">Primero</a> </td>";
	//// desplazamiento

	if ( $PagAct>1 )
		echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
	if ( $PagAct<$PagUlt )
		echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
	if ( $PagUlt > 0 )
		echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
	echo "</tr>
		  </table>";
	break;
case 8://PAGINACION DE BUSQUEDA
	$idexamen = $_POST['idexamen'];
	$proce 	  = $_POST['proce'];
	$idarea   = $_POST['idarea'];
	$unidades = $_POST['unidades'];
	$sexo 	  = $_POST['sexo'];
	$redad 	  = $_POST['redad'];

	if ( empty( $_POST['rangoini'] ) ) {
		$rangoini="NULL";
	} else {
		$rangoini = '\''.$_POST['rangoini'].'\'';
	}
	if ( empty( $_POST['rangofin'] ) ) {
		$rangofin="NULL";
	} else {
		$rangofin = '\''.$_POST['rangofin'].'\'';
	}
	if ( empty( $_POST['Fechaini'] ) ) {
		$Fechaini="NULL";
	} else {
		$FechaI=explode( '/', $_POST['Fechaini'] );
		$Fechaini='\''.$FechaI[2].'-'.$FechaI[1].'-'.$FechaI[0].'\'';
	}
	if ( empty( $_POST['Fechafin'] ) ) {
		$Fechafin="NULL";
	} else {
		$FechaF=explode( '/', $_POST['Fechafin'] );
		$Fechafin='\''.$FechaF[2].'-'.$FechaF[1].'-'.$FechaF[0].'\'';
	}

	$query = "SELECT lppe.id AS idprocedimientoporexamen,
					lcee.codigo_examen AS idexamen,
					lcee.nombre_examen AS nombreexamen,
					casd.id AS idarea,
				 	casd.nombrearea,
				 	lppe.nombreprocedimiento,
				 	lppe.unidades,
				 	lppe.rangoinicio,
				 	lppe.rangofin,
				 	TO_CHAR(lppe.fechaini::timestamp, 'DD/MM/YYYY') AS fechaini,
				 	TO_CHAR(lppe.fechafin::timestamp, 'DD/MM/YYYY')AS fechafin,
				 	CASE WHEN cex.id IS NULL THEN 'NULL'
				         ELSE cex.id::text
				    END AS idsexo,
				 	CASE WHEN cex.nombre IS NULL THEN 'Ambos'
				         ELSE cex.nombre
				    END AS sexovn,
				 	cre.id AS idedad,
				 	cre.nombre AS nombregrupoedad
			  FROM lab_procedimientosporexamen			 lppe
			  INNER JOIN lab_conf_examen_estab 			 lcee ON (lcee.id = lppe.id_conf_examen_estab)
			  INNER JOIN lab_plantilla					 lpla ON (lpla.id = lcee.idplantilla)
			  INNER JOIN mnt_area_examen_establecimiento mnt4 ON (mnt4.id = lcee.idexamen)
			  INNER JOIN ctl_area_servicio_diagnostico	 casd ON (casd.id = mnt4.id_area_servicio_diagnostico)
			  LEFT OUTER JOIN ctl_sexo 					 cex  ON (cex.id  = lppe.idsexo AND cex.abreviatura != 'I')
			  LEFT OUTER JOIN ctl_rango_edad 			 cre  ON (cre.id  = lppe.idrangoedad)
			  WHERE lpla.idplantilla = 'E' AND lcee.condicion = 'H' AND lppe.idestablecimiento = $lugar AND";

	$ban=0;
	//VERIFICANDO LOS POST ENVIADOS

	if ( !empty( $_POST['idarea'] ) && $_POST['idarea'] !== '0' ) {
		$query .= " casd.id = ".$_POST['idarea']." AND";
	}

	if ( !empty( $_POST['idexamen'] ) && $_POST['idexamen'] !== '0' ) {
		$query .= " lcee.id = ".$_POST['idexamen']." AND";
    }

	if ( !empty( $_POST['proce'] ) ) {
		$query .= " lppe.nombreprocedimiento ILIKE '%".$_POST['proce']."%' AND";
	}

	if ( !empty( $_POST['unidades'] ) ) {
		$query .= " lppe.unidades = '".$_POST['unidades']."' AND";
	}

	if ( !empty( $_POST['rangoini'] ) ) {
		$query .= " lppe.rangoinicio = ".$rangoini." AND";
	}

	if ( !empty( $_POST['rangofin'] ) ) {
		$query .= " lppe.rangofin = ".$rangofin." AND";
	}

	if ( !empty( $_POST['sexo'] ) && $_POST['sexo'] !== '0' ) {
		$query .= " CASE WHEN cex.id IS NULL THEN 'NULL' ELSE cex.id::text END = '".$_POST['sexo']."' AND";
	}

	if ( !empty( $_POST['redad'] ) && $_POST['redad'] !== '0' ) {
		$query .= " lppe.idrangoedad = ".$_POST['redad']." AND";
	}

	if ( !empty( $_POST['Fechaini'] ) ) { 
		$query .= " fechaini = ".$Fechaini." AND"; }

	if ( !empty( $_POST['Fechafin'] ) ) {
		$query .= " fechafin = ".$Fechafin." AND"; }

	if ( ( empty( $_POST['idarea'] ) || $_POST['idarea'] === '0') and ( empty( $_POST['idexamen'] ) || $_POST['idexamen'] === '0' ) and ( empty( $_POST['proce'] ) ) and ( empty( $_POST['unidades'] ) ) and ( empty( $_POST['rangoini'] ) )
		and ( empty( $_POST['rangofin'] ) ) and ( empty( $_POST['Fechafin'] ) ) and ( empty( $_POST['Fechaini'] ) ) and ( empty( $_POST['sexo'] ) || $_POST['sexo'] === '0') and ( empty( $_POST['redad'] ) || $_POST['redad'] === '0' ) ) {
		$ban=1;
	}

	if ( $ban==0 ) {
		$query = substr( $query , 0, strlen( $query )-3 );
	} else {
		$query = substr( $query , 0, strlen( $query )-6 );
	}

	$query_search = $query. " ORDER BY lcee.codigo_examen";
	//echo $query_search;
	//ENVIANDO A EJECUTAR LA BUSQUEDA!!
	//require_once("clsLab_Procedimientos.php");
	////para manejo de la paginacion
	$RegistrosAMostrar=4;
	$RegistrosAEmpezar=( $_POST['Pag']-1 )*$RegistrosAMostrar;
	$PagAct=$_POST['Pag'];

	/////LAMANDO LA FUNCION DE LA CLASE
	//$obje=new clsLab_Procedimientos;
	$consulta= $objdatos->consultarpagbus( $query_search, $RegistrosAEmpezar, $RegistrosAMostrar );

	//muestra los datos consultados en la tabla
	echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
           <tr>
                <td aling='center' class='CobaltFieldCaptionTD'> Modificar</td>
              	<!--  <td aling='center' class='CobaltFieldCaptionTD'> Eliminar</td> -->
             	<td class='CobaltFieldCaptionTD'> IdExamen </td>
                <td class='CobaltFieldCaptionTD'> Examen </td>
                <td class='CobaltFieldCaptionTD'> Procedimiento </td>
                <td class='CobaltFieldCaptionTD'> Unidades </td>
                <td class='CobaltFieldCaptionTD'> Rangos </td>
                <td class='CobaltFieldCaptionTD'> Sexo</td>
                <td class='CobaltFieldCaptionTD'> Rango de Edad </td>
                <td class='CobaltFieldCaptionTD'> Fecha Inicio </td>
                <td class='CobaltFieldCaptionTD'> Fecha Finalización </td>
	</tr>";
	while ( $row = pg_fetch_array( $consulta ) ) {
		echo "<tr>
                <td aling='center'><img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" onclick=\"pedirDatos('".$row['idprocedimientoporexamen']."')\"></td>
               	<!-- <td aling ='center'><img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" onclick=\"eliminarDato('".$row['idprocedimientoporexamen']."',$lugar)\"></td> -->
                <td>".$row['idexamen']."</td> 
                <td>".htmlentities( $row['nombreexamen'] )."</td>
                <td>".htmlentities( $row['nombreprocedimiento'] )."</td>
                <td>".htmlentities( $row['unidades'] )."</td>
                <td>".$row['rangoinicio']."-".$row['rangofin']."</td>
                <td>".$row['sexovn']."</td>
                <td>".$row['nombregrupoedad']."</td>";
		if ( ( $row['fechaini']=="NULL" ) || ( $row['fechaini']=="00/00/0000" ) ||( empty( $row['fechaini'] ) ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechaini']."</td>";

		if ( ( empty( $row['fechafin'] ) ) || ( $row['fechafin']=="NULL" ) || ( $row['fechafin']=="00/00/0000" ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechafin']."</td>
		          ";
		echo"</tr>";
	}
	echo "</table>";
	//determinando el numero de paginas
	
	$NroRegistros= $objdatos->NumeroDeRegistrosbus( $query_search );
	$PagAnt=$PagAct-1;
	$PagSig=$PagAct+1;

	$PagUlt=$NroRegistros/$RegistrosAMostrar;

	//verificamos residuo para ver si llevar� decimales
	$Res=$NroRegistros%$RegistrosAMostrar;
	//si hay residuo usamos funcion floor para que me
	//devuelva la parte entera, SIN REDONDEAR, y le sumamos
	//una unidad para obtener la ultima pagina
	if ( $Res>0 ) $PagUlt=floor( $PagUlt )+1;

	echo "<table align='center'>
	    	<tr>
		   		<td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
		   	</tr>
		   	<tr>
		   		<td><a onclick=\"show_event_search('1')\">Primero</a> </td>";
	//// desplazamiento

	if ( $PagAct>1 )
		echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
	if ( $PagAct<$PagUlt )
		echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
	if ( $PagUlt > 0 )
		echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
	echo "</tr>
		  </table>";
	break;
}
?>
