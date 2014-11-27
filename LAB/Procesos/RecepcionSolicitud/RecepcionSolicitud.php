<?php
session_start();
include_once("clsRecepcionSolicitud.php");
$usuario = $_SESSION['Correlativo'];
$lugar   = $_SESSION['Lugar'];
$area    = $_SESSION['Idarea'];
?>
<script language="JavaScript" type="text/javascript" src="ajax_RecepcinSolicitud.js"></script>
<script language="JavaScript" ></script>
<?php
//variables POST
$idexpediente = $_POST['idexpediente'];
$fechacita    = $_POST['fechacita'];
$Nfecha       = explode("/", $fechacita);

$Nfechacita = $Nfecha[2] . "/" . $Nfecha[1] . "/" . $Nfecha[0];
$estado     = 'D';
$idEstablecimiento = $_POST['idEstablecimiento'];

$arraysolic  = array();
$arraypiloto = array();
$i   = 0;
$j   = 0;
$ban = 0;
$pos = 0;
//actualiza los datos del empleados
$objdatos = new clsRecepcionSolicitud;
$consulta = $objdatos->BuscarSolicitudes($idexpediente, $Nfechacita, $lugar, $idEstablecimiento);

$NroRegistros = $objdatos->NumeroDeRegistros($idexpediente, $Nfechacita, $lugar, $idEstablecimiento);
$pil = $objdatos->Piloto($idexpediente, $Nfechacita, $lugar, $idEstablecimiento);

while ($piloto = pg_fetch_array($pil)) {
    $arraypiloto[$j] = $piloto[0];
    $j++;
}

while ($rowsolic = pg_fetch_array($consulta)) {
    $arraysolic[$i] = $rowsolic[0];
    $i++;
}

for ($i = 0; $i < $NroRegistros; $i++) {
    $ConsultaDatos = $objdatos->BuscarDatosSolicitudes($idexpediente, $Nfechacita, $arraysolic[$i], $lugar);

    while ($row = pg_fetch_array($ConsultaDatos)) {
        echo "<table width='70%' border='0' align='center' class='StormyWeatherFormTABLE'>
                <tr>
                    <td colspan='4' align='center' class='CobaltFieldCaptionTD'>DATOS SOLICITUD</td>
                </tr>
                <tr>
                    <td class='StormyWeatherFieldCaptionTD'>Tipo Solicitud</td>
                    <td colspan='3' class='StormyWeatherDataTD'>" . htmlentities($row['tiposolicitud']) . "</td>
                </tr>
                <tr>
                    <td class='StormyWeatherFieldCaptionTD'>Establecimiento Solicitante</td>
                    <td colspan='3' class='StormyWeatherDataTD'>" . htmlentities($row['nombre']) . "</td>
                </tr>
                <tr>
                    <td class='StormyWeatherFieldCaptionTD'>Expediente</td>
                    <td colspan='1' class='StormyWeatherDataTD'><h3>" . htmlentities($row['idnumeroexp']) . "</h3></td>
                    <td class='StormyWeatherFieldCaptionTD'>Paciente</td>
                    <td colspan='1' class='StormyWeatherDataTD'>" . htmlentities($row['nombrepaciente']) . "
                        <input name='txtpaciente[" . $i . "]' id='txtpaciente[" . $i . "]' type='hidden' value='" . htmlentities($row['nombrepaciente']) . "' disabled='disabled' />
                    </td>
                </tr>
                <tr>
                    <td class='StormyWeatherFieldCaptionTD'>Conocido Por</td>
                    <td colspan='3' class='StormyWeatherDataTD'>" . $row['conocidopor'] . "</td>
                </tr>
                <tr>
                    <td class='StormyWeatherFieldCaptionTD'>Edad</td>
                    <td class='StormyWeatherDataTD'><div id='divsuedad[" . $i . "]'></div></td>
                    <td class='StormyWeatherFieldCaptionTD'>Sexo:</td>
                    <td class='StormyWeatherDataTD'>" . $row['sexo'] . "</td>
                </tr>
                <tr>
                    <td class='StormyWeatherFieldCaptionTD'>Procedencia</td>
                    <td colspan='1' class='StormyWeatherDataTD'>" . htmlentities($row['precedencia']) . "
                        <input name='txtprecedencia[" . $i . "]' id='txtprecedencia[" . $i . "]' type='hidden'  value='" . $row['precedencia'] . "'/>
			<input name='txtidsolicitud[" . $i . "]' id='txtidsolicitud[" . $i . "]' type='hidden' value='" . $arraysolic[$i] . "'/>
			<input name='txtfecha[" . $i . "]' id='txtfecha[" . $i . "]' type='hidden' value='" . $row['fecha'] . "'/>
                        <input name='suEdad[" . $i . "]'' id='suEdad[" . $i . "]' type='hidden' value='" . $row['fechanacimiento'] . "'/>
                    </td>
                    <td class='StormyWeatherFieldCaptionTD'>Origen</td>
                    <td class='StormyWeatherDataTD' colspan='1'>" . $row['origen'] . "</td>
                </tr>
                <tr>
                    <td class='StormyWeatherFieldCaptionTD'>M&eacute;dico</td>
                    <td colspan='3' class='StormyWeatherDataTD'>" . htmlentities($row['nombremedico']) . "</td>
		</tr>
                <tr>
                    <td class='StormyWeatherFieldCaptionTD'>Diagnostico </td>
                    <td colspan='3' class='StormyWeatherDataTD'>" . htmlentities($row['sct_name_es']) . "</td>
		</tr>
		<tr>
                    <td class='StormyWeatherFieldCaptionTD'>Peso</td>
                    <td class='StormyWeatherDataTD'>" . $row['peso'] . "&nbsp;&nbsp;Kg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td class='StormyWeatherFieldCaptionTD'>Talla:</td>
                    <td class='StormyWeatherDataTD'>" . $row['talla'] . "&nbsp;&nbsp;cm.</td>
		</tr>
            </table><br>";
        echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
		<tr>
                    <td colspan='5' align='center' class='CobaltFieldCaptionTD'>ESTUDIOS SOLICITADOS</td>
		</tr>
		<tr class='StormyWeatherFieldCaptionTD'>
                    <td >Código Prueba</td>
                    <td >IdArea</td>
                    <td> Examen </td>
                    <td> Indicaciones </td>
		</tr>";
        $detalle = $objdatos->BuscarDetalleSolicitud($idexpediente, $Nfechacita, $arraysolic[$i], $idEstablecimiento);
        while ($rows = pg_fetch_array($detalle)) {
            echo "<tr>
                    <td >" . $rows['idestandar'] . " </td>
                    <td >" . $rows['idarea'] . " </td>
                    <td>" . htmlentities($rows['nombreexamen']) . "</td>";
            if (!empty($rows['indicacion'])) {
                echo "<td>" . htmlentities($rows['indicacion']) . "</td>";
            } else
                echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            //***************** bandera ************************
            if (($rows['idexamen'] == 'COA001')or ( $rows['idexamen'] == 'COA002') or ( $rows['idexamen'] == 'COA016')) {
                $ban = 1;
            }
            echo "</tr>";
        }// while detalle


        echo "</table>
              <table align='center'><br>
                <tr>
                    <td>
                        <input type='button' name='btnActualizar[" . $i . "]' id='btnActualizar[" . $i . "]' value='Procesar Solicitud' onClick='AsignarNumeroMuestra(" . $i . ");'/>
                        <input type='hidden' name='oculto' id='text' value='" . $i . "' />
                    </td>
                    <td>
                        <div id='divoculto[" . $i . "]' style='display:none' >
                            <input type='button' name='btnImprimir[" . $i . "]' id='btnImprimir[" . $i . "]' value='Imprimir Vi&ntilde;etas' onClick='ImprimirExamenes(" . $i . ");'/>
                            <input type='button' name='btnImpSolicitud[" . $i . "]' id='btnImpSolicitud[" . $i . "]' value='Imprimir Solicitud' onClick='ImprimirSolicitud(" . $i . ");'/>
			</div>
                    </td>
		</tr>
              </table><br>";
    }//del while
    echo "<input type='hidden' name='topei' id='topei' value='" . $NroRegistros . "' /> ";
}
?>

    <table align="center">
        <tr>
            <td><input type="button" name="btnOtra" id="btnOtra" value="Ingresar otra solicitud" onClick="window.location.replace('Proc_RecepcionSolicitud.php')"></td>
        </tr>
    </table>
</form>
