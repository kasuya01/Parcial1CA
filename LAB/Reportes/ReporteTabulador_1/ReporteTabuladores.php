<?php 
include_once("clsReporteTabuladores.php");
@session_start();
$objeto = new clsReporteTabuladores(); 
//creando los objetos de las clases
//$objeto = new clsReporteTabuladores();
 if(isset($_SESSION['Correlativo']) || isset($_SESSION["ADM"])){
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
$ROOT_PATH = $_SESSION['ROOT_PATH'];
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
         $("#cmbExamen").select2({
           placeholder: "Todos los examenes",
           allowClear: true,
           dropdownAutoWidth: true
        });
         $("#cmbArea").select2({
           placeholder: "Todas las secciones",
           allowClear: true,
           dropdownAutoWidth: true
        });
         $("#idmntareamodestab").select2({
           placeholder: "Todas las instituciones",
           allowClear: true,
           dropdownAutoWidth: true
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
	}
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
$toy=date('Y-m');
$toy2=date('Y-m-d');        
?><br>

<!-- <form name=" cons_tabulador" onSubmit="return false;" action="excelOrd_x_id.php" method="post" target="_blank">-->
<form name=" cons_tabulador" onSubmit="return enviaxfis()" action="excelOrd_x_id2.php" method="post" target="_blank">
<table align="center" width="100%">

<tr>
<td align="center">
<center>
   <div  style="width: 45%">
      <div class="panel panel-primary">                        
         <div class="panel-heading"><h3>Tabulador</h3> </div>                        
          <div class="panel-body" id="pb-primervez">                            
              <table class="table table-white no-v-border table-condensed" border="0" style="border:0px; width: 100%" >
                  <tr><th width="15%">Secci&oacute;n o grupo</th>
                  <td width="85%"> 
                     <select id="cmbArea" name="cmbArea"  size="1" onChange="BuscarExamen(this.value)" style="width:100%;" class="height placeholder js-example-basic-single">
                        <?php
                        echo '<option></option>';
                        //include('../../../../Laboratorio/LAB/Mantenimientos/Lab_Areas/clsLab_Areas.php');
                        $consulta = $objeto->fillGruposPruebas();
                        while ($row = pg_fetch_array($consulta)) {
                            echo "<option value='" . $row['id'] . "'>" . htmlentities($row['nombre']) . "</option>";
                        }
                        ?>		  
                     </select> 
                  </td>
                   </tr>
                   <tr>
                      <th>Examen</th>
                      <td>
                        <div id="divExamen">
                           <select name="cmbExamen" id="cmbExamen" class="height js-example-basic-multiple placeholder" style="width:100%" size="1" multiple="multiple"  > 
                                
                           </select>
                       </div>
                      </td>
                      
                   </tr>
                   <tr>
                      <th>Institución</th>
                      <td>
                         <?php
                          $insti=$objeto->buscarinstitucion($lugar);
                        echo '<select id="idmntareamodestab" name="idmntareamodestab" style="width:100%;" class="height placeholder js-example-basic-single" onchange="carganombre()">';
                         echo '<option></option>';
                         while ($rowi=  pg_fetch_array($insti)){
                            echo '<option value='.$rowi[0].'>'.$rowi[1].'</option>';
                         }
                        
                         
                         echo '</select> '; 
                         echo '<input id="nombareamodestab" value="nombareamodestab" type="hidden" />';
                         ?>
                      </td>
                   </tr>
                   <tr>
                       <th>Año-Mes</th>
                                                          <td>   
                                                             <input type="text" id="d_fecha" name="d_fecha" style="width: 25%; text-align: center; position: inherit; top: auto" placeholder="<?php echo $toy; ?>"  class="datepicker form-control height placeholder"  autocomplete="off" />
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
</center></td></tr></table>
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