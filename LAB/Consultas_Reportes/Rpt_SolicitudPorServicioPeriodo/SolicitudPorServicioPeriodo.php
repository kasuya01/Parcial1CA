<?php session_start();
if(isset($_SESSION['Correlativo'])){
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$nivel=$_SESSION['NIVEL'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
include_once("clsSolicitudesPorServicioPeriodo.php"); 
//consulta los datos por su id
$obj = new clsSolicitudesPorServicioPeriodo;
$consulta=$obj->DatosEstablecimiento($lugar);
$row = pg_fetch_array($consulta);
//valores de las consultas
$tipo=$row[0];
$nombrEstab=$row[1];
$nomtipo=$row[2];
 ?>















<html lang = "en">
<head>
    <meta charset="utf-8" />
<title>Consulta Solicitudes Por Sub-Servicio</title>


<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>

<!--<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />-->
<?php include_once $ROOT_PATH."/public/js.php";?>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudPorServicioPeriodo.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">

<!--referencias del estilo del calendario-->
<link rel="stylesheet" type="text/css" media="all" href="../../../calendarstructure/skins/aqua/theme.css" title="Aqua" />
<link rel="alternate stylesheet" type="text/css" media="all" href="../../../calendarstructure/calendar-blue.css" title="blue" />

<!--llamado al archivo de funciones del calendario-->
<script type="text/javascript" src="../../../calendarstructure/calendar.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-es.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-setup.js"></script>
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>

 





<script language="JavaScript" type="text/javascript">
    

    
function MostrarBusqueda()
{ 
	if ((document.getElementById('txtfechainicio').value == "") || (document.getElementById('txtfechafin').value == ""))
	{
		
             
        /*  $(function hola() {
                $("#dialog").dialog();
                autoOpen: false //| false // Modo de apertura
              //  resizable: tue //| false // Modificar las dimensiones
              //  height: integer // altura
              //  modal: true //| false // capa y fondo opaco
                //buttons: //: permite añadir botones
                //cancel: /*crear botón de cancelar // ejemplo */
               /* });*/
                                    
                          /*         $(function () {
                    $("#dialog").dialog( {
                    
                    autoOpen: false,
                    modal: true
                    
                    
                    });
                    $("#abrir")
                    //.button()
                    //.click(function () {
                    $("#dialog").dialog("open");
                    //});
                    });*/
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
	/*else 
		BuscarDatos(1);*/
        
        else {
                        jQuery('#divBusqueda').empty();
                        jQuery('#divBusqueda').append('<center><img id="wait" src="<?php echo $base_url; ?>/Laboratorio/public/images/spin.gif" alt="wait" width="24" height="24"><div id="search-message" style="color:#888888;font-weight: bold;">Buscando...</div></center>');
                        
                        setTimeout(function() {
                            jQuery('#divBusqueda').empty();
                            BuscarDatos(1);
                        }, 500);
                    }
        
}

function BuscarMedicos(idsubservicio){
    
        
 	LlenarComboMedico(idsubservicio);
       
}

function BuscarEstablecimiento(idtipoesta){

	LlenarComboEstablecimiento(idtipoesta);
}

function BuscarServicio(IdServicio){
	
	LlenarComboServicio(IdServicio);
}

</script>
</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" >
<div id="dialog" style='display:none;' title="¡Aviso!">
    <p> <cente>¡Complete el Rango de las Fechas!</cente></p>
</div>
<?php 

if ($nivel==1){
	include_once ('../../../PaginaPrincipal/index_laboratorio2.php');}
if ($nivel==2){
	include_once ('../../../PaginaPrincipal/index_laboratorio22.php');}
if ($nivel==31){
	include_once ('../../../PaginaPrincipal/index_laboratorio31.php');}
if ($nivel==33){
	include_once ('../../../PaginaPrincipal/index_laboratorio33.php');}
?><br>
<table align="center" width="100%">

<tr>
<td>

<div  id="divInicial" >
<form>
<p>&nbsp;</p>
	<table align="center"  class="StormyWeatherFormTABLE" width="85%">
          
		<tr>
			<td colspan="5" align="center" class="CobaltFieldCaptionTD">
				<h3><strong>Consulta de Solicitudes por Sub-Servicio</strong></h3>
			</td>
		</tr>
		<tr>		
			<td class="StormyWeatherFieldCaptionTD" width="15%">Tipo Establecimiento</td>
			<td class="StormyWeatherDataTD" width="18%">
				<select name="cmbTipoEstab" id="cmbTipoEstab" style="width:375px" onChange="BuscarEstablecimiento(this.value)" class="form-control height"  >
        				<option value="0" >Seleccione un Tipo de Establecimiento</option>
					<?php
				$db = new ConexionBD;
				if($db->conectar()==true){
					$consulta  = "SELECT id,nombre FROM ctl_tipo_establecimiento ORDER BY nombre";
					$resultado = pg_query($consulta);
					//por cada registro encontrado en la tabla me genera un <option>
					while ($rows = pg_fetch_array($resultado)){
						echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>'; 
					}
						echo '<option value="'. $tipo .'" selected="selected">' .htmlentities($nomtipo). '</option>';
                                                
                                                
				}
			?>
        			</select>
			</td>
        	<td class="StormyWeatherFieldCaptionTD" width="15%">Establecimiento</td>
        	<td class="StormyWeatherDataTD" width="18%" >
				<div id="divEstablecimiento">
					<select name="cmbEstablecimiento" id="cmbEstablecimiento"  style="width:375px" class="form-control height">
						<option value="0" >Seleccione un Establecimiento</option>
							<?php 
				  echo '<option value="'. $lugar .'" selected="selected">' .htmlentities($nombrEstab). '</option>';
		              	include_once("../../../Conexion/ConexionBD.php");
					$con = new ConexionBD;
					if($con->conectar()==true){			  
						//$consulta  = "SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento WHERE IdTipoEstablecimiento='$tipo' ORDER BY Nombre";
                                                $consulta  = "SELECT id,nombre FROM ctl_establecimiento WHERE id_tipo_establecimiento='$tipo' ORDER BY nombre";
						$resultado = @pg_query($consulta);
						//por cada registro encontrado en la tabla me genera un <option>
						while ($rows = @pg_fetch_array($resultado)){
							echo '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]). '</option>';
						}
		            }
				?>		
						</select>
			    </div>
	        </td>
        </tr>
		<tr>	
			<td class="StormyWeatherFieldCaptionTD" width="15%">Procedencia</td>
			<td class="StormyWeatherDataTD" width="18%">
				<select name="CmbServicio" id="CmbServicio" style="width:375px" onChange="BuscarServicio(this.value)" class="form-control height" >
					<option value="0" selected="selected" align="center"> Seleccione Procedencia </option>
					<?php
							$db = new ConexionBD;
							if($db->conectar()==true){
								$consulta  = /*"SELECT mnt_servicio.IdServicio,mnt_servicio.NombreServicio FROM mnt_servicio 
								INNER JOIN mnt_servicioxestablecimiento 
								ON mnt_servicio.IdServicio=mnt_servicioxestablecimiento.IdServicio
								WHERE IdTipoServicio<>'DCO' AND IdTipoServicio<>'FAR' AND IdEstablecimiento=$lugar";*/
                                                                        "SELECT t01.id,
                                                                 t01.nombre
                                                          FROM ctl_area_atencion t01
                                                          WHERE t01.id IN (
                                                                SELECT DISTINCT id_area_atencion 
                                                                FROM mnt_area_mod_estab WHERE id_establecimiento = $lugar)";
                                                                        
								$resultado = pg_query($consulta);
													
										//por cada registro encontrado en la tabla me genera un <option>
										while ($rows = pg_fetch_array($resultado)){
											echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>'; 
										}
							}
						?>
                                                
                                                
                                              
                </select>
			</td>
			<td class="StormyWeatherFieldCaptionTD" width="15%">Sub-Servicio</td>
			<td class="StormyWeatherDataTD" width="18%">
				<div id="divsubserv">
					<select name="cmbSubServ" id="cmbSubServ" onChange="BuscarMedicos(this.value)" style="width:250px" class="form-control height">
						<option value="0" selected="selected"> Seleccione un Servicio </option>
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<td class="StormyWeatherFieldCaptionTD" width="15%" >Medicos</td>
			<td  class="StormyWeatherDataTD"  width="18%" colspan="3">
				<div id="divMedico">
					<select name="cboMedicos" id="cboMedicos" 
                                                style="width:250px" class="form-control height"> 
						<option value="0">--Seleccione Un  Medico--</option>
					</select>
				</div>
			</td> 
		</tr>
		<tr>
			
                        
                        <td class="StormyWeatherFieldCaptionTD" width="19%">Fecha  Inicio</td>
		<td  class="StormyWeatherDataTD" width="20%" ><input type="text" size="28" name="txtfechainicio" id="txtfechainicio" style="width:250px" class="date form-control  height placeholder"  placeholder="aaaa-mm-dd" />
		</td>
                        
                
                            <td class="StormyWeatherFieldCaptionTD" width="19%">Fecha Final</td>
		<td  class="StormyWeatherDataTD" width="20%" ><input type="text" size="28"  name="txtfechafin" id="txtfechafin" style="width:250px" class="date form-control  height placeholder"  placeholder="aaaa-mm-dd" />
		</td>
                      
                        
                        
		</tr>
		<tr>
			 <!--<td class="StormyWeatherDataTD" colspan="5" align="right"><input type="button" id="btnbuscar" value="Buscar" onClick="MostrarBusqueda();">
				<input type="button" id="btnClear" value="Nueva Busqueda" class="MailboxButton" onClick="window.location.replace('SolicitudPorServicioPeriodo.php')">
			</td>-->
                    <td class="StormyWeatherDataTD" colspan="6" align="right">
                        <button type='button' align="center" class='btn btn-primary' id="abrir" onclick='MostrarBusqueda(); '><span class='glyphicon glyphicon-search'></span> Buscar </button>
                        <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('SolicitudPorServicioPeriodo.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                    </td>
                        
		</tr>
            
	</table>
</form>
<!--<script type="text/javascript">
	Calendar.setup(
	{
	    inputField  : "txtfechainicio",         // el ID texto 
	    ifFormat    : "%d/%m/%Y",    // formato de la fecha
	    button      : "trigger"       // el ID del boton			  	  
	}
	);
	Calendar.setup(
	{
	      inputField  : "txtfechafin",         // el ID texto 
	      ifFormat    : "%d/%m/%Y",    // formato de la fecha
	      button      : "trigger2"       // el ID del boton			  	  
	}
	);
</script> -->
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
