<?php
include('clsLab_Empleados.php');
include('../Lab_Areas/clsLab_Areas.php');
@session_start();
$obj = new clsLab_Empleados;
$nivel = $_SESSION['NIVEL'];
$corr  = $_SESSION['Correlativo'];
$lugar = $_SESSION['Lugar'];
$area  = $_SESSION['Idarea'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
?>
<html>
    <head>

        <meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
        <title>Mantenimiento de Empleados de Laboratorio</title>
        <script language="JavaScript" type="text/javascript" src="ajax_Lab_Empleados.js"></script>
        <link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
        <link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
        <?php include_once $ROOT_PATH.'/public/css.php';?>
        <?php include_once $ROOT_PATH.'/public/js.php';?>
        <script language="JavaScript" >
            
            function ValidarCampos()
{

    var resp = true;
    
    if (document.getElementById('txtidempleado').value == "")
    {
        resp = false;
    }
    if (document.getElementById('txtnombre').value == "")
    {
        resp = false;
    }
    if (document.getElementById('cmbArea').value == "0")
    {
        resp = false;
    }
    if (document.getElementById('cmbCargo').value == "0")
    {
        resp = false;
    }
    if (document.getElementById('txtlogin').value == "")
    {
        resp = false;
    }
    if (document.getElementById('cmbModalidad').value == "0")
    {
        resp = false;
    }
     if (document.getElementById('txtapellido').value == "")
    {
        resp = false;
    }

    return resp;
}
            
            function Guardar() {
               
    if (ValidarCampos()) {
        
                IngresarRegistro();
            
         }else 
        {
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
                            $("#dialog").dialog("open");
                             });
            
         
        }
               
            }
            function AsignarIdExamen(idArea)
            {
                SolicitarUltimoCodigo(idArea);
            }
            
            function Modificar()
            {
                enviarDatos();
            }
            function Nuevo()
            {
                MostrarFormularioNuevo();
            }
            function Buscar()
            {
                if(   (document.getElementById('txtidempleado').value == "")
                      && (document.getElementById('txtnombre').value == "")
                      && (document.getElementById('cmbArea').value == "0")
                      && (document.getElementById('cmbCargo').value == "0")
                      && (document.getElementById('txtlogin').value == "")
                      && (document.getElementById('cmbModalidad').value == "0")
                      && (document.getElementById('txtapellido').value == "")
                        ){
                    
                    $(function ()   {
                                  $("#dialog1").dialog({
                                    autoOpen: false,
                                    modal: true,
                                    buttons: {      
                                                "Cerrar": function () 
                                                            {
                                                                $(this).dialog("close");
                                                            }
                                            }
                                        });
                            $("#buscar")
                            $("#dialog1").dialog("open");
                             });
                    
                  //   alert("ingrese al menos un parametro de busqueda");
                }else {
                  BuscarDatos();  
                }
                
                
                
            }
            function Cancelar()
            {
                LimpiarCampos();
                show_event(1);
            }
        </script>
    </head>

    <body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="show_event(1);">
        <div id="dialog" style='display:none;' title="¡Aviso!">
    <p> <cente>¡Complete los datos a Ingresar!!</cente></p>
</div>
         <div id="dialog1" style='display:none;' title="¡Aviso!">
    <p> <cente>¡Ingrese Al Menos Un Parámetro De Búsqueda !!</cente></p>
</div>
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
            include_once ('../../../PaginaPrincipal/index_laboratorio52.php');
        }
         if ($nivel == 6) {
            include_once ('../../../PaginaPrincipal/index_laboratorio62.php');
        }
        if ($nivel == 7) {
            include_once ('../../../PaginaPrincipal/index_laboratorio72.php');
        }
        ?><br>

        <table align="center" width="80%">
            <tr>
                <td>
                    <div id="divOculto">
                        <input type="hidden" name="txtoculto" id="txtoculto" value="NNN"/>
                    </div>
                    <div  id="divFrmNuevo" >
                        <form name="frmnuevo" >
                            <table width="57%" border="0" align="center" class="StormyWeatherFormTABLE">
                                <tr>
                                    <td colspan="2" align="center" class="CobaltFieldCaptionTD"><h3><strong>Mantenimiento de Empleados de Laboratorio Cl&iacute;nico</h3></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td  class="StormyWeatherFieldCaptionTD" width="40%">C&oacute;digo del Empleado</td>
                                    <td width="60%" class="StormyWeatherDataTD"> <div id="divCodigo"><input type="text" id="txtidempleado"  name="txtidempleado" placeholder="Código Empleado " disabled="disabled" style="width:60%"  class="form-control height placeholder" /></div></td>
                                </tr>
                                <tr>
                                    <td class="StormyWeatherFieldCaptionTD"> &Aacute;rea</td>
                                    <td class="StormyWeatherDataTD">
                                        <select id="cmbArea" name="cmbArea" size="1" onclick="AsignarCodigoEmpleado();"  style="width:60%" class="form-control height" >
                                            <option value="0" >Seleccione un &Aacute;rea</option>
                                            <?php
                                            $objeareas = new clsLab_Areas;
                                            $consulta = $objeareas->consultarareas($lugar);
                                            while ($row = pg_fetch_array($consulta)) {
                                                echo "<option value='" . $row['idarea'] . "'>" . $row['nombrearea'] . "</option>";
                                            }
                                            ?>		  
                                        </select>		 
                                    </td>
                                </tr>
                                <tr>
                                    <td class="StormyWeatherFieldCaptionTD"> Modalidad de Contrato</td>
                                    <td class="StormyWeatherDataTD">
                                        <select id="cmbModalidad" name="cmbModalidad" size="1" style="width:60%" class="form-control height">
                                            <option value="0" >Seleccione una modalidad</option>
                                            <?php
                                           
                                            $consulta = $obj->consultarModalidad($lugar);
                                            while ($row = pg_fetch_array($consulta)) {
                                                echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
                                            }
                                            ?>        
                                        </select>        
                                    </td>
                                </tr>
                                 <tr>
                                    <td class="StormyWeatherFieldCaptionTD"> Fondo de Contratación</td>
                                    <td class="StormyWeatherDataTD">
                                        <select id="cmbPago" name="cmbPago" size="1" style="width:60%" class="form-control height">
                                            <option value="0" >Seleccione un Fondo de Contratación</option>
                                            <?php
                                           
                                            $conempleador = $obj->consultar_empleador($lugar);
                                            while ($row = pg_fetch_array($conempleador)) {
                                                echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
                                            }
                                            ?>    
                                        </select>        
                                    </td>
                                </tr>
                                <tr>
                                    <td class="StormyWeatherFieldCaptionTD">Nombre del Empleado</td>
                                    <td class="StormyWeatherDataTD"><input type="text" id="txtnombre" name="txtnombre" size="40"  style="width:60%"  placeholder="Ingrese Nombre" class="form-control height placeholder"/></td>
                                </tr>
                                <tr>
                                    <td class="StormyWeatherFieldCaptionTD">Apellido del Empleado</td>
                                    <td class="StormyWeatherDataTD"><input type="text" id="txtapellido" name="txtapellido" size="40"  style="width:60%"  placeholder="Ingrese Apellido" class="form-control height placeholder"/></td>
                                </tr>
                                
                                <tr>
                                    <td class="StormyWeatherFieldCaptionTD">Cargo</td>
                                    <td class="StormyWeatherDataTD">
                                        <select id="cmbCargo" name="cmbCargo" size="1" style="width:60%" class="form-control height">
                                            <option value="0">Seleccione Cargo</option>
                                            <?php
                                            $objeareas = new clsLab_Empleados;
                                            $consulta = $objeareas->LeerCargos();
                                            while ($row = pg_fetch_array($consulta)) {
                                                echo "<option value='" . $row['idcargoempleado'] . "'>" . $row['cargo'] . "</option>";
                                            }
                                            ?>	
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <TD class="StormyWeatherFieldCaptionTD">Usuario</TD>
                                    <td class="StormyWeatherDataTD"><input type="text" id="txtlogin" name="txtlogin" size="40" style="width:60%"  placeholder="Ingrese Usuario" class="form-control height placeholder" /></td>
                                </tr>
                               <!-- <tr>
                                    <td colspan="2" align="right" class="StormyWeatherDataTD">
                                        <input type="button" name="btnGuardar" value="Guardar" onClick="Guardar();">
                                        <input type="button" name="btnBuscar" value="Buscar" onClick="Buscar();">
                                        <input type="button" name="btnCancelar" value="Cancelar" onClick="Cancelar();">			
                                    </td>
                                </tr>-->
                                
                                <tr>  
                            <td class="StormyWeatherDataTD" colspan="6" align="right">
                                <button type='button' align="center" class='btn btn-primary' id="abrir"  onclick='Guardar(); '><span class='glyphicon glyphicon-floppy-disk'></span> Guardar </button>
                                <button type='button' align="center" class='btn btn-primary' id="buscar" onclick='Buscar(); '><span class='glyphicon glyphicon-search'></span>  Buscar </button>
                                <button type='button' align="center" class='btn btn-primary'  onClick="Cancelar();"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                            </td>
                         </tr>
                            </table>
                        </form>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div  id="divresultado" >

                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div  id="divFrmModificar" >

                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div  id="divinicial" >

                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>
