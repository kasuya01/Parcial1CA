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
<link type="text/css" href="../../../public/jquery-ui-1.10.3.custom/css/cupertino/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
<link type="text/css" href="../../../public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />

   

  
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
         if (document.frmnuevo.cant_metodologia.value!="0"){
             if (document.frmnuevo.cmbmetodologia.value==0){
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
        
	MostrarResultadoExamen(idsolicitud,iddetalle,idarea,idexamen,resultado,lectura,interpretacion,observacion,responsable,nombrearea,procedencia,origen,impresion,establecimiento,codresult,fechanac,sexo, cmbmetodologia, txtnec, idresultado);
	
    }else
    {    alert("Complete la Informacion Requerida");   }
}

function RecogeValor()
{
var vtmp=location.search;
var vtmp2 = vtmp.substring(1,vtmp.length);
var query = decodeURI(top.location.search.substring(1));
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



jQuery(document).ready(function($){

    $(".date").datepicker({
     onClose:  function() {
                 validafecha($(this).val(), $(this).attr('name'),$('#dateftomamx').val() ); 
                 valdatesolicita($(this).val(), $(this).attr('name'));
                }
   });
   
});
</script>

</head>

<body onLoad="RecogeValor();">
    

<?php  

//FUNCION PARA VERIFICAR DATOS REQUERIDOS EN RESULTADOS
$bandera=$_GET['var12'];
/*$fechanac=$_GET['var14'];
$idexamen=$_GET['var3'];*/
$IdEstandar=$_GET['var16'];
$IdHistorial=$_GET['var17'];
$solicitud=$_GET['var6'];
$referido=$_GET['referido'];
$idarea=$_GET['var4'];
$iddetallesolicitud=$_GET['var5'];
$idexamen_=$_GET['var3'];
$cant=$objdatos->buscarAnterioresPUnica($solicitud,$iddetallesolicitud, $idarea);

if (pg_num_rows($cant)>0){
   $buscarinfo=$objdatos->consfecha($solicitud, $iddetallesolicitud,  $lugar);
$rowdeta=pg_fetch_array($buscarinfo);
$fechatomamues= isset($rowdeta['f_tomamuestra']) ? $rowdeta['f_tomamuestra'] : null;

//echo strtotime(date( 'Y-m-d', $fechatomamues ));
$timeftomamx = strtotime($fechatomamues);
$dateftomamx = date("Y-m-d", $timeftomamx);

if ($referido!="t"){
    
$condatos=$objdatos->condatos($IdHistorial, $lugar);
$edad=$objdatos->calc_edad($IdHistorial);

	$rows = pg_fetch_array($condatos);
        
        $Peso=isset($rows['Peso']) ? $rows['Peso'] : null;
        $Talla=isset($rows['Talla']) ? $rows['Talla'] : null;
        $Diagnostico=isset($rows['diagnostico']) ? $rows['diagnostico'] : null;
        $ConocidoPor=isset($rows['conocido_por']) ? $rows['conocido_por'] : null;
  }
  else{
      $Peso='-';
      $Talla='-';
      $Diagnostico='-';
      $ConocidoPor='-';
      $edad=$objdatos->calc_edadref($IdHistorial);
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
			 	<input type="hidden" name="txtidexamen" id="txtidexamen" />
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
                            <td colspan="1" class="StormyWeatherDataTD"><?php echo $ConocidoPor;?>
                               <input type="hidden" id="conocido_por" name="conocido_por" value="<?php echo $ConocidoPor;?>">
                            </td>
                            <td class="StormyWeatherFieldCaptionTD">Edad</td>
                            <td colspan="1" class="StormyWeatherDataTD"><?php echo $edad;?>
                               
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
                             <td class="StormyWeatherFieldCaptionTD">F. Toma Muestra</td>
                             <td class="StormyWeatherDataTD" colspan="1"> <?php echo $fechatomamues ;?>
                                <input type="hidden" id="fecha_tmuestra" name="f_tmuestra" value="<?php echo $fechatomamues;?>"/>
                                <input type="hidden" id="dateftomamx" name="dateftomamx" value="<?php echo $dateftomamx;?>"/>
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
                        <?php
                        $motive=$objdatos->consmotivo($solicitud,$lugar, $_GET['var2']);
                        if (pg_num_rows($motive)>0){
                           $romo=pg_fetch_array($motive);
                           $motivo=$romo['nombre'];
                           echo '<tr>'
                           . '<td class="StormyWeatherFieldCaptionTD">Motivo Solicitud: </td>'
                                   . '<td  colspan="3" class="StormyWeatherDataTD">'.$motivo.'</td>'
                           . '</tr>';
                           }
                        
                        
                        ?>
                       
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Examen </td>
                            <td  colspan="3" class="StormyWeatherDataTD"> <?php echo $_GET['var2'];?>
                               <input type="hidden" name="txtexamen" id="txtexamen" disabled="disabled" size="60"  value="<?php echo $_GET['var2'];?>"/>
                                 <input type="hidden" id="num_campos" name="num_campos" value="0" />
                                    <input type="hidden" id="cant_campos" name="cant_campos" value="0" />                
                            </td>
                        </tr>
                        <tr id="metodo" >
                            <td class="StormyWeatherFieldCaptionTD">*Metodologia</td>
                            <td class="StormyWeatherDataTD" colspan="3">
                                  <div id="divMetodologia">
                                     <select id="cmbmetodologia" name="cmbmetodologia" size="1" onchange="buscarPosResMet(this.value)" style='width:96%; height:100%' class="form-control  height">
                                        <option value="0" >--Seleccione Metodologia--</option>
                                    </select>
                                                        
                                      <input type='hidden' id='cant_metodologia' name='cant_metodologia' value='0'>  
                                      <input type='hidden' id='id_exa_met' name='id_exa_met' value=''>  
                                         
                                </div>
                               
                            </td>
                        </tr>
                         <tr>
                            <td class="StormyWeatherFieldCaptionTD" style="white-space: nowrap;">*Fecha Realización </td>
                            <td  colspan="1" class="StormyWeatherDataTD"> 
<!--                               <input type="text" class="date form-control height" name="fecha_realizacion" id="fecha_realizacion" size="60"  placeholder="aaaa-mm-dd"  onchange="validafecha(this.value,'fecha_realizacion' ,<?php echo "'". $dateftomamx."'" ;?>); valdatesolicita(this.value, 'fecha_realizacion');"/>-->
                               <input type="text" class="date form-control height" name="fecha_realizacion" id="fecha_realizacion" size="60"  placeholder="aaaa-mm-dd" />
                            </td>
                             <td class="StormyWeatherFieldCaptionTD" width="196 px">*Fecha Reporte </td>
                            <td  colspan="1" class="StormyWeatherDataTD"> 
                                <input type="text" class="date form-control height" name="fecha_reporte" id="fecha_reporte" size="60"  value="<?php echo date("Y-m-d"); ?>"   style="width:90%"/>                                               <input type="hidden" name="fecha_reporteaux" id="fecha_reporteaux" size="60"  value="<?php echo date("Y-m-d"); ?>"  /> 
                            </td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">*Validado Por</td>
                            <td class="StormyWeatherDataTD" colspan="3">
                                <div id="divEncargado">
                                    <select id="cmbEmpleados" name="cmbEmpleados" size="1" class="form-control  height">
                                        <option value="0" >--Seleccione Empleado--</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                             <td class="StormyWeatherFieldCaptionTD">*Resultado</td>
                             <td class="StormyWeatherDataTD" colspan="3">
                                 <div id="divResult">
                                    <textarea  name="txtresultado" cols="50" size="43"  id="txtresultado" placeholder="Debe seleccionar una metodología" disabled="disabled" class="form-control  height placeholder" style="width:96%"/></textarea> <input type="hidden" id="idresultado" name="idresultado" value="x" />    
                                </div>
                               
                             </td>
                             
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Marca</td>
                            <td class="StormyWeatherDataTD" colspan="3">
                               <textarea name="txtmarca" cols="50" id="txtmarca" style="width:96%" class="form-control  height"></textarea></td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Lectura</td>
                            <td class="StormyWeatherDataTD" colspan="3">
                               <textarea name="txtlectura" cols="50" id="txtlectura" style="width:96%" class="form-control  height"></textarea></td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD">Observaci&oacute;n</td>
                            <td class="StormyWeatherDataTD" colspan="3"><textarea name="txtcomentario" cols="50" id="txtcomentario" style="width:96%" class="form-control"></textarea></td>
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
                              <a href="#masunoresultado">  <button type="button" align="right" id="agregarresults" class="btn btn-primary" onclick="agregaresultado('0');"><span class='glyphicon glyphicon-plus-sign'></span>&nbsp;Agregar Resultado </button>       </a>                         
                            </td>
                        </tr>
                        
                        </table>
                    <table width="100%" border="0" align="center">
                        <tr><td colspan="4">
		<div id = 'masunoresultado' style="display: none;"><br/>
			<table id="addresultado" name="addresult" cellpadding="0" cellspacing="0" border="1" id="table" class="table table-bordered table-condensed table-white" style="table-layout:fixed;font-size:89%; color: #000000" width="100%">
                           <thead>   <tr>	
					<td colspan="11"  style="text-align:center;background-color: '#D0E6FF';">
						Resultados Ingresados
					</td>
                              </tr>
				<tr class="CobaltFieldCaptionTD" >
                                   <th width="10%" style="height:35px" title="Examen">Examen</th>
					<th width="13%" title="Metodologia">Metodologia</th>
					<th width="6%" title="Fecha Realiza">Fecha Realizaci&oacute;n</th>
					<th width="6%" title="Fecha Resultado">Fecha Resultado</th>
					<th width="10%" title="Responsable">Responsable</th>
					<th  width="10%" title="Resultado">Resultado</th>
					<th  width="10%" title="Marca">Marca</th>
					<th  width="10%" title="Lectura">Lectura</th>
					<th  width="12%" title="Observacion">Observaci&oacute;n</th>
					<th  width="8%" title="Tabulador">Tabulador</th>
					<th  width="5%" title="Eliminar">Elim.</th>
					
				</tr>
                           </thead>
			</table>
                                </div>

                                </td>
                        </tr>	
                        
                        <tr><td colspan="4"><center><br>
                        <div id="valresult" style="display:none; width: 80%;" >
                                <br/>
                        <fieldset><legend style="color:white; background-color: #428bca; height:45px; padding: 0.2em 0.5em; float:left;margin-bottom: 0px">VALIDAR PRUEBA <small> <?php echo $_GET['var2'];?></small></legend>
                <table align="left" border="0"cellpadding="0" class="table table-bordered table-condensed table-white no-v-border">
                <tr>
                   <td><b>Resultado Final:</b></td>
                        <td colspan="1">
                           <?php
                           $buscarResfin=$objdatos->buscarresfin($idexamen_);
                           
                           $canti=pg_num_rows($buscarResfin);
                           echo '<input type="hidden" id="cantresultfin" class="form-control  height" name="cantresultfin" value="'.$canti.'"/>';
                           if ($canti>0){
                              echo '<select id="idresultadofin" name="idresultadofin" style="width:96%" class="form-control  height">';

                            if ($canti>1){
echo '<option value="xyz">Seleccione una opción</option>';
                              while ($pr= pg_fetch_array($buscarResfin)){
                                 echo '<option value='.$pr["id"].'>'.$pr["posible_resultado"].'</option>';
                                 
                              }//fin while posible resultado
                            }//fin if cant>1
                            else{
                                echo '<option id='.$pr["id"].'>'.$pr["posible_resultado"].'</option>';
                            }
                             echo '</select>';
                          }
                          else {                                                                        echo '<textarea  name="v_resultfin" cols="50" size="43"  id="v_resultfin" class="form-control height"/></textarea><input type="hidden" id="idresultadofin" name="idresultadofin" value="x"/>';
                          }
                          
                           ?>
<!--                           
                                <input type="text" id="v_resultfin" name="v_resultfin" style="width:100%">										-->
                        </td>
                        <td align="right"><b>F. Emisión Resultado final:</b></td>
                        <td colspan="1" >
                                 <input type="text" class="date form-control height" name="d_resultfin" id="d_resultfin" size="60"  value="<?php echo date("Y-m-d"); ?>"  />	<input type="hidden" name="fechaact" id="fechaact" size="60"  value="<?php echo date("Y-m-d"); ?>"  />	
                        </td>
                </tr>
                <tr>
                    <td><b>Lectura</b></td>
                    <td  colspan="3"><textarea name="txtlecturafin" cols="100" id="txtlecturafin" class="form-control  height" style="width:100%"></textarea></td>
                </tr>
                        <tr>
                            <td><b>Interpretaci&oacute;n</b></td>
                            <td  colspan="3"><textarea name="txtinterpretacionfin" cols="100" id="txtinterpretacionfin" class="form-control  height" style="width:100%"></textarea></td>
                        </tr>
                <tr>
                        <td><b>Observaci&oacute;n Final:</b></td>
                        <td colspan="3">
                                <input type="text" id="v_obseresultfin" name="v_obseresultfin" style="width:100%" class="form-control">										
                        </td>
                </tr>	
                 <tr>
                        <td><b>Valido Resultado:</b></td>
                        <td colspan="3">
                           <div id="divEncargado1" style="width:100%">
                                    <select id="cmbEmpleadosfin" name="cmbEmpleadosfin" size="1" style="width:100%" class="form-control  height">
                                        <option value="0" >--Seleccione Empleado--</option>
                                    </select>
                                </div>										
                        </td>
                </tr>
                </table>
                </fieldset>

                         
                <p><center><br />
<!--                        <div id="responde2" style="display: block">-->
<a href="#divresultado2">    <button type="button" align="center" class="btn btn-primary" onclick="enviarDatosResult(1,0);" ><span class='glyphicon glyphicon-floppy-disk'></span>&nbsp;Guardar</button></a>
   <button type="button" align="center" class="btn btn-primary" onclick="Cerrar();"><span class='glyphicon glyphicon-remove-circle'></span>&nbsp;Cancelar</button> 
                  
                                </center></p>
                        </div>
                            </center>
                </td></tr>
                        
                 <tr>    
                     <td colspan="6" align="center">
                         <div id="responde" style="display: none">
                            <center><br>
                               <a href="#valresult">      <button type="button" align="center" class="btn btn-primary" onclick="ValidarResultado();" title="Validar y Finalizar"><span class='glyphicon glyphicon-check'></span>&nbsp;Validar</button></a>
	<!--<button type="button" align="center" class="fg-button ui-state-default ui-corner-all" onclick="enviarDatosResult(0,0);" title="Guardar sin validar">Guardar</button>-->
       
		<button type="button" align="center" class="btn btn-primary" title="Regresar a Inicio"  onclick="Cerrar();"><span class='glyphicon glyphicon-remove-circle'></span>&nbsp;Cerrar</button>
	
	</center>  
                          </div>
                          
                         
                         
                     </td>
                     
                 </tr>
                        
                        
                        
                        
                  </table>
                </form>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div  id="divresultado" style="display:none"></div>
        </td>
    </tr>
    </tr>
    <tr>
        <td>
            <div  id="divresultado2" style="display:none"></div>
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
