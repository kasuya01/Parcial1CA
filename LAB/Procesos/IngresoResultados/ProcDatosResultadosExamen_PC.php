<?php session_start();
include_once("../../../Conexion/ConexionBD.php");
$db = new ConexionBD;
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$nec = $_GET['var1']; 
 $examen = $_GET['var2'];
 $codigoex = $_GET['var3'];
 $area= $_GET['var4'];		   
 $iddetallesol = $_GET['var5'];  
 $idsolicitud = $_GET['var6']; 
 $paciente = $_GET['var7'];
 $idrecepcionsol = $_GET['var8'];
 $nombrearea =$_GET['var9'];
 $procedencia = $_GET['var10'];
 $origen = $_GET['var11'];
 $impresion = $_GET['var12'];
 $establecimiento = $_GET['var13'];
 $fechanac = $_GET['var14'];
 $sexo = $_GET['var15'];
 $IdEstandar = $_GET['var16'];
 $IdHistorial = $_GET['var17'];
 $estabext = $_GET['var18'];
include('clsConsultarElementosPlantillaC.php');
$obj=new clsConsultarElementosPlantillaC;
?>
<html>
<head> 
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
<title>Resultados de Ex&aacute;menes de Laboratorio </title>
<?php include_once $ROOT_PATH."/public/css.php";?>
<?php include_once $ROOT_PATH."/public/js.php";?>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<!--<link type="text/css" href="../../../public/jquery-ui-1.10.3.custom/css/cupertino/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
<link type="text/css" href="../../../public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />-->
<script language="JavaScript" >
//Recarga funcion buscar de la página padre
window.onunload = refreshParent;  
function Guardar(){
   	IngresarRegistro();
}
function LlenarComboExamen(idArea)
{
  LlenarExamenes(idArea);
}
function Buscar(){
	BuscarDatos();
}
function Actualizar(){
	enviarDatos();
}
function Nuevo(){
	MostraFormularioNuevo();
}

function salir()
 { Cerrar(); }


function RecogeValor() {
            var vtmp=location.search;
            var vtmp2 = vtmp.substring(1,vtmp.length);
            //alert(vtmp2);
            var query = unescape(top.location.search.substring(1));
            var getVars = query.split(/&/);
            
            for ( i = 0; i < getVars.length; i++) {
                if ( getVars[i].substr(0,5) == 'var1=' )//loops through this array and extract each name and value
                    nec = getVars[i].substr(5);
                if ( getVars[i].substr(0,5) == 'var2=' )
                   examen = getVars[i].substr(5);
                if ( getVars[i].substr(0,5) == 'var3=' )
                   codigoex = getVars[i].substr(5);
                if ( getVars[i].substr(0,5) == 'var4=' )
                   area = getVars[i].substr(5);
                if ( getVars[i].substr(0,5) == 'var5=' )
                   iddetallesol = getVars[i].substr(5);
                if ( getVars[i].substr(0,5) == 'var6=' )
                  idsolicitudsol= getVars[i].substr(5);	
                if ( getVars[i].substr(0,5) == 'var7=' )
                  paciente= getVars[i].substr(5);
                if ( getVars[i].substr(0,5) == 'var8=' )
                  idrecepcionsol = getVars[i].substr(5);
                if ( getVars[i].substr(0,5) == 'var9=' )
                  nombrearea= getVars[i].substr(5);
                if ( getVars[i].substr(0,5) == 'var10=' )
                  procedencia=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'var11=' )
                  origen=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'var12=' )
                  impresion=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'var13=' )
                  establecimiento=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'var14=' )
                  FechaNac=escape(getVars[i].substr(5)); 
                if ( getVars[i].substr(0,5) == 'var15=' )
                  Sexo=escape(getVars[i].substr(5)); 
                if ( getVars[i].substr(0,5) == 'var16=' )
                    IdEstandar=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'var17=' )
                    IdHistorial=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'var19=' )
                    f_tomamuestra=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'var20=' )
                    tipomuestra=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'var21=' )
                    idestabext=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'fecha_recepcion_=' )
                    $fecha_recepcion_=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'origenmuestra=' )
                    origenmuestra=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'edad=' )
                    origenmuestra=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'nomsexo=' )
                    nomsexo=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'idareaPA=' )
                    idareaPA=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'f_consulta=' )
                    f_consulta=escape(getVars[i].substr(5));
            }
            
            document.frmnuevo.txtnec.value=nec;
            document.frmnuevo.txtarea.value=area;
            document.frmnuevo.txtpaciente.value=paciente;
            document.frmnuevo.txtexamen.value=examen;
            document.frmnuevo.txtidsolicitud.value=idsolicitudsol;
            document.frmnuevo.txtiddetalle.value=iddetallesol;
            document.frmnuevo.txtidexamen.value=codigoex;
            document.frmnuevo.txtidrecepcion.value=idrecepcionsol;
            document.frmnuevo.txtnombrearea.value=nombrearea;

            LlenarComboResponsable(area);

        }
     jQuery(document).ready(function($){

            $(".date").datepicker({
              onClose:  function() {
                          validafecha($(this).val(), $(this).attr('name'),$('#dateftomamx').val() ); 
                          valdatesolicita($(this).val(), $(this).attr('name'));
                         }
            });
              $("#cmbOrganismo").select2({
                 allowClear: true,
                 dropdownAutoWidth: true
             });
             

         });
    </script>

<?php   
        
    $IdHistorial=$_GET['var17'];
    $bandera=$_GET['var12'];
    $ftx= $_GET['var19'];
    $iddetallesolicitud= $_GET['var5'];
    $solicitud= $_GET['var6'];
    $idarea= $_GET['var4'];
    $edad=$_GET['edad'];
    $nomsexo=$_GET['nomsexo'];
   $origen=$_GET['var21'];
   $idarea= $_GET['idareaPA'];
    $f_consulta=$_GET['f_consulta'];
  // echo $origen." - ".$_GET['var20'];
    //echo $solicitud." - ".$iddetallesolicitud." - ".$area;
    $cant=$obj->buscarAnterioresPUnica($solicitud,$iddetallesolicitud,$idarea);
 //   print_r($cant);
    if (pg_num_rows($cant)>0){
        if($db->conectar()==true) {
      $condatos = "SELECT t07.peso,t07.talla,
                        CASE WHEN t04.id_snomed IS NOT NULL
                            THEN t06.sct_name_es 
                            ELSE t08.diagnostico
                        END AS diagnostico,especificacion,conocido_por
                        FROM sec_historial_clinico               t01
                        INNER JOIN mnt_expediente                t02 ON (t02.id = t01.id_numero_expediente)
                        INNER JOIN mnt_paciente                  t03 ON (t03.id = t02.id_paciente)
                        LEFT OUTER JOIN sec_diagnostico_paciente t04 ON (t01.id = t04.id_historial_clinico)
                        LEFT OUTER JOIN mnt_snomed_cie10         t06 ON (t06.id = t04.id_snomed)
                        LEFT OUTER JOIN mnt_cie10 t08 ON (t08.id = t04.id_cie10_medico)
                        LEFT OUTER JOIN sec_signos_vitales       t07 ON (t01.id = t07.id_historial_clinico)
                        WHERE t01.id = $IdHistorial AND t01.idestablecimiento = $lugar";
      
      
      

            $resultado = pg_query($condatos);
            $rows = pg_fetch_array($resultado);

            $Peso=$rows['peso'];
            $Talla=$rows['talla'];
            $Diagnostico=$rows['diagnostico'];
            $Especificacion=$rows['especificacion'];
            $ConocidoPor=$rows['conocido_por'];
            
            $fechatomamues= isset($ftx) ? $ftx : null;
            $timeftomamx = strtotime($fechatomamues);
            $dateftomamx = date("Y-m-d", $timeftomamx);
            
            
        }
    ?>

</head>

<body onLoad="RecogeValor();">

    <table align="center" width="100%">
        <tr>
            <td>
                <div  id="divFrmNuevo" style="display:block" >
                    <form name="frmnuevo">
                        <table width="80%" border="0" align="center" class="StormyWeatherFormTABLE" >
                            <tr class="CobaltButton">
                                <td colspan="5" align="center"> <h3>DATOS GENERALES</h3></td>
                            </tr>
                            <tr>
                                <td width="35%" class="StormyWeatherFieldCaptionTD" colspan="1">Establecimiento solicitante</td>
                                <td width="65%" class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var18'];?></td>
                            </tr>
                            <tr>
                                <td class="StormyWeatherFieldCaptionTD" colspan="1">Procedencia</td>
                                <td class="StormyWeatherDataTD" ><?php echo $_GET['var10'] ?></td>
                                <td class="StormyWeatherFieldCaptionTD" colspan="1">Servicio</td>
                                <td class="StormyWeatherDataTD" ><?php echo $_GET['var11'] ?></td>
                            </tr>
                            <tr>
                                <td class="StormyWeatherFieldCaptionTD">NEC</td>
                                <td class="StormyWeatherDataTD" ><?php echo $_GET['var1'] ?></td>
                                <td class="StormyWeatherFieldCaptionTD">No. Order</td>
                                <td class="StormyWeatherDataTD"><?php echo $_GET['var6'];?>
                                    <input type="hidden" name="txtnec" id="txtnec" disabled="disabled" />
                                    <input type="hidden" name="txtidsolicitud" id="txtidsolicitud" />
                                    <input type="hidden" name="txtiddetalle" id="txtiddetalle" value="<?php echo $_GET['var5']?>"/>
                                    <input type="hidden" name="txtidexamen" id="txtidexamen" />
                                    <input type="hidden" name="txtidrecepcion" id="txtidrecepcion" />
                                    <input type="hidden" name="txtarea" id="txtarea" />
                                    <input type="hidden" name="txtprocedencia" id="txtprocedencia" />
                                    <input type="hidden" name="txtorigen" id="txtorigen" />	
                                    <input type="hidden" name="txtEstablecimiento" id="txtEstablecimiento" value="<?php echo $_GET['var13']?>" />
                                    <input type="hidden" name="txtf_tomamuestra" id="txtf_tomamuestra" value="<?php echo $_GET['var19']?>" />
                                    <input type="hidden" name="txttipomuestra" id="txttipomuestra" value="<?php echo $_GET['var20']?>" />
                                    <input type="hidden" name="solicitud_" id="solicitud_" value="<?php echo $_GET['var6'];?>" />
                                    <input type="hidden" name="idexpediente_" id="idexpediente_"  value="<?php echo $_GET['var1'];?>"/>
                                    <input type="hidden" name="fecharecepcion" id="fecharecepcion"  value="<?php echo $_GET['fecha_recepcion'];?>"/>
                                    <input type="hidden" name="idestabext_" id="idestabext_"  value="<?php echo $_GET['var21'];?>"/>
                                    <input type="hidden" name="txtidareaPA" id="txtidareaPA" value="<?php echo $_GET['idareaPA']?>" />

                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" class="StormyWeatherFieldCaptionTD">Paciente</td>
                                <td colspan="4" class="StormyWeatherDataTD" ><?php echo $_GET['var7'] ?>
                                    <input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" /></td>
                            </tr>
                            <tr>
                                <td class="StormyWeatherFieldCaptionTD">Conocido Por</td>
                                <td colspan="3" class="StormyWeatherDataTD"><?php echo $ConocidoPor;?>
                                    <input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" />
                                </td>
                            </tr>
                            <tr>                                     
                                <td class="StormyWeatherFieldCaptionTD">Edad</td>
                                <td class="StormyWeatherDataTD" ><?php echo $edad;?></td>
                                <td class="StormyWeatherFieldCaptionTD">Sexo</td>
                                <td class="StormyWeatherDataTD" ><?php echo $nomsexo;?></td>
                            </tr>
                            <tr>
                                <td class="StormyWeatherFieldCaptionTD">Diagnostico</td>
                                <td colspan="3" class="StormyWeatherDataTD"><?php echo $Diagnostico;?> </td>
                            </tr>
                            <tr>
                                <td class="StormyWeatherFieldCaptionTD">Datos Clinicos</td>
                                <td colspan="3" class="StormyWeatherDataTD"><?php echo $Especificacion;?>
                                    <input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" />
                                </td>
                            </tr>
                            <tr>
                                <td class="StormyWeatherFieldCaptionTD" width="25%">Peso</td>
                                <td class="StormyWeatherDataTD" width="25%">
                                  <?php if (!empty($Peso))
                                           echo htmlentities($Peso)." Kg";
                                  ?>
                                </td>
                                <td class="StormyWeatherFieldCaptionTD" width="18%">Talla</td>
                                <td class="StormyWeatherDataTD"><?php 
                                    if(!empty($Talla))
                                         echo htmlentities($Talla)." cm";?></td>
                            </tr>
                            <tr>
                                <td colspan="1" class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
                                <td class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var9'] ?>
                                    <input type="hidden" name="txtnombrearea" id="txtnombrearea" disabled="disabled">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1"  class="StormyWeatherFieldCaptionTD">Examen</td>
                                <td class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var2'] ?>
                                    <input type="hidden" name="txtexamen" id="txtexamen" disabled="disabled" size="60" /></td>
                            </tr>
                            <tr>
                                <td class="StormyWeatherFieldCaptionTD">Muestra Recibida</td>
                                <td class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var20']." ".$_GET['origenmuestra'] ?></td>
                            </tr>
                            <tr>
                                <td class="StormyWeatherFieldCaptionTD">Fecha de Toma de Muestra</td>
                                <td class="StormyWeatherDataTD" colspan="1" width="25%"><?php echo $_GET['var19'] ?>
                                    <input type="hidden" id="fecha_tmuestra" name="f_tmuestra" value="<?php echo $ftx;?>"/>
                                    <input type="hidden" id="dateftomamx" name="dateftomamx" value="<?php echo $dateftomamx;?>"/>
                                    <input type="hidden" id="f_consulta" name="f_consulta" value="<?php echo $f_consulta;?>"/></td>
                                </td>
                                <td class="StormyWeatherFieldCaptionTD" width="20%">Fecha de Consulta</td> 
                                <td class="StormyWeatherDataTD" colspan="1" width="20%"><?php echo $f_consulta; ?></td>
                            </tr>
                            <tr>
                                <td colspan="1"  class="StormyWeatherFieldCaptionTD">*Validado Por</td>
                                <td class="StormyWeatherDataTD" colspan="4">
                                    <div id="divEncargado">
                                        <select id="cmbEmpleados" name="cmbEmpleados" size="1" class="form-control height">
                                            <option value="0" >--Seleccione Empleado--</option>
                                        </select>
                                    </div>
                                </td>
                            </tr> 
                            <tr>
                                <td class="StormyWeatherFieldCaptionTD" width="35%">Fecha y hora inicio Proceso</td>
                                <td class="StormyWeatherDataTD" width="15%" >
                                    <input type="text" class="date form-control height placeholder" name="txtresultrealiza" id="txtresultrealiza" size="15"  placeholder="aaaa-mm-dd" style="width:100%"/>
                                         <!-- <input type="text" class="date" id="txtresultrealiza"  name="txtresultrealiza" size="15">-->

              <!-- <input type="text" class="date" id="txtresultrealiza"  name="txtresultrealiza" size="15">--> 
                                </td>
                                <td class="StormyWeatherFieldCaptionTD" width="15%">Fecha Resultado</td>
                                <td class="StormyWeatherDataTD" width="35%">
                                    <input type="text" class="date form-control height" name="txtresultfin" id="txtresultfin" size="35" style="width:100%"  value="<?php echo date("Y-m-d"); ?>"  />
                                    <input type="hidden" name="fecha_reporteaux" id="fecha_reporteaux" size="35"  value="<?php echo date("Y-m-d"); ?>"  /> 
                                        <!-- <input type="text" class="date" name="txtresultfin" id="txtresultfin" size="15"  value="<?php //echo date("Y-m-d"); ?>"/>-->

                                </td>
                            </tr>
                            <tr>
                                <td width="35%" colspan="1" class="StormyWeatherFieldCaptionTD">Resultado</td>
                                <td width="65%" class="StormyWeatherDataTD" colspan="4">
                                    <select id="cmbResultado" name="cmbResultado" size="1" class="form-control height" onChange="LlenarObservaciones();"   >
                                        <option value="0" >--Seleccione--</option>
                                        <option value="P">Positivo</option>
                                         <option value="N">Negativo</option>
                                             <option value="O">Otro</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                     <td width="100%" colspan="5" class="StormyWeatherFieldCaptionTD">
                                         <div id="divObservacion" style="display:none">
                                             <table class="StormyWeatherFormTABLE" width="80%" >
                                                 <tr>
                                                     <td  colspan="1" class="StormyWeatherFieldCaptionTD">Resultado</td>
                                                     <td  colspan="4" class="StormyWeatherDataTD" >
                                                         <select id="cmbObservacion" name="cmbObservacion" size="1" style="width:100%" class="form-control height" >
                                                             <option value="0" >--Seleccione Observaci&oacute;n--</option>
                                                         </select>
                                                     </td>
                                                </tr>
                                                <tr>
                                                      <td width="35%" colspan="1" class="StormyWeatherFieldCaptionTD">Número de Resiembras:</td>
                                                    <td width="65%"  colspan="4" class="StormyWeatherDataTD" >
                                                        <input name='txtresiembra' type='text' id='txtresiembra' size='10' value='0' />
                                                    </td>
                                                </tr>
                                             </table>
                                         </div>
                                     </td>
                                </tr>
                                <tr class="StormyWeatherDataTD">
                                    <td colspan="5" width="100%">
                                        <div id="divResPositivo" style="display:none" >
                                            <table class="StormyWeatherFormTABLE" width="100%" >
                                                <!-- <tr>
                                                     <td class="StormyWeatherFieldCaptionTD">Observación:</td>
                                                     <td class="StormyWeatherDataTD"><textarea name="txtobservacion" type="text" id="txtobservacion" size="50" cols="50"></textarea></td>
                                                 </tr>-->

                                                 <tr>
                                                     <td width="35%" class="StormyWeatherFieldCaptionTD">Organismos</td>
                                                     <td width="65%" class="StormyWeatherDataTD">
                                                         <select id="cmbOrganismo" name="cmbOrganismo" style="width:100%" class="height js-example-basic-single" >
                                                             <option value="0" >--Seleccione una bacteria--</option>
                                                             <?php
                                                                 //style="width:450px"
                                                                 $consulta_bac= $obj->LeerBacterias();
                                                                                 while($row = pg_fetch_array($consulta_bac)){
                                                                                 echo "<option value='" . $row[0]. "'>" . $row[1] . "</option>";
                                                                                 }
                                                                                 pg_free_result($consulta_bac);
                                                                         ?>	
                                                         </select>			
                                                     </td>
                                                 </tr>
                                                 <tr>
                                                    <td width="35%" class="StormyWeatherFieldCaptionTD">Tarjeta</td>
                                                    <td width="65%" class="StormyWeatherDataTD">
                                                        <select id="cmbTarjeta" name="cmbTarjeta" style="width:100%" class="form-control  height" onChange="MostrarAntibioticos();">
                                                            <option value="0" >-- Seleccione una tarjeta --</option>
                                                             <?php
                                                                 //$obj=new clsConsultarElementosPlantillaC;
                                                                 $consulta= $obj->LeerTarjeta($lugar);
                                                                         while($row = pg_fetch_array($consulta)){
                                                                                 echo "<option value='" . $row[0]. "'>" . $row[1] . "</option>";
                                                                         }
                                                                         pg_free_result($consulta);
                                                                                 ?>
                                                        </select>
                                                    </td>
                                                 </tr>
                                             </table>
                                         </div>
                                     </td>
                                 </tr>
                 <?php 
                if ($bandera==1){
                ?>
                        <tr>
                                <td colspan="5"  class="StormyWeatherDataTD" align="center" style="color:#DD0000; font:bold">
                                    <h3>El m&eacute;dico ha solicitado la impresi&oacute;n de este Resultado </h3></td>
                        </tr>
                          <?php 
                }?>
                        <tr class="StormyWeatherDataTD"> <?php
/*}
else{
 echo '<center><br><br><h1><img src="../../../Imagenes/alerta.png" valign="middle"/>'
   . '&nbsp;'
            . 'El resultado del exámen seleccionado del expediente "'.$_GET['var1'].'",<br/> ya fue ingresado.</h1> ';
            echo " <button type='submit' class='btn btn-primary' id='btnSalir' value='Cerrar' Onclick='Cerrar() ;' /><span class='glyphicon glyphicon-remove-circle'></span>&nbsp;Cerrar</button></center>";
               
}*/
?>
                                <td height="100%" colspan="5" class="StormyWeatherDataTD" >
                                        <div id="divBotonPrevie" style="display:none">
                                                <table width="100%" height="100%" >
                                                    <tr> 
                                                        <td width="35%" colspan="1" class="StormyWeatherFieldCaptionTD">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                        <td width="65%"  colspan="4" class="StormyWeatherDataTD" >
                                                            <textarea name='txtObservacion' type='text' id='txtObservacion' cols='55' placeholder='Escriba aqui la Observación si no le parece ninguna de la lista'/></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr><td width="100%" colspan="5" class="StormyWeatherDataTD">NOTA: Debe de ingresar solo una de las observaciones. </td></tr>
                                                    <tr>
                                                        <td width="35%" colspan="1" class="StormyWeatherFieldCaptionTD">Número de Resiembras</td>
                                                        <td width="65%"  colspan="4" class="StormyWeatherDataTD" >
                                                            <input name='txtresiembra' type='text' id='txtresiembra' size='10' value='0' />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td  colspan="5" align="right">
                                                      <!--  <input type="button" name="Submit" value="Mostrar Datos Previos" Onclick="PreviosNegativos() ;">-->
                                                        <button type='button' id='Submit' align='center' class='btn btn-primary' title='Vista Previa de Resultados'  onclick='PreviosNegativos();'>&nbsp;Vista Previa de Resultados</button>
                                                        </td>
                                                    </tr>
                                                </table>
                                        </div>

                                </td>
                        </tr>
                </table>
	</form>
    <tr>
        <td>          
            </div>
            <div  id="divexamen" style="display:none" >
            </div>
            <div  id="divresultado" style="display:none">
            </div>
         </td>
    </tr>

    </table>
<?php
    }
    else{
        echo '<center><br><br><h1><img src="../../../Imagenes/alerta.png" valign="middle"/>'
            . '&nbsp;'
            . 'El resultado del exámen seleccionado del expediente "'.$_GET['var1'].'",<br/> ya fue ingresado.</h1> ';
        echo " <button type='submit' class='btn btn-primary' id='btnSalir' value='Cerrar' Onclick='Cerrar() ;' /><span class='glyphicon glyphicon-remove-circle'></span>&nbsp;Cerrar</button></center>";
               
    }
?>
</body>
</html>
