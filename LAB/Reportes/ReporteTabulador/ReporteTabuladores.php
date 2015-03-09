<?php session_start();
 if(isset($_SESSION['Correlativo']) || isset($_SESSION["ADM"])){
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
echo $ROOT_PATH = $_SESSION['ROOT_PATH'];

//echo $lugar;
 ?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Tabulador Diario del Laboratorio</title>
<script language="JavaScript" type="text/javascript" src="ajax_ReporteTabuladores.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<!--referencias del estilo del calendario-->
<link rel="stylesheet" type="text/css" media="all" href="../../../calendarstructure/skins/aqua/theme.css" title="Aqua" />
<link rel="alternate stylesheet" type="text/css" media="all" href="../../../calendarstructure/calendar-blue.css" title="blue" />
<style>
    .ui-datepicker-calendar {
        display: none;
        }
    </style>
<?php include_once $ROOT_PATH."/public/css.php";?>
<?php include_once $ROOT_PATH."/public/js.php";?>
<!--llamado al archivo de funciones del calendario-->
<script type="text/javascript" src="../../../calendarstructure/calendar.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-es.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-setup.js"></script>
<script language="JavaScript" type="text/javascript">
function MostrarBusqueda()
{
	 if ((document.getElementById('txtfechainicio').value == "")&& (document.getElementById('txtfechafin').value == ""))
	 {
		alert("Seleccione un rango de fechas!");
	 }
	 else 
		BuscarDatos();
}

function BuscarExamen(idarea){

	if (document.getElementById('cmbArea').value == 0){
		  alert("Debe Seleccionar una Area");
 
	} 
	else{
		LlenarComboExamen(idarea);
	
	}
}
$(document).ready(function() {
        //$('#cmbExamen').multiselect();
         $('#cmbExamen').multiselect({
            buttonWidth: '100%',
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            inheritClass: true
           
        });
    });

</script>
<style type="text/css">
<!--
@media print{
#boton{display:none;}
#divInicial{display:none;}
#divInicial{display:none;}
}

-->
</style>
</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onload="mesanio();">

<?php 

if ($nivel==1){
	if(!isset($_SESSION["ADM"])){
		include_once ('../../../PaginaPrincipal/index_laboratorio2.php');}
	}else{ 
		$NivelArchivo="../../../../";
		include('../../MenuADM/MenuADM.php');
	}
if ($nivel==2){
	include_once ('../../../PaginaPrincipal/index_laboratorio22.php');}
if ($nivel==31){
	include_once ('../../../PaginaPrincipal/index_laboratorio31.php');}
if ($nivel==33){
	include_once ('../../../PaginaPrincipal/index_laboratorio33.php');}
$toy=date('Y-m');
$toy2=date('Y-m-d');        
?><br>

<!-- <form name=" cons_tabulador" onSubmit="return false;" action="excelOrd_x_id.php" method="post" target="_blank">-->
<form name=" cons_tabulador" onSubmit="return enviaxfis()" action="excelOrd_x_id2.php" method="post" target="_blank">
<table align="center" width="100%">

<tr>
<td align="center">

   <div class="col-md-9" style="float:center; width: 45%">
      <div class="panel panel-primary">                        
         <div class="panel-heading"><h3>Tabulador</h3> </div>                        
          <div class="panel-body" id="pb-primervez">                            
            <table class="table table-white no-v-border table-condensed" border="0" style="border:0px; width: 100%" >                              <tr><th>&Aacute;rea</th>
                  <td> 
                     <select id="cmbArea" name="cmbArea"  size="1" onChange="BuscarExamen(this.value)" style="width:100%;" class="form-control height">
                        <?php
                        echo '<option value="0" selected="selected">Seleccione una Area</p></option>';
                        include('../../../../Laboratorio/LAB/Mantenimientos/Lab_Areas/clsLab_Areas.php');
                        $objeareas = new clsLab_Areas;
                        $consulta = $objeareas->consultaractivas($lugar);
                        while ($row = pg_fetch_array($consulta)) {
                            echo "<option value='" . $row['idarea'] . "'>" . htmlentities($row['nombrearea']) . "</option>";
                        }
                        ?>		  
                     </select> 
                  </td>
                   </tr>
                   <tr>
                      <th>Examen</th>
                      <td>
                        <div id="divExamen">
                           <select name="cmbExamen" id="cmbExamen" class="form-control height" style="width:100%" size="1"  multiple="multiple"> 
                              
                           </select>
                       </div>
                      </td>
                      
                   </tr>
                   <tr>
                       <th>Año-Mes</th>
                                                          <td>   
                                                             <input type="text" id="d_fecha" name="d_fecha" style="width: 25%; text-align: center;" placeholder="<?php echo $toy; ?>"  class="datepicker form-control height"  autocomplete="off" />
                                                              </td>
                   </tr>
                   <tr>
                      <td colspan="2" align="center"> 
                         <br/>
                         <button type="submit" align="right" style="text-align: right" class="btn btn-primary"><span class='glyphicon glyphicon-file'></span>&nbsp;Generar Resultado </button></td>
                   </tr>                  
            </table>                        
         </div>                    
      </div>
   </div>
<!--
<div  id="divInicial" >

<p>&nbsp;</p>
	<table align="center"  class="StormyWeatherFormTABLE" width="70%">
		<tr>
			<td colspan="5" align="center" class="CobaltFieldCaptionTD">
				<h3><strong>Tabulador Diario
                                    </strong></h3>
			</td>
		</tr>
		<tr>
			<td class="StormyWeatherFieldCaptionTD" >Procedencia</td>
			<td class="StormyWeatherDataTD">
				<span class="StormyWeatherDataTD">
					<select name="cmbProcedencia" class="MailboxSelect" id="cmbProcedencia" onChange="BuscarSubServicio(this.value)">
						<option value="0">--Todas las Procedencia--</option>
		         			<?php
							/*include_once("../../../Conexion/ConexionBD.php");
							$con = new ConexionBD;
							if($con->conectar()==true){			  
                                                            $consulta  = "SELECT IdServicio,NombreServicio FROM mnt_servicio 
                                                                          WHERE IdTipoServicio<>'DCO' AND IdTipoServicio<>'FAR' ";
                                                                          $resultado = @mysql_query($consulta) or die('La consulta fall&oacute;: ' . @mysql_error());
									//por cada registro encontrado en la tabla me genera un <option>
                                                                          while ($rows = @mysql_fetch_array($resultado)){
										echo '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]). '</option>';
									  }
                                                                	@mysql_free_result($consulta); // Liberar memoria usada por consulta.
							}*/
						?>
					</select>
				</span>
			</td>
			<td class="StormyWeatherFieldCaptionTD">C&oacute;digo del &Aacute;rea</td>
			<td class="StormyWeatherDataTD">
				<select id="cmbArea" name="cmbArea" size="1" onChange="BuscarExamen(this.value)"> >
					<option value="0" >--Seleccione un &Aacute;rea--</option>
						<?php
							include ("clsReporteTabuladores.php");
							$obj = new clsReporteTabuladores;
							$consulta= $obj->consultaractivas($lugar);
							while($row = mysql_fetch_array($consulta)){
								echo "<option value='" . $row['IdArea']. "'>" . $row['NombreArea'] . "</option>";
							}
										
						?>		  
				</select>
			</td>
                        <td  class="StormyWeatherFieldCaptionTD"> </td>
                        <td  class="StormyWeatherDataTD" style="width:205px">
                            <div id="divExamen">
                                    <select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:250px"> 
                                            <option value="0"> Seleccione Examen </option>
                                    </select>
                            </div>
                        </td> 
		</tr>
		<tr>
			<td class="StormyWeatherFieldCaptionTD" style="width:120px">Fecha Inicio </td>
			<td class="StormyWeatherDataTD" style="width:210px">
				<input type="text" name="txtfechainicio" id="txtfechainicio">
				<input name="button" type="button" id="trigger"  value="...">
			</td>
			<td class="StormyWeatherFieldCaptionTD" style="width:120px">Fecha Final </td>
			<td class="StormyWeatherDataTD" style="width:210px"><input type="text" name="txtfechafin" id="txtfechafin" />
				<input name="button2" type="button" id="trigger2" value="...">
			</td>
		</tr>
		<tr>
			<td class="StormyWeatherDataTD" colspan="5" align="right">
                            <input type="button" id="btnbuscar" value="Buscar" onClick="MostrarBusqueda();">
			    <input type="button" id="btnClear" value="Nueva Busqueda" class="MailboxButton" onClick="window.location.replace('ReporteTabuladores.php')">			
			</td>
		</tr>
	</table>-->
</form>


<div id="divBusqueda">

</div>
</body>
</html>
<?php
}
else{?>
<script language="javascript">
	window.location="../../../login.php";
</script>
<?php }?>