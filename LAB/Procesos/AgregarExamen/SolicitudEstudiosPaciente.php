<?php session_start();
include_once("../AgregarExamen/clsAgregarExamen.php");
if(isset($_SESSION['Correlativo']))
{
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$nivel=$_SESSION['NIVEL'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];


?>

<?php
	$idexpediente=$_GET['var1'];
	$idsolicitud=$_GET['var2'];
	$idestablecimiento=$_GET['var3'];
        $fecharecepcion=$_GET['var4'];
        $banderacerrar=$_GET['var5'];
        if ($banderacerrar==''){
           $banderacerrar=0;
        }
	//echo $usuario."##".$idsolicitud.'<br>';
	$obj = new clsAgregarExamen;
	//$ConEmp=$obj->ObtenerCodigoTecnico($usuario);
	//$rowEmp=pg_fetch_array($ConEmp);
	//$CodEmpleado=$rowEmp[0];
	//echo $CodEmpleado;

	$conEst=$obj->DatosEstablecimiento($idestablecimiento);
	$rowEst = pg_fetch_array($conEst);
	$tipo=$rowEst[0];
	$nombrEstab=$rowEst[1];
	$nomtipo=$rowEst[2];

	$consulta=$obj->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
	$row = pg_fetch_array($consulta);


//valores de las consultas                        console.log(modalBody);

	//obteniedo los datos generales de la solicitud
	//valores de las consultas
	/*$medico=$row['NombreMedico'];
	$idmedico=$row['IdMedico'];
	$paciente=$row['NombrePaciente'];
	$edad=$row['Edad'];
	$sexo=$row['Sexo'];
	$procedencia=$row['Precedencia'];
	$origen=$row['Origen'];
	$DatosClinicos=$row['DatosClinicos'];
	$Estado=$row['Estado'];
	$fechasolicitud=$row['FechaSolicitud'];
	$FechaNac=$row['FechaNacimiento'];*/

                $idsolicitudPadre=$row[0];
		$medico=$row['medico'];
		$idempleadop=$row['idempleado'];
		$paciente=$row['paciente'];
		$edad=$row['edad'];
		$sexo=$row['sexo'];
		$precedencia=$row['nombreservicio'];
		$origen=$row['nombresubservicio'];
		//$DatosClinicos=$row['DatosClinicos'];
		$DatosClinicos=isset($row['DatosClinicos']) ? $row['DatosClinicos'] : null ;
		$Estado=$row['estado'];
//		$fechasolicitud=$row['FechaSolicitud'];
//                $FechaNac=$row['FechaNacimiento'];
		$fechasolicitud=$DatosClinicos=isset($row['FechaSolicitud']) ? $row['FechaSolicitud'] : null ;
                $FechaNac=isset($row['FechaNacimiento']) ? $row['FechaNacimiento'] : null ;
                $fechatomamuestra=$row['fechatomamuestra'];
	        //recuperando los valores del detalle de la solicitud
	        //$consultadetalle=$obj->obtener_fecha_tomamuestra($idexpediente,$idsolicitud,$lugar);
 //               $row_detalle = pg_fetch_array($con_detalle);
if ($banderacerrar==0){
                ?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<link type="text/css" href="../../../public/jquery-ui-1.10.3.custom/css/cupertino/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
<link type="text/css" href="../../../public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
<title>Datos del Generales de la Solicitud</title>
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>


</head>
<body >
   <?php

}//fin if $banderacerrar
echo' <script language="JavaScript" type="text/javascript" src="../AgregarExamen/ajax_AgregarExamen.js"></script>';
   ?>
<div id="divFormulario">
    <form name="frmDatos">
		<table width="90%" border="0" align="center" class="StormyWeatherFormTABLE">
		    <tr class="CobaltButton"><td colspan="4" align="center"><h3>DATOS GENERALES</h3></td></tr>
			<tr>

				  <td width="6%" class="StormyWeatherFieldCaptionTD" >Establecimiento Solicitante</td>
				  <td width="12%" class="StormyWeatherDataTD" class="height" style="height: 25px"><?php echo $nombrEstab;?></td>


                                  <input type="hidden" name="txtidsolicitud" id="txtidsolicitud" value="<?php echo $idsolicitud ?>"/>
                                  <input type="hidden" name="txtidsoli" id="txtidsoli" value="<?php echo $idsolicitudPadre ?>"/>
                                  <input type="hidden" name="txtempleado" id="txtempleado" value="<?php echo $idempleadop ?>"/>
                                  <input type="hidden" name="txtIdEstablecimiento" id="txtIdEstablecimiento" value="<?php echo $idestablecimiento ?>"/>
                                  <input type="hidden" name="txtIdexpediente" id="txtIdexpediente" value="<?php echo $idexpediente ?>"/>
                                  <input type="hidden" name="txtfecharecep" id="txtfecharecep" value="<?php echo  $fecharecepcion ?>"/>
			</tr>
		 	<tr>

				  <td width="6%" class="StormyWeatherFieldCaptionTD" >Procedencia</td>

				  <td width="12%" class="StormyWeatherDataTD" style="height: 25px"><?php echo $precedencia;?></td>
				  </tr>
				   <tr>
				  <td width="6%" class="StormyWeatherFieldCaptionTD" >Servicio</td>
				  <td width="12%" class="StormyWeatherDataTD" style="height: 25px"><?php echo $origen;?></td>


				  </tr>

			<tr>
		        <td width="6%" class="StormyWeatherFieldCaptionTD" >NEC</td>
                        <td width="12%" class="StormyWeatherDataTD" style="height: 25px"><?php echo $idexpediente; ?>
			</tr>
			<tr>
		        <td width="6%" class="StormyWeatherFieldCaptionTD">Área</td>
		        <td width="12%" class="StormyWeatherDataTD">
                           <select id="cmbArea" name="cmbArea" size="1"  onChange="LlenarComboExamen(this.value)" style="width:405px" class="form-control height">
                               <option value="0" >--Seleccione Área--</option>
                                    <?php
				include('../../../../Laboratorio/LAB/Mantenimientos/Lab_Areas/clsLab_Areas.php');
				$objeareas=new clsLab_Areas;
				$consulta= $objeareas->consultaractivas($lugar);
				while($row = pg_fetch_array($consulta)){
			        echo "<option value='" . $row['idarea']. "'>" . htmlentities($row['nombrearea']) . "</option>";
				}
				//echo '<option value="'.$area1.'" selected="selected">'.htmlentities("--Seleccione Área--").'</option>';
				?>
                            </select></td>
			</tr>

			<tr>
                            <td width="6%" class="StormyWeatherFieldCaptionTD">Examen</td>
                            <td width="12%" class="StormyWeatherDataTD">
                                <div id="divExamen">
                                    <select id="cmbExamen" name="cmbExamen" size="1" onchange="LlenarComboMuestra1(this.value); llenararealizar(this.value);" style="width:405px" class="form-control height">
					<option value="0" >--Seleccione Examen--</option>
                                    </select>
				</div>
                            </td>
			</tr>
			<tr>
				<td width="6%" class="StormyWeatherFieldCaptionTD">Tipo Muestra</td>
		        <td width="12%" class="StormyWeatherDataTD">
					<div id="divMuestra">
						<select id="cmbMuestra" name="cmbMuestra" size="1" onchange="LlenarComboOrigen1(this.value)" style="width:405px" class="form-control height">
							<option value="0" >--Seleccione Tipo de Muestra--</option>
						</select>
					</div>
				</td>
			</tr>
			<tr>
			    <td width="8%" class="StormyWeatherFieldCaptionTD">Origen Muestra</td>
		            <td width="12%" class="StormyWeatherDataTD">
					<div id="divOrigen">
						<select id="cmbOrigen" name="cmbOrigen" size="1" style="width:405px" class="form-control height">
							<option value="0" >--Seleccione Origen Muestra--</option>
						</select>
					</div>
			    </td>
			</tr>
			<tr>
                        <td class="StormyWeatherFieldCaptionTD">*Fecha Toma de Muestra</td>
                            <td class="StormyWeatherDataTD">
                                <div id="divfechatoma">
									<input type="text" style="width:405px" class="datepicker form-control height" disabled name="txttomamuestra" id="txttomamuestra" size="15" />
                                </div>
                            </td>
			</tr>
			<tr id="arealizar" style="display:none">
				<td width="8%" class="StormyWeatherFieldCaptionTD">A realizar</td>
				<td width="12%" class="StormyWeatherDataTD">
				<div id="divarealizar">

				</div>
				</td>
			</tr>
			<tr>

				<td width="6%" class="StormyWeatherFieldCaptionTD">Indicación</td>
		        <td width="12%" class="StormyWeatherDataTD"><textarea name="txtindicacion" cols="50" id="txtindicacion" style="width:405px" class="form-control"></textarea></td>
			</tr>
			<tr>
				<td width="6%" class="StormyWeatherFieldCaptionTD">Observación</td>
		        <td width="12%" class="StormyWeatherDataTD"><textarea name="txtObservacion" cols="50" id="txtObservacion" style="width:405px" class="form-control"></textarea></td>

			</tr>
			<tr>
                            <td width="6%" colspan="2" class="StormyWeatherFieldCaptionTD" align="right">
					<!--<input type="button" name="Submit" value="Guardar Cambios" Onclick="GuardarExamen();"> -->
                                        <button type='button' align="center" class='btn btn-primary' id='buscarsolicitud' onclick='GuardarExamen(<?php echo $banderacerrar;?>); '><span class='glyphicon glyphicon-floppy-save'></span> Guardar Cambios</button>
                            </td>
                        </tr>

		</table>
	</form>
</div>

   <?php
   if ($banderacerrar==0){
                ?>
</body>
</html>
<?php
                }//fin if bandera==0
}
else{?>
<script language="javascript">
	window.location="../../../login.php";
</script>
<?php }?>
