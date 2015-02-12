<?php session_start();
include_once("../../../Conexion/ConexionBD.php"); 
include ("clsSolicitudesProcesadas.php");
$objdatos = new clsSolicitudesProcesadas;
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$db = new ConexionBD;
$ROOT_PATH = $_SESSION['ROOT_PATH'];

?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Resultados de Examenes de Laboratorio </title>
<style type="text/css">
			*{ font-size:12px; font-family:verdana; }
			h1 { font-size:22px; }
			input { width:250px; border: 2px solid #CCC; line-height:20px;height:20px; border-radius:3px; padding:2px; }
		</style>
<?php include_once $ROOT_PATH."/public/css.php";?>
<?php include_once $ROOT_PATH."/public/js.php";?>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script> 
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<!--<link type="text/css" href="../../../public/jquery-ui-1.10.3.custom/css/cupertino/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
<link type="text/css" href="../../../public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />-->

   

  
<!--
<script type="text/javascript" src="../../../public/datepicker/dp/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="../../../public/datepicker/dp/jquery-ui.min.js"></script>
<script type="text/javascript" src="../../../public/datepicker/dp/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../../public/datepicker/dp/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../../../public/datepicker/dp/jquery-ui-sliderAccess.js"></script>


<script type="text/javascript" src="../../../public/js/jquery.js"></script>
<script language = "javascript" type="application/x-javascript" src="../../../public/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.js">
<script type="text/javascript" src="../../../public/datepicker/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="../../../public/datepicker/i18n/jquery-ui-timepicker-es.js"></script>
 <script type="text/javascript" src="../../../public/datepicker/jquery-ui.js"></script>
<script language = "javascript" type="application/x-javascript" src="../../../public/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.js">
<script type="text/javascript" src="../../../public/datepicker/date.js"></script>
<script type="text/javascript" src="../../../public/datepicker/jquery.ui.datepicker-es.js"></script>


</script>
-->


<script language="JavaScript" >
function Guardar(){
   	GuardarResultados();
	//window.close();
}

function ValidarCampos()
{
  var resp = true;
	 if (document.frmnuevo.cmbEmpleados.value == "0")
		 {
			resp= false;		
		 }
         if (document.getElementById('idresultado').value=='x'){
            if (document.frmnuevo.txtresultado.value==""){
               resp=false;
               }
            }
         else{
            if (document.getElementById('idresultado').value=='xyz'){
               resp=false;
            }
         }
//	 if (document.frmnuevo.txtresultado.value == "" || document.frmnuevo.txtresultado.value=="xyz")
//		 {
//			resp= false;		
//		 }
         if (document.frmnuevo.cant_metodologia!="0"){
             if (document.frmnuevo.cmbmetodologia==0){
                        resp=false;
             }
         if (document.frmnuevo.fecha_reporte.value == ""){
             resp=false
             }
         if (document.frmnuevo.fecha_realizacion.value == ""){
                resp=false
             }
         if (document.frmnuevo.cmbResultado2.value == 0){
                resp=false
             }
         }
         
     
  return resp;
}

function VerResultados()
{
    if (ValidarCampos())
    {
	idexamen=document.frmnuevo.txtidexamen.value;
	idsolicitud=document.frmnuevo.txtidsolicitud.value;
        iddetalle=document.frmnuevo.txtiddetalle.value;
	idarea=document.frmnuevo.txtarea.value;
	//resultado=document.frmnuevo.txtresultado.value;
	idresultado=document.getElementById('idresultado').value;
        if (idresultado=='x'){
           resultado=document.getElementById('txtresultado').value;
        }
        else
        {
           resultado=$("#idresultado").find('option:selected').text();
        }
	marca=document.getElementById('txtmarca').value;
	lectura=document.getElementById('txtlectura').value;
	interpretacion=document.getElementById('txtinterpretacion').value;
	observacion=document.frmnuevo.txtcomentario.value;
	responsable=document.frmnuevo.cmbEmpleados.value;
	nombrearea=document.frmnuevo.txtnombrearea.value;
	procedencia=document.frmnuevo.txtprocedencia.value;
	origen=document.frmnuevo.txtorigen.value;
	impresion=document.frmnuevo.txtimpresion.value;
        establecimiento=document.frmnuevo.txtEstablecimiento.value;
	codresult=document.frmnuevo.cmbResultado2.value;
        fechanac=document.frmnuevo.txtFechaNac.value;
        sexo=document.frmnuevo.txtSexo.value;
        cmbmetodologia=document.frmnuevo.cmbmetodologia.value;
        txtnec=document.frmnuevo.txtnec.value;
        fecha_realizacion=document.frmnuevo.fecha_realizacion.value;
        fecha_reporta=document.frmnuevo.fecha_reporte.value;

	MostrarResultadoExamen(idsolicitud,iddetalle,idarea,idexamen,resultado,lectura,interpretacion,observacion,responsable,nombrearea,procedencia,origen,impresion,establecimiento,codresult,fechanac,sexo, cmbmetodologia, txtnec, fecha_realizacion, fecha_reporta, idresultado, marca);
	
    }else
    {    alert("Complete la Informacion Requerida");   }
}

function RecogeValor()
{
var vtmp=location.search;
var vtmp2 = vtmp.substring(1,vtmp.length);
var query = unescape(top.location.search.substring(1));
var getVars = query.split(/&/);
for ( i = 0; i < getVars.length; i++)
	{
           // alert(getVars[i]+' - '+getVars[i].substr(0,5))
		if ( getVars[i].substr(0,5) == 'var1=' )//loops through this array and extract each name and value
          	     	nec = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var2=' )
			examen = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var3=' )
			idexamen= getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var4=' )
			area = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var5=' )
			iddetalle = getVars[i].substr(5);			
		if ( getVars[i].substr(0,5) == 'var6=' )
			idsolicitud= getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var7=' )
			paciente = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var8=' )
		        idrecepcion= getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var9=' )
		        nombrearea=escape(getVars[i].substr(5));
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
                if ( getVars[i].substr(0,5) == 'referido=' )
                        referido=escape(getVars[i].substr(5)); 
                    
		}
document.frmnuevo.txtnec.value=nec;
document.frmnuevo.txtarea.value=area;
document.frmnuevo.txtpaciente.value=paciente;
document.frmnuevo.txtexamen.value=examen;
document.frmnuevo.txtidsolicitud.value=idsolicitud;
document.frmnuevo.txtiddetalle.value=iddetalle;
document.frmnuevo.txtidexamen.value=idexamen;
document.frmnuevo.txtidrecepcion.value=idrecepcion;
document.frmnuevo.txtnombrearea.value=nombrearea;
document.frmnuevo.referido.value=referido;
//document.frmnuevo.txtEstablecimiento.value=establecimiento;
LlenarComboMetodologia(idexamen, area);


//alert(area);
}
</script>
</head>

<body onLoad="RecogeValor();">
<?php  
//FUNCION PARA VERIFICAR DATOS REQUERIDOS EN RESULTADOS
$bandera=$_GET['var12'];
/*$fechanac=$_GET['var14'];*/
$idexamen_=$_GET['var3'];
$IdEstandar=$_GET['var16'];
$IdHistorial=$_GET['var17'];
$solicitud=$_GET['var6'];
$referido=$_GET['referido'];
$iddetallesolicitud=$_GET['var5'];
$idarea=$_GET['var4'];
$cant=$objdatos->buscarAnterioresPUnica($solicitud,$iddetallesolicitud, $idarea);
 
if (pg_num_rows($cant)>0){
   
   //  echo $referido.'<br>';
$buscarinfo=$objdatos->consfecha($solicitud, $iddetallesolicitud,  $lugar);
//echo 'bucarinfo: '.$buscarinfo;
$rowdeta=pg_fetch_array($buscarinfo);
//$fechatomamues= $rowdeta['f_tomamuestra'];
$fechatomamues= isset($rowdeta['f_tomamuestra']) ? $rowdeta['f_tomamuestra'] : null;


if ($referido!="t"){
  
   // echo $IdHistorial.' -- lugar: '.$lugar; 
$condatos=$objdatos->condatos($IdHistorial, $lugar);

/*

  if($db->conectar()==true){
        $condatos = "SELECT sec_examenfisico.Peso, sec_examenfisico.Talla, Diagnostico, ConocidoPor
                     FROM sec_historial_clinico
                     INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp = mnt_expediente.IdNumeroExp
                     INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente = mnt_datospaciente.IdPaciente
                     LEFT JOIN sec_diagnosticospaciente ON sec_historial_clinico.IdHistorialClinico = sec_diagnosticospaciente.IdHistorialClinico
                     LEFT JOIN mnt_cie10 ON sec_diagnosticospaciente.IdDiagnostico1 = mnt_cie10.IdCie10
                     LEFT JOIN sec_examenfisico ON sec_historial_clinico.IdHistorialClinico = sec_examenfisico.IdHistorialClinico
                     WHERE sec_historial_clinico.IdHistorialClinico=$IdHistorial
                     AND sec_historial_clinico.IdEstablecimiento =$lugar";

        $resultado = mysql_query($condatos);*/
	$rows = pg_fetch_array($condatos);
        
        $Peso=$rows['peso'];
        $Talla=$rows['talla'];
        //$Diagnostico=$rows['diagnostico'];
        $Diagnostico=isset($rows['diagnostico']) ? $rows['diagnostico'] : null;
       // $ConocidoPor=$rows['conocidoPor'];
        $ConocidoPor=isset($rows['conocidoPor']) ? $rows['conocidoPor'] : null ;
  }
  else{
      $Peso='-';
      $Talla='-';
      $Diagnostico='-';
      $ConocidoPor='-';
  }
?>


    
  
			
<table align="center" width="100%">
    <tr>
        <td>
            <div  id="divFrmNuevo" style="display:block" >
                <form name="frmnuevo" method="get" action="ProcDatosResultadosExamen_PA.php" enctype="multipart/form-data">
                   <table width="70%" border="0" align="center" class="StormyWeatherFormTABLE"  style="height: 525px; ">
                        
                        <tr>
                            <td colspan="4" align="center" class="CobaltFieldCaptionTD"><h3>INGRESO DE RESULTADOS</h3></td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Establecimiento Solicitante</td>
                            <td colspan="3" class="StormyWeatherDataTD"><?php echo $_GET['var13'];?></td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">NEC</td>
                            <td class="StormyWeatherDataTD"><?php echo $_GET['var1'];?>
                            <td class="StormyWeatherFieldCaptionTD">No. Order</td>
                            <td class="StormyWeatherDataTD"><?php echo $_GET['var6'];?></td>
                                <input type="hidden" name="txtnec" id="txtnec" disabled="disabled" />
			 	<input type="hidden" name="txtidsolicitud" id="txtidsolicitud" />
			 	<input type="hidden" name="txtiddetalle" id="txtiddetalle" />
			 	<input type="hidden" name="txtidexamen" id="txtidexamen"  />
			 	<input type="hidden" name="txtidrecepcion" id="txtidrecepcion" />
			 	<input type="hidden" name="txtarea" id="txtarea" />
			 	<input type="hidden" name="txtprocedencia" id="txtprocedencia" value="<?php echo $_GET['var10']?>"/>
			 	<input type="hidden" name="txtorigen" id="txtorigen"  value="<?php echo $_GET['var11']?>"/>
			 	<input type="hidden" name="txtimpresion" id="txtimpresion" value="<?php echo $_GET['var12']?>"/>
				<input type="hidden" name="txtEstablecimiento" id="txtEstablecimiento" value="<?php echo $_GET['var13']?>" />
                                <input type="hidden" name="txtFechaNac" id="txtFechaNac" value="<?php echo $_GET['var14']?>" />
                                <input type="hidden" name="txtSexo" id="txtSexo" value="<?php echo $_GET['var15']?>" />
                                <input type="hidden" name="txtIdEstandar" id="txtIdEstandar" value="<?php echo $_GET['var16']?>" />
                                <input type="hidden" name="txtIdHistorial" id="txtIdHistorial" value="<?php echo $_GET['var17']?>" />
                                <input type="hidden" name="referido" id="referido" value="<?php echo $_GET['referido']?>" />
                            </td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Paciente</td>
                            <td colspan="3" class="StormyWeatherDataTD"><?php echo $_GET['var7'];?>
                                <input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" />
                            </td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Conocido Por</td>
                            <td colspan="3" class="StormyWeatherDataTD"><?php echo $ConocidoPor;?>
                               
                            </td>
			</tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Procedencia</td>
                            <td class="StormyWeatherDataTD"><?php echo $_GET['var10'];?></td>
                            <td class="StormyWeatherFieldCaptionTD">Servicio</td>
                            <td class="StormyWeatherDataTD"><?php echo $_GET['var11'];?></td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
                            <td class="StormyWeatherDataTD" colspan="1"> <?php echo $_GET['var9'] ;?> 
                                <input type="hidden" name="txtnombrearea" id="txtnombrearea" disabled="disabled" size="60" />
                            </td>
                            <td class="StormyWeatherFieldCaptionTD" style="white-space:nowrap;">F. Toma Muestra</td>
                            <td class="StormyWeatherDataTD" colspan="1"> <?php echo $fechatomamues ;?>
                            </td>
                        </tr>
                         <tr>
                            <td class="StormyWeatherFieldCaptionTD">Diagnostico</td>
                            <td colspan="3" class="StormyWeatherDataTD"><?php echo $Diagnostico;?>
                            </td>
			</tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Peso</td>
                            <td class="StormyWeatherDataTD">
                                <?php  
                                   if (!empty($Peso)) 
                                        echo $Peso;?>
                            </td>
                            <td class="StormyWeatherFieldCaptionTD">Talla</td>
                            <td class="StormyWeatherDataTD">
                                <?php  
                                    if(!empty($Talla))
                                        echo $Talla;?>
                            </td>
                        </tr>
                       
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Examen </td>
                            <td  colspan="3" class="StormyWeatherDataTD"> <?php echo $_GET['var2'];?>
                                <input type="hidden" name="txtexamen" id="txtexamen" disabled="disabled" size="60"  />
                                 <input type="hidden" id="num_campos" name="num_campos" value="0" />
                                    <input type="hidden" id="cant_campos" name="cant_campos" value="0" />                
                            </td>
                        </tr>
                        <tr id="metodo" >
                            <td class="StormyWeatherFieldCaptionTD">*Metodologia</td>
                            <td class="StormyWeatherDataTD" colspan="3">
                                  <div id="divMetodologia">
                                    <select id="cmbmetodologia" name="cmbmetodologia" size="1" class="form-control  height">
                                        <option value="0" >--Seleccione Metodologia--</option>
                                    </select>
                                                        
                                      <input type='hidden' id='cant_metodologia' name='cant_metodologia' value='0'>  
                                         
                                </div>
                               
                            </td>
                        </tr>
                         <tr>
                            <td class="StormyWeatherFieldCaptionTD" style="white-space:nowrap;">Fecha Realización </td>
                            <td  colspan="1" class="StormyWeatherDataTD"> 
                               <input type="text" class="datepicker form-control height placeholder" name="fecha_realizacion" id="fecha_realizacion" size="60"  placeholder="aaaa-mm-dd" onchange="valfechasolicita(this, 'fecha_realizacion')" style="width:100%"/>
                            </td>
                             <td class="StormyWeatherFieldCaptionTD" style="white-space:nowrap;">Fecha Reporte </td>
                            <td  colspan="1" class="StormyWeatherDataTD"> 
                                <input type="text" class="datepicker form-control height" name="fecha_reporte" id="fecha_reporte" size="60" style="width:90%"  value="<?php echo date("Y-m-d H:i"); ?>"   onchange="valfechasolicita(this, 'fecha_reporte')"/><input type="hidden" name="fecha_reporteaux" id="fecha_reporteaux" size="60"  value="<?php echo date("Y-m-d H:i"); ?>"  /> 
                            </td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">*Validado Por</td>
                            <td class="StormyWeatherDataTD" colspan="3">
                                <div id="divEncargado">
                                   <select id="cmbEmpleados" name="cmbEmpleados" size="1" style="width:96%" class="form-control  height">
                                        <option value="0" >--Seleccione Empleado--</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                             <td class="StormyWeatherFieldCaptionTD">*Resultado</td>                             
                             <td class="StormyWeatherDataTD" colspan="3">
                                <?php
                                 $posible=$objdatos->consultarPosibleRes($idexamen_);
                                 $cant=pg_num_rows($posible);
                                 
                                 
                                 if ($cant>0){
                                     echo '<select id="idresultado" name="idresultado" onchange="setCodResultado(this.value)" style="width:96%" class="form-control  height">';
                                  
                                   if ($cant>1){
echo '<option value="xyz">Seleccione una opción</option>';
                                      while ($pr= pg_fetch_array($posible)){
                                                                               echo '<option value='.$pr["id"].'>'.$pr["posible_resultado"].'</option>';
                                         
                                      }//fin while posible resultado
                                   }//fin if cant>1
                                   else{
                                       echo '<option id='.$pr["id"].'>'.$pr["posible_resultado"].'</option>';
                                   }
                                    echo '</select>';
                                 }
                                 else {                                                                        echo '<textarea  name="txtresultado" cols="50" size="43"  id="txtresultado" class="form-control  height" style="width:96%"/></textarea><input type="hidden" id="idresultado" name="idresultado" value="x"/>';
                                 }
                               
                                ?>
                                
                             </td>
                             
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Marca</td>
                            <td class="StormyWeatherDataTD" colspan="3">
                               <textarea name="txtmarca" cols="50" id="txtmarca" class="form-control  height" style="width:96%"></textarea></td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Lectura</td>
                            <td class="StormyWeatherDataTD" colspan="3" ><textarea name="txtlectura" cols="50" id="txtlectura" class="form-control  height" style="width:96%"></textarea></td>
                        </tr>
                        
                        <tr>
                            <td  class="StormyWeatherFieldCaptionTD">Interpretaci&oacute;n</td>
                            <td  class="StormyWeatherDataTD" colspan="3"><textarea name="txtinterpretacion" cols="50" id="txtinterpretacion" class="form-control  height" style="width:96%"></textarea></td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Observaci&oacute;n</td>
                            <td class="StormyWeatherDataTD" colspan="3"><textarea name="txtcomentario" cols="50" id="txtcomentario" class="form-control" style="width:96%"></textarea></td>
                        </tr>
                        <tr>
                            <td  class="StormyWeatherFieldCaptionTD" style="white-space:nowrap;">*Resultado Tabulador</td>
                            <td  class="StormyWeatherDataTD" colspan="1">
                                  <div id="divCodResultado">
                                     <select id="cmbResultado2" name="cmbResultado2" size="1" style="width:100%" class="form-control  height">
                                        <option value="0" >--Seleccione Resultado--</option>

                                        <?php 
                                        $resscod=$objdatos->BuscarResultados($IdEstandar);

                                        while ($rows = pg_fetch_array($resscod)){
                                            echo '<option value="' . $rows['idresultado'] . '">' . $rows['idresultado'] . '  -  ' . $rows['resultado'] . '</option>'; 
                                        }
                                        ?>
                                    </select>
                                </div>
				
                            </td>
                            <td  class="StormyWeatherDataTD" colspan="2"></td>
                        </tr>
                        <tr>
			<?php 
			if ($bandera==1){
		 	 ?>
		
                            <td colspan="4" align="center" class="StormyWeatherDataTD" style="color:#DD0000; font:bold"><h3>El m&eacute;dico ha solicitado la impresi&oacute;n de este Resultado </h3></td>
                        </tr>
                        <tr>
                            <?php 
                          }?>
                            <td colspan="4" style="text-align:right">
                               <button type="button" align="right" style="text-align: right" class="btn btn-primary" onclick="VerResultados();"><span class='glyphicon glyphicon-file'></span>&nbsp;Ver Resultado </button>
<!--                                <input type="button" name="add" value="Agregar Resultado" Onclick="AddResultado() ;"> -->
                        
                            </td>
                        </tr>
                        
                        </table>
                    
                </form>
            </div>
        </td>
    </tr>
    <tr>
        <td>
             <div id="divEncargado1" style="display:none">
                                    <select id="cmbEmpleadosfin" name="cmbEmpleadosfin" size="1">
                                        <option value="0" >--Seleccione Empleado--</option>
                                    </select>
                                </div>
        </td>
    </tr>
    <tr>
        <td>
            <div  id="divresultado" style="display:none"></div>
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
<!-- <script type="text/javascript" src="../../../public/datepicker/jquery-1.11.1.min.js"></script>
                <script type="text/javascript" src="../../../public/datepicker/jquery-ui.min.js"></script>
		<script type="text/javascript" src="../../../public/datepicker/jquery-ui-timepicker-addon.js"></script>
                <script type="text/javascript" src="../../../public/datepicker/jquery-ui-timepicker-addon-i18n.min.js"></script>
                <script type="text/javascript" src="../../../public/datepicker/jquery-ui-timepicker-es.js"></script>
                <script type="text/javascript" src="../../../public/datepicker/jquery-ui-sliderAccess.js"></script>
                <script type="text/javascript" src="../../../public/datepicker/script.js"></script>    -->
    
</body>
</html>
