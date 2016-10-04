<?php session_start();
include('../Lab_Areas/clsLab_Areas.php');
include('clsLab_Examenes.php');
include('../Lab_CodigosEstandar/clsLab_CodigosEstandar.php');
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
$obje=new clsLab_CodigosEstandar;
$objeareas=new clsLab_Areas;
$obj=new clsLab_Examenes;
$idproce=0;
 //echo $lugar;
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Mantenimiento de Examenes de Laboratorio</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_Examenes.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>
<script language="JavaScript" >
function Guardar(){
       	IngresarRegistro();


}
function AsignarIdExamen(idArea)
{
   SolicitarUltimoCodigo(idArea);
}
function Modificar()
{
	enviarDatos();
        //LimpiarCampos();
}
function LlenarComboExamen(idarea)
{
    //alert("aqui"+idarea);
     LlenarExamenes(idarea);
}
function Buscar()
{
    BuscarDatos();
}
function Cancelar()
{ 	LimpiarCampos();
	show_event(1);
}
function popup(URL) {
        myWindow=window.open(URL, '" + "', 'scrollbars=yes, width=700, height=700, left=100, top = 100');
    }
function mypopup(){
      resultado=frmModificar.resultado.value;
      resultado_nombre=frmModificar.resultado_nombre.value;
      nombre=$('input[name=txtnombreexamen]').val();
      id_resultado=frmModificar.id_resultado.value;
      window.open('consulta_metodologias1.php?form=frmModificar&resultado='+resultado+
                                        '&resultado_nombre='+encodeURIComponent(resultado_nombre)+'&nombre='+nombre+ '&id_resultado='+id_resultado, '" + "', 'scrollbars=yes, width=700, height=700, left=100, top = 100');
   }
    function habilitar_metodologia(obj){
        if(obj.value !== "") {
            obj1 = document.getElementById('add_metodologia');
            obj2 = document.getElementById('add_presultado');
            obj2.disabled=false;
            obj1.disabled = false;
        }
    }
    function iniciarselects2(){
        $("#cmbEstandarRep").select2({
           placeholder: "Seleccione un Estádar...",
           allowClear: true,
           dropdownAutoWidth: true
        });
        $("#cmbArea").select2({
           placeholder: "Seleccione una Área...",
           allowClear: true,
           dropdownAutoWidth: true
        });
        $("#cmbEstandar").select2({
           placeholder: "Seleccione un Exámen...",
           allowClear: true,
           dropdownAutoWidth: true
        });

        $("#cmbPlantilla").select2({
           placeholder: "Seleccione una Plantilla...",
           allowClear: true,
           dropdownAutoWidth: true
        });

        $("#cmbUbicacion").select2({
           placeholder: "Seleccione una Opción...",
           allowClear: true,
           dropdownAutoWidth: true
        });

        $("#cmbFormularios").select2({
         //  placeholder: "Seleccione un Formulario...",
           allowClear: true,
           dropdownAutoWidth: true
        });

        $("#cmbEtiqueta").select2({
          // placeholder: "Seleccione un Tipo de etiqueta...",
           allowClear: true,
           dropdownAutoWidth: true
        });

        $("#cmbsexo").select2({
          // placeholder: "Seleccione un Tipo de etiqueta...",
           allowClear: true,
           dropdownAutoWidth: true
        });

        $("#cmbUrgente").select2({
          // placeholder: "Seleccione un Tipo de etiqueta...",
           allowClear: true,
           dropdownAutoWidth: true
        });

        $("#cmbHabilitar").select2({
          // placeholder: "Seleccione un Tipo de etiqueta...",
           allowClear: true,
           dropdownAutoWidth: true
        });

        $("#cmbTipoMuestra").select2({
           placeholder: "Seleccione al menos un tipo de muestra...",
           allowClear: true,
           dropdownAutoWidth: true
        });

        $("#cmbPerfil").select2({
           placeholder: "Seleccione los perfiles a los que pertenece la prueba...",
           allowClear: true,
           dropdownAutoWidth: true
        });

        $("#cmbEstabReferido").select2({
           placeholder: "Seleccione los establecimiento a donde referira la prueba...",
           allowClear: true,
           dropdownAutoWidth: true
        });

        $("#cmbRealizadopor").select2({
           placeholder: "Seleccione una opción",
           allowClear: true,
           dropdownAutoWidth: true
        });
    }
    $(document).ready(function() {
      iniciarselects2();

        //$('#cmbExamen').multiselect();
//         $('#cmbEstandarRep').multiselect({
//            buttonWidth: '86.5%',
//            enableFiltering: true,
//            enableCaseInsensitiveFiltering: true,
//            inheritClass: true
//
//        });
//         $('button.tabulador').removeClass('btn');
    });

</script>
<!--<script type="text/javascript">
    $('#cmbEstandarRep').multiselect();
</script>-->
</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="show_event(1);">

<?php

//$nivel=$_SESSION['NIVEL'];
//$_SESSION['correlativo']=$_SESSION['correlativo'];
if ($nivel==1){
	include_once ('../../../PaginaPrincipal/index_laboratorio2.php');}
if ($nivel==2){
	include_once ('../../../PaginaPrincipal/index_laboratorio22.php');}
if ($nivel==31){
	include_once ('../../../PaginaPrincipal/index_laboratorio31.php');}
if ($nivel==33){
	include_once ('../../../PaginaPrincipal/index_laboratorio33.php');}
if ($nivel == 5) {
        include_once ('../../../PaginaPrincipal/index_laboratorio52.php');}
if ($nivel == 6) {
        include_once ('../../../PaginaPrincipal/index_laboratorio62.php');}
if ($nivel == 7) {
        include_once ('../../../PaginaPrincipal/index_laboratorio72.php'); }
?><br>
<table align="center" width="100%">
    <tr>
        <td>
            <div id="divOculto">
                <input type="hidden" name="txtoculto" id="txtoculto" value="NNN"/>
            </div>
            <div  id="divFrmNuevo" >
                <form name="frmnuevo" >
                    <table width="70%" border="0" align="center" class="StormyWeatherFormTABLE">
                        <tr>
                            <td colspan="3" align="center" class="CobaltFieldCaptionTD"><strong><h3>Mantenimiento de Ex&aacute;menes de Laboratorio Cl&iacute;nico</h3></strong></td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD" >C&oacute;digo del Examen</td>
                            <td class="StormyWeatherDataTD"> <div id="divCodigo"><input type="text" id="txtidexamen"  name="txtidexamen" disabled="disabled" style="width:250px" class="form-control height placeholder" /></div></td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD" >&Aacute;rea</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbArea" name="cmbArea" size="1"  onChange="LlenarComboExamen(this.value);" style="width:75%" class=""height js-example-basic-single">
                                    <option value="0" >Seleccione un &Aacute;rea...</option>
                                    <?php
                                        $consulta= $objeareas->consultaractivas($lugar);
					while($row = pg_fetch_array($consulta)){
                                            echo "<option class='placeholder' value='" . $row['idarea']. "'>" . $row['nombrearea'] . "</option>";
					}
                                    ?>
				</select>
                            </td>
			</tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD" >C&oacute;digo del Est&aacute;ndar</td>
                            <td class="StormyWeatherDataTD">
                               <div id="divExamen">
                                    <select name="cmbEstandar" id="cmbEstandar"  style="width:75%"  class="height js-example-basic-single" onchange="cargaestablecimientoaref('ins');">

                                             <option value="0">Seleccione un Examen...</option>

                                    </select>
                               </div>
                            </td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD" >Nombre del Examen </td>
                            <td class="StormyWeatherDataTD"><input type="text" id="txtnombreexamen" name="txtnombreexamen"   style="width:75%" size="50"  placeholder="Ingrese Nombre del Examen" class="form-control height placeholder" onblur="habilitar_metodologia(this);"/></td>
			</tr>
                           <tr>
                            <td class="StormyWeatherFieldCaptionTD" >C&oacute;digo en tabulador</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbEstandarRep" name="cmbEstandarRep" style="width:75%; size: 10" class="height js-example-basic-single">
                                    <option value="0">Seleccione un Código...</option>
                                        <?php
                                            //$obje=new clsLab_CodigosEstandar;
                                            $consulta= $obj->consultar_estandar();
                                            while($row = pg_fetch_array($consulta)){
                                                echo "<option value='" . $row['0']. "'>" . $row['1'].'- '.$row['2'] . "</option>";
                                            }
								//mysql_free_result($row);
					?>
				</select>
                            </td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD" >Plantilla</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbPlantilla" name="cmbPlantilla" size="1" style="width:75%" class="height js-example-basic-single">
                                    <option value="0">Seleccione una Plantilla...</option>
                                        <?php
                                            $obje=new clsLab_Examenes;
                                            $consulta= $obje->LeerPlantilla();
                                            while($row = pg_fetch_array($consulta)){
						echo "<option value='" . $row[0]. "'>" . $row[1] . "</option>";
                                            }
					?>
				</select>
                            </td>
			</tr>

			<tr>
                            <td class="StormyWeatherFieldCaptionTD"><strong>Solicitado en </strong> </td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbUbicacion" name="cmbUbicacion" size="1" style="width:75%" class="height js-example-basic-single">
<!--                                    <option value="" >Seleccione una Opción...</option>-->
                                    <option value="0" >Todas las procediencias</option>
                                    <option value="1" >Hospitalización y Emergencia</option>
                                    <option value="3" >Ninguna</option>
                                    <option value="4" >Laboratorio</option>
				</select>
                            </td>
			</tr>

			<tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Formulario para Examen</td>
                            <td width="83%"  class="StormyWeatherDataTD">

                                    <select name="cmbFormularios" id="cmbFormularios" size="1" style="width:75%" class="height js-example-basic-single">
                                        <option value="0">Ninguno</option>
                                        <?php
                                            $consulta= $obj->consultar_formularios($lugar);
                                            while($row = pg_fetch_array($consulta)){
                                                echo "<option value='" . $row['0']. "'>" .$row['1'] . "</option>";
                                            }
                                        ?>
                                    </select>
                            </td>
                       </tr>

			<tr>
                            <td class="StormyWeatherFieldCaptionTD" >Tipo Etiqueta</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbEtiqueta" name="cmbEtiqueta" size="1" style="width:75%" class="height js-example-basic-single">
<!--                                    <option value="0">--Seleccione un un tipo de etiqueta--</option>-->
                                    <option value="G" selected>General</option>
                                    <option value="O">Especial</option>
				</select>
                            </td>
			</tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Examen Solicitado Urgente</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbUrgente" name="cmbUrgente" size="1" style="width:75%" class="height js-example-basic-single">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                           <td class="StormyWeatherFieldCaptionTD" title="Sexo  al que se le realiza la prueba">Sexo</td>
                           <td class="StormyWeatherDataTD">
                                <select id="cmbsexo" name="cmbsexo" size="1" style="width:75%" class="height js-example-basic-single" >
<!--                                     <option value="0">Ninguno</option>-->
                                     <option value="4" selected>Ambos</option>
                                    <?php
                                       // $obje1=new clsLab_Examenes;
                                        $consulta= $obj->catalogo_sexo();
                                        while($row = pg_fetch_array($consulta)){
                                            echo "<option value='" . $row['0']. "'>" .$row['1'] . "</option>";
                                        }
				    ?>
                                </select>
                            </td>
                         </tr>
                         <tr>
                            <td class="StormyWeatherFieldCaptionTD">Habilitar Prueba</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbHabilitar" name="cmbHabilitar" size="1" style="width:75%"  class="height js-example-basic-single">
<!--                                    <option value="0">-- Seleccione Condici&oacute;n --</option>-->
                                    <option value="H" selected>Habilitado</option>
                                    <option value="I">Inhabilitado</option>
                                </select>
                            </td>
                         </tr>
                         <tr>
                            <td class="StormyWeatherFieldCaptionTD">Tipo de Muestra</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbTipoMuestra" name="cmbTipoMuestra[]" size="1" style="width:75%"  class="height js-example-placeholder-multiple" multiple="multiple">
<!--                                    <option value="0">-- Seleccione Condici&oacute;n --</option>-->
                                    <?php
                                        $tipomuestra= $obj->tipo_muestra();
                                        while($row = pg_fetch_array($tipomuestra)){
                                            echo "<option value='" . $row['id']. "'>" .$row['tipomuestra'] . "</option>";
                                        }
				    ?>
                                </select>
                            </td>
                         </tr>
                         <tr>
                         <tr>
                            <td class="StormyWeatherFieldCaptionTD" title="Seleccione los perfiles a los que la prueba pertenece">Perfiles</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbPerfil" name="cmbPerfil[]" size="1" style="width:75%"  class="height js-example-placeholder-multiple" multiple="multiple">
                                     <?php
                                        $perfil= $obj->perfil();
                                        while($row = pg_fetch_array($perfil)){
                                            echo "<option value='" . $row['id']. "'>" .$row['nombre'] . "</option>";
                                        }
				    ?>
                                </select>
                            </td>
                         </tr>
                         <tr id="estabreferido" style="display:none">
                            <td class="StormyWeatherFieldCaptionTD" title="Seleccione los establecimientos a donde refiere esta prueba">Establecimientos a donde referira la prueba</td>
                            <td class="StormyWeatherDataTD">
                            <div id="estabref">
                                <select id="cmbEstabReferido" name="cmbEstabReferido[]" size="1" style="width:75%"  class="height js-example-placeholder-multiple" multiple="multiple">
                                     <?php
                                        $estref= $obj->establecimiento_referido();
                                        while($row2 = pg_fetch_array($estref)){
                                            echo '<option value="' . $row2[0] . '|'.$row2[1].'" >' . htmlentities($row2[2]) . '</option>';
                                        }
				    ?>
                                </select>
                            </div>
                            </td>
                         </tr>
                         <tr>
                            <td class="StormyWeatherFieldCaptionTD" >A realizar :</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbRealizadopor" name="cmbRealizadopor[]" size="1" style="width:75%"  class="height js-example-placeholder-multiple" multiple="multiple">
                                     <?php
                                        $estref= $obj->forma_realizacion();
                                        while($row2 = pg_fetch_array($estref)){
                                            echo '<option value="' . $row2[0] .'" >' . htmlentities($row2[1]) . '</option>';
                                        }
				    ?>
                                </select>
                            </td>
                         </tr>
                         <tr>
                            <td nowrap class="StormyWeatherFieldCaptionTD">Tiempo Previo para <br>entrega de resultado(en dias)&nbsp;</td>
                            <td class="StormyWeatherDataTD">
                                <input id="inidate" name="inidate" class="form-control" style="width:250px; height:28px;" maxlength=3 onkeypress='return isNumberKey(event);' ></td>
                        </tr>
                        <tr>
                        <td nowrap class="StormyWeatherFieldCaptionTD">Metodologías</td>
                        <td class="StormyWeatherDataTD">
                            <input type="hidden" name="metodologias_sel" id="metodologias_sel">
                            <input type="hidden" name="text_metodologias_sel" id="text_metodologias_sel">
                            <input type="hidden" name="id_metodologias_sel" id="id_metodologias_sel">
                            <button type='button' class='btn btn-default' disabled="disabled"  name="add_metodologia" id="add_metodologia" style="width:250px; text-align: left;" onclick="
                                popup('consulta_metodologias.php?form=frmnuevo&metodologias_sel='+document.getElementById('metodologias_sel').value+
                                        '&text_metodologias_sel='+document.getElementById('text_metodologias_sel').value+
                                        '&nombre='+document.getElementById('txtnombreexamen').value+ '&id_metodologias_sel='+document.getElementById('id_metodologias_sel').value);"><span class='glyphicon glyphicon-th-list'></span> ..:Seleccionar Metodologías:..</button>

                        </td>

                        </tr>


                        <tr>
                        <td nowrap class="StormyWeatherFieldCaptionTD">Posible  Resultado</td>
                        <td class="StormyWeatherDataTD">
                            <input type="hidden" name="resultado" id="resultado">
                            <input type="hidden" name="resultado_nombre" id="resultado_nombre">
                            <input type="hidden" name="id_resultado" id="id_resultado">
                            <button type='button' class='btn btn-default' disabled="disabled"  name="add_presultado" id="add_presultado" style="width:250px; text-align: left;" onclick="
                                popup('consulta_metodologias1.php?form=frmnuevo&resultado='+document.getElementById('resultado').value+
                                        '&resultado_nombre='+document.getElementById('resultado_nombre').value+
                                        '&nombre='+document.getElementById('txtnombreexamen').value+ '&id_resultado='+document.getElementById('id_resultado').value);"><span class='glyphicon glyphicon-th-list'></span> ..:Seleccionar Resultado:..</button>

                        </td>



                         <!--<tr>
                             <td colspan="2" align="right" class="StormyWeatherDataTD">
                                <input type="button" name="btnGuardar" value="Guardar" onClick="Guardar();">
                                <input type="button" name="btnBuscar" value="Buscar" onClick="Buscar();">
				<input type="button" name="btnCancelar" value="Cancelar" onClick="Cancelar();">
                            </td>
			</tr>-->
                        <tr>
                            <td class="StormyWeatherDataTD" colspan="6" align="right">
                                <button type='button' align="center" id="btnGuardar" class='btn btn-primary'  onclick='Guardar(); '><span class='glyphicon glyphicon-floppy-disk'></span> Guardar </button>
                                <button type='button' align="center" id="btnBuscar" class='btn btn-primary'  onclick='Buscar(); '><span class='glyphicon glyphicon-search'></span>  Buscar </button>
                                <button type='button' align="center" id="btnCancelar" class='btn btn-primary'  onClick="Cancelar();"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                            </td>
                         </tr>

                    </table>
		</form>
            </div>
	</td>
    </tr>
    <tr>
	<td>
            <div  id="divresultado" >

            </div>
	</td>
    </tr>
    <tr>
    	<td>
            <div  id="divFrmModificar" >

            </div>
	</td>
    </tr>
    <tr>
	<td>
            <div  id="divinicial" >

            </div>
	</td>
    </tr>
</table>
</body>
</html>
