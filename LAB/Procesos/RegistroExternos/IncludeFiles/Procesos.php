<?php session_start();

include("../../../Conexion/ConexionBD.php");
include_once("../../../Funciones/Common.php");
include_once("ClsProcedimientos.php");

$con = new ConexionBD;
$comun = new clsGeneral;
$objdatos = new clsProcedimientos;

$Proceso = $_GET['Proceso'];
$IdUsuarioReg=$_SESSION['usereg'];
$IdEstablecimiento=$_SESSION['IdEstablecimiento'];	
$LugardeAtencion=$_SESSION['Area'];
$FechaReg = $comun->FechaServer();


switch($Proceso){
	
case 'show_registros'://muestra el listado de programaciones
	 
	$registros = 100;
        $Pag =$_GET['Pag'];
        $inicio = ($Pag-1) * $registros;
        
        if($con->conectar()==true){
        
        $totalRegs = "SELECT COUNT(*)
FROM mnt_procedimientosxestablecimiento
INNER JOIN mnt_ciq ON mnt_procedimientosxestablecimiento.IdCiq=mnt_ciq.IdCiq
INNER JOIN mnt_rangohoras ON mnt_procedimientosxestablecimiento.IdRngHr=mnt_rangohoras.IdRangohoras 
WHERE Area=$LugardeAtencion and IdEstablecimiento=$IdEstablecimiento AND yrs >= YEAR(curdate()) ORDER BY Dia";

        $query_totalregs = mysql_query($totalRegs) or die('La consulta fallo&oacute;: ' . mysql_error());
        $totalRegs= mysql_fetch_array($query_totalregs);//cuenta los numeros de registro q hay en la tabla
                
        $totalPag = ceil($totalRegs[0] / $registros);//resultado aproxima al entero mas cercano > o = l 
                
	$tblResult = "<table border = 1 class='CobaltFormTABLE'>";

        if($totalRegs[0] > 0){
	    $sql=	"SELECT mnt_procedimientosxestablecimiento.IdCiq,UPPER(mnt_ciq.Procedimiento)
			FROM mnt_procedimientosxestablecimiento
			INNER JOIN mnt_ciq ON mnt_procedimientosxestablecimiento.IdCiq=mnt_ciq.IdCiq
			WHERE IdEstablecimiento=$IdEstablecimiento AND yrs >= year(curdate()) GROUP BY mnt_procedimientosxestablecimiento.IdCiq";
	$querysql = mysql_query($sql) or die('La consulta fallo&oacute;: ' . mysql_error());

	
 	while($rw = mysql_fetch_array($querysql)){

	$tblResult.=	"<tr>".
			"<td class='CobaltDataTD' colspan='12' nowrap align='center' style='color:#990000; font:bold'><h2>".htmlentities($rw[1])."</h2></td>".
			"</tr>".
			 "<tr>".
                       "<td nowrap class='CobaltFieldCaptionTD' aling='center'>Modificar</td>".
		       "<td nowrap class='CobaltFieldCaptionTD'> Dia</td>".
		       "<td nowrap class='CobaltFieldCaptionTD'> Citas a Cupo</td>".
		       "<td nowrap class='CobaltFieldCaptionTD'> Citas Programadas</td>".
		       "<td nowrap class='CobaltFieldCaptionTD'> Tiempo <br>de Lectura(dias)</td>".
		       "<td nowrap class='CobaltFieldCaptionTD'> Rango de Hora</td>".
                       "<td nowrap class='CobaltFieldCaptionTD'> Medico que realiza<br>Procedimiento</td>".
		       "<td nowrap class='CobaltFieldCaptionTD'> Año</td>".
                       "</tr>";


		$query_Select =	"SELECT UPPER(Procedimiento),CONCAT(CAST(hora_ini as char),'-',CAST(hora_fin as char)) AS Horario,TechoMaximo,CantidadAcupo,TiempoPrevio,CASE dia
				WHEN 2 THEN 'Lunes'
				WHEN 3 THEN 'Martes'
				WHEN 4 THEN 'Miercoles'
				WHEN 5 THEN 'Jueves'
				WHEN 6 THEN 'Viernes' 
			END AS dia,Yrs,IdProcedimiento,mnt_empleados.NombreEmpleado
			FROM mnt_procedimientosxestablecimiento
			INNER JOIN mnt_ciq ON mnt_procedimientosxestablecimiento.IdCiq=mnt_ciq.IdCiq
                        LEFT JOIN mnt_empleados ON mnt_procedimientosxestablecimiento.IdEmpleado=mnt_empleados.IdEmpleado
			INNER JOIN mnt_rangohoras ON mnt_procedimientosxestablecimiento.IdRngHr=mnt_rangohoras.IdRangohoras 
			WHERE Area=$LugardeAtencion 
			AND mnt_ciq.IdCiq='$rw[0]' AND mnt_procedimientosxestablecimiento.IdEstablecimiento=$IdEstablecimiento AND yrs >= YEAR(curdate()) 
			ORDER BY yrs,Dia DESC LIMIT $inicio, $registros";
            $query = mysql_query($query_Select) or die('La consulta 25 fallo&oacute;: ' . mysql_error());
            
            $i=0;
            
        while($row = mysql_fetch_array($query)){
          $tblResult.= "<tr>".
                    "<td class='CobaltDataTD' aling='center'>". 
                    "<img src='../../Iconos/moduser.png' ".
                    "style='text-decoration:underline;cursor:pointer;' ".
                    "onclick='UploadUser(\"$row[7]\")'></td>".
                    "<td class='CobaltDataTD'>".htmlentities($row[5])."</td>". 
		    "<td class='CobaltDataTD'>".htmlentities($row[3])."</td>". 
                    "<td class='CobaltDataTD'>".htmlentities($row[2])."</td>".    
 		    "<td class='CobaltDataTD'>".htmlentities($row[4])."</td>". 
                    "<td class='CobaltDataTD'>".htmlentities($row[1])."</td>".    
                    "<td class='CobaltDataTD'>".htmlentities($row[8])."</td>".    
		    "<td class='CobaltDataTD'>".htmlentities($row[6])."</td>".    	
                    "</tr>";
                   
        }     
     
        $tblResult.="<tr>".
                    "<td class='CobaltSeparatorTD' colspan='8'>&nbsp</td>". 
                    "</tr>";
	} 
        $tblResult.="<tr>".
                    "<td class='CobaltDataTD' colspan='8'>Total de Registros:&nbsp;$totalRegs[0]</td>". 
                    "</tr>".
                    "<tr>".
                    "<td class='CobaltFooterTD' nowrap colspan='8'>";
                       
    
                    
                      }         
                            
        $tblResult.="</td></tr></table>";

        echo $tblResult;
     
        }
	
	break;

case 'InsertarRegistro':
	
	$IdCIQ=$_GET['IdCIQ'];
	$Acupo=$_GET['Acupo'];
	$CuposFijos=$_GET['CantidadFija'];
	$DiasLectura=$_GET['Intervalo'];
	$IdRngHr=$_GET['IdRngHr'];
	$Yrs=$_GET['Yrs'];
	$ArrayDias=$_GET['chequeados'];
	$IdEmpleado=$_GET['IdEmpleado'];
	
	$consulta=$objdatos->InsertarReg($IdCIQ,$Acupo,$DiasLectura,$IdRngHr,$CuposFijos,$ArrayDias,$LugardeAtencion,$IdEstablecimiento,$IdUsuarioReg,$FechaReg,$Yrs,$IdEmpleado);
	echo $consulta;
	break;

	
case 'ModUser':
	
	$IdCIQ=$_GET['IdCIQ'];
	$Acupo=$_GET['Acupo'];
	$CuposFijos=$_GET['CantidadFija'];
	$DiasLectura=$_GET['Intervalo'];
	$IdRngHr=$_GET['IdRngHr'];
	$Yrs=$_GET['Yrs'];
	$ArrayDias=$_GET['chequeados'];
	$IdProcedimiento=$_GET['Id'];
	$IdRngHrViejo=$_GET['IdRngHrViejo'];
        $IdEmpleado=$_GET['IdEmpleado'];
        
	$consulta=$objdatos->Actualizar($IdProcedimiento,$IdCIQ,$Acupo,$DiasLectura,$IdRngHr,$CuposFijos,$ArrayDias,$LugardeAtencion,$IdEstablecimiento,$IdUsuarioReg,$FechaReg,$Yrs,$IdRngHrViejo,$IdEmpleado);
	echo $consulta;
	break;

case 'ComboBloqueoHorario':
        $IdCIQ=$_GET['IdCIQ'];
        $Data=$objdatos->ComboHorariosBloqueo($IdCIQ);
        $combo = '<select id="cmbRngHr" style="width:170px">
                <option value="0">--Seleccione--</option>';
        while ($rows = mysql_fetch_array($Data)){
            $combo .= '<option value="' . $rows[0] . '">' . $rows[1] . '</option>'; 
        }
        $combo .='</select>';
        
        echo $combo;
        break;      

case 'BloqueoProcedimientos':
        $IdCIQ=$_GET['IdCIQ'];
	$FechaInicio=$_GET['inidate'];
	$FechaFinal=$_GET['findate'];
	$IdRngHr=$_GET['IdRngHr'];
	$ArrayDias=$_GET['chequeados'];
	
	$consulta=$objdatos->InsertarBloqueo($IdCIQ,$FechaInicio,$FechaFinal,$IdRngHr,$ArrayDias,$LugardeAtencion,$IdEstablecimiento,$IdUsuarioReg,$FechaReg);
	echo $consulta;
	break;
    

case 'show_bloqueos':
    $registros = 100;
        $Pag =$_GET['Pag'];
        $inicio = ($Pag-1) * $registros;
        
        if($con->conectar()==true){
        
        $totalRegs = "SELECT COUNT(*)
                    FROM cit_eventos
                    inner join mnt_ciq on cit_eventos.Idempleado=mnt_ciq.IdCiq
                    inner join mnt_rangohoras on cit_eventos.IdRngHr=mnt_rangohoras.IdRangoHoras
                    WHERE IdModalidad=$LugardeAtencion and IdEstablecimiento=$IdEstablecimiento 
                    AND (year(FechaIni)=year(curdate()) and year(FechaFin)=year(curdate()))";

        $query_totalregs = mysql_query($totalRegs) or die('La consulta fallo&oacute;: ' . mysql_error());
        $totalRegs= mysql_fetch_array($query_totalregs);//cuenta los numeros de registro q hay en la tabla
                
        $totalPag = ceil($totalRegs[0] / $registros);//resultado aproxima al entero mas cercano > o = l 
                
	$tblResult = "<table border = 1 class='CobaltFormTABLE'>";

        if($totalRegs[0] > 0){
	    $sql=	"SELECT IdEmpleado,Procedimiento
                        FROM cit_eventos
                        inner join mnt_ciq on cit_eventos.Idempleado=mnt_ciq.IdCiq
                        inner join mnt_rangohoras on cit_eventos.IdRngHr=mnt_rangohoras.IdRangoHoras
			WHERE IdEstablecimiento=$IdEstablecimiento AND (year(FechaIni)=year(curdate()) and year(FechaFin)=year(curdate()))
                        GROUP BY cit_eventos.Idempleado";
	$querysql = mysql_query($sql) or die('La consulta fallo&oacute;: ' . mysql_error());

	
 	while($rw = mysql_fetch_array($querysql)){

	$tblResult.=	"<tr>".
			"<td class='CobaltDataTD' colspan='12' nowrap align='center' style='color:#990000; font:bold'><h2>".htmlentities($rw[1])."</h2></td>".
			"</tr>".
			 "<tr>".
                       "<td nowrap class='CobaltFieldCaptionTD' aling='center'>Eliminar</td>".
		       "<td nowrap class='CobaltFieldCaptionTD'> Rango de Fechas a Bloquear</td>".
		       "<td nowrap class='CobaltFieldCaptionTD'> Rango de Hora de Bloqueo</td>".
		       "<td nowrap class='CobaltFieldCaptionTD'> Dia de Semana</td>".
                       "</tr>";


		$query_Select =	"SELECT UPPER(Procedimiento),IdCiq,concat_ws(' al ',FechaIni,FechaFin)as RangoFecha,concat_ws('-',Hora_Ini,Hora_Fin),CASE DiaSemana
				WHEN 0 THEN 'Todos'
                                WHEN 2 THEN 'Lunes'
				WHEN 3 THEN 'Martes'
				WHEN 4 THEN 'Miercoles'
				WHEN 5 THEN 'Jueves'
				WHEN 6 THEN 'Viernes' 
                                END AS dia,IdEvento
                                FROM cit_eventos
                                inner join mnt_ciq on cit_eventos.Idempleado=mnt_ciq.IdCiq
                                inner join mnt_rangohoras on cit_eventos.IdRngHr=mnt_rangohoras.IdRangoHoras 
                                WHERE IdModalidad=$LugardeAtencion AND cit_eventos.Idempleado= $rw[0] AND IdEstablecimiento=$IdEstablecimiento 
                                ORDER BY DiaSemana DESC LIMIT $inicio, $registros";
            $query = mysql_query($query_Select) or die('La consulta fallo&oacute;: ' . mysql_error());
            
            $i=0;
            
        while($row = mysql_fetch_array($query)){
          $tblResult.= "<tr>".
                    "<td class='CobaltDataTD' aling='center'>". 
                    "<img src='../../Iconos/eliminar.png' ".
                    "style='text-decoration:underline;cursor:pointer;' ".
                    "onclick='delEvt(\"$row[5]\")'></td>".
                    "<td class='CobaltDataTD'>".htmlentities($row[2])."</td>". 
		    "<td class='CobaltDataTD'>".htmlentities($row[3])."</td>".   
 		    "<td class='CobaltDataTD'>".htmlentities($row[4])."</td>".   	
                    "</tr>";
                   
        }     
     
        $tblResult.="<tr>".
                    "<td class='CobaltSeparatorTD' colspan='8'>&nbsp</td>". 
                    "</tr>";
	} 
        $tblResult.="<tr>".
                    "<td class='CobaltDataTD' colspan='8'>Total de Registros:&nbsp;$totalRegs[0]</td>". 
                    "</tr>".
                    "<tr>".
                    "<td class='CobaltFooterTD' nowrap colspan='8'>";
         }         
                            
        $tblResult.="</td></tr></table>";

        echo $tblResult;
     
        }
	break;
     
  case 'delEvt'://proceso para eliminar un evento
        $rslt = 0;
        $IdTipo=$_GET["IdEvento"];
        
        if($con->conectar()==true){
            $query_Select = "DELETE FROM cit_eventos WHERE IdEvento=$IdTipo";
            $query = mysql_query($query_Select) or die('La consulta fallo&oacute;: ' . mysql_error());
            $rslt = 2;
          }
            echo $query_Select;
            break;
break;
}
?>
