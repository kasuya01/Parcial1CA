<?php session_start();
include('../Lab_Areas/clsLab_Areas.php');
include("clsLab_Procedimientos.php");

 
$objdatos=new clsLab_Procedimientos;
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
$idproce=0;
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
 //$idexamen=$_POST['resultado'];
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
<title>Mantenimiento de Procedimientos de Examen</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_Procedimientos.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<!--referencias del estilo del calendario-->
<!--<link rel="stylesheet" type="text/css" media="all" href="../../../calendarstructure1/skins/aqua/theme.css" title="Aqua" />
<link rel="alternate stylesheet" type="text/css" media="all" href="../../../calendarstructure1/calendar-blue.css" title="blue" -->
<!--llamado al archivo de funciones del calendario-->
<!--<script type="text/javascript" src="../../../calendarstructure1/calendar.js"></script>
<script type="text/javascript" src="../../../calendarstructure1/calendar-es.js"></script>
<script type="text/javascript" src="../../../calendarstructure1/calendar-setup.js"></script>-->
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>


<script language="JavaScript" >
function Guardar(){
   	IngresarRegistro();
}

function LlenarComboExamen(idArea)
{
  //alert  (idArea);
       LlenarExamenes(idArea);
}

function llenarcomboRango(idexa){
    //alert(idexa);
    LlenarRango(idexa)
    
}
function Buscar(){
    
    
     if ( (document.getElementById('cmbArea').value == 0) 
          &&(document.getElementById('cmbExamen').value == 0)
          &&(document.getElementById('cmbSexo').value == 0)
          &&(document.getElementById('cmbEdad').value == 0)
          &&(document.getElementById('txtproc').value == "")
          &&(document.getElementById('txtunidades').value == "")
          &&(document.getElementById('cmbExamen').value == 0)
          &&(document.getElementById('txtrangoini').value == "")
          &&(document.getElementById('txtrangofin').value == "")
          
          &&(document.getElementById('txtFechainicio').value == "")
          &&(document.getElementById('txtFechaFin').value == "")
          
                
                )
                        {  
                            //alert("Debe de Ingresar un Parámetro de Búsqueda ");
                            
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
                        else{
                            BuscarDatos();
                        }
    
	
}
function Actualizar(){
	enviarDatos();
}
function Nuevo(){
	MostraFormularioNuevo();
}
function popup(URL) {
        myWindow=window.open(URL, '" + "', 'scrollbars=yes, width=700, height=700, left=100, top = 100');
    }


function habilitar_metodologia(obj){
        if(obj.value !== "") {
           // alert("se hablitara el boton");
            obj1 = document.getElementById('add_presultado');
            obj1.disabled = false;
        }
    }
    
    function habilitar_rango(obj){
        if(obj.value !== "") {
          //  habilitar_metodologia();
            // alert("se hablitara el combo");
            obj1 = document.getElementById('add_cmbrango');
            obj1.disabled = false;
            
            obj2 = document.getElementById('add_metodologia');
            obj2.disabled = false;
            
        }
    }

</script>
<style type="text/css">
</style>
</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="show_event(1);">
<div id="dialog" style='display:none;' title="¡Aviso!">
    <p> <cente>¡Debe de Ingresar al menos un Parámetro de Búsqueda!!</cente></p>
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
             <div  id="divFrmNuevo" >
                <form name="frmnuevo" >
                    <table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
                        <tr>
                            <td colspan="3" align="center" class="CobaltFieldCaptionTD"><h3><strong>Procedimientos de Ex&aacute;menes de Laboratorio</h3></strong>
                            </td>
			</tr>	
			<tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
                            <td width="83%" class="StormyWeatherDataTD">
                                <select id="cmbArea" name="cmbArea" size="1" style="width:50%" onChange="LlenarComboExamen(this.value);" class="form-control height">
                                    <option value="0" >--Seleccione un &Aacute;rea--</option>
                                        <?php
                                            $objeareas=new clsLab_Areas;
                                            $consulta= $objeareas->consultaractivas($lugar);
                                            while($row = pg_fetch_array($consulta)){
                                                 echo "<option value='" . $row[0]. "'>" . $row['nombrearea'] . "</option>";
                                                 
                                                
                                            }
                                            
                                            
					?>		  
           			</select>		  
                            </td>
    			</tr>
   			<tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Examen </td>
                            <td width="83%"  class="StormyWeatherDataTD">
                                <div id="divExamen">
                                    <select name="cmbExamen" id="cmbExamen" style="width:50%"  class="form-control height"> 
                                        <option value="0">--Seleccione un Examen--</option>
                                        
                                      
                                    </select>
				</div>
                            </td>
        		</tr>
                       <tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Sexo</td>
                            <td width="83%"  class="StormyWeatherDataTD">
                                <select id="cmbSexo" name="cmbSexo" size="1" style="width:50%"  class="form-control height" >
                                    <option value="0" >--Seleccione Sexo--</option>
                                        <?php
                                            $consultaS= $objdatos->consultarsexo();
                                            while($row =pg_fetch_array($consultaS)){
                                                echo "<option value='" . $row['id']. "'>". $row['nombre'] . "</option>";
                                            }
                                            echo "<option value='3'>Ambos</option>"
                                        ?>        
                                </select>		  
			    </td>        
                       </tr>
                       <tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Rango Edad</td>
                            <td width="83%"  class="StormyWeatherDataTD">
                                <select id="cmbEdad" name="cmbEdad" size="1" style="width:50%"  class="form-control height" >
                                    <option value="0" >--Seleccione un Rango de Edad--</option>
                                    <?php
                                        $conEdad = $objdatos->RangosEdades();
                                        while($row = pg_fetch_array($conEdad)){
                                            echo "<option value='" . $row[0]. "'>". $row[1] . "</option>";
                                        }
                                    ?>    
                                </select>		  
                            </td>        
                       </tr>
                       <tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Procedimiento</td>
                            <td width="83%" class="StormyWeatherDataTD"><input name="txtproc" type="text" id="txtproc" style="width:250px" size="50"  placeholder="Ingrese Procedimiento" class="form-control height placeholder"></td>
                       </tr>
                       <tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Unidades</td>
                            <td width="83%" class="StormyWeatherDataTD"><input name="txtunidades" type="text" id="txtunidades" style="width:250px" size="10" placeholder="Ingrese Unidades" class="form-control height placeholder"></td>
			</tr>
                        
                        <tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Orden </td>
                            <td width="83%"  class="StormyWeatherDataTD">
                                <div id="divRango">
                                    <select   name="cmborden"  id="cmborden" style="width:50%"  class="form-control height"  > 
                                        <option value="0">--Seleccione un Orden--</option>
                                        <!--disabled="disabled"-->
                                  </select>
				</div>
                            </td>
        		</tr>
                        
                        <tr>
                            <td nowrap class="StormyWeatherFieldCaptionTD">Posible  Resultado</td>
                            <td class="StormyWeatherDataTD">
                                <input type="hidden" name="resultado" id="resultado">
                                <input type="hidden" name="resultado_nombre" id="resultado_nombre">
                                <input type="hidden" name="id_resultado" id="id_resultado">
                                <button type='button' class='btn btn-default'  disabled="disabled"  name="add_presultado" id="add_presultado" style="width:250px" onclick="
                                popup('consulta_metodologias1.php?form=frmnuevo&metodologias_sel='+document.getElementById('resultado').value+
                                        '&text_metodologias_sel='+document.getElementById('resultado_nombre').value+
                                        '&nombre='+document.getElementById('cmbExamen').value+ '&id_metodologias_sel='+document.getElementById('id_resultado').value);"><span class='glyphicon glyphicon-th-list'></span> ..:Seleccionar Resultado:..</button>
                         
                        </td>
                         </tr>  
                         
                        
                        
                        <tr>
                            <td colspan="2" class="StormyWeatherDataTD">
                                <table width="900" border="0" align="center" class="StormyWeatherFormTABLE">
                                    <td width="17%" class="StormyWeatherFieldCaptionTD">Rango Inicio</td>
                                            <td class="StormyWeatherDataTD">
                                                <input name="txtrangoini" type="text" id="txtrangoini" size="25"   style="width:250px" placeholder="Ingrese Rango Inicial" class="form-control height placeholder" >
                                            </td>
                                            <td class="StormyWeatherFieldCaptionTD" > Rango Fin </td>
                                            <td class="StormyWeatherDataTD">
                                                <input name="txtrangofin" type="text" id="txtrangofin" size="25"   style="width:250px" placeholder="Ingrese Rango Final" class="form-control height placeholder" >
                                            </td>
				</table>	             
                            </td> 
			</tr>
                        
                        <tr>
                            <td colspan="2" class="StormyWeatherDataTD">
                                <table width="900" border="0" align="center" class="StormyWeatherFormTABLE">
                                    <tr>   
                                        <td width="17%" class="StormyWeatherFieldCaptionTD">Fecha Inicio</td>
                                            <td class="StormyWeatherDataTD">
                                                <input  name="txtFechainicio" type="text" id="txtFechainicio" size="25" style="width:250px" placeholder="aaaa-mm-dd"  class="date form-control  height placeholder"    >
                                            </td>
                                                                    
                                           
                                            <td class="StormyWeatherFieldCaptionTD" >  Fecha Fin </td>
                                            <td class="StormyWeatherDataTD">
                                                <input name="txtFechaFin" type="text" id="txtFechaFin" size="25"   style="width:250px" placeholder="aaaa-mm-dd"  class="date form-control height placeholder" >
                                            </td>
                                    </tr>   
                                    
				</table>
                            </td>				
			</tr>
			<!--<tr>
                            <td class="StormyWeatherDataTD" colspan="2" align="right">
                                <input type="button" name="btnGuardar" value="Guardar" onClick="Guardar() ;">
				<input type="button" name="btnBuscar" value="Buscar" Onclick="Buscar() ;">
				<input type="button" name="Submit" value="Cancelar" onClick="window.location.replace('MntProcedimientosExamen.php')">			
                            </td>
			</tr>-->
                        
                         <tr>  
                            <td class="StormyWeatherDataTD" colspan="6" align="right">
                                <button type='button' align="center" class='btn btn-primary'   onclick='Guardar(); '><span class='glyphicon glyphicon-floppy-disk'></span> Guardar </button>
                                <button type='button' align="center" class='btn btn-primary' id="abrir" onclick='Buscar(); '><span class='glyphicon glyphicon-search'></span>  Buscar </button>
                                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntProcedimientosExamen.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                            </td>
                         </tr>
                          <?php 
                                     $consulta=$objdatos->ultimoidprocede();
                  while ( $row = @pg_fetch_array( $consulta ) ) {
                  $mismo=$row['id'] ;
                  
                  
                  }
                                       ?> 
                        <input type="hidden" name="mismo" id="mismo" size="50" value="<?php echo $mismo; ?>" />
                    </table>
		</form>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div  id="divFrmModificar" >
            
            </div>
            <div  id="divinicial" >
	
            </div>
	</td>
    </tr>
</table>
	<script type="text/javascript">
	/*	Calendar.setup(
		{
			inputField  : "txtFechainicio",         // el ID texto 
			ifFormat    : "%d/%m/%Y",    // formato de la fecha
			button      : "trigger"       // el ID del boton			  	  
		}
		);
		Calendar.setup(
		{
			inputField  : "txtFechaFin",         // el ID texto 
			ifFormat    : "%d/%m/%Y",    // formato de la fecha
			button      : "trigger2"       // el ID del boton			  	  
		}
		);*/
	</script>
</body>
</html>
