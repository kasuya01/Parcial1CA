<?php session_start();
include_once("../../../Conexion/ConexionBD.php");
$db = new ConexionBD;
include ("clsPlantillaE.php");
$obj = new clsPlantillaE;
$usuario = $_SESSION['Correlativo'];
$lugar   = $_SESSION['Lugar'];
$area    = $_SESSION['Idarea'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
//echo $lugar
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
<title>Resultados de Ex&aacute;menes de Laboratorio</title>
<?php include_once $ROOT_PATH."/public/css.php";?>
<?php include_once $ROOT_PATH."/public/js.php";?>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script> 
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<link type="text/css" href="../../../public/jquery-ui-1.10.3.custom/css/cupertino/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
<link type="text/css" href="../../../public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
<script language="JavaScript" >
//Recarga funcion buscar de la página padre
window.onunload = refreshParent;  
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
	 if (document.frmnuevo.txtresultado.value == "")
		 {
			resp= false;		
		 }
         if (document.frmnuevo.txtresultrealiza.value == "")
		 {
			resp= false;		
		 }
         if (document.frmnuevo.txtresultfin.value == "")
		 {
			resp= false;		
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
	resultado=document.frmnuevo.txtresultado.value;
	//observacion=document.frmnuevo.txtcomentario.value;
	procedencia=document.frmnuevo.txtprocedencia.value;
	origen=document.frmnuevo.txtorigen.value;
	MostrarResultadoExamen(idsolicitud,iddetalle,idarea,idexamen,resultado,observacion,nombrearea,procedencia,origen);
	//alert(resultado);
    }
    else
    {    alert("Complete la Informacion Requerida");   }
}

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
                if ( getVars[i].substr(0,5) == '$fecha_recepcion_=' )
                    $fecha_recepcion_=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'edad=' )
                    origenmuestra=escape(getVars[i].substr(5));
                if ( getVars[i].substr(0,5) == 'nomsexo=' )
                    nomsexo=escape(getVars[i].substr(5));
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

//FUNCION PARA VERIFICAR DATOS REQUERIDOS EN RESULTADOS
 var nav4 = window.Event ? true : false;
        function acceptNum(evt){
        var key = nav4 ? evt.which : evt.keyCode;
       // alert(key);
       //return ((key < 13) || (key >= 48 && key <= 57) || (key>=97 && key<=122) ||(key>=65 && key<=90) || key==32 || key==46 || key==209 || key==241) ;//47 es '/', 46 es '.' 32 'space'
       return ((key!=47) && (key!=92));//47 es '/', 46 es '.' 32 'space'
        }//Filtracion de teclas

jQuery(document).ready(function($){

            $(".date").datepicker({
              onClose:  function() {
                          validafecha($(this).val(), $(this).attr('name'),$('#dateftomamx').val() ); 
                          valdatesolicita($(this).val(), $(this).attr('name'));
                         }
            });

         }); 
</script>
<?php   
        
	$bandera=$_GET['var12'];
        $IdEstandar=$_GET['var16'];
        $IdHistorial=$_GET['var17'];
       // $codarea=$_GET['var4'];
        $ftx= $_GET['var19'];
        $idsolicitud=$_GET['var6'];
        $edad=$_GET['edad'];
        $nomsexo=$_GET['nomsexo'];
        $IdDetalleSolicitud=$_GET['var5'];
        $idarea= $_GET['var4'];
        $f_consulta=$_GET['f_consulta'];
       // echo $idsolicitud." - ".$IdDetalleSolicitud." - ".$idarea;
        $cant=$obj->buscarAnterioresPUnica($idsolicitud,$IdDetalleSolicitud,$idarea);
        if (pg_num_rows($cant)>0){
        
            if($db->conectar()==true){

               $condatos = "SELECT t07.peso,t07.talla,
                            CASE WHEN t04.id_snomed IS NOT NULL
                                THEN t06.sct_name_es 
                                ELSE t08.diagnostico
                            END AS diagnostico,especificacion,conocido_por
                            FROM sec_historial_clinico               t01
                            INNER JOIN mnt_expediente                t02 ON (t02.id = t01.id_numero_expediente)
                            INNER JOIN mnt_paciente                  t03 ON (t03.id = t02.id_paciente)
                            LEFT OUTER JOIN sec_diagnostico_paciente t04 ON (t01.id = t04.id_historial_clinico)
                            LEFT OUTER JOIN mnt_snomed_cie10               t06 ON (t06.id = t04.id_snomed)
                            LEFT OUTER JOIN mnt_cie10 t08 ON (t08.id = t04.id_cie10_medico)
                            LEFT OUTER JOIN sec_signos_vitales         t07 ON (t01.id = t07.id_historial_clinico)
                            WHERE t01.id = $IdHistorial AND t01.idestablecimiento =$lugar";

                  $resultado = pg_query($condatos);
                  $rows = pg_fetch_array($resultado);

                  $Peso=$rows['peso'];
                  $Talla=$rows['talla'];
                  $Diagnostico=$rows['diagnostico'];
                  $ConocidoPor=$rows['conocido_por'];
                  $Especificacion=$rows['especificacion'];
                  //echo $Peso." * ".$Talla." * ".$Diagnostico." * ".$ConocidoPor;
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
            <div  id="divFrmNuevo" >
                <form name="frmnuevo" method="get" action="../IngresoResultados/ProcDatosResultadosExamen_PA.php" enctype="multipart/form-data">
        <table width="80%" border="0" align="center" class="StormyWeatherFormTABLE">
                <tr class="CobaltButton" >
                    <td colspan="5" align="center"> <h3>DATOS GENERALES</h3></td>
                </tr>
                <tr>
                    <td class="StormyWeatherFieldCaptionTD">Establecimiento Solicitante</td>
                    <td class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var18'];?></td>
                </tr>
                <tr>
                    <td class="StormyWeatherFieldCaptionTD">Procedencia</td>
                    <td class="StormyWeatherDataTD"><?php echo $_GET['var10'];?></td>
                    <td class="StormyWeatherFieldCaptionTD">Servicio</td>
                    <td class="StormyWeatherDataTD"><?php echo $_GET['var11'];?></td>
                </tr>
                <tr>
                    <td class="StormyWeatherFieldCaptionTD">NEC</td>
                    <td class="StormyWeatherDataTD"><?php echo $_GET['var1'] ?></td>
                    <td class="StormyWeatherFieldCaptionTD">No. Order</td>
                    <td class="StormyWeatherDataTD"><?php echo $_GET['var6'];?>
                            <input type="hidden" name="txtnec" id="txtnec" disabled="disabled" />
                            <input type="hidden" name="txtidsolicitud" id="txtidsolicitud" />
                            <input type="hidden" name="txtiddetalle" id="txtiddetalle" />
                            <input type="hidden" name="txtidexamen" id="txtidexamen" />
                            <input type="hidden" name="txtidrecepcion" id="txtidrecepcion" />
                            <input type="hidden" name="txtarea" id="txtarea" />
                            <input type="hidden" name="txtprocedencia" id="txtprocedencia" />
                            <input type="hidden" name="txtorigen" id="txtorigen" />
                            <input type="hidden" name="txtestablecimiento" id="txtestablecimiento" value="<?php echo $_GET['var13']?>"/>
                            <input type="hidden" name="txtFechaNac" id="txtFechaNac" value="<?php echo $_GET['var14']?>" />
                            <input type="hidden" name="txtSexo" id="txtSexo" value="<?php echo $_GET['var15']?>" />
                            <input type="hidden" name="txtf_tomamuestra" id="txtf_tomamuestra" value="<?php echo $_GET['var19']?>" />
                            <input type="hidden" name="txttipomuestra" id="txttipomuestra" value="<?php echo $_GET['var20']?>" />
                            <input type="hidden" name="solicitud_" id="solicitud_" value="<?php echo $_GET['var6'];?>" />
                            <input type="hidden" name="idexpediente_" id="idexpediente_"  value="<?php echo $_GET['var1'];?>"/>
                            <input type="hidden" name="fecharecepcion" id="fecharecepcion"  value="<?php echo $_GET['fecha_recepcion'];?>"/>
                            <input type="hidden" name="idestabext_" id="idestabext_"  value="<?php echo $_GET['var21'];?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="StormyWeatherFieldCaptionTD">Paciente</td>
                    <td class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var7'] ?>
                        <input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" />
                    </td>
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
                       <td colspan="3" class="StormyWeatherDataTD"><?php echo $Diagnostico;?>

                       </td>
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

                           ?></td>
                       <td class="StormyWeatherFieldCaptionTD" width="25%">Talla</td>
                       <td class="StormyWeatherDataTD"><?php 
                            if(!empty($Talla))
                                echo htmlentities($Talla)." cm";?></td>
                </tr>

                <tr>
                <td class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
                <td class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var9'] ?>
                                <input type="hidden" name="txtnombrearea" id="txtnombrearea" disabled="disabled" size="60" />
                        </td>
            </tr>
            <tr>
                <td class="StormyWeatherFieldCaptionTD">Examen </td>
                <td class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var2'] ?>
                        <input type="hidden" name="txtexamen" id="txtexamen" disabled="disabled" size="60" />
                </td>
            </tr>
            <tr>
                <td class="StormyWeatherFieldCaptionTD">Muestra Recibida</td>
                <td class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var20'] ?></td>
            </tr>
            <tr>
                <td class="StormyWeatherFieldCaptionTD">Fecha de Toma de Muestra</td>
                <td class="StormyWeatherDataTD" colspan="1"><?php echo $_GET['var19'] ?>
                     <input type="hidden" id="fecha_tmuestra" name="f_tmuestra" value="<?php echo $ftx;?>"/>
                     <input type="hidden" id="dateftomamx" name="dateftomamx" value="<?php echo $dateftomamx;?>"/>
                     <input type="hidden" id="f_consulta" name="f_consulta" value="<?php echo $f_consulta;?>"/>
                </td>
                <td class="StormyWeatherFieldCaptionTD">Fecha de Consulta</td> 
                <td class="StormyWeatherDataTD" colspan="1"><?php echo $f_consulta; ?></td>
            </tr>
            <tr>
                <td class="StormyWeatherFieldCaptionTD">*Validado Por</td>
                <td class="StormyWeatherDataTD" colspan="4">
                                <div id="divEncargado">
                                         <select id="cmbEmpleados" name="cmbEmpleados" size="1" class="form-control  height">
                                                <option value="0" >--Seleccione Empleado--</option>
                                         </select>
                                </div>
                        </td>
                </tr>
                <!--<tr>
                        <td class="StormyWeatherFieldCaptionTD">Observaci&oacute;n </td>
                        <td class="StormyWeatherDataTD" colspan="4">
                                  <textarea name="txtobservacion" cols="60" id="txtobservacion"></textarea>
                        </td>
                </tr>-->
                <tr>
                        <td class="StormyWeatherFieldCaptionTD">*Fecha y hora inicio Proceso</td>
                        <td class="StormyWeatherDataTD">
                             <input type="text" class="date form-control height placeholder" name="txtresultrealiza" id="txtresultrealiza" size="60"  placeholder="aaaa-mm-dd" style="width:100%"/>
                            <!-- <input type="text" class="date" id="txtresultrealiza"  name="txtresultrealiza" size="15">-->
                        </td>

                        <td class="StormyWeatherFieldCaptionTD">*Fecha Resultado</td>
                        <td class="StormyWeatherDataTD" colspan="2">
                            <!--<input type="text" class="date" name="txtresultfin" id="txtresultfin" size="15"  value="<?php echo date("Y-m-d"); ?>"  />-->	
                            <input type="text" class="date form-control height" name="txtresultfin" id="txtresultfin" size="40" style="width:90%"  value="<?php echo date("Y-m-d"); ?>"  />
                            <input type="hidden" name="fecha_reporteaux" id="fecha_reporteaux" size="40"  value="<?php echo date("Y-m-d"); ?>"  /> 
                        </td>
                </tr>
                   <?php 
              if ($bandera==1){
                 ?>
                <tr>
                        <td colspan="4" align="center" class="StormyWeatherDataTD" style="color:#DD0000; font:bold">
                            <h3>El m&eacute;dico ha solicitado la impresi&oacute;n de este Resultado </h3>
                        </td>
                </tr>
                          <?php 
                          }?>
                <tr>
                        <td colspan="4" class="StormyWeatherDataTD" align="rigth">
                           <!-- <input type="button" name="Submit" value="Ingresar Resultados" Onclick="IngresarResultadosPlantillaE() ;">  -->
                            <button type="button" align="right" style="text-align: right" class="btn btn-primary" onclick="IngresarResultadosPlantillaE();">&nbsp;Ingresar Resultado </button>
                        </td>
                        </td>
                </tr>
                </table>

                    </form>
                </div>
            </td>
        </tr>
        <tr>
            <td>
            </td>
        </tr>
        <tr>
            <td>
                <div  id="divexamen" style="display:none">

                </div>
            </td>
        </tr>
        <tr>
            <td>
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
