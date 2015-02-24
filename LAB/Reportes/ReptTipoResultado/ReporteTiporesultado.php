<?php session_start();
 if(isset($_SESSION['Correlativo']) || isset($_SESSION["ADM"])){
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
 ?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<title>Recepcion de Examenes en &Aacute;reas de Laboratorio</title>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>

<script language="JavaScript" type="text/javascript" src="ajax_ReporteTiporesultado.js"></script>
<!--referencias del estilo del calendario-->
<?php include_once $ROOT_PATH."/public/css.php";?>
<?php include_once $ROOT_PATH."/public/js.php";?>

<script language="JavaScript" type="text/javascript">





function MostrarBusqueda()
{
	 if ((document.getElementById('txtfechainicio').value == "") || (document.getElementById('txtfechafin').value == ""))
	 {
		//alert("Seleccione un rango de fechas!");
                  
                         $(function ()   {
                    $("#dialog").dialog({
                                autoOpen: false,
                                modal: true,
                                    buttons: {      
                                                "Cerrar": function () 
                                                            {
                                                                $(this).dialog("close");
                                                            }
                                            }
                                        });
                            $("#abrir")
                            //.button()
                            //.click(function () {
                            $("#dialog").dialog("open");
                           // });
                                });
                
	 }
	 else 
		BuscarDatos(1);
}

function BuscarSubServicio(proce){
  
		LlenarComboSubServicio(proce);
	
	
}
function BuscarExamen(idarea) {
                    if (document.getElementById('cmbArea').value == 0) {
                        alert("Debe Seleccionar una Área");
                    }
                    else {
                        LlenarComboExamen(idarea);

                    }
                }

</script>

</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" >
<div id="dialog" style='display:none;' title="¡Aviso!">
    <p> <cente>¡Seleccione un rango de fechas!!</cente></p>
</div>
<?php 
//$nivel=$_SESSION['NIVEL']; 
//$_SESSION['correlativo']=$_SESSION['Correlativo']; 
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
?><br>
<table  align="center" width="100%">

<tr>
<td>

<div  id="divInicial" >
<form>
<p>&nbsp;</p>
	<table align="center"  class="StormyWeatherFormTABLE" width="70%">
		<tr>
			<td colspan="5" align="center" class="CobaltFieldCaptionTD">
				<h3><strong>Reporte Por Tipo De Resultado</strong></h3>			</td>
		</tr>
	<!--	<tr>
                    <td class="StormyWeatherFieldCaptionTD" > Procedencia</td>
			<td  class="StormyWeatherDataTD" ><span class="StormyWeatherDataTD" >
			  <select name="cmbProcedencia" class="ui-corner-all" id="cmbProcedencia" style="width:200px" onChange="BuscarSubServicio(this.value)"  >
               			 <option value="0">--Todas las Procedencia--</option>
         			<?php
					include_once("../../../Conexion/ConexionBD.php");
					$con = new ConexionBD;
					if($con->conectar()==true){			  
						$consulta  = /*"SELECT IdServicio,NombreServicio FROM mnt_servicio 
						WHERE IdTipoServicio<>'DCO' AND IdTipoServicio<>'FAR' ";*/
                                                        
                                                        "SELECT t01.id,
                                                                 t01.nombre
                                                          FROM ctl_area_atencion t01
                                                          WHERE t01.id IN (
                                                                SELECT DISTINCT id_area_atencion 
                                                                FROM mnt_area_mod_estab WHERE id_establecimiento = $lugar)";
                                                        
						$resultado = @pg_query($consulta) or die('La consulta fall&oacute;: ' . @pg_error());
						//por cada registro encontrado en la tabla me genera un <option>
					while ($rows = @pg_fetch_array($resultado)){
						echo '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]). '</option>';
					}
					@pg_free_result($consulta); // Liberar memoria usada por consulta.
					}
					?>
              </select>
			</span></td>
                        <td class="StormyWeatherFieldCaptionTD" style="font-weight:bold"> Servicio</td>
			<td  class="StormyWeatherDataTD">
				<div id="divSubServ">
					<select name="cmbSubServicio" id="cmbSubServicio" class="ui-corner-all" style="width:200px"> 
						<option value="0">--Seleccione un Servicio--</option>
					</select>
				</div>		</td> 
		</tr> -->
                <tr>
                            <td class="StormyWeatherFieldCaptionTD" style="font-weight:bold">&Aacute;rea de Laboratorio</td>
                            <td class="StormyWeatherDataTD" colspan="1">
                                <select id="cmbArea" name="cmbArea" class="form-control height" style="width:250px" size="1" onChange="BuscarExamen(this.value)">
                                    
                                    
                                    <?php
                                    include('../../../../Laboratorio/LAB/Mantenimientos/Lab_Areas/clsLab_Areas.php');
				$objeareas=new clsLab_Areas;
				$consulta= $objeareas->consultaractivas($lugar);
				while($row = pg_fetch_array($consulta)){
			        echo "<option value='" . $row['idarea']. "'>" . htmlentities($row['nombrearea']) . "</option>";
				}
				echo '<option value="'. $row['idarea'].'" selected="selected">'.htmlentities('--Seleccione un Área--').'</option>';
				?>
                                </select>
                            </td>
                            <td  class="StormyWeatherFieldCaptionTD">Examen</td>
                            <td  class="StormyWeatherDataTD"  style="width:200px">
                                <div id="divExamen">
                                    <select name="cmbExamen" id="cmbExamen"   class="form-control height" style="width:250px"> 
                                        <option value="0">--Seleccione Examen--</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                
		<tr>
                    
                    <td class="StormyWeatherFieldCaptionTD" width="15%" >Fecha Resultado</td>
                        <td class="StormyWeatherDataTD" width="20%">
                            <input type="text" size="17" name="txtfechainicio" id="txtfechainicio" style="width:250px" class="date form-control  height placeholder"  placeholder="aaaa-mm-dd"/>
                    
                    
                        
                        
                        <td class="StormyWeatherFieldCaptionTD" width="15%" >Hasta</td>
                        <td class="StormyWeatherDataTD" width="20%">
                            <input type="text" size="17" name="txtfechafin" id="txtfechafin" style="width:250px" class="date form-control  height placeholder"  placeholder="aaaa-mm-dd"/>
		</tr>
		<tr>
			 
                           
                                 
                            <td class="StormyWeatherDataTD" colspan="4" align="right">
                              
                            <br>
                                    <!-- <input type="button" id="btnbuscar"  class="fg-button ui-state-default ui-corner-all  value="Buscar" onClick="MostrarBusqueda();"> -->
                                    <!-- <input type="button" id="btnClear" value="Nueva Busqueda"  onClick="window.location.replace('ImprimirResultado.php')">	-->
                               <button type='button' align="center" class='btn btn-primary' id="abrir" onclick='MostrarBusqueda(); '><span class='glyphicon glyphicon-search'></span> Buscar</button>
                               <button type='button' align="center" class='btn btn-primary' id='nuevabusqueda' onclick="window.location.replace('ReporteTiporesultado.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                               <br>
                           
                        </td>
		</tr>
	</table>
</form>
 <!--<script type="text/javascript">
                    Calendar.setup(
                        {
                            inputField  : "txtfechasolicitud",         // el ID texto 
                            ifFormat    : "%d/%m/%Y",    // formato de la fecha
                            button      : "trigger"       // el ID del boton			  	  
                        }
                    );
                </script>  -->
</div>
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