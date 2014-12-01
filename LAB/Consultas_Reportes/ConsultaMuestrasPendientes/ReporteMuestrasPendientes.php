<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<link rel="stylesheet" type="text/css" href="../../Webstyle/Themes/Cobalt/Style.css">
<style type="text/css">
<!--
@media print{
#boton{display:none;}
}



-->
</style>
<title>Reporte de Muestras Pendientes</title>
<script language="JavaScript" type="text/javascript" src="ajax_ConsultaMuestrasPendientes.js"></script>
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
                       	idarea = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var2=' )
			idexpediente = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var3=' )
			fecharecep = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var4=' )
			IdEstab = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var5=' )
			IdServ = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var6=' )
			IdSubServ = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var7=' )
			idexamen = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var8=' )
			PNombre = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var9=' )
			SNombre = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var10=' )
			PApellido = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var11=' )
			SApellido = getVars[i].substr(5);
	}

// CargarDatosFormulario(especialidad,fechainicio,fechafin,medico);
}

function otroreport(){
	window.location.replace('Pro_ConsultaMuestraPnediente.php');
}



// imprimir(){
// document.getElementById('btnImprimir').style.visibility="hidden";
// window.print();
// document.getElementById('btnImprimir').style.visibility="visible";
//}
</script>
</head>

<body onLoad="RecogeValor();">

<?php 
include_once("clsConsultaMuestrasPendientes.php");
//$especialidad=$_GET['var1'];
//$fechainicio=$_GET['var2'];
//$fechafin=$_GET['var3'];
//$medico=$_GET['var4'];
$query_search="";
$ban=0;
$objdatos = new clsConsultaMuestrasPendientes;

        $cond1="";
        $cond2="";
        $query="";
        $query2="";
        $where_with="";
      //  echo $IdEstab." - ".$lugar;
         $IdEstab=$_GET['var4'];
    
     /*  if (!empty($_GET['var4'])) {
          // if ($_GET['var4']<>$lugar){
               $cond1 .= " t02.id_establecimiento_externo = " . $_GET['var4']. " AND";
               $cond2 .= " t02.id_establecimiento_externo = " . $_GET['var4'] . " AND";
           }
          
      //  }*/

        if (!empty($_GET['var5'])) {
            $cond1 .= " t13.id  = " . $_GET['var5'] . " AND";
            $cond2 .= " t13.id  = " . $_GET['var5'] . " AND";
            $where_with = "id_area_atencion = " . $_GET['var5'] . " AND ";
        }

        if (!empty($_GET['var6'])) {
            $cond1 .= " t10.id = " .$_GET['var6'] . " AND";
            $cond2 .= " t10.id = " . $_GET['var6'] . " AND";
        }

        if (!empty($_GET['var1'])) {
            $cond1 .= " t08.id = " . $_GET['var1'] . " AND";
            $cond2 .= " t08.id = " . $_GET['var1'] . " AND";
        }

        if (!empty($_GET['var2'])) {
            $cond1 .= " t06.numero = '" . $_GET['var2'] . "' AND";
            $cond2 .= " t06.numero = '" . $_GET['var2'] . "' AND";
        }

        if (!empty($_GET['var7'])) {
             $cond1 .= " t04.id = " . $_GET['var7'] . " AND";
             $cond2 .= " t04.id = " . $_GET['var7'] . " AND";
        }

        if (!empty($_GET['var3'])) {
             $cond1 .= " t03.fecharecepcion = '" . $_GET['var3'] . "' AND";
             $cond2 .= " t03.fecharecepcion = '" . $_GET['var3'] . "' AND";
        }

        if (!empty($_GET['var8'])) {
            $cond1 .= " t07.primer_nombre ILIKE '" . $_GET['var8'] . "%' AND";
            $cond2 .= " t07.primer_nombre ILIKE '" . $_GET['var8'] . "%' AND";
        }

        if (!empty($_GET['var9'])) {
            $cond1 .= " t07.segundo_nombre ILIKE '" . $_GET['var9'] . "%' AND";
            $cond2 .= " t07.segundo_nombre ILIKE '" . $_GET['var9'] . "%' AND";
        }

        if (!empty($_GET['var10'])) {
            $cond1 .= " t07.primer_apellido ILIKE '" . $_GET['var10'] . "%' AND";
            $cond2 .= " t07.primer_apellido ILIKE '" . $_GET['var10'] . "%' AND";
        }

        if (!empty($_GET['var11'])) {
            $cond1 .= " t07.segundo_apellido ILIKE '" . $_GET['var11'] . "%' AND";
            $cond2 .= " t07.segundo_apellido ILIKE '" . $_GET['var11'] . "%' AND";
        }

      if((empty($_GET['var2'])) AND (empty($_GET['var1'])) AND (empty($_GET['var3'])) AND (empty($_GET['var4'])) AND (empty($_GET['var5'])) AND (empty($_GET['var6'])) AND (empty($_GET['var8'])) AND (empty($_GET['var9'])) AND (empty($_GET['var10'])) AND (empty($_GET['var11'])) AND (empty($_GET['var7'])))
		{
			$ban=1;
		}
		

       
        if ($ban == 0) {

            $cond1 = substr($cond1, 0, strlen($query) - 3);
            $cond2 = substr($cond2, 0, strlen($query) - 3);
            
          //  echo $query1;
           // $query_search = 
        }     
       // echo $cond2;
         $query="WITH tbl_servicio AS (
                    SELECT t02.id,
                        CASE WHEN t02.nombre_ambiente IS NOT NULL THEN      
                            CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'-->' ||t02.nombre_ambiente
                                 ELSE t02.nombre_ambiente
                            END
                        ELSE
                            CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'--> ' || t01.nombre
                                 WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=t01.nombre) THEN t01.nombre
                            END
                        END AS servicio 
                    FROM  ctl_atencion                  t01 
                    INNER JOIN mnt_aten_area_mod_estab              t02 ON (t01.id = t02.id_atencion)
                    INNER JOIN mnt_area_mod_estab           t03 ON (t03.id = t02.id_area_mod_estab)
                    LEFT  JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab)
                    LEFT  JOIN mnt_servicio_externo             t05 ON (t05.id = t04.id_servicio_externo)
                    WHERE $where_with t02.id_establecimiento = $lugar
                    ORDER BY 2)
            
                    SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                       t01.id ,
                       t02.id AS idsolicitudestudio,
                       t04.idplantilla, 
                       t01.id AS iddetallesolicitud,
                       t03.numeromuestra, 
                       t06.numero AS idnumeroexp, 
                       t03.id AS idrecepcionmuestra, 
                       t04.codigo_examen AS idexamen, 
                       t04.nombre_examen AS nombreexamen, 
                       t01.indicacion, t08.nombrearea, 
                       CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,
                       t07.segundo_apellido,t07.apellido_casada) AS paciente,
                       t20.servicio AS nombresubservicio,
                       t13.nombre AS nombreservicio, 
                       t02.impresiones, 
                       t14.nombre, 
                       t09.id AS idhistorialclinico,
                       TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud, 
                       t17.tiposolicitud AS prioridad, 
                       t07.fecha_nacimiento AS fechanacimiento, 
                       t19.nombre AS sexo, 
                       t18.idestandar,
                       t02.id_establecimiento_externo,
                       (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                        t01.observacion
            FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02                ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra t03                 ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04                ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05      ON (t05.id = t04.idexamen) 
            INNER JOIN mnt_expediente t06                       ON (t06.id = t02.id_expediente) 
            INNER JOIN mnt_paciente t07                         ON (t07.id = t06.id_paciente) 
            INNER JOIN ctl_area_servicio_diagnostico t08        ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN sec_historial_clinico t09                ON (t09.id = t02.id_historial_clinico) 
            INNER JOIN mnt_aten_area_mod_estab t10              ON (t10.id = t09.idsubservicio) 
            INNER JOIN ctl_atencion t11                         ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12                   ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13                    ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14                  ON (t14.id = t09.idestablecimiento) 
            INNER JOIN cit_citas_serviciodeapoyo t15            ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico t16      ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud t17                    ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico t18      ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19                             ON (t19.id = t07.id_sexo)
            INNER JOIN tbl_servicio t20                         ON (t20.id = t10.id AND t20.servicio IS NOT NULL)
            WHERE (t16.idestado = 'D')  
            AND t02.id_establecimiento = $lugar
            AND $cond1
        
            UNION

            SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                   t01.id ,
                   t02.id AS idsolicitudestudio,
                   t04.idplantilla, 
                   t01.id AS iddetallesolicitud,
                   t03.numeromuestra,
                   t06.numero AS idnumeroexp,
                   t03.id AS idrecepcionmuestra,
                   t04.codigo_examen AS idexamen,
                   t04.nombre_examen AS nombreexamen,
                   t01.indicacion, t08.nombrearea,
                   CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,
                   t07.apellido_casada) AS paciente, 
                   t11.nombre AS nombresubservicio, 
                   t13.nombre AS nombreservicio, 
                   t02.impresiones, 
                   t14.nombre,
                   t09.id AS idhistorialclinico, 
                   TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud, 
                   t17.tiposolicitud AS prioridad, 
                   t07.fecha_nacimiento AS fechanacimiento, 
                   t19.nombre AS sexo, 
                   t18.idestandar,
                   t02.id_establecimiento_externo,
                   (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
            t01.observacion
                FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02                    ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra t03                     ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04                    ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05          ON (t05.id = t04.idexamen)
            INNER JOIN mnt_dato_referencia t09                      ON t09.id=t02.id_dato_referencia 
            INNER JOIN mnt_expediente_referido t06                  ON (t06.id = t09.id_expediente_referido) 
            INNER JOIN mnt_paciente_referido t07                    ON (t07.id = t06.id_referido) 
            INNER JOIN ctl_area_servicio_diagnostico t08            ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN mnt_aten_area_mod_estab t10                  ON (t10.id = t09.id_aten_area_mod_estab) 
            INNER JOIN ctl_atencion t11                             ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12                       ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13                        ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14                      ON (t14.id = t09.id_establecimiento)
            INNER JOIN cit_citas_serviciodeapoyo t15                ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico t16          ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud t17 ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico t18          ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19                                 ON (t19.id = t07.id_sexo)
            WHERE (t16.idestado = 'D') 
            AND t02.id_establecimiento = $lugar 
                AND $cond2";  
                  
	
	 // $consulta=$objdatos->ListadoSolicitudesPorArea($query_search);  
	//$row = pg_fetch_array($consulta1);?>
 <table width="95%" border="0" cellspacing="0" align='center' >
			<tr>
				<td colspan="7" align="center"><h3><strong>REPORTE DE MUESTRAS PENDIENTES
				</h3></strong></td>
			</tr>
			</table>
  <?php 
  $consulta=$objdatos->ListadoSolicitudesPorArea($query);  ?>
 <table border='1' cellspacing='0' width='95%' >
			
    <tr bgcolor="gray" align="center">
		<td width="6%" bgcolor="gray" align="center" ><h4><strong>MUESTRA</strong></h4></td>
		<td width="8%" bgcolor="gray" align="center"><h4><strong>NEC </strong></h4></td>
		<td width="25%" bgcolor="gray" align="center" ><h4><strong>
NOMBRE PACIENTE</strong><h4></td>
		<td width="6%" bgcolor="gray" align="center"><h4><strong>COD EXAMEN</strong><h4></td>
		<td width="18%"   bgcolor="gray" align="center"><h4><strong>NOMBRE EXAMEN</strong><h4></td>
		<td width="12%" bgcolor="gray" align="center"><h4><strong>SERVICIO</strong><h4></td>
		<td width="10%"  bgcolor="gray" align="center"><h4><strong>PROCEDENCIA</strong><h4></td>
		<td width="12%" bgcolor="gray" align="center"><h4><strong>ESTABLECIMIENTO</strong><h4></td>
		<td width="8%" bgcolor="gray" ><h4><strong>FECHA RECEPCI&Oacute;N</strong><h4></td>
	</tr>    
<?php $pos=0;
    while ($row = pg_fetch_array($consulta))
	{ ?>
	<tr >
		<td width="6%" align="center"><?php echo $row['numeromuestra']; ?></td>
		<td width="8%" align="center"><?php echo $row['idnumeroexp'];?></td>
		<td width="25%" ><?php echo $row['paciente'];?></td>
		<td width="6%" align="center"><?php echo $row['idexamen'];?></td>
		<td width="18%"><?php echo htmlentities($row['nombreexamen']);?></td>
		<td width='12%' align="center"><?php echo htmlentities($row['nombresubservicio']); ?></td>
		<td width='10%' align="center"><?php echo htmlentities($row['nombreservicio']); ?></td>
		<td width='12%' align="center"><?php echo htmlentities($row['estabext']); ?></td>
		<td width="8%"><?php echo $row['fecharecepcion'];?></td>
	</tr>
 <?php
	$pos=$pos + 1;
	}
	
	pg_free_result($consulta);?>
	
   <input type="hidden" name="oculto" id="text" value='".$pos."' /> 
   
		</table>
    
	<div id="boton">
	<table width="90%" border="0" align="center">
			<tr>
			<td colspan="7" align="center">	
			<div id="boton">	
			<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
			<input type="button" name="btncl" id="btnct" value="Regresar" onClick="window.close();"> </div>
			</div>	</td>
				
			</tr></table>
</body>
</html>
