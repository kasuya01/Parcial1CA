<?php session_start();
include_once("clsSubElementosExamen.php");
$objdatos = new clsSubElementosExamen;
$Clases = new clsLabor_SubElementosExamen;
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

//variables POST
/*$idelemento=$_POST['idelemento'];
$idsubelemento=$_POST['idsubelemento'];
$subelemento=$_POST['subelemento'];
$elemento=$_POST['elemento'];*/
$Pag =$_POST['Pag'];
$opcion=$_POST['opcion'];
//$unidad=$_POST['unidad'];

//actualiza los datos del empleados

$usuario=1;
switch ($opcion) 
{
	case 1:  //INSERTAR	
		$idelemento=$_POST['idelemento'];
		$subelemento=$_POST['subelemento'];
		$elemento=$_POST['elemento'];
		//$unidad=$_POST['unidad'];
                //$sexo=$_POST['sexo'];
                //$redad=$_POST['redad'];
                $unidad=(empty($_POST['unidad'])) ? 'NULL' : "'" . pg_escape_string($_POST['unidad']) . "'"; 
                $sexo=(empty($_POST['sexo'])) ? 'NULL' : "'" . pg_escape_string($_POST['sexo']) . "'";        
                $redad=(empty($_POST['redad'])) ? 'NULL' : "'" . pg_escape_string($_POST['redad']) . "'"; 
                $rangoini=(empty($_POST['rangoini'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangoini']) . "'";
                $rangofin=(empty($_POST['rangofin'])) ? 'NULL' : "'" . pg_escape_string($_POST['rangofin']) . "'";
                $Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
		$Fechafin=(empty($_POST['Fechafin'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechafin']) . "'";              
		
        	if ($objdatos->insertar($idelemento,$unidad,$subelemento,$rangoini,$rangofin,$Fechaini,$Fechafin,$lugar,$sexo,$redad)==true) 
                //&& ($Clases->insertar_labo($idelemento,$unidad,$subelemento,$rangoini,$rangofin,$Fechaini,$Fechafin,$lugar,$sexo,$redad)==true)){
		//if ($Clases->insertar_labo($idelemento,$unidad,$subelemento,$Fechaini,$Fechafin,$lugar)==true){
			echo "Registro Agregado";
		//}
		else
			echo "No se pudo Ingresar el Registro";			
		
		
	
	break;
	case 2:  //MODIFICAR   
		$idsubelemento=$_POST['idsubelemento'];
		//$unidad=$_POST['unidad'];
		$subelemento=$_POST['subelemento'];
                $unidad=(empty($_POST['unidad'])) ? 'NULL' : "'" . pg_escape_string($_POST['unidad']) . "'"; 
                $sexo=(empty($_POST['sexo'])) ? 'NULL' : "'" . pg_escape_string($_POST['sexo']) . "'";        
                $redad=(empty($_POST['redad'])) ? 'NULL' : "'" . pg_escape_string($_POST['redad']) . "'"; 
                $rangoini=(empty($_POST['rangoini'])) ? 0 : "'" . pg_escape_string($_POST['rangoini']) . "'";
                $rangofin=(empty($_POST['rangofin'])) ? 0 : "" . pg_escape_string($_POST['rangofin']) . "";
                $Fechaini=(empty($_POST['Fechaini'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechaini']) . "'";
		$Fechafin=(empty($_POST['Fechafin'])) ? 'NULL' : "'" . pg_escape_string($_POST['Fechafin']) . "'";   
              
		//echo $rangoini."-".$rangofin;
		if ($objdatos->actualizar($idsubelemento,$unidad,$subelemento,$rangoini,$rangofin,$Fechaini,$Fechafin,$lugar,$sexo,$redad)==true) 
                   //&& ($Clases->actualizar_labo($idsubelemento,$unidad,$subelemento,$rangoini,$rangofin,$Fechaini,$Fechafin,$lugar,$sexo,$redad)==true)){
			echo "Registro Actualizado"; 
		else 
                        echo "No se pudo actualizar el registro";
	break;
	case 3:  //ELIMINAR 
		//Vefificando Integridad de los datos
		$idsubelemento=$_POST['idsubelemento'];
		if ($objdatos->eliminar($idsubelemento)==true){ 
                        //&& ($Clases->eliminar_labo($idsubelemento)== true)){		
			echo "Registro Eliminado" ;		
		}
		else{
			echo "El registro no pudo ser eliminado";
		}			
		
	break;
	case 4:// PAGINACION
		$idelemento=$_POST['idelemento'];
	
		$Pag =$_POST['Pag'];
		////para manejo de la paginacion
		$RegistrosAMostrar=4;
		$RegistrosAEmpezar=($_POST['Pag']-1)*$RegistrosAMostrar;
		$PagAct=$_POST['Pag'];
	
		 /////LAMANDO LA FUNCION DE LA CLASE 
		$consulta= $objdatos->consultarpag($idelemento,$RegistrosAEmpezar, $RegistrosAMostrar);

		//muestra los datos consultados en la tabla
	  echo "<table border = 1 align='center' class='StormyWeatherFormTABLE'>
	        <tr>
                    <td class='CobaltFieldCaptionTD' aling='center'> Modificar</td>";
               echo "     <td class='CobaltFieldCaptionTD' aling='center'> Eliminar</td>
                    <td class='CobaltFieldCaptionTD'> SubElemento </td>
                    <td class='CobaltFieldCaptionTD'> Unidad </td>
                    <td class='CobaltFieldCaptionTD'> Valores Normales </td>
                    <td class='CobaltFieldCaptionTD'> Rango Edad </td>
                    <td class='CobaltFieldCaptionTD'> Sexo</td>
		    <td class='CobaltFieldCaptionTD'> Fecha Inicio</td>	
                    <td class='CobaltFieldCaptionTD'> Fecha Fin</td>
                    <td class='CobaltFieldCaptionTD'> Atar a cat&aacute;logo</td>
	        </tr>";
    while($row = pg_fetch_array($consulta)){
          echo "<tr>
                    <td aling='center'> 
			<img src='../../../Iconos/modificar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
			onclick=\"pedirDatosSubElementos('".$row['id']."')\"> </td>
                    <td aling ='center'> 
			<img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" 
			onclick=\"eliminarDatoSubElemento('".$row['id']."')\"> </td>
                    <td>".htmlentities($row['subelemento'])."</td>";
           if (!empty($row['unidad']))            
              echo" <td>".htmlentities($row['unidad'])."</td>";
           else
               echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
               
          if ((empty($row['rangoinicio'])) AND (empty($row['rangofin'])))
              echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";
          else
              echo "<td>".$row['rangoinicio']."-".$row['rangofin']."</td>";
              
              echo "<td>".$row['nombreedad']."</td>";
                     if (empty($row['nombresexo']))
                            echo "<td> Ambos </td>";
                        else
                            echo "<td>".$row['nombresexo']."</td>";
                        
                           
                   
                 echo "   <td>".htmlentities($row['fechaini'])."</td>";
	  if (($row['fechafin']=="00-00-0000") ||($row['fechafin']=="(NULL)") ||(empty($row['fechafin'])) )
	      echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
	  else 	
	      echo "<td>".htmlentities($row['fechafin'])."</td>";
          
          if ($row['catalogo'] != '{NULL}') { 
                echo "<td><input type='button'  value='Cat&aacute;logo' onclick='popup(".'"consulta_SubElemento.php?id_subelemento='.$row['id'].'"'.")' /></td>";
          }
          else {
              echo "<td><input type='button' value='...'  onclick='popup(".'"consulta_SubElemento.php?id_subelemento='.$row['id'].'"'.")' /></td>";
          }
          
	  echo "</tr>";
		}
	  echo "</table>"; 

	//determinando el numero de paginas
	 $NroRegistros= $objdatos->NumeroDeRegistros($idelemento);
	 $PagAnt=$PagAct-1;
	 $PagSig=$PagAct+1;
		 
	 $PagUlt=$NroRegistros/$RegistrosAMostrar;
		 
	 //verificamos residuo para ver si llevar� decimales
	 $Res=$NroRegistros%$RegistrosAMostrar;
	 //si hay residuo usamos funcion floor para que me
	 //devuelva la parte entera, SIN REDONDEAR, y le sumamos
	 //una unidad para obtener la ultima pagina
	 if($Res>0) $PagUlt=floor($PagUlt)+1;
	  echo "<table align='center'>
	        <tr>
	     		<td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
		</tr>
		<tr>
			<td><a onclick=\"show_subelemento('1',$idelemento)\">Primero</a> </td>";
		//// desplazamiento
        
	  if($PagAct>1) 
	           echo "<td> <a onclick=\"show_subelemento('$PagAnt',$idelemento)\">Anterior</a> </td>";
	  if($PagAct<$PagUlt)  
	    echo "<td><a onclick=\"show_subelemento('$PagSig',$idelemento)\">Siguiente</a></td>";
		echo "<td><a onclick=\"show_subelemento('$PagUlt',$idelemento)\">Ultimo</a></td>";
         echo "</tr>
	         </table>";
	break;
	case 5:
		              
                $idsubelemento=$_POST['idsubelemento'];
		
                $consulta=$objdatos->consultarid($idsubelemento);
		$row = pg_fetch_array($consulta);
        
		//valores de las consultas
		$unidad=$row['unidad'];
		$subelemento=$row['subelemento'];
		$idelemento=$row['id'];
		$elemento=$row['elemento'];
		$FechaIni=$row['fechaini'];
		$FechaFin=$row['fechafin'];
		$idsexo=$row['idsexo'];
                $nombresexo=$row['nombresexo'];
                $idedad=$row['idedad'];
                $rangoedad=$row['nombreedad'];
                $rangoini=$row['rangoinicio'];
                $rangofin=$row['rangofin'];
                if (empty($row['idsexo'])){
                 $idsexo=0;
                $nombresexo="Ambos";} 
	$imprimir="<form name= 'frmModificar' >
                        <table width='90%' border='0' align='center' class='StormyWeatherFormTABLE'>
                            <tr>
                                <td colspan='4' class='CobaltFieldCaptionTD' align='center'><h3><strong>SubElementos de  Examen</strong></h3></td>
                            </tr>	
                            <tr>
                                <td class='StormyWeatherFieldCaptionTD'>Elemento</td>
                                <td colspan='3' class='StormyWeatherDataTD'>
                                        <input name='idelemento' type='hidden' id='idelemento' value='".$idelemento."' >
                                        <input name='idsubelemento' type='hidden' id='idsubelemento' value='".$idsubelemento."' >
                                        <input name='txtelemento' type='text' id='txtelemento' value='".htmlentities($elemento)."' size='60' disabled='disabled'>
                                </td>
                            </tr>
                            <tr>
                                <td width='17%'  class='StormyWeatherFieldCaptionTD'>SubElemento</td>
                                <td colspan='3' width='63%' class='StormyWeatherDataTD'>
                                        <input name='txtsubelemento' type='text' id='txtsubelemento' value='".htmlentities($subelemento)."' size='60'>
                                </td>
                            </tr>
                            <tr>
                                <td  class='StormyWeatherFieldCaptionTD'>Sexo</td>
                                <td colspan='3' class='StormyWeatherDataTD'>
                                    <select id='cmbSexo' name='cmbSexo' size='1' >
                                             <option value='0'>Ambos</option>";

                                                $consultaS= $objdatos->consultarsexo();
                                                while($row =pg_fetch_array($consultaS)){
                                                    $imprimir.= "<option value='" . $row[0]. "'>". $row[1] ."</option>";
                                                }
                                               $imprimir.= "<option value='" . $idsexo . "' selected='selected'>" .$nombresexo. "</option>";

                             $imprimir.="</select>		  
                                </td>        
                            </tr>
                            <tr>
                                <td class='StormyWeatherFieldCaptionTD'>Rango Edad</td>
                                <td colspan='3' class='StormyWeatherDataTD'>
                                    <select id='cmbEdad' name='cmbEdad' size='1' >
                                        <option value='0' >--Seleccione un Rango de Edad--</option>";

                                            $conEdad = $objdatos->RangosEdades();
                                            while($row = pg_fetch_array($conEdad)){
                                                 $imprimir.="<option value='" . $row[0]. "'>". $row[1] . "</option>";
                                            }
                                             $imprimir.= "<option value='" . $idedad . "' selected='selected'>" .$rangoedad. "</option>";
                            $imprimir.="</select>		  
                                </td>        
                            </tr>
                            <tr>
                                <td width='17%' class='StormyWeatherFieldCaptionTD'>Unidad</td>
                                <td colspan='3' width='63%' class='StormyWeatherDataTD'>
                                    <input name='txtunidad' type='text' id='txtunidad' value='".htmlentities($unidad)."' size='20'>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='4' class='StormyWeatherDataTD'>
                                    <fieldset><legend><span>Rangos</span></legend>
                                    <table width='200' border='0' align='center' class='StormyWeatherFormTABLE'>
                                    <tr>
                                            <td class='StormyWeatherFieldCaptionTD'>Inicio</td>            
                                            <td class='StormyWeatherDataTD'>
                                                <input name='txtrangoinicio' type='text' id='txtrangoini' value='".$rangoini."' size='8'>
                                            </td>
                                            <td class='StormyWeatherFieldCaptionTD'>Fin </td>
                                            <td class='StormyWeatherDataTD'>
                                                <input name='txtrangofin' type='text' id='txtrangofin' value='".$rangofin." ' size='8'></td>
                                    </tr>
                                    </table>
                                    </fieldset>               
                                </td>
                            </tr>
                            <tr>
				<td width='10%' class='StormyWeatherFieldCaptionTD'>Fecha Inicio</TD>
				<td width='35%' class='StormyWeatherDataTD'>
					<input name='txtFechainicio' type='text' id='txtFechainicio' size='10' value='".htmlentities($FechaIni)."'>dd/mm/aaaa
				</td>
				<td  class='StormyWeatherFieldCaptionTD'>Fecha Final</TD>
				<td  class='StormyWeatherDataTD'>
                                        <input name='txtFechaFin' type='text' id='txtFechaFin' size='10' value='".htmlentities($FechaFin)."' >dd/mm/aaaa
				</td>
                            </tr>
                            <tr>
                                 <td colspan='4' class='StormyWeatherDataTD' align='right'>
                                    <input type='button' name='Submit' value='Actualizar' onClick='enviarDatosSubElemento();'>
                                    <input type='button' name='btnCancelar' value='Cerrar' onClick='window.close();'>
                                 </td>
                             </tr>
                        </table>
                    </form>";
		echo $imprimir;
	break;
		
}

?>