<?php session_start();

include("../Lab_CodigosEstandar/clsLab_CodigosEstandar.php");
//$objdatos=new clsLab_CodigosxExamen;
//$obje=new clsLab_CodigosEstandar;
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
<title>Mantenimiento de Códigos de pruebas por Examen</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_CodigosxExamen.js"></script>
<!--referencias del estilo del calendario-->
<link rel="stylesheet" type="text/css" media="all" href="../../../calendarstructure/skins/aqua/theme.css" title="Aqua" />
<link rel="alternate stylesheet" type="text/css" media="all" href="../../../calendarstructure/calendar-blue.css" title="blue" />
<!--llamado al archivo de funciones del calendario-->
<script type="text/javascript" src="../../../calendarstructure/calendar.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-es.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-setup.js"></script>

<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<script language="JavaScript" >
function Guardar(){
    alert("GUardar");
   if ((document.getElementById('cmbgrupo').value == 0) && (document.getElementById('cmbEstandar').value == 0) && (document.getElementById('txtFechainicio').value == ""))	
	alert("Ingrese los datos");
   else
   	IngresarRegistro();
}

function LlenarComboExamen(idArea)
{
  LlenarExamenes(idArea);
}

function Buscar(){
 if ((document.getElementById('cmbArea').value == 0) && (document.getElementById('cmbExamen').value == 0) 
          && (document.getElementById('cmbResultado').value == 0))	
	alert("Ingrese los datos a buscar");
  else
   BuscarDatos();
}

function Actualizar(){
	enviarDatos();
}

function Nuevo(){
	MostraFormularioNuevo();
}

function Cancelar()
{
    LimpiarCampos();    
    show_event(1);
}

</script>
<style type="text/css">
</style>
</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="show_event(1);">
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
        <div id="divFrmNuevo" >
            <form name="frmnuevo" action="" onSubmit="IngresarRegistro(); return false">
                <table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
                   <tr>
                        <td colspan="3" align="center" class="CobaltFieldCaptionTD"><h3><strong>Mantenimiento de C&oacute;digos de Resultados porEst&aacute;ndares de Laboratorio</h3></strong>
                        </td>
                   </tr>
                   <tr>
                        <td class="StormyWeatherFieldCaptionTD">Grupo</td>
                        <td class="StormyWeatherDataTD">
                             <select id="cmbgrupo" name="cmbgrupo" size="1" onChange="BuscarCodigosEstandarxGrupo(this.value);">
                                <option value="0" >--Seleccione un grupo--</option>
                                    <?php

                                    $obj=new clsLab_CodigosEstandar;
                                    $consulta= $obj->Leer_grupos();
                                    while($row = mysql_fetch_array($consulta)){
                                       echo "<option value='" . $row['idgrupo']. "'>".$row['idgrupo']." - ".htmlentities($row['nombregrupo']) . "</option>";
                                    }

                                    ?>		  
                            </select>		 
                        </td>
                    </tr>
                    <tr>
                        <td class="StormyWeatherFieldCaptionTD" >C&oacute;digo del Est&aacute;ndar</td>
                        <td class="StormyWeatherDataTD">
                            <div id="divEstandar">
                                <select id="cmbEstandar" name="cmbEstandar" size="1">
                                    <option value="0">--Seleccione un Código de prueba--</option>
                                 </select>
                            </div>
                         </td>
                    </tr>
                    <tr>
                            <td class="StormyWeatherFieldCaptionTD">Código Resultado</td>
                            <td class="StormyWeatherDataTD">
                                <select id="cmbgrupo" name="cmbResultado" size="1">
                                    <option value="0" >--Seleccione un grupo--</option>
                                     <?php
                                     
                                          $con = new ConexionBD;
                                     //usamos el metodo conectar para realizar la conexion
                                        if($con->conectar()==true){

                                            $query="SELECT IdResultado, Resultado
                                                    FROM lab_codigosresultados";

                                            $conResul= @mysql_query($query);
                                             while($row = mysql_fetch_array($conResul)){
                                           echo "<option value='" . $row['IdResultado']. "'>".$row['IdResultado']." - ".htmlentities($row['Resultado']) . "</option>";
                                        }

                                        }
                                      
                                    ?>	
                                </select>		 
                            </td>
                    </tr>
                    <tr>
                            <td class="StormyWeatherDataTD" colspan="2" align="right">
                                <input type="button" name="Submit" value="Guardar" onClick="IngresarRegistro()">
                                <input type="button" name="Submit" value="Buscar" onClick="BuscarCodigo();">
                                <input type="button" name="Submit" value="Cancelar" onClick="Cancelar();">
                            </td>
                    </tr>
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
		
			

</body>
</html>
