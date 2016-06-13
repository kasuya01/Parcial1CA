<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
//include_once("clsSolicitudesPorArea.php");

?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">-->
<link rel="stylesheet" type="text/css" href="../../Webstyle/Themes/Cobalt/Style.css">
<style type="text/css">
<!--
@media print{
#boton{display:none;}
}

.Estilo5 {font-family: Helvetica; font-size: 7pt}
.Estilo6 {font-family: Helvetica; font-size: 8pt}
.Estilo7 {font-family: Helvetica; font-size: 9pt}
-->
</style>
<title>Reporte Citas Programadas</title>
<script language="JavaScript" type="text/javascript" src="ajax_CitasPorPaciente.js"></script>
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>
<script language="JavaScript" >
function RecogeValor()
{

var vtmp=location.search;
var vtmp2 = vtmp.substring(1,vtmp.length);
var query = unescape(top.location.search.substring(1));
var getVars = query.split(/&/);
for ( i = 0; i < getVars.length; i++)
		{
			if ( getVars[i].substr(0,5) == 'var1=' )//loops through this array and extract each name and value
                        idexpediente = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var2=' )
						fecha = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var3=' )
						primernombre = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var4=' )
						segundonombre = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var5=' )
						primerapellido = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var6=' )
						segundoapellido = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var7=' )
						IdEstab= getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var8=' )
						IdServ= getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var9=' )
						IdSubServ= getVars[i].substr(5);
		}

// CargarDatosFormulario(especialidad,fechainicio,fechafin,medico);
}

function otroreport(){
	window.location.replace('SolicitudPorServicioPeriodo.php');
}



function imprimir(){
document.getElementById('btnImprimir').style.visibility="hidden";
window.print();
document.getElementById('btnImprimir').style.visibility="visible";
}
</script>
</head>

<body onLoad="RecogeValor();">

<?php 

include_once("clsCitasPorPaciente.php");
//$especialidad=$_GET['var1'];
// echo $fechacita=$_GET['var2'];

$query_search="";
//$fechafin=$_GET['var3'];
//$medico=$_GET['var4'];
$objdatos = new clsCitasPorPaciente;
$cond2="";
$cond1="";
        $query="";
        $query2="";
        $where_with="";
        $cond0="and";
        
         $est_local = $objdatos-> DatosEstablecimiento($lugar);
                $row_local = pg_fetch_array( $est_local);
                $nombre_lugar = $row_local[nombre];
               // echo $nombre_lugar;
 
        if (!empty($_GET['var7'])) {
           if ($_GET['var7']<>$lugar){
               $cond1 .=$cond0. "  t02.id_establecimiento_externo = " . $_GET['var7'] . " ";
               $cond2 .=$cond0. "  t02.id_establecimiento_externo = " . $_GET['var7'] . " ";
           }
         }
        
        
        if (!empty($_GET['var9'])) {
            $cond1 .= $cond0." t10.id = " . $_GET['var9'] . " ";
            $cond2 .= $cond0." t10.id = " . $_GET['var9'] . " ";
        }   
        
        if (!empty($_GET['var8'])) {
            $cond1 .=$cond0 ."  t13.id  = " . $_GET['var8'] . "     ";
            $cond2 .=$cond0 ."  t13.id  = " . $_GET['var8'] . "     ";
            $where_with = "id_area_atencion = ".$_GET['var8']."  AND ";
        }
       
        if (!empty($_GET['var1'])) {
          $idexpediente="'".$idexpediente."'";
            
            $cond1 .= "and t06.numero = '".$_GET['var1'] ."'    ";
            $cond2 .= "and t06.numero = '".$_GET['var1'] ."'   ";
        }

        if (!empty($_GET['var2'])) {
             $cond1 .= " and t15.fecha = '".$_GET['var2']."'       ";
             $cond2 .= " and t15.fecha = '".$_GET['var2']."'       ";
        }

        if (!empty($_GET['var3'])) {
          
            $cond1 .= " and t07.primer_nombre  ILIKE  '".$_GET['var3']."%'      ";
            $cond2 .= " and  t07.primer_nombre ILIKE  '".$_GET['var3']."%'      ";
        }

        if (!empty($_GET['var4'])) {
             $cond1 .= " and t07.segundo_nombre  ILIKE '". $_GET['var4'] ."%'       ";
             $cond2 .= " and t07.segundo_nombre  ILIKE '". $_GET['var4'] ."%'       ";
        }

        if (!empty($_GET['var5'])) {
            $cond1 .= " and  t07.primer_apellido ILIKE '".$_GET['var5']."%'         ";
            $cond2 .="  and  t07.primer_apellido ILIKE '".$_GET['var5']."%'         ";
        }

        if (!empty($_GET['var6'])) {
            $cond1 .=" and t07.segundo_apellido ILIKE '".$_GET['var6']."%'       ";
            $cond2 .=" and t07.segundo_apellido ILIKE '".$_GET['var6']."%'       ";
        }

        

        if ((empty($_POST['idexpediente'])) AND ( empty($_POST['idarea'])) AND ( empty($_POST['fecha']))
                AND ( empty($_POST['IdEstab'])) AND ( empty($_POST['IdServ'])) AND ( empty($_POST['IdSubServ']))
                AND ( empty($_POST['primernombre'])) AND ( empty($_POST['segundonombre'])) AND ( empty($_POST['primerapellido']))
                AND ( empty($_POST['segundoapellido'])) AND ( empty($_POST['idexamen'])) AND ( empty($_POST['TipoSolic']))) {
            $ban = 1;
        }
        
        if ($ban == 0) {

            $cond1 = substr($cond1, 0, strlen($query) - 3);
            $cond2 = substr($cond2, 0, strlen($query) - 3);
            
          //  echo $query1;
           // $query_search = 
            //echo $cond1;
            //echo $cond2;
        }     
       // echo $cond2;
         $query="WITH tbl_servicio AS ( SELECT t02.id, 
                CASE WHEN t02.nombre_ambiente IS NOT NULL THEN 
                    CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura  ||'   -   ' || t02.nombre_ambiente 
                          
                    END 
                    ELSE 
                            CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura  ||'   -   ' ||  t01.nombre 
                                 WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=t01.nombre)  
                                  
                                    THEN t01.nombre
                    END 

                END AS servicio,
               (CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'-->'  || t06.nombre
                    ELSE   t07.nombre ||'-->' || t06.nombre
                END) as procedencia
                FROM ctl_atencion t01 
                INNER JOIN mnt_aten_area_mod_estab t02 ON (t01.id = t02.id_atencion) 
                INNER JOIN mnt_area_mod_estab t03 ON (t03.id = t02.id_area_mod_estab) 
                LEFT JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab) 
                LEFT JOIN mnt_servicio_externo t05 ON (t05.id = t04.id_servicio_externo) 
                INNER JOIN  ctl_area_atencion t06  on  t06.id = t03.id_area_atencion
                INNER JOIN ctl_modalidad  t07 ON t07.id = t03.id_modalidad_estab
                WHERE t02.id_establecimiento =  $lugar ORDER BY 2)
                    
                SELECT ordenar.* FROM (
                    SELECT distinct (TO_CHAR(t15.fecha, 'DD/MM/YYYY')) AS fechacita,t02.id AS idsolicitudestudio, t06.numero AS idnumeroexp, 
                    CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido, t07.segundo_apellido,t07.apellido_casada) AS paciente,
                    t20.servicio AS nombresubservicio, t20.procedencia AS nombreservicio, t02.impresiones, t14.nombre, t09.id AS idhistorialclinico, 
                    TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud, t17.tiposolicitud AS prioridad, t07.fecha_nacimiento AS fechanacimiento, 
                    t19.nombre AS sexo, t02.id_establecimiento_externo, (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext 
                FROM sec_solicitudestudios t02 
                INNER JOIN mnt_expediente t06 ON (t06.id = t02.id_expediente) 
                INNER JOIN mnt_paciente t07 ON (t07.id = t06.id_paciente) 
                INNER JOIN sec_historial_clinico t09 ON (t09.id = t02.id_historial_clinico) 
                INNER JOIN mnt_aten_area_mod_estab t10 ON (t10.id = t09.idsubservicio) 
                INNER JOIN ctl_atencion t11 ON (t11.id = t10.id_atencion) 
                INNER JOIN mnt_area_mod_estab t12 ON (t12.id = t10.id_area_mod_estab) 
                INNER JOIN ctl_area_atencion t13 ON (t13.id = t12.id_area_atencion) 
                INNER JOIN ctl_establecimiento t14 ON (t14.id = t09.idestablecimiento) 
                INNER JOIN cit_citas_serviciodeapoyo t15 ON (t02.id = t15.id_solicitudestudios) 
                INNER JOIN ctl_estado_servicio_diagnostico t16 ON (t16.id = t02.estado) 
                INNER JOIN lab_tiposolicitud t17 ON (t17.id = t02.idtiposolicitud) 
                INNER JOIN ctl_sexo t19 ON (t19.id = t07.id_sexo) 
                INNER JOIN tbl_servicio t20 ON (t20.id = t10.id AND t20.servicio IS NOT NULL)
                WHERE (t16.idestado = 'D') 
                AND t02.id_establecimiento = $lugar
                 $cond1
        
            UNION

             SELECT distinct (TO_CHAR(t15.fecha, 'DD/MM/YYYY')) AS fechacita,t02.id AS idsolicitudestudio, t06.numero AS idnumeroexp, 
                CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,t07.apellido_casada) AS paciente, t11.nombre AS nombresubservicio,t13.nombre AS nombreservicio,
                t02.impresiones,t14.nombre,t09.id AS idhistorialclinico, TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud,t17.tiposolicitud AS prioridad,t07.fecha_nacimiento AS fechanacimiento, 
                t19.nombre AS sexo, t02.id_establecimiento_externo,(SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext 
                FROM sec_solicitudestudios t02 
                INNER JOIN mnt_dato_referencia t09 ON t09.id=t02.id_dato_referencia 
                INNER JOIN mnt_expediente_referido t06 ON (t06.id = t09.id_expediente_referido) 
                INNER JOIN mnt_paciente_referido t07 ON (t07.id = t06.id_referido) 
                INNER JOIN mnt_aten_area_mod_estab t10 ON (t10.id = t09.id_aten_area_mod_estab) 
                INNER JOIN ctl_atencion t11 ON (t11.id = t10.id_atencion) 
                INNER JOIN mnt_area_mod_estab t12 ON (t12.id = t10.id_area_mod_estab) 
                INNER JOIN ctl_area_atencion t13 ON (t13.id = t12.id_area_atencion) 
                INNER JOIN ctl_establecimiento t14 ON (t14.id = t09.id_establecimiento) 
                INNER JOIN cit_citas_serviciodeapoyo t15 ON (t02.id = t15.id_solicitudestudios) 
                INNER JOIN ctl_estado_servicio_diagnostico t16 ON (t16.id = t02.estado) 
                INNER JOIN lab_tiposolicitud t17 ON (t17.id = t02.idtiposolicitud) 
                INNER JOIN ctl_sexo t19 ON (t19.id = t07.id_sexo) 
            WHERE (t16.idestado = 'D') 
            AND t02.id_establecimiento = $lugar 
             $cond2) ordenar
                ORDER BY to_date(ordenar.fechacita, 'DD/MM/YYYY') DESC"; 
	
		//ECHO $query;
		
             $consulta1=$objdatos->BuscarCitasPaciente($query); 
			 
			$row1 = pg_fetch_array($consulta1);?>
    
 <table width="100%" border="0" align='center'>
    <tr>
        <td colspan="1" align="left" width="15%"><img id="Image1" style='width: auto; height: 55px;' src="../../../Imagenes/escudo.png" name="Image1"></td>
        <td align="center" colspan="5" width="70%" class="Estilo6">
            <p><strong><h6><?php echo $nombre_lugar; ?></h6> </strong></p>
            <p><strong><strong>REPORTE DE CITAS PROGRAMADAS</strong></strong></p>
        </td>
        <td colspan="1" align="right" width="15%"><img id="Image3" style='width: auto; height: 55px;' src="../../../Imagenes/paisanito.png" name="Image3"></td>
    </tr>
    <tr>
	<td colspan="7" align="center" class="Estilo6"><strong><?php echo $row1['nombreservicio']; ?></strong></td>
    </tr>
			
 </table> <BR>
  <?php 
  $consulta=$objdatos->BuscarCitasPaciente($query); ?>
 <table width="100%" border="1" align="center">
        <tr>
		<td width="6%" class="Estilo6" align="justify"><strong>Fecha cita</strong></td>
		<td width="5%" class="Estilo6" align="center"><strong>NEC </strong></td>
		<td width="28%" class="Estilo6"><strong>Nombre Paciente</strong></td>
		<td width="12%" class="Estilo6"><strong>Origen</strong></td>
		<td width="19%" class="Estilo6"><strong>Procedencia</strong></td>
		<td width="30%" class="Estilo6"><strong>Establecimiento</strong></td>
	</tr>    
   <?php $pos=0;
    while ($row = pg_fetch_array($consulta))
	{ ?>
	<tr>
		<td class="Estilo6"><?php echo $row['fechacita']; ?></td>
		<td class="Estilo6"><?php echo $row['idnumeroexp'];?></td>
		<td class="Estilo6"><?php echo $row['paciente'];?></td>
		<td class="Estilo6"><?php echo $row['nombresubservicio'];?></td>
		<td class="Estilo6"><?php echo $row['nombreservicio'];?></td>
		<td class="Estilo6"><?php echo $row['estabext'];?></td>	
	</tr>
 <?php
            $pos=$pos + 1;

           
        
	}
	
	pg_free_result($consulta);?>
	
   <input type="hidden" name="oculto" id="text" value='".$pos."' /> 
</table>
    <br>
<div id="boton">
    <table width="100%" border="0" align="center">
	<tr>
            <td colspan="7" align="center">	
	        <button type='button' align="center" class='btn btn-primary'  onclick='window.print(); '><span class='glyphicon glyphicon-print'></span> Imprimir </button>
                <button type='button' align="center" class='btn btn-primary'  onClick="window.close();"><span class='glyphicon glyphicon-arrow-left'></span> Regresar </button>
            </td>
	</tr>
    </table>
    <br>
</div>
</body>
</html>
