<?php
session_start();
include_once("clsRecepcionSolicitud.php");
$usuario = $_SESSION['Correlativo'];
$lugar   = $_SESSION['Lugar'];
$area    = $_SESSION['Idarea'];
?>
<script language="JavaScript" type="text/javascript" src="ajax_RecepcinSolicitud.js"></script>
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
                    <td class='StormyWeatherFieldCaptionTD'  width='20%'>Expediente</td>
                    <td colspan='1' class='StormyWeatherDataTD' width='35%'><h3>" . htmlentities($row['idnumeroexp']) . "</h3></td>
                    <td class='StormyWeatherFieldCaptionTD' width='20%'>Paciente</td>
                    <td colspan='1' class='StormyWeatherDataTD' width='35%'>" . htmlentities($row['nombrepaciente']) . "
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
        echo "<div class='table-responsive' style='width: 100%;' style='align:center;'>          
           <table border = 0 align='center' class='table table-hover table-bordered table-condensed table-white' cellspacing='0' style='width:70%'><thead>
		<tr>
                    <td colspan='5' align='center' class='CobaltFieldCaptionTD'>ESTUDIOS SOLICITADOS</td>
		</tr>
		<tr class='StormyWeatherFieldCaptionTD'>
                    <th >Código Prueba</th>
                    <th >Cód. Area</th>
                    <th> Examen </th>
                    <th> Indicaciones </th>
                    <th> Fecha Tmx. </th>
		</tr></thead><tbody>";
        $detalle = $objdatos->BuscarDetalleSolicitud($idexpediente, $Nfechacita, $arraysolic[$i], $idEstablecimiento);
        $k=1;
        while ($rows = pg_fetch_array($detalle)) {
          echo "<tr>
                    <td>" . $rows['idestandar'] . " </td>
                    <td>" . $rows['idarea'] . " </td>
                    <td>" . htmlentities($rows['nombreexamen']) . "</td>";
            if (!empty($rows['indicacion'])) {
                echo "<td>" . htmlentities($rows['indicacion']) . "</td>";
            } else
                echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                $fecha= date('Y-m-d H:i');
                $iddetalle=$rows['iddetalle'];
            echo " <td style='width:225px'><input type='text' class='datepicker form-control height'  id='f_tomamuestra_".$k."' name='f_tomamuestra_'  value='". date('Y-m-d H:i')."' onchange=\"valfechasolicita(this, 'f_tomamuestra_".$k."')\" style='width:200px' />"
                    . "<input type='hidden' id='iddetalle_".$k."' name='iddetalle_' value='".$iddetalle."'/>"
                    . "<input type='hidden' id='hdn_numexOrd".$k."' name='hdn_numexOrd' value='".$k."'/>"
                    . "</td>";
            //***************** bandera ************************
            if (($rows['idexamen'] == 'COA001')or ( $rows['idexamen'] == 'COA002') or ( $rows['idexamen'] == 'COA016')) {
                $ban = 1;
            }
            echo "</tr>";
            $k++;
        }// while detalle
// <input type='text' class='datepicker' name="fecha_realizacion" id="fecha_realizacion" size="60"  placeholder="aaaa-mm-dd" onchange="valfechasolicita(this, 'fecha_realizacion')"/>
//          <input type='text' class='datepicker' name='fecha_reporte' id='fecha_reporte' size='15'  value='".date('Y-m-d H:i')."' onchange=\'valfechasolicita(this, 'fecha_reporte')\'/><br>           <input type='button' name='btnImprimir[" . $i . "]' id='btnImprimir[" . $i . "]' value='Imprimir Vi&ntilde;etas' onClick='ImprimirExamenes(" . $i . ");'/>   <input type='button' name='btnImpSolicitud[" . $i . "]' id='btnImpSolicitud[" . $i . "]' value='Imprimir Solicitud' onClick='ImprimirSolicitud(" . $i . ");'/>
         
        echo "</tbody></table></div>
              <table align='center' class='table table-condensed table-white' style='width:45%;border:0'><br>
                <tr>
                    <td align='right'>
                        <button type='button'  name='btnActualizar[" . $i . "]' id='btnActualizar[" . $i . "]' align='right' style='text-align: right' class='btn btn-primary' onclick='AsignarNumeroMuestra(" . $i . ");'><span class='glyphicon glyphicon-check'></span>&nbsp;Procesar Solicitud </button>
                        <input type='hidden' name='oculto' id='text' value='" . $i . "' />
                    </td>
                    <td id='divoculto[" . $i . "]' style='display:none'><center>
                       
                         <button type='button'  name='btnImprimir[" . $i . "]' id='btnImprimir[" . $i . "]' align='right' style='text-align: right' class='btn btn-primary' onclick='ImprimirExamenes(" . $i . ");'><span class='glyphicon glyphicon-barcode'></span>&nbsp;Imprimir Viñetas </button>
                          <button type='button'  name='btnImpSolicitud[" . $i . "]' id='btnImpSolicitud[" . $i . "]' align='right' style='text-align: right' class='btn btn-primary' onclick='ImprimirSolicitud(" . $i . ");'><span class='glyphicon glyphicon-list-alt'></span>&nbsp;Imprimir Solicitud </button>  
                         </center>
                         
			</div>
                    </td>
              ";
    }//del while
    echo "<input type='hidden' name='topei' id='topei' value='" . $NroRegistros . "' /> ";
}
?>

<!--    <table align="center">
        <tr>-->
            <td>
               <button type='button'  name='btnOtra' id='btnOtra' align='right' style='text-align: right' class='btn btn-primary' onclick='window.location.replace("Proc_RecepcionSolicitud.php");'><span class='glyphicon glyphicon-refresh'></span>&nbsp;Ingresar otra solicitud </button></td>
<!--               <input type="button" name="btnOtra" id="btnOtra" value="Ingresar otra solicitud" onClick="window.location.replace('Proc_RecepcionSolicitud.php')"></td>-->
        </tr>
    </table>
</form>
