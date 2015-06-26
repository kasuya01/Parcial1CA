<?php session_start();
include_once("../../../Conexion/ConexionBD.php");
$db = new ConexionBD;
$usuario = $_SESSION['Correlativo'];
$lugar   = $_SESSION['Lugar'];
$area    = $_SESSION['Idarea'];
//echo $lugar
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
<title>Resultados de Ex&aacute;menes de Laboratorio</title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script> 
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<link type="text/css" href="../../../public/jquery-ui-1.10.3.custom/css/cupertino/jquery-ui-1.10.3.custom.css" rel="stylesheet" />
<link type="text/css" href="../../../public/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
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

</script>
<?php   
        
	$bandera=$_GET['var12'];
        $IdEstandar=$_GET['var16'];
        $IdHistorial=$_GET['var17'];
        $codarea=$_GET['var4'];
        
        if($db->conectar()==true){
         
                   $condatos = "SELECT t07.peso,t07.talla,t06.sct_name_es AS diagnostico,especificacion,conocido_por
                                FROM sec_historial_clinico               t01
                                INNER JOIN mnt_expediente                t02 ON (t02.id = t01.id_numero_expediente)
                                INNER JOIN mnt_paciente                  t03 ON (t03.id = t02.id_paciente)
                                LEFT OUTER JOIN sec_diagnostico_paciente t04 ON (t01.id = t04.id_historial_clinico)
                                LEFT OUTER JOIN mnt_snomed_cie10               t06 ON (t06.id = t04.id_snomed)
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
          }
?>
</head>

<body onLoad="RecogeValor();">
<table align="center" width="100%">
<tr>

</tr>
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
        <td class="StormyWeatherDataTD" colspan="4"><?php echo $_GET['var19'] ?></td>
    </tr>
    <tr>
        <td class="StormyWeatherFieldCaptionTD">*Validado Por</td>
        <td class="StormyWeatherDataTD" colspan="4">
			<div id="divEncargado">
				 <select id="cmbEmpleados" name="cmbEmpleados" size="1">
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
                     <input type="text" class="datepicker" id="txtresultrealiza"  name="txtresultrealiza" size="15">										
                </td>
                            
                <td class="StormyWeatherFieldCaptionTD">*Fecha Resultado</td>
                <td class="StormyWeatherDataTD" colspan="2">
                    <input type="text" class="datepicker" name="txtresultfin" id="txtresultfin" size="15"  value="<?php echo date("Y-m-d h:m"); ?>"  />	
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
		<td colspan="4" class="StormyWeatherDataTD" align="rigth"><input type="button" name="Submit" value="Ingresar Resultados" Onclick="IngresarResultadosPlantillaE() ;">  </td>
		</td>
	</tr>
	</table>
                    <script type="text/javascript" src="../../../public/datepicker/jquery-1.11.1.min.js"></script>
                    <script type="text/javascript" src="../../../public/datepicker/jquery-ui.min.js"></script>
                    <script type="text/javascript" src="../../../public/datepicker/jquery-ui-timepicker-addon.js"></script>
                    <script type="text/javascript" src="../../../public/datepicker/jquery-ui-timepicker-addon-i18n.min.js"></script>
                    <script type="text/javascript" src="../../../public/datepicker/jquery-ui-timepicker-es.js"></script>
                    <script type="text/javascript" src="../../../public/datepicker/jquery-ui-sliderAccess.js"></script>
                    <script type="text/javascript" src="../../../public/datepicker/script.js">
                </script>  
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
</body>
</html>
