<?php
session_start();
include ("clsReporteDemanda.php");
//creando los objetos de las clases
//if (isset($_SESSION['Correlativo'] $_SESSION['Correlativo'] ) || isset($_SESSION["ADM"])) {
if (isset($_SESSION['Correlativo']) || isset($_SESSION["ADM"])) {
   $nivel = $_SESSION['NIVEL'];
   $corr = $_SESSION['Correlativo'];
   $lugar = $_SESSION['Lugar'];
   $area = $_SESSION['Idarea'];
   $ROOT_PATH = $_SESSION['ROOT_PATH'];
$obj = new clsReporteDemanda();
//echo $lugar;
   ?>
   <html>
      <head>
         <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
         <title>Demanda Insatisfecha</title>
         <script language="JavaScript" type="text/javascript" src="ajax_ReporteDemanda.js"></script>
         <link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
         <link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
         <!--referencias del estilo del calendario-->
         <style>
/*            .ui-datepicker-calendar {
               display: none;
            }*/
         </style>
         <?php include_once $ROOT_PATH . "/public/css.php"; ?>
   <?php include_once $ROOT_PATH . "/public/js.php"; ?>
         <!--llamado al archivo de funciones del calendario-->
         <script language="JavaScript" type="text/javascript">
            function MostrarBusqueda()
            {
               if ((document.getElementById('txtfechainicio').value == "")
                       && (document.getElementById('txtfechafin').value == ""))
               {
                  alert("Seleccione un rango de fechas!");
               }
               else
                  BuscarDatos();
            }

            function BuscarExamen(idarea) {

               if (document.getElementById('cmbArea').value == 0) {
                  alert("Debe Seleccionar una Area");

               }
               else {
                  LlenarComboExamen(idarea);

               }
            }
            $(document).ready(function () {
               $("#cmbExamen").select2({
                  placeholder: "Seleccione examenes",
                  allowClear: true,
                  dropdownAutoWidth: true
               });
               $("#cmbArea").select2({
                  placeholder: "Seleccione una Area",
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
      <body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7">

         <?php
         if ($nivel == 1) {
            
               include_once ('../../../PaginaPrincipal/index_laboratorio2.php');
           
         
         }
         if ($nivel == 2) {
            include_once ('../../../PaginaPrincipal/index_laboratorio22.php');
         }
         if ($nivel == 31) {
            include_once ('../../../PaginaPrincipal/index_laboratorio31.php');
         }
         if ($nivel == 33) {
            include_once ('../../../PaginaPrincipal/index_laboratorio33.php');
         }
         if ($nivel == 5) {
            include_once ('../../../PaginaPrincipal/index_laboratorio52.php');}
        if ($nivel == 6) {
            include_once ('../../../PaginaPrincipal/index_laboratorio62.php');}
        if ($nivel == 7) {
            include_once ('../../../PaginaPrincipal/index_laboratorio72.php'); } 
         $femu=@pg_fetch_array($obj->fechamuestra());
         $feact=$femu[0];
         $toy = date('Y-m');
         $toy2 = date('Y-m-d');
         ?><br>

         <!-- <form name=" cons_tabulador" onSubmit="return false;" action="excelOrd_x_id.php" method="post" target="_blank">-->
         <form name=" cons_demanda">
            <table align="center" width="100%">

               <tr>
                  <td align="center">
               <center>
                     <div style="width: 45%">
                        <div class="panel panel-primary">                        
                           <div class="panel-heading"><h3>Demanda Insatisfecha</h3> </div>                        
                           <div class="panel-body" id="pb-primervez">                            
                              <table class="table table-white no-v-border table-condensed" border="0" style="border:0px; width: 100%" cellpading="2">                              <tr><th width="15%">&Aacute;rea</th>
                                    <td width="85%"  colspan="3"> 
                                       <select id="cmbArea" name="cmbArea"  size="1" onChange="BuscarExamen(this.value)" style="width:100%;" class="height placeholder js-example-basic-single">
                                          <?php
                                          echo '<option></option>';
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
                                    <td colspan="3">
                                       <div id="divExamen">
                                          <select name="cmbExamen" id="cmbExamen" class="height js-example-basic-multiple placeholder" style="width:100%" size="1" multiple="multiple"  > 

                                          </select>
                                       </div>
                                    </td>

                                 </tr>
                                 <tr>
                                    <th width="25%">Rango de Fechas</th>
                                    <td width="30%">   
                                       <input type="text" id="d_fechadesde" name="d_fechadesde" style="width: 90%; text-align: center;  position: fix; top: auto " placeholder="<?php echo $feact; ?>"  class="date form-control height placeholder"  autocomplete="off" />
                                    </td>
                                    <td width="15%"><center>Hasta</center></td>
                                    <td width="30%"><input type="text" id="d_fechahasta" name="d_fechahasta" style="width: 90%; text-align: center; position: fix; top: auto" placeholder="<?php echo $toy2; ?>"  class="date form-control height placeholder"  autocomplete="off" /></td>

                                 </tr>
                                 <tr>
                                    <td colspan="4" align="center"> 
                                       <br/>
                                       <button type="button" align="right" style="text-align: right" class="btn btn-primary" onclick="MostrarReporteDemanda()"><span class='glyphicon glyphicon-list-alt'></span>&nbsp;Generar Reporte </button></td>
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
                     /* include_once("../../../Conexion/ConexionBD.php");
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
                       } */
                     ?>
                                                             </select>
                                                     </span>
                                             </td>
                                             <td class="StormyWeatherFieldCaptionTD">C&oacute;digo del &Aacute;rea</td>
                                             <td class="StormyWeatherDataTD">
                                                     <select id="cmbArea" name="cmbArea" size="1" onChange="BuscarExamen(this.value)"> >
                                                             <option value="0" >--Seleccione un &Aacute;rea--</option>
                     <?php
//                     include ("clsReporteTabuladores.php");
//                     $obj = new clsReporteTabuladores;
//                     $consulta = $obj->consultaractivas($lugar);
//                     while ($row = mysql_fetch_array($consulta)) {
//                        echo "<option value='" . $row['IdArea'] . "'>" . $row['NombreArea'] . "</option>";
//                     }
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
                                                 <input type="button" id="btnClear" value="Nueva Busqueda" class="MailboxButton" onClick="window.location.replace('ReporteTabuladores.php')">	-->		
               </center>    </td>
                                     </tr>
                             </table>
                     </form>
      <center>
 <div style="width: 65%">
                     <div id="divBusqueda">
                        <?php
//                       $var="1|2,3|1";
//                       echo $var.'<br>';
//                       $var1=explode(",", $var);
//                        echo $num_tags = count($var1);
//                        for ($i=0; $i<$num_tags; $i++){
//                            $fin=explode("|", $var1[$i]);
//                            echo 'i: '.$i.'  --Var: '.$var1[$i].'<br>';
//                            echo 'fin1: '.$fin[0].' fin2: '.$fin[1].'<br>';
//                        }
//                       
                        ?>
<!--             
          
               <div class="panel panel-default">
               <div class="panel-body">

                    <div class="container">
                       <h3>Reportes de demanda insatisfecha</h3>
                       <div class="panel-group" id="accordion">
                          <div class="panel panel-primary">

                             <div class="panel-heading" >
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" style="color: white; text-align: left">
                                   <h4 class="panel-title">
                                      Total por tipo de demanda insatisfecha
                                   </h4></a>
                             </div>
                             <div id="collapse1" class="panel-collapse collapse in">
                                <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                   sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                   quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
                             </div>
                          </div>
                          <div class="panel panel-primary">
                             <div class="panel-heading">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" style="color: white; text-align: left">
                                <h4 class="panel-title">
                                  Collapsible Group 2
                                </h4></a>
                             </div>
                             <div id="collapse2" class="panel-collapse collapse">
                                <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                   sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                   quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
                             </div>
                          </div>
                          <div class="panel panel-primary">
                             <div class="panel-heading">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" style="color: white; text-align: left">
                                <h4 class="panel-title">
                                   Collapsible Group 3
                                </h4></a>
                             </div>
                             <div id="collapse3" class="panel-collapse collapse">
                                <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                   sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                   quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
                             </div>
                          </div>
                       </div> 
                    </div>                 
                 
               </div>
             </div>         
   
   -->



                        
                        
                     
                     
                     </div><!--Fin de divBusqueda-->
 </div></center>
    
    
                     </body>
                     </html>
   <?php
} else {
   ?>
                     <script language="javascript">
                        window.location = "../../../login.php";
                     </script>
<?php
}?>