<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

include ("clsPlantillaD.php");
//creando los objetos de las clases
$obj = new clsPlantillaD;

//variables POST
$opcion=$_POST['opcion'];


switch ($opcion)
{
  case 1:
	//variables POST
	$idsolicitud= $_POST['idsolicitud'];
	$idexamen=$_POST['idexamen'];//*
	$iddetalle= $_POST['iddetalle'];
	$idrecepcion= $_POST['idrecepcion'];
	$idempleado= $_POST['idempleado'];
	//$observacion= $_POST['observacion'];
	$idelemento= $_POST['idelemento'];
	$idcantidad= $_POST['idcantidad'];
	$idresultado= $_POST['idresultado'];
	$tab=$_POST['tab'];
        $fecharealiz=$_POST['fecharealiz'];
        $fecharesultado=$_POST['fecharesultado'];
        //echo $fecharealiz." - ".$fecharesultado;
	//echo $idsolicitud."tab".$tab."detalle".$iddetalle;

	//$usuario=1;
	//$usuario=$_SESSION['correlativo'];
	$resultado="";
   	//guardandolos datos generales del resultado
	$ultimo= $obj->insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$idempleado,$usuario,$tab,$fecharealiz,$fecharesultado,$lugar);
        // echo $ultimo;                      
	//guardando el detalle del resultado
	$obj->insertar_elemento($ultimo,$idelemento,$idcantidad,$lugar);
             
	$obj->CambiarEstadoDetalle($iddetalle);
	$obj->CambiarEstadoSolicitud($idsolicitud);
	//consultando los resultados ingresados
	$idresultado=$ultimo;
	$consulta=$obj->MostrarElementosAgregados($idresultado);
	$resultado="
				<input name='oresultado' type='hidden' id='oresultado' value='".$ultimo."'>
				<table aling='center' border='1'>
				<tr class='CobaltButton'>
				<td aling='center' >Eliminar</td>
				<td aling='center' >Elemento</td>
				<td aling='center' >Cantidad</td>
				</tr>
				<tr>";
	while($row = pg_fetch_array($consulta)){
			$resultado.= "<tr>
							<td aling ='center'>
							<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
							onclick=\"EliminarElemento('".$row[0]."')\"> </td>
							<td> $row[0] </td>
							<td> $row[1] </td>
							</tr>";
				}
                                 $resultado.= "  <input type='hidden' name='txtresultrealiza' id='txtresultrealiza' disabled='disabled' value='".$fecharealiz."'>
                                                <input type='hidden' name='txtfresultado' id='txtfresultado' disabled='disabled' value='".$fecharesultado."' />";
	$resultado.="</table>";

	echo $resultado;
	//$obj->CambiarEstadoSolicitud($idsolicitud);
   break;

   case 2:  // AGREGA NUEVO ELEMENTO DE TINCION AL RESULTADO
	$idelemento= $_POST['idelemento'];
	$idcantidad= $_POST['idcantidad'];
	$idresultado= $_POST['idresultado'];
        //echo $idresultado; 
        //$fecharealiz=$_POST['fecharealiz'];
        //$fecharesultado=$_POST['fecharesultado'];
	//$tab= $_POST['tab'];
	//echo $tab;
	$resultado="";
	//VERIFICANDO QUE NO EXISTA ESTE ELEMENTO PARA ESTE RESULTADO
	$reg=$obj->ValidacionElemento($idresultado,$idelemento);

	if($reg == 0)
	 {

		$obj->insertar_elemento($idresultado,$idelemento,$idcantidad,$lugar);
		//consultando los resultados ingresados
		//$idresultado=$ultimo;
		$consulta=$obj->MostrarElementosAgregados($idresultado);
		$resultado.="
			<input name='oresultado' type='hidden' id='oresultado' value='".$idresultado."'>
			<table aling='center' border='1'>
			<tr class='CobaltButton'>
			<td aling='center' >Eliminar</td>
			<td aling='center' >Elemento</td>
			<td aling='center' >Cantidad</td>
				</tr>
				<tr>";
		while($row = pg_fetch_array($consulta)){
				$resultado.= "<tr>
							<td aling ='center'>
								<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
								onclick=\"EliminarElemento('".$row[0]."')\"> </td>
							<td> $row[0] </td>
							<td> $row[1] </td>
						</tr>";
					}
			pg_free_result($consulta);

		$resultado.="</table>";

	}
	else{$resultado="N";}
            echo $resultado;
   break;

   case 3: //VISTA PREVIA DE LOS RESULTADOS
   	$idexamen=$_POST['idexamen'];//
	$idsolicitud= $_POST['idsolicitud'];
	$idrecepcion= $_POST['idrecepcion'];
	$idempleado= $_POST['idempleado'];
	$idresultado= $_POST['idresultado'];
	$establecimiento=$_POST['estab'];
	$tab= $_POST['tab'];
	//echo $tab;
	$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
	$row_estab = pg_fetch_array($Consulta_Estab);

	$consulta=$obj->MostrarElementosAgregados($idresultado);
        $consulta_datos=$obj->LeerDatos($idexamen);
	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$lugar);
	$datos_empleado=$obj->DatosEmpleado($idempleado);
	$row_generales= pg_fetch_array($datos_generales);
	$row_area = pg_fetch_array($consulta_datos);
	$row_empleado = pg_fetch_array($datos_empleado);

   $resultado ="
		<table width='100%' border='0' align='center' class='StormyWeatherFormTABLE'>
                    <tr>
                        <td colspan='6'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='1' align='left' width='20%'><img id='Image1' style='width: auto; height: 55px;' src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
                        <td align='center' colspan='4' width='60%' class='Estilo5'>
                            <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                            <p><strong>".$row_estab['nombre']."</strong></p>
                            <p><strong>&Aacute;REA DE ".htmlentities($row_area['nombrearea'])." </strong></p>
			</td>
                        <td colspan='1' align='right' width='20%'><img id='Image3' style='width: auto; height: 55px;' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
                    </tr>
                    <tr>
			<td colspan='6'>&nbsp;</td>
                    </tr>
                    <tr>
                    	<td colspan='1' ><strong>Establecimiento Solicitante:</strong></td>
                    	<td colspan='2' >".$row_generales['estabext']."</td>
			<td colspan='1' ><strong>Fecha Recepción:</strong></td>
			<td colspan='2' >".$row_generales['fecharecep']."</td>
                    </tr>
                    <tr>
			<td colspan='1' ><strong>Expediente:</strong></td>
			<td colspan='2' >".$row_generales['numero']."</td>
                    </tr>
                    <tr>
			<td colspan='1' ><strong>Paciente:</strong></td>
			<td colspan='5' >".htmlentities($row_generales['paciente'])."</td>
                    </tr>
                    <tr>
			<td colspan='1'><strong>Edad:</strong></td>
			<td colspan='2'>".$row_generales['edad']."</td>
                        <td colspan='1'><strong>Sexo:</strong></td>
			<td colspan='2'>".$row_generales['sexo']."</td>
                    </tr>
                    <tr>
			<td colspan='1'><strong>Procedencia:</strong></td>
			<td colspan='2'>".htmlentities($row_generales['procedencia'])."</td>
			<td colspan='1'><strong>Servicio:</strong></td>
			<td colspan='2'>".$row_generales['subservicio']."</td>
                    </tr>
                    <tr>
			<td colspan='1'><strong>Examen Realizado:</strong></td>
			<td colspan='2'>".htmlentities($row_area['nombre_reporta'])."</td>
                    </tr>
                    <tr>
			<td colspan='1' ><strong>Validado Por:</strong></td>
			<td colspan='5'>".htmlentities($row_empleado['nombreempleado'])."</td>
                    </tr>";
				$nomcod=$obj->ObtenerNombreCodigo($tab);
                                     $row_codigo= pg_fetch_array($nomcod);
										// echo $row_codigo[0];
       $resultado.="<tr>
                        <td colspan='1'>Resultado Tabulador:</td><td colspan='4'>".$row_codigo[0]."</td>
                    </tr>
                    <tr>
			<td colspan='6'>&nbsp;</td>
                    </tr>
                    <tr>
			<td colspan='1'>&nbsp;</td>
                        <td colspan='4' aling='center' >
                        	<table width='100%' border='0'aling='center' cellspacing='0' >
                                    <tr>
                                        <td  aling='center' ><strong>Elemento de Tinci&oacute;n</strong>
					</td>
					<td  aling='center' ><strong>Cantidad</strong>
					</td>
                                    </tr>";
			while($row = pg_fetch_array($consulta)){
	$resultado.= "              <tr>
					<td>$row[0]</td>
					<td>$row[1]</td>
                                    </tr>";
			}
			pg_free_result($consulta);
			pg_free_result($consulta_datos);
			pg_free_result($datos_generales);
                   $resultado.="</table>
                       </td>
                       <td colspan='1'>&nbsp;</td>
                   </tr>";
       $resultado.="<tr>
			<td coslpan='6' aling='center'>
				<input type='submit' id='btnImprimir' value='Imprimir' Onclick='ImprimirPlantillaD(".$idsolicitud.",\"".$idexamen."\",".$idresultado.",\"".$idempleado."\",\"".$establecimiento."\") ;' />
				<input type='button' id='btnSalir' value='Cerrar' onclick='Cerrar()'>
			</td>
                    </tr>
		</table>";
	echo $resultado;
   break;

   case 4:

	$idelemento= $_POST['idelemento'];
	$idresultado= $_POST['idresultado'];
	//$resultado=" ";
        $obj->EliminarElementoDetalle($idresultado,$idelemento);
        $consulta=$obj->MostrarElementosAgregados($idresultado);
	$resultado="
				<input name='oresultado' type='hidden' id='oresultado' value='".$idresultado."'>
				<table aling='center' border='1'>
				<tr >
				<td aling='center' class='CobaltFieldCaptionTD'>Eliminar</td>
				<td aling='center' class='CobaltFieldCaptionTD'>Elemento</td>
				<td aling='center' class='CobaltFieldCaptionTD'>Cantidad</td>
				</tr>
				<tr>";
	while($row = pg_fetch_array($consulta)){
			$resultado.= "<tr>
							<td aling ='center'>
							<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
							onclick=\"EliminarElemento('".$row[0]."')\"> </td>
							<td> $row[0] </td>
							<td> $row[1] </td>
							</tr>";
				}
				pg_free_result($consulta);

	$resultado.="</table>";
	echo $resultado;
   break;

  }

?>
