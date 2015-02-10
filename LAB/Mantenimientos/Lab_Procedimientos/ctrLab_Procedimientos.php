<?php session_start();
include_once("clsLab_Procedimientos.php");
//include_once("Clases_labo.php");//lkillm

$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//variables POST


//$Pag =$_POST['Pag'];
$opcion=$_POST['opcion'];
//echo $opcion;
$objdatos = new clsLab_Procedimientos;
$Clases = new clsLabor_Procedimientos;
  


switch ( $opcion ) {
case 1:  //INSERTAR
	$idexamen=$_POST['idexamen'];
	$proce=$_POST['proce'];
	$idarea=$_POST['idarea'];
	$unidades=$_POST['unidades'];
	//$unidades=isset($_POST['unidades']) ? $_POST['unidades'] : null;;
        $cmborden=$_POST['cmborden'];
        $resultado=$_POST['resultado'];
       
	$sexo=$_POST['sexo'];
	$redad=$_POST['redad'];
        $Fechaini=$_POST['Fechaini'];
        $Fechafin=$_POST['Fechafin'];
        
        
        
        if ($sexo==3){
            $sexo="NULL";
            
        }
        
        if ($Fechafin==""){
            $Fechafin="NULL";
            
        }else{
            $Fechafin="'".$Fechafin."'";
        }

	if ( empty( $_POST['rangoini'] ) ) {
		$rangoini="NULL";
	} else {
		$rangoini=$_POST['rangoini'];
	}

	if ( empty( $_POST['rangofin'] ) ) {
		$rangofin="NULL";
	} else {
		$rangofin=$_POST['rangofin'];
	}

	if ( empty( $_POST['unidades'] ) ) {
		$unidades="NULL";
	} else {
		$unidades="'".$_POST['unidades']."'";
	}
        
	/*if ( empty( $_POST['Fechaini'] ) ) {
		$Fechaini="NULL";
	} else {
		$FechaI=explode( '/', $_POST['Fechaini'] );
		$Fechaini='\''.$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0].'\'';
	}*/

	/*if ( empty( $_POST['Fechafin'] ) ) {
		$Fechafin="NULL";
	} else {
		$FechaF=explode( '/', $_POST['Fechafin'] );
		$Fechafin='\''.$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0].'\'';
	}*/

	//echo $Fechaini."-".$Fechafin;
$proce= utf8_encode($proce);
if ( $objdatos->insertar($proce, $idarea, $idexamen, $unidades, $rangoini, $rangofin, $usuario, $lugar, $Fechaini, $Fechafin, $sexo, $redad,$cmborden,$resultado ) == true ) {
		echo "Registro Agregado";        
        }
	else {
		echo "No se pudo Agregar";      
	}
	break;

case 2:  //MODIFICAR
	$idexamen=$_POST['idexamen'];
	$proce=$_POST['proce'];
	$idarea=$_POST['idarea'];
	$unidades=$_POST['unidades'];
	$idproce=$_POST['idproce'];
        
	$sexo=$_POST['sexo'];
	$redad=$_POST['redad'];
        $cmborden=$_POST['cmborden'];
        
        
        
	if ( empty( $_POST['rangoini'] ) ) {
		$rangoini="NULL";
	}
	else {
		$rangoini=$_POST['rangoini'];
	}

	if ( empty( $_POST['rangofin'] ) ) {
		$rangofin="NULL";
	}else {
		$rangofin=$_POST['rangofin'];
	}

	if ( empty( $_POST['Fechaini'] ) ) {
		$Fechaini="NULL";
	}else {
		$FechaI=explode( '/', $_POST['Fechaini'] );
		$Fechaini='\''.$FechaI[2].'-'.$FechaI[1].'-'.$FechaI[0].'\'';
	}

	if ( empty( $_POST['Fechafin'] ) ) {
		$Fechafin="NULL";
	}else {
		$FechaF=explode( '/', $_POST['Fechafin'] );
		$Fechafin='\''.$FechaF[2].'-'.$FechaF[1].'-'.$FechaF[0].'\'';
	}
$proce= utf8_encode($proce);
	if ( $objdatos->actualizar( $idproce, $proce, $idarea, $idexamen, $unidades, $rangoini, $rangofin, $usuario, $lugar, $Fechaini, $Fechafin, $sexo, $redad,$cmborden )==true ) {
		echo "Registro Actualizado";
	} else {
		echo "No se pudo actualizar";
	}
	break;
case 3:  //ELIMINAR
	//Vefificando Integridad de los datos
	$idproce =$_POST['idproce'];
	//echo $idproce."**".$lugar;
	if ( $objdatos->eliminar( $idproce, $lugar )==true ) {
		//&& ($Clases->eliminar_labo($idproce,$lugar))){
		echo "Registro Eliminado" ;

	}
	else {
		echo "El registro no pudo ser eliminado ";
	}
	break;
        
case 9:  //habilitado
             
                $cond=$_POST['condicion'];
		$idlppe=$_POST['idlppe'];
                //$fechafinhabilitado="NULL";
             //	echo $idexamen."-".$condicion;
		//$resultado=Estado::EstadoCuenta($idexamen,$cond,$lugar);
		if($objdatos->EstadoCuenta($idlppe,$cond,$usuario)==true )
                {
                   // echo "cambio";
                }else{
                   // echo "no cambio";
                }
	break;
        
        

case 4:// PAGINACION
	//require_once("clsLab_Procedimientos.php");
	////para manejo de la paginacion
	$RegistrosAMostrar=5;
	$RegistrosAEmpezar=( $_POST['Pag']-1 )*$RegistrosAMostrar;
	$PagAct=$_POST['Pag'];

	/////LAMANDO LA FUNCION DE LA CLASE
        
       $consulta= $objdatos->updatehabilitadot();
        $consulta= $objdatos->updatehabilitadof();
        
	$consulta= $objdatos->consultarpag( $lugar, $RegistrosAEmpezar, $RegistrosAMostrar );

	//muestra los datos consultados en la tabla
	echo "<center >
               <table border = 1 style='width: 80%;'  class='table table-hover table-bordered table-condensed table-white table-striped'>
	           <thead>
                        <tr>
                                <th   aling='center'> Modificar</th>
                                <!--<th   aling='center'> Eliminar</th>-->
                                <th aling='center' > Habilitado</th>
                                <th> IdExamen          </th>
                                <th> Examen            </th>
                                
                                <th> Procedimiento     </th>
                                <th> Unidades          </th>
                                <th> Valores Normales  </th>
                                <th> Sexo              </th>
                                <th> Rango de Edad     </th>
                                <th> Fecha Inicio      </th>
                                <th> Fecha Finalización </th>
                                <th> Orden </th>
                        </tr>
                   </thead><tbody>
                    </center>";

	while ( $row = @pg_fetch_array( $consulta ) ) {

            //if (
               
            // echo $pro= $row['idprocedimientoporexamen'];
              //   $idelem= $row['idelemento'];
            $habilitado=$row['habilitado'] ;
                   // == "t") {
                
          //  }
                 if ($habilitado=="t")
                     {
                    // echo " if";
                     echo "<tr>
                    <td aling='center'>
                        <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\"
			onclick=\"pedirDatos('".$row['idprocedimientoporexamen']."')\"> </td>
                   <!-- <td aling ='center'>
			 <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
			onclick=\"eliminarDato('".$row['idprocedimientoporexamen']."',$lugar)\"> </td> -->
                      <td width='6%'><span style='color: #0101DF;'>
                   	 <a style ='text-decoration:underline;cursor:pointer;' onclick='Estado(\"".$row['idlppe']."\",\"".$row['habilitado']."\")'>".$row['cond']."</a></td>
                    <td>".$row['idexamen']."</td>
                    <td>".htmlentities( $row['nombreexamen'] )."</td>
                   
                    <td>".htmlentities( $row['nombreprocedimiento'] )."</td>
                    <td>".htmlentities( $row['unidades'] )."</td>
                    <td>".$row['rangoinicio']."-".$row['rangofin']."</td>";
		echo "<td>".$row['sexovn']."</td>
                    <td>".$row['nombregrupoedad']."</td>";
		if ( ( $row['fechaini']=="NULL" ) || ( $row['fechaini']=="00/00/0000" ) ||( empty( $row['fechaini'] ) ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechaini']."</td>";

		if ( ( empty( $row['fechafin'] ) ) || ( $row['fechafin']=="NULL" ) || ( $row['fechafin']=="00/00/0000" ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechafin']."</td>
			          ";
                 echo "<td>".$row['orden']."</td>
			          ";
		echo"</tr> ";
                     
                 }
                 else {
                     //echo " else";
                       echo "<tr>
                         
                       
                    <td aling='center'>
                        <img src='../../../Imagenes/Search.png' style=\"text-decoration:underline;cursor:pointer;\"
			onclick=\"pedirDatos('".$row['idprocedimientoporexamen']."')\"  height='40' width='50'> </td>
                   <!-- <td aling ='center'>
			 <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
			onclick=\"eliminarDato('".$row['idprocedimientoporexamen']."',$lugar)\"> </td> -->
                      <td width='6%'>   ".$row['cond']."</td>
                    <td>".$row['idexamen']."</td>
                    <td>".htmlentities( $row['nombreexamen'] )."</td>
                    <td>".htmlentities( $row['nombreprocedimiento'] )."</td>
                    <td>".htmlentities( $row['unidades'] )."</td>
                    <td>".$row['rangoinicio']."-".$row['rangofin']."</td>";
		echo "<td>".$row['sexovn']."</td>
                    <td>".$row['nombregrupoedad']."</td>";
		if ( ( $row['fechaini']=="NULL" ) || ( $row['fechaini']=="00/00/0000" ) ||( empty( $row['fechaini'] ) ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechaini']."</td>";

		if ( ( empty( $row['fechafin'] ) ) || ( $row['fechafin']=="NULL" ) || ( $row['fechafin']=="00/00/0000" ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechafin']."</td>
			          ";
                                  echo "<td>".$row['orden']."</td>
			          ";
		echo"</tr> ";
                
                
                
                     
                 }
                 
                 
                 //echo "</table>";
		
	}
	
	//determinando el numero de paginas
	$NroRegistros= $objdatos->NumeroDeRegistros( $lugar );
	$PagAnt=$PagAct-1;
	$PagSig=$PagAct+1;

	$PagUlt=$NroRegistros/$RegistrosAMostrar;

	//verificamos residuo para ver si llevar� decimales
	$Res=$NroRegistros%$RegistrosAMostrar;
	//si hay residuo usamos funcion floor para que me
	//devuelva la parte entera, SIN REDONDEAR, y le sumamos
	//una unidad para obtener la ultima pagina
	if ( $Res>0 ) $PagUlt=floor( $PagUlt )+1;
	echo "<table align='center'>
               <tr>
                    <td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
               </tr>
               <tr>
		    <td><a onclick=\"show_event('1')\">Primero</a> </td>";
	//// desplazamiento

	if ( $PagAct>1 )
		echo "<td> <a onclick=\"show_event('$PagAnt')\">Anterior</a> </td>";
	if ( $PagAct<$PagUlt )
		echo "<td> <a onclick=\"show_event('$PagSig')\">Siguiente</a> </td>";
	echo "<td> <a onclick=\"show_event('$PagUlt')\">Ultimo</a></td>";
	echo "</tr>
	      </table>";
        
        
               echo " <center> <ul class='pagination'>";
                          for ($i=1 ; $i<=$PagUlt; $i++)
                                    {
                             
					 echo " <li ><a  href='javascript: show_event(".$i.")'>$i</a></li>";
                                     }
                    echo " </ul></center>";
        
        
       
        
        
	break;
case 5:  //LLENAR COMBO DE EXAMENES
	$idarea=$_POST['idarea'];
	$rslts='';
	$consultaex= $objdatos->ExamenesPorArea( $idarea, $lugar );
	//$dtMed=$obj->LlenarSubServ($proce);

	$rslts = '<select name="cmbExamen" id="cmbExamen"  style="width:50%"  onblur="habilitar_metodologia(this);" class="form-control height" onChange="llenarcomboRango(this.value);">';
	$rslts .='<option value="0">--Seleccione un Examen--</option>';

	while ( $rows =pg_fetch_array( $consultaex ) ) {
		$rslts.= '<option value="' .$rows['idexamen'].'" >'.htmlentities( $rows['nombreexamen'] ).'</option>';
	}
	$rslts .= '</select>';
	echo $rslts;


	break;
        
case 11:  //LLENAR COMBO DE RANGOS
	$idexa=$_POST['idexa'];
        $rslts='';
        
           $rslts = '<select name="cmborden" id="cmborden" style="width:50%"  class="form-control height"   >';
           $rslts .='<option value="0">--Seleccione un Orden--</option>';
        
            
                                     $datosDB=existeOrden($idexa);
                                     
                                    //echo  $datosDB[3];
                                        for ($index = 1 ; $index <=10 ; $index++) 
                                        {
                                          $rest=areglo ($datosDB,$index);
                                          if($rest==0){
                                            $rslts.='<OPTION VALUE="'.$index.'">'.$index.'</OPTION>';  
                                          }
                                            
                           
                                        }
                                
            $rslts .= '</select>';
                            echo $rslts;
	
                      

	break;

case 6: //DIBUJANDO EL FORMULARIO DE NUEVO

	break;
case 7: //BUSQUEDA
	$idexamen = $_POST['idexamen'];
	$proce 	  = $_POST['proce'];
	$idarea   = $_POST['idarea'];
	$unidades = $_POST['unidades'];
	$sexo 	  = $_POST['sexo'];
	$redad 	  = $_POST['redad'];

	if ( empty( $_POST['rangoini'] ) ) {
		$rangoini="NULL";
	} else {
		$rangoini = '\''.$_POST['rangoini'].'\'';
	}
	if ( empty( $_POST['rangofin'] ) ) {
		$rangofin="NULL";
	} else {
		$rangofin = '\''.$_POST['rangofin'].'\'';
	}
	/*if ( empty( $_POST['Fechaini'] ) ) {
		$Fechaini="NULL";
	} else {
		$FechaI=explode( '/', $_POST['Fechaini'] );
		$Fechaini='\''.$FechaI[2].'-'.$FechaI[1].'-'.$FechaI[0].'\'';
	}*/
	/*if ( empty( $_POST['Fechafin'] ) ) {
		$Fechafin="NULL";
	} else {
		$FechaF=explode( '/', $_POST['Fechafin'] );
		$Fechafin='\''.$FechaF[2].'-'.$FechaF[1].'-'.$FechaF[0].'\'';
	}*/
        
        

	$query = "SELECT lppe.id AS idprocedimientoporexamen,
					lcee.codigo_examen AS idexamen,
					lcee.nombre_examen AS nombreexamen,
					casd.id AS idarea,
				 	casd.nombrearea,
				 	lppe.nombreprocedimiento,
				 	lppe.unidades,
				 	lppe.rangoinicio,
				 	lppe.rangofin,
				 	TO_CHAR(lppe.fechaini::timestamp, 'DD/MM/YYYY') AS fechaini,
				 	TO_CHAR(lppe.fechafin::timestamp, 'DD/MM/YYYY')AS fechafin,
				 	CASE WHEN cex.id IS NULL THEN 'NULL'
				         ELSE cex.id::text
				    END AS idsexo,
				 	CASE WHEN cex.nombre IS NULL THEN 'Ambos'
				         ELSE cex.nombre
				    END AS sexovn,
				 	cre.id AS idedad,
				 	cre.nombre AS nombregrupoedad,
                                        
                                                (CASE WHEN lppe.habilitado='f' THEN 'Inhabilitado'
						WHEN lppe.habilitado='t' THEN 'Habilitado' END) AS cond,
                                                
						lppe.habilitado,
                                                lppe.id as idlppe,
                                                mnt4.id as idmnt4,
                                                lcee.condicion,
                                                lppe.orden
			  FROM lab_procedimientosporexamen                      lppe
			  INNER JOIN lab_conf_examen_estab 			lcee ON (lcee.id = lppe.id_conf_examen_estab)
			  INNER JOIN lab_plantilla                              lpla ON (lpla.id = lcee.idplantilla)
			  INNER JOIN mnt_area_examen_establecimiento            mnt4 ON (mnt4.id = lcee.idexamen)
			  INNER JOIN ctl_area_servicio_diagnostico              casd ON (casd.id = mnt4.id_area_servicio_diagnostico)
			  LEFT OUTER JOIN ctl_sexo                              cex  ON (cex.id  = lppe.idsexo AND cex.abreviatura != 'I')
			  LEFT OUTER JOIN ctl_rango_edad 			cre  ON (cre.id  = lppe.idrangoedad)
			  WHERE 
                         --  lpla.idplantilla = 'E' 
                          --AND lcee.condicion = 'H'
                         --AND 
                         lppe.idestablecimiento = $lugar";

	$ban=0;
	//VERIFICANDO LOS POST ENVIADOS

	/*if ( !empty( $_POST['idarea'] ) && $_POST['idarea'] !== '0' ) {
		$query .= "AND  casd.id = ".$_POST['idarea']." ";
	}*/
        
         if (!empty($_POST['idarea'])) {
            $query .= " AND casd.id = " . $_POST['idarea'] ."    ";
            
        }
        
        

	if ( !empty( $_POST['idexamen'] )  ) {
		$query .= "AND lcee.id = ".$_POST['idexamen']."     ";
    }

	if ( !empty( $_POST['proce'] ) ) {
		$query .= "AND lppe.nombreprocedimiento ILIKE '%".$_POST['proce']."%'   ";
	}

	if ( !empty( $_POST['unidades'] ) ) {
		$query .= "AND lppe.unidades = '".$_POST['unidades']."'     ";
	}

	if ( !empty( $_POST['rangoini'] ) ) {
		$query .= "AND lppe.rangoinicio = ".$rangoini."     ";
	}

	if ( !empty( $_POST['rangofin'] ) ) {
		$query .= "AND lppe.rangofin = ".$rangofin."    ";
	}

	if ( !empty( $_POST['sexo'] ) && $_POST['sexo'] !== '0' ) {
		
                if ( $_POST['sexo']==3){
                    
                    $query .= //"  AND CASE WHEN cex.id IS NULL THEN 'NULL' ELSE cex.id::text END = '".$_POST['sexo']."'   ";
                "AND ((cex.id IS NULL) or (cex.id=".$_POST['sexo']."))        ";
                }
                else{
                    $query .="AND cex.id=".$_POST['sexo']."         ";
                    
                }
                
        }

	if ( !empty( $_POST['redad'] ) && $_POST['redad'] !== '0' ) {
		$query .= "AND lppe.idrangoedad = ".$_POST['redad']."   ";
	}

	/*if ( !empty( $_POST['Fechaini'] ) ) { 
		$query .= " fechaini = ".$Fechaini." AND"; }*/
                
                if (!empty($_POST['Fechaini'])) {
             $query .= "AND  fechaini= '" . $_POST['Fechaini'] . "'      ";
             
        }
                

	/*if ( !empty( $_POST['Fechafin'] ) ) {
		$query .= " fechafin = ".$Fechafin." AND"; }*/
                
                      if (!empty($_POST['Fechafin'])) {
             $query .= "AND fechafin= '" . $_POST['Fechafin'] . "'      ";
             
        }
                

	if ( ( empty( $_POST['idarea'] ) || $_POST['idarea'] === '0') and ( empty( $_POST['idexamen'] ) || $_POST['idexamen'] === '0' ) and ( empty( $_POST['proce'] ) ) and ( empty( $_POST['unidades'] ) ) and ( empty( $_POST['rangoini'] ) )
		and ( empty( $_POST['rangofin'] ) ) and ( empty( $_POST['Fechafin'] ) ) and ( empty( $_POST['Fechaini'] ) ) and ( empty( $_POST['sexo'] ) || $_POST['sexo'] === '0') and ( empty( $_POST['redad'] ) || $_POST['redad'] === '0' ) ) {
		$ban=1;
	}

	if ( $ban==0 ) {
		$query = substr( $query , 0, strlen( $query )-3 );
	} else {
		$query = substr( $query , 0, strlen( $query )-6 );
	}

	$query_search = $query. " ORDER BY lcee.codigo_examen";
	//echo $query_search;
	//ENVIANDO A EJECUTAR LA BUSQUEDA!!
	//require_once("clsLab_Procedimientos.php");
	////para manejo de la paginacion
	$RegistrosAMostrar=5;
	$RegistrosAEmpezar=( $_POST['Pag']-1 )*$RegistrosAMostrar;
	$PagAct=$_POST['Pag'];

	/////LAMANDO LA FUNCION DE LA CLASE
	//$obje=new clsLab_Procedimientos;
	$consulta= $objdatos->consultarpagbus( $query_search, $RegistrosAEmpezar, $RegistrosAMostrar );

	//muestra los datos consultados en la tabla
	echo "<center >
               <table border = 1 style='width: 80%;'  class='table table-hover table-bordered table-condensed table-white'>
	           <thead>
                        <tr>
                                <th   aling='center'> Modificar</th>
                                <th aling='center' > Habilitado</th>
                                <!--<th   aling='center'> Eliminar</th>-->
                                <th> IdExamen          </th>
                                <th> Examen            </th>
                                <th> Procedimiento     </th>
                                <th> Unidades          </th>
                                <th> Valores Normales  </th>
                                <th> Sexo              </th>
                                <th> Rango de Edad     </th>
                                <th> Fecha Inicio      </th>
                                <th> Fecha Finalización </th>
                                <th> Orden </th>
                        </tr>
                   </thead><tbody>
                    </center>";
	while ( $row = @pg_fetch_array( $consulta ) ) {
		/*echo "<tr>
                <td aling='center'><img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" onclick=\"pedirDatos('".$row['idprocedimientoporexamen']."')\"></td>
               	<!-- <td aling ='center'><img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" onclick=\"eliminarDato('".$row['idprocedimientoporexamen']."',$lugar)\"></td> -->
                 <td width='6%'><span style='color: #0101DF;'>
                   	 <a style ='text-decoration:underline;cursor:pointer;' onclick='Estado(\"".$row['idlppe']."\",\"".$row['habilitado']."\")'>".$row['cond']."</a></td>
                    
                <td>".$row['idexamen']."</td> 
                <td>".htmlentities( $row['nombreexamen'] )."</td>
                <td>".htmlentities( $row['nombreprocedimiento'] )."</td>
                <td>".htmlentities( $row['unidades'] )."</td>
                <td>".$row['rangoinicio']."-".$row['rangofin']."</td>
                <td>".$row['sexovn']."</td>
                <td>".$row['nombregrupoedad']."</td>";
		if ( ( $row['fechaini']=="NULL" ) || ( $row['fechaini']=="00/00/0000" ) ||( empty( $row['fechaini'] ) ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechaini']."</td>";

		if ( ( empty( $row['fechafin'] ) ) || ( $row['fechafin']=="NULL" ) || ( $row['fechafin']=="00/00/0000" ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechafin']."</td>
		          ";
		echo"</tr>";*/
            
          $habilitado=$row['habilitado'] ;
                   // == "t") {
                
          //  }
                 if ($habilitado=="t")
                     {
                     
                     echo "<tr>
                    <td aling='center'>
                        <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\"
			onclick=\"pedirDatos('".$row['idprocedimientoporexamen']."')\"> </td>
                   <!-- <td aling ='center'>
			 <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
			onclick=\"eliminarDato('".$row['idprocedimientoporexamen']."',$lugar)\"> </td> -->
                      <td width='6%'><span style='color: #0101DF;'>
                   	 <a style ='text-decoration:underline;cursor:pointer;' onclick='Estado(\"".$row['idlppe']."\",\"".$row['habilitado']."\")'>".$row['cond']."</a></td>
                    <td>".$row['idexamen']."</td>
                    <td>".htmlentities( $row['nombreexamen'] )."</td>
                    <td>".htmlentities( $row['nombreprocedimiento'] )."</td>
                    <td>".htmlentities( $row['unidades'] )."</td>
                    <td>".$row['rangoinicio']."-".$row['rangofin']."</td>";
		echo "<td>".$row['sexovn']."</td>
                    <td>".$row['nombregrupoedad']."</td>";
		if ( ( $row['fechaini']=="NULL" ) || ( $row['fechaini']=="00/00/0000" ) ||( empty( $row['fechaini'] ) ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechaini']."</td>";

		if ( ( empty( $row['fechafin'] ) ) || ( $row['fechafin']=="NULL" ) || ( $row['fechafin']=="00/00/0000" ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechafin']."</td>
			          ";
                 echo "<td>".$row['orden']."</td>
			          ";
		echo"</tr> ";
                     
                 }
                 else {
                     
                       echo "<tr>
                    <td aling='center'>
                        <img src='../../../Imagenes/Search.png' style=\"text-decoration:underline;cursor:pointer;\"
			onclick=\"pedirDatos('".$row['idprocedimientoporexamen']."')\"  height='40' width='50'> </td>
                   <!-- <td aling ='center'>
			 <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
			onclick=\"eliminarDato('".$row['idprocedimientoporexamen']."',$lugar)\"> </td> -->
                      <td width='6%'>   ".$row['cond']."</td>
                    <td>".$row['idexamen']."</td>
                    <td>".htmlentities( $row['nombreexamen'] )."</td>
                    <td>".htmlentities( $row['nombreprocedimiento'] )."</td>
                    <td>".htmlentities( $row['unidades'] )."</td>
                    <td>".$row['rangoinicio']."-".$row['rangofin']."</td>";
		echo "<td>".$row['sexovn']."</td>
                    <td>".$row['nombregrupoedad']."</td>";
		if ( ( $row['fechaini']=="NULL" ) || ( $row['fechaini']=="00/00/0000" ) ||( empty( $row['fechaini'] ) ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechaini']."</td>";

		if ( ( empty( $row['fechafin'] ) ) || ( $row['fechafin']=="NULL" ) || ( $row['fechafin']=="00/00/0000" ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechafin']."</td>
			          ";
                 echo "<td>".$row['orden']."</td>
			          ";
		echo"</tr> ";
                     
                 }
                 
            
            
	}
	echo "</table>";
	//determinando el numero de paginas

	$NroRegistros= $objdatos->NumeroDeRegistrosbus( $query_search );
	$PagAnt=$PagAct-1;
	$PagSig=$PagAct+1;

	$PagUlt=$NroRegistros/$RegistrosAMostrar;

	//verificamos residuo para ver si llevar� decimales
	$Res=$NroRegistros%$RegistrosAMostrar;
	//si hay residuo usamos funcion floor para que me
	//devuelva la parte entera, SIN REDONDEAR, y le sumamos
	//una unidad para obtener la ultima pagina
	if ( $Res>0 ) $PagUlt=floor( $PagUlt )+1;

	echo "<table align='center'>
	    	<tr>
		   		<td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
		   	</tr>
		   	<tr>
		   		<td><a onclick=\"show_event_search('1')\">Primero</a> </td>";
	//// desplazamiento

	if ( $PagAct>1 )
		echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
	if ( $PagAct<$PagUlt )
		echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
	if ( $PagUlt > 0 )
		echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
	 echo "</tr>
	         </table>";
         
         echo " <center> <ul class='pagination'>";
                          for ($i=1 ; $i<=$PagUlt; $i++)
                                    {
                             
					 echo " <li ><a  href='javascript: show_event_search(".$i.")'>$i</a></li>";
                                     }
                    echo " </ul></center>";
        
	break;

case 8://PAGINACION DE BUSQUEDA
	$idexamen = $_POST['idexamen'];
	$proce 	  = $_POST['proce'];
	$idarea   = $_POST['idarea'];
	$unidades = $_POST['unidades'];
	$sexo 	  = $_POST['sexo'];
	$redad 	  = $_POST['redad'];
        $Fechaini = $_POST['Fechaini'];
        $Fechaini = $_POST['Fechafin'];
        
	if ( empty( $_POST['rangoini'] ) ) {
		$rangoini="NULL";
	} else {
		$rangoini = '\''.$_POST['rangoini'].'\'';
	}
	if ( empty( $_POST['rangofin'] ) ) {
		$rangofin="NULL";
	} else {
		$rangofin = '\''.$_POST['rangofin'].'\'';
	}
	/*if ( empty( $_POST['Fechaini'] ) ) {
		$Fechaini="NULL";
	} else {
		$FechaI=explode( '/', $_POST['Fechaini'] );
		$Fechaini='\''.$FechaI[2].'-'.$FechaI[1].'-'.$FechaI[0].'\'';
	}
	if ( empty( $_POST['Fechafin'] ) ) {
		$Fechafin="NULL";
	} else {
		$FechaF=explode( '/', $_POST['Fechafin'] );
		$Fechafin='\''.$FechaF[2].'-'.$FechaF[1].'-'.$FechaF[0].'\'';
	}*/

	$query = "SELECT lppe.id AS idprocedimientoporexamen,
					lcee.codigo_examen AS idexamen,
					lcee.nombre_examen AS nombreexamen,
					casd.id AS idarea,
				 	casd.nombrearea,
				 	lppe.nombreprocedimiento,
				 	lppe.unidades,
				 	lppe.rangoinicio,
				 	lppe.rangofin,
				 	TO_CHAR(lppe.fechaini::timestamp, 'DD/MM/YYYY') AS fechaini,
				 	TO_CHAR(lppe.fechafin::timestamp, 'DD/MM/YYYY')AS fechafin,
				 	CASE WHEN cex.id IS NULL THEN 'NULL'
				         ELSE cex.id::text
				    END AS idsexo,
				 	CASE WHEN cex.nombre IS NULL THEN 'Ambos'
				         ELSE cex.nombre
				    END AS sexovn,
				 	cre.id AS idedad,
				 	cre.nombre AS nombregrupoedad,
                                        (CASE WHEN lcee.condicion='H' THEN 'Habilitado'
						WHEN lcee.condicion='I' THEN 'Inhabilitado' END) AS cond1,
                                                
                                                (CASE WHEN lppe.habilitado='f' THEN 'Inhabilitado'
						WHEN lppe.habilitado='t' THEN 'Habilitado' END) AS cond,
                                                

						lppe.habilitado,
                                                lppe.id as idlppe,
                                                mnt4.id as idmnt4,
                                                lcee.condicion,
                                                lppe.orden
			  FROM lab_procedimientosporexamen                      lppe
			  INNER JOIN lab_conf_examen_estab 			lcee ON (lcee.id = lppe.id_conf_examen_estab)
			  INNER JOIN lab_plantilla                              lpla ON (lpla.id = lcee.idplantilla)
			  INNER JOIN mnt_area_examen_establecimiento            mnt4 ON (mnt4.id = lcee.idexamen)
			  INNER JOIN ctl_area_servicio_diagnostico              casd ON (casd.id = mnt4.id_area_servicio_diagnostico)
			  LEFT OUTER JOIN ctl_sexo                              cex  ON (cex.id  = lppe.idsexo AND cex.abreviatura != 'I')
			  LEFT OUTER JOIN ctl_rango_edad 			cre  ON (cre.id  = lppe.idrangoedad)
			  WHERE 
                           --lpla.idplantilla = 'E' 
                          --AND lcee.condicion = 'H'
                         --AND 
                         lppe.idestablecimiento = $lugar AND";

	$ban=0;
	//VERIFICANDO LOS POST ENVIADOS

	if ( !empty( $_POST['idarea'] ) && $_POST['idarea'] !== '0' ) {
		$query .= " casd.id = ".$_POST['idarea']." AND";
	}

	if ( !empty( $_POST['idexamen'] ) && $_POST['idexamen'] !== '0' ) {
		$query .= " lcee.id = ".$_POST['idexamen']." AND";
    }

	if ( !empty( $_POST['proce'] ) ) {
		$query .= " lppe.nombreprocedimiento ILIKE '%".$_POST['proce']."%' AND";
	}

	if ( !empty( $_POST['unidades'] ) ) {
		$query .= " lppe.unidades = '".$_POST['unidades']."' AND";
	}

	if ( !empty( $_POST['rangoini'] ) ) {
		$query .= " lppe.rangoinicio = ".$rangoini." AND";
	}

	if ( !empty( $_POST['rangofin'] ) ) {
		$query .= " lppe.rangofin = ".$rangofin." AND";
	}

	if ( !empty( $_POST['sexo'] ) && $_POST['sexo'] !== '0' ) {
		
                if ( $_POST['sexo']==3){
                    
                    $query .= //"  AND CASE WHEN cex.id IS NULL THEN 'NULL' ELSE cex.id::text END = '".$_POST['sexo']."'   ";
                " ((cex.id IS NULL) or (cex.id=".$_POST['sexo']."))        ";
                }
                else{
                    $query .=" cex.id=".$_POST['sexo']."         ";
                    
                }
                
        }

	if ( !empty( $_POST['redad'] ) && $_POST['redad'] !== '0' ) {
		$query .= " lppe.idrangoedad = ".$_POST['redad']." AND";
	}

	if ( !empty( $_POST['Fechaini'] ) ) { 
		$query .= " fechaini = ".$Fechaini." AND"; }

	if ( !empty( $_POST['Fechafin'] ) ) {
		$query .= " fechafin = ".$Fechafin." AND"; }

	if ( ( empty( $_POST['idarea'] ) || $_POST['idarea'] === '0') and ( empty( $_POST['idexamen'] ) || $_POST['idexamen'] === '0' ) and ( empty( $_POST['proce'] ) ) and ( empty( $_POST['unidades'] ) ) and ( empty( $_POST['rangoini'] ) )
		and ( empty( $_POST['rangofin'] ) ) and ( empty( $_POST['Fechafin'] ) ) and ( empty( $_POST['Fechaini'] ) ) and ( empty( $_POST['sexo'] ) || $_POST['sexo'] === '0') and ( empty( $_POST['redad'] ) || $_POST['redad'] === '0' ) ) {
		$ban=1;
	}

	if ( $ban==0 ) {
		$query = substr( $query , 0, strlen( $query )-3 );
	} else {
		$query = substr( $query , 0, strlen( $query )-6 );
	}

	$query_search = $query. " ORDER BY lcee.codigo_examen";
	//echo $query_search;
	//ENVIANDO A EJECUTAR LA BUSQUEDA!!
	//require_once("clsLab_Procedimientos.php");
	////para manejo de la paginacion
	$RegistrosAMostrar=5;
	$RegistrosAEmpezar=( $_POST['Pag']-1 )*$RegistrosAMostrar;
	$PagAct=$_POST['Pag'];

	/////LAMANDO LA FUNCION DE LA CLASE
	//$obje=new clsLab_Procedimientos;
	$consulta= $objdatos->consultarpagbus( $query_search, $RegistrosAEmpezar, $RegistrosAMostrar );

	//muestra los datos consultados en la tabla
	echo "<center >
               <table border = 1 style='width: 80%;'  class='table table-hover table-bordered table-condensed table-white'>
	           <thead>
                        <tr>
                                <th   aling='center'> Modificar</th>
                                 <th aling='center' > Habilitado</th>
                                <!--<th   aling='center'> Eliminar</th>-->
                                <th> IdExamen          </th>
                                <th> Examen            </th>
                                <th> Procedimiento     </th>
                                <th> Unidades          </th>
                                <th> Valores Normales  </th>
                                <th> Sexo              </th>
                                <th> Rango de Edad     </th>
                                <th> Fecha Inicio      </th>
                                <th> Fecha Finalización </th>
                                <th> Orden </th>
                        </tr>
                   </thead><tbody>
                    </center>";
	while ( $row = @pg_fetch_array( $consulta ) ) {
		/*echo "<tr>
                <td aling='center'><img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" onclick=\"pedirDatos('".$row['idprocedimientoporexamen']."')\"></td>
               	<!-- <td aling ='center'><img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" onclick=\"eliminarDato('".$row['idprocedimientoporexamen']."',$lugar)\"></td> -->
                <td width='6%'><span style='color: #0101DF;'>
                   	 <a style ='text-decoration:underline;cursor:pointer;' onclick='Estado(\"".$row['idlppe']."\",\"".$row['habilitado']."\")'>".$row['cond']."</a></td>
                    
                <td>".$row['idexamen']."</td> 
                <td>".htmlentities( $row['nombreexamen'] )."</td>
                <td>".htmlentities( $row['nombreprocedimiento'] )."</td>
                <td>".htmlentities( $row['unidades'] )."</td>
                <td>".$row['rangoinicio']."-".$row['rangofin']."</td>
                <td>".$row['sexovn']."</td>
                <td>".$row['nombregrupoedad']."</td>";
		if ( ( $row['fechaini']=="NULL" ) || ( $row['fechaini']=="00/00/0000" ) ||( empty( $row['fechaini'] ) ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechaini']."</td>";

		if ( ( empty( $row['fechafin'] ) ) || ( $row['fechafin']=="NULL" ) || ( $row['fechafin']=="00/00/0000" ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechafin']."</td>
		          ";
		echo"</tr>";*/
            
            
            $habilitado=$row['habilitado'] ;
                   // == "t") {
                
          //  }
                 if ($habilitado=="t")
                     {
                    // echo " if";
                     echo "<tr>
                    <td aling='center'>
                        <img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\"
			onclick=\"pedirDatos('".$row['idprocedimientoporexamen']."')\"> </td>
                   <!-- <td aling ='center'>
			 <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
			onclick=\"eliminarDato('".$row['idprocedimientoporexamen']."',$lugar)\"> </td> -->
                      <td width='6%'><span style='color: #0101DF;'>
                   	 <a style ='text-decoration:underline;cursor:pointer;' onclick='Estado(\"".$row['idlppe']."\",\"".$row['habilitado']."\")'>".$row['cond']."</a></td>
                    <td>".$row['idexamen']."</td>
                    <td>".htmlentities( $row['nombreexamen'] )."</td>
                    <td>".htmlentities( $row['nombreprocedimiento'] )."</td>
                    <td>".htmlentities( $row['unidades'] )."</td>
                    <td>".$row['rangoinicio']."-".$row['rangofin']."</td>";
		echo "<td>".$row['sexovn']."</td>
                    <td>".$row['nombregrupoedad']."</td>";
		if ( ( $row['fechaini']=="NULL" ) || ( $row['fechaini']=="00/00/0000" ) ||( empty( $row['fechaini'] ) ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechaini']."</td>";

		if ( ( empty( $row['fechafin'] ) ) || ( $row['fechafin']=="NULL" ) || ( $row['fechafin']=="00/00/0000" ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechafin']."</td>
			          ";
                 echo "<td>".$row['orden']."</td>
			          ";
		echo"</tr> ";
                     
                 }
                 else {
                    // echo " else";
                       echo "<tr>
                    <td aling='center'>
                        <img src='../../../Imagenes/Search.png' style=\"text-decoration:underline;cursor:pointer;\"
			onclick=\"pedirDatos(".$row['idprocedimientoporexamen'].")\"  height='40' width='50'> </td>
                   <!-- <td aling ='center'>
			 <img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\"
			onclick=\"eliminarDato('".$row['idprocedimientoporexamen']."',$lugar)\"> </td> -->
                      <td width='6%'>  ".$row['cond']."</td>
                    <td>".$row['idexamen']."</td>
                    <td>".htmlentities( $row['nombreexamen'] )."</td>
                    <td>".htmlentities( $row['nombreprocedimiento'] )."</td>
                    <td>".htmlentities( $row['unidades'] )."</td>
                    <td>".$row['rangoinicio']."-".$row['rangofin']."</td>";
		echo "<td>".$row['sexovn']."</td>
                    <td>".$row['nombregrupoedad']."</td>";
		if ( ( $row['fechaini']=="NULL" ) || ( $row['fechaini']=="00/00/0000" ) ||( empty( $row['fechaini'] ) ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechaini']."</td>";

		if ( ( empty( $row['fechafin'] ) ) || ( $row['fechafin']=="NULL" ) || ( $row['fechafin']=="00/00/0000" ) )
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		else
			echo "<td>".$row['fechafin']."</td>
			          ";
                 echo "<td>".$row['orden']."</td>
			          ";
		echo"</tr> ";
                     
                 }
                 
            
            
	}
	echo "</table>";
	//determinando el numero de paginas
	
	$NroRegistros= $objdatos->NumeroDeRegistrosbus( $query_search );
	$PagAnt=$PagAct-1;
	$PagSig=$PagAct+1;

	$PagUlt=$NroRegistros/$RegistrosAMostrar;

	//verificamos residuo para ver si llevar� decimales
	$Res=$NroRegistros%$RegistrosAMostrar;
	//si hay residuo usamos funcion floor para que me
	//devuelva la parte entera, SIN REDONDEAR, y le sumamos
	//una unidad para obtener la ultima pagina
	if ( $Res>0 ) $PagUlt=floor( $PagUlt )+1;

	echo "<table align='center'>
	    	<tr>
		   		<td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
		   	</tr>
		   	<tr>
		   		<td><a onclick=\"show_event_search('1')\">Primero</a> </td>";
	//// desplazamiento

	if ( $PagAct>1 )
		echo "<td> <a onclick=\"show_event_search('$PagAnt')\">Anterior</a> </td>";
	if ( $PagAct<$PagUlt )
		echo "<td> <a onclick=\"show_event_search('$PagSig')\">Siguiente</a> </td>";
	if ( $PagUlt > 0 )
		echo "<td> <a onclick=\"show_event_search('$PagUlt')\">Ultimo</a></td>";
	echo "</tr>
		  </table>";
        
                    echo " <center> <ul class='pagination'>";
                          for ($i=1 ; $i<=$PagUlt; $i++)
                                    {
                             
					 echo " <li ><a  href='javascript: show_event_search(".$i.")'>$i</a></li>";
                                     }
                    echo " </ul></center>";
        
	break;
       
       
        
}
 function existeOrden($idexa){
          $respuesta=0;
          $objdatos = new clsLab_Procedimientos;
          $consulta=$objdatos->llenarrangoproc($idexa);
          $hola=array();                      
                                while ($row=pg_fetch_array($consulta))
                                    {
                                       /* if($row['orden']==$index)
                                        {
                                            $respuesta=1;
                                        }else{
                                           $respuesta=0; 
                                        }
                                        echo $row['orden'];  */
                                    $hola[]=$row['orden'];
                                    }
                                    
           return $hola;                        
        }
    function areglo ($arr,$dato){
        $respuesta=0;
        $max = sizeof($arr);
        for ($index = 0 ; $index<$max; $index++) 
            {
               if($dato<>$arr[$index]){
                   $respuesta=0;//no mostrar
              }else{
                    $respuesta=1;//si mostrar
                    $index=$max;
                    
               } 
            }
            return $respuesta;    
    }    
?>
