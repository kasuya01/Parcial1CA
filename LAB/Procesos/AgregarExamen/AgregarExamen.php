<?php session_start();
if(isset($_SESSION['Correlativo'])){
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$nivel=$_SESSION['NIVEL'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
include_once("clsAgregarExamen.php"); 
//consulta los datos por su id
$obj = new clsAgregarExamen;
$consulta=$obj->DatosEstablecimiento($lugar);
$row = pg_fetch_array($consulta);
//valores de las consultas
$tipo=$row[0];
$nombrEstab=$row[1];
$nomtipo=$row[2];

 ?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
<title>Agregar Examen a Solicitud</title>

<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<script language="JavaScript" type="text/javascript" src="ajax_AgregarExamen.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>
<script  type="text/javascript">
        $(document).ready(function() {
            $("#cmbEstablecimiento").select2({
                allowClear: true,
                dropdownAutoWidth: true
            });
        });
</script>
<script language="JavaScript" type="text/javascript">
function MostrarBusqueda()
{
	/*if ((document.getElementById('txtexpediente').value == "") 
            &&(document.getElementById('PrimerNombre').value == "")
            &&(document.getElementById('SegundoNombre').value == "")
            &&(document.getElementById('PrimerApellido').value == "")
            &&(document.getElementById('SegundoApellido').value == "")
            &&(document.getElementById('txtfecharecep').value == 0)
            && (document.getElementById('cmbEstablecimiento').value == 0) 
            && (document.getElementById('CmbServicio').value == 0) 
            && (document.getElementById('cmbSubServ').value == 0) ) {
	   alert("Ingrese un parmetro de busqueda");
	   document.getElementById('txtexpediente').focus();
	   }
    	/* else{	
             
             
	     BuscarDatos(1);
         }*/
        
       // else {*/
                        jQuery('#divBusqueda').empty();
                        jQuery('#divBusqueda').append('<center><img id="wait" src="<?php echo $base_url; ?>/Laboratorio/public/images/spin.gif" alt="wait" width="24" height="24"><div id="search-message" style="color:#888888;font-weight: bold;">Buscando...</div></center>');
                        
                        setTimeout(function() {
                            jQuery('#divBusqueda').empty();
                             BuscarDatos(1);
                        }, 500);
                    //}
        
   //   alert  (document.getElementById('txtfecharecep').value ==0)
        
        
}

function ImprimirExamenes()
{
	imprimiretiquetas();
}

function BuscarEstablecimiento(idtipoesta){

	LlenarComboEstablecimiento(idtipoesta);
}


function BuscarServicio(IdServicio){
	
		LlenarComboServicio(IdServicio);
	
	
}


function BuscarExamen(idarea){

	if (document.getElementById('cmbArea').value == 0){
		  alert("Debe Seleccionar una Area");
 
	} 
	else{
		LlenarComboExamen(idarea);
	
	}
}
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
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad=" document.getElementById('txtexpediente').focus() ">

<?php 

if ($nivel==1){
	include_once ('../../../PaginaPrincipal/index_laboratorio2.php');}
if ($nivel==2){
	include_once ('../../../PaginaPrincipal/index_laboratorio22.php');}
if ($nivel==31){
	include_once ('../../../PaginaPrincipal/index_laboratorio31.php');}
if ($nivel==33){
	include_once ('../../../PaginaPrincipal/index_laboratorio33.php');}
if ($nivel==4){
        include_once ('../../../PaginaPrincipal/index_laboratorio42.php');}
if ($nivel == 5) {
        include_once ('../../../PaginaPrincipal/index_laboratorio52.php');}
if ($nivel == 6) {
        include_once ('../../../PaginaPrincipal/index_laboratorio62.php');}
if ($nivel == 7) {
        include_once ('../../../PaginaPrincipal/index_laboratorio72.php'); }        
?><br> <div  id="divInicial" >
<table align="center" width="100%">
    <tr>
	<td>
           
		<form>
                    <p>&nbsp;</p>
                    <table align="center"  class="StormyWeatherFormTABLE" width="67%">
                        <tr>
                            <td colspan="5" align="center" class="CobaltFieldCaptionTD">
				    <h3><strong>Agregar Examen a Solicitud</strong></h3>
                            </td>
			</tr>
			<tr>		
                            <td class="StormyWeatherFieldCaptionTD" width="20%">Tipo Establecimiento</td>
                            <td class="StormyWeatherDataTD" width="35%">
                                <select name="cmbTipoEstab" id="cmbTipoEstab" style="width:443px" onChange="BuscarEstablecimiento(this.value)" class="form-control height">
                                    <option value="0">Seleccione un Tipo de Establecimiento</option>
                                    <?php
                                        $db = new ConexionBD;
                                        if($db->conectar()==true){
                                            $consulta  = "SELECT id,nombre FROM ctl_tipo_establecimiento ORDER BY nombre";
                                            $resultado = pg_query($consulta);
                                            //por cada registro encontrado en la tabla me genera un <option>
                                            while ($rows = pg_fetch_array($resultado)){
						echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>'; 
                                            }
						//echo '<option value="'. $tipo .'" selected="selected">' .htmlentities($nomtipo). '</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                            <td class="StormyWeatherFieldCaptionTD"  width="20%">Establecimiento</td>
                            <td class="StormyWeatherDataTD" width="35%">
				<div id="divEstablecimiento">
                                    <select name="cmbEstablecimiento" id="cmbEstablecimiento"  style="width:500px" class="js-example-basic-single">
					  	<option value="0" >Seleccione un Establecimiento</option>
                                        <?php 
                                            echo '<option value="'. $lugar .'" selected="selected">' .htmlentities($nombrEstab). '</option>';
                                                include_once("../../../Conexion/ConexionBD.php");
                                                $con = new ConexionBD;
                                                if($con->conectar()==true){			  
                                                    //$consulta  = "SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento WHERE IdTipoEstablecimiento='$tipo' ORDER BY Nombre";
                                                    $consulta  = "SELECT id,nombre FROM ctl_establecimiento ORDER BY nombre";
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
                            <td class="StormyWeatherFieldCaptionTD">Procedencia</td>
                            <td class="StormyWeatherDataTD">
                                <select name="CmbServicio" id="CmbServicio" style="width:443px" onChange="BuscarServicio(this.value)"  class="form-control height">
                                   <option value="0" selected="selected" align="center"> Seleccione Procedencia </option>
                                        <?php
                                            $db = new ConexionBD;
                                            if($db->conectar()==true){
                                               $consulta = " SELECT mnt_area_mod_estab.id as codigo ,CASE WHEN id_servicio_externo_estab IS NOT NULL THEN mnt_servicio_externo.abreviatura ||'-->'  || ctl_area_atencion.nombre                               ELSE   ctl_modalidad.nombre ||'-->' || ctl_area_atencion.nombre
                                                       END
                                                       FROM mnt_area_mod_estab
                                                       INNER JOIN  ctl_area_atencion  on (ctl_area_atencion.id = mnt_area_mod_estab.id_area_atencion AND (ctl_area_atencion.id_tipo_atencion=1 OR ctl_area_atencion.id_tipo_atencion=4))                 INNER JOIN  mnt_modalidad_establecimiento ON mnt_modalidad_establecimiento.id=mnt_area_mod_estab.id_modalidad_estab
                                                       INNER JOIN ctl_modalidad ON ctl_modalidad.id = mnt_modalidad_establecimiento.id_modalidad
                                                       LEFT JOIN mnt_servicio_externo_establecimiento ON (mnt_servicio_externo_establecimiento.id = mnt_area_mod_estab.id_servicio_externo_estab)
                                                       LEFT JOIN mnt_servicio_externo ON (mnt_servicio_externo.id = mnt_servicio_externo_establecimiento.id_servicio_externo)
                                                       ORDER by ctl_modalidad.nombre,mnt_servicio_externo.nombre,ctl_area_atencion.nombre        ";
                                                      /* "SELECT mnt_area_mod_estab.id as codigo ,CASE WHEN id_servicio_externo_estab IS NOT NULL THEN mnt_servicio_externo.abreviatura ||'-->'  || ctl_area_atencion.nombre
                                                            ELSE ctl_modalidad.nombre ||'-->' || ctl_area_atencion.nombre 
                                                            END
                                                            FROM mnt_area_mod_estab
                                                            INNER JOIN  ctl_area_atencion  on (ctl_area_atencion.id = mnt_area_mod_estab.id_area_atencion AND ctl_area_atencion.id_tipo_atencion=1)
                                                            --LEFT JOIN mnt_aten_area_mod_estab ON (ctl_area_atencion.id = mnt_aten_area_mod_estab.id_atencion) 
                                                            INNER JOIN  mnt_modalidad_establecimiento ON mnt_modalidad_establecimiento.id=mnt_area_mod_estab.id_modalidad_estab
                                                            INNER JOIN ctl_modalidad ON ctl_modalidad.id = mnt_modalidad_establecimiento.id_modalidad
                                                            LEFT JOIN mnt_servicio_externo_establecimiento ON (mnt_servicio_externo_establecimiento.id = mnt_area_mod_estab.id_servicio_externo_estab) 
                                                            LEFT JOIN mnt_servicio_externo ON (mnt_servicio_externo.id = mnt_servicio_externo_establecimiento.id_servicio_externo) 
                                                            ORDER by ctl_modalidad.nombre,mnt_servicio_externo.nombre,ctl_area_atencion.nombre ";*/
                                                            
                                                $resultado = pg_query($consulta);
                                                //por cada registro encontrado en la tabla me genera un <option>
                                                    while ($rows = pg_fetch_array($resultado)){
                                                        echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>'; 
                                                    }
                                                }
                                        ?>
                                </select>
                            </td>
                            <td class="StormyWeatherFieldCaptionTD">Servicio</td>
                            <td class="StormyWeatherDataTD">
                                <div id="divsubserv">
                                    <select name="cmbSubServ" id="cmbSubServ" style="width:500px" class="form-control height">
                                        <option value="0" selected="selected"> Seleccione un Servicio </option>
                                    </select>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="StormyWeatherFieldCaptionTD"  >Expediente</td>
                            <td  class="StormyWeatherDataTD"  ><input type="text" size="28" name="txtexpediente" id="txtexpediente" class="form-control height"  placeholder="Ingrese Expediente"/>
                            </td>
                            <td class="StormyWeatherFieldCaptionTD" >Fecha Recepi&oacute;n</td>
                            <td  class="StormyWeatherDataTD"  ><input type="text" size="28" name="txtfecharecep" id="txtfecharecep"  class="date form-control height placeholder"  placeholder="aaaa-mm-dd" />
                            </td>
                        </tr>
                        <tr>
                            <td  class="StormyWeatherFieldCaptionTD" align="left"><strong>Primer Nombre&nbsp;</strong>   </td> 
                            <td class="StormyWeatherDataTD" >
                                <input class="form-control height" maxlength="35" size="28" name="PrimerNombre" id="PrimerNombre"  placeholder="Ingrese Primer Nombre"></td> 
                            <td class="StormyWeatherFieldCaptionTD" align="left"><strong>Segundo Nombre</strong>   </td> 
                            <td class="StormyWeatherDataTD">
                               <input  maxlength="35" size="28" name="SegundoNombre" id="SegundoNombre" class="form-control height" placeholder="Ingrese Segundo Nombre" ></td> 
                        </tr>
                        <tr>
                            <td class="StormyWeatherFieldCaptionTD" align="left"><strong>Primer Apellido</strong></td> 
                            <td class="StormyWeatherDataTD">
                                <input class="form-control height" maxlength="35" size="28" name="PrimerApellido" id="PrimerApellido" placeholder="Ingrese Primer Apellido" ></td> 
                            <td  class="StormyWeatherFieldCaptionTD" align="left"><strong>Segundo Apellido</strong></td> 
                            <td class="StormyWeatherDataTD" >
                                <input class="form-control height" maxlength="35" size="28" name="SegundoApellido" id="SegundoApellido" placeholder="Ingrese Segundo Apellido" ></td>
                        </tr>
                        <tr>
                            <td class="StormyWeatherDataTD" colspan="4" align="right">
                                <button type='button' align="center" class='btn btn-primary' id='buscarsolicitud' onclick='MostrarBusqueda(); '><span class='glyphicon glyphicon-search'></span> Buscar Solicitudes</button>
                                <button type='button' align="center" class='btn btn-primary' id='nuevabusqueda' onclick="window.location.replace('AgregarExamen.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                            </td>
                        </tr>
                    </table>
                </form>
           
        </TD>
    </TR> </table>
</div>   
    <div id="divBusqueda">

    </div>
    <div id="divSolicitud">

    </div>
</body>

<?php
}else{?>
<script language="javascript">
	window.location="../../../login.php";
</script>
<?php }?>