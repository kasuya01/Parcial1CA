<?php session_start();
include_once("../../../Conexion/ConexionBD.php");
$db = new ConexionBD;
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
<title>Resultados de Ex&aacute;menes de Laboratorio</title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script> 
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
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
	observacion=document.frmnuevo.txtcomentario.value;
	procedencia=document.frmnuevo.txtprocedencia.value;
	origen=document.frmnuevo.txtorigen.value;
	MostrarResultadoExamen(idsolicitud,iddetalle,idarea,idexamen,resultado,observacion,nombrearea,procedencia,origen);
	//alert(resultado);
	}
	 else
   {    alert("Complete la Informacion Requerida");   }
}

function RecogeValor()
{
var vtmp=location.search;
var vtmp2 = vtmp.substring(1,vtmp.length);
//alert(vtmp2);
var query = unescape(top.location.search.substring(1));
var getVars = query.split(/&/);
for ( i = 0; i < getVars.length; i++)
{         if ( getVars[i].substr(0,5) == 'var1=' )//loops through this array and extract each name and value
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
/*  alert("area:"+area);
alert("nomexamen:"+examen);
alert("idexamen:"+codigoex);
alert("iddetalle:"+iddetallesol);
alert("idrecepcion:"+idrecepcionsol);  */
LlenarComboResponsable(area);
//CargarElementosExamen(codigoex);

}

//FUNCION PARA VERIFICAR DATOS REQUERIDOS EN RESULTADOS
<?php   
 
	$bandera=$_GET['var12'];
        $IdEstandar=$_GET['var16'];
        $IdHistorial=$_GET['var17'];
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

              $resultado = mysql_query($condatos);
              $rows = mysql_fetch_array($resultado);

              $Peso=$rows['Peso'];
              $Talla=$rows['Talla'];
              $Diagnostico=$rows['Diagnostico'];
              $ConocidoPor=$rows['ConocidoPor'];
              //echo $Peso." * ".$Talla." * ".$Diagnostico." * ".$ConocidoPor;
          }
?>
</script>
</head>

<body onLoad="RecogeValor();">
<table align="center" width="100%">
<tr>

</tr>
<tr>
<td>
<div  id="divFrmNuevo" >
<form name="frmnuevo" method="get" action="../IngresoResultados/ProcDatosResultadosExamen_PA.php" enctype="multipart/form-data">
<table width="60%" border="0" align="center" class="StormyWeatherFormTABLE">
	<tr class="CobaltButton" >
		<td colspan="5" align="center"> <h3>DATOS GENERALES</h3></td>
	</tr>
	<tr>
		<td class="StormyWeatherFieldCaptionTD">Establecimiento</td>
		<td colspan="3" class="StormyWeatherDataTD"><?php echo $_GET['var13'];?></td>
	</tr>
	<tr>
		<td class="StormyWeatherFieldCaptionTD">Procedencia</td>
		<td colspan="3" class="StormyWeatherDataTD"><?php echo $_GET['var10'];?></td>
        </tr>
	<tr>
		<td class="StormyWeatherFieldCaptionTD">Origen</td>
		<td colspan="3" class="StormyWeatherDataTD"><?php echo $_GET['var11'];?></td>
	</tr>
	<tr>
        	<td class="StormyWeatherFieldCaptionTD">NEC</td>
            	<td colspan="3" colspan="2" class="StormyWeatherDataTD"><?php echo $_GET['var1'] ?>
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
                         <input type="hidden" name="txtIdEstandar" id="txtIdEstandar" value="<?php echo $_GET['var16']?>" />
                         <input type="hidden" name="txtIdHistorial" id="txtIdHistorial" value="<?php echo $_GET['var17']?>" />
		</td>
        </tr>
	<tr>
        	<td class="StormyWeatherFieldCaptionTD">Paciente</td>
            	<td colspan="3" class="StormyWeatherDataTD"><?php echo $_GET['var7'] ?>
             		<input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" />
		</td>
        </tr>
        <tr>
                <td class="StormyWeatherFieldCaptionTD">Conocido Por</td>
                <td colspan="3" class="StormyWeatherDataTD"><?php echo htmlentities($ConocidoPor);?>
                        <input type="hidden" name="txtpaciente" id="txtpaciente" disabled="disabled" size="60" />
                </td>
	</tr>
        <tr>
               <td class="StormyWeatherFieldCaptionTD">Diagnostico</td>
               <td colspan="3" class="StormyWeatherDataTD"><?php echo htmlentities($Diagnostico);?>
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
            	<td colspan="3" class="StormyWeatherDataTD"><?php echo htmlentities($_GET['var9']) ?>
			<input type="hidden" name="txtnombrearea" id="txtnombrearea" disabled="disabled" size="60" />
		</td>
        </tr>
        <tr>
        	<td class="StormyWeatherFieldCaptionTD">Examen </td>
            	<td colspan="3" class="StormyWeatherDataTD"><?php echo $_GET['var2'] ?>
           		<input type="hidden" name="txtexamen" id="txtexamen" disabled="disabled" size="60" />
		</td>
        </tr>
	<tr>
	        <td class="StormyWeatherFieldCaptionTD">*Validado Por</td>
                <td colspan="3" class="StormyWeatherDataTD">
			 <div id="divEncargado">
				 <select id="cmbEmpleados" name="cmbEmpleados" size="1">
					<option value="0" >--Seleccione Empleado--</option>
				 </select>
			</div>
		</td>
        </tr>
	<tr>
		<td class="StormyWeatherFieldCaptionTD">Observaci&oacute;n </td>
		<td colspan="3" class="StormyWeatherDataTD">
			  <textarea name="txtobservacion" cols="60" id="txtobservacion"></textarea>
		</td>
	</tr>
	   <?php 
      if ($bandera==1){
	 ?>
	<tr>
		<td colspan="2" align="center" class="StormyWeatherDataTD" style="color:#DD0000; font:bold">
		    <h3>El m&eacute;dico ha solicitado la impresi&oacute;n de este Resultado </h3>
				</td>
			</tr>
		  <?php 
       }?>
		  <tr>
		  <td colspan="4" class="StormyWeatherDataTD" 	align="rigth"><input type="button" name="Submit" value="Ingresar Resultados" Onclick="IngresarResultadosPlantillaE() ;">  </td>
		  </td>
		  </tr>
	</table>
</form>
</div>
</td>
</tr>
<tr>
<tr>
<td>
</td>
</tr>
<td>
<div  id="divexamen" style="display:none">

</div>
</td>
</tr>
<tr>
<td>
<div  id="divresultado" style="display:none"></div></td>
</tr>
</table>
</body>
</html>
