
<?php
//session_start();

include_once("clsConsultarElementos.php");
//$nivel=$_SESSION['NIVEL'];
//$usuario=$_SESSION['correlativo'];
//consulta los datos por su id
//$idexamen=$_POST['idexamen'];
$idexamen='HEM001';
$obj = new clsConsultarElementos;
$consulta=$obj->LeerElementosExamen($idexamen);

$imprimir="<table width='60%' border='0' align='center'>
			 <tr>
			 <td>";
while($row = mysql_fetch_array($consulta))
{
	$imprimir.= "<tr>
				  <td>".$row['Elemento']."</td>
				 </tr>";
  if($row['SubElemento']=="S")
  {
	  $consulta2=$obj->LeerSubElementosExamen($row['IdElemento']);
	  $imprimir.= "<tr>
					  <td width='25%'></td>
					  <td width='25%'>Resultado</td>
					  <td width='25%'>Unidades</td>
					 </tr>";
	  while($rowsub = mysql_fetch_array($consulta2))
	   {
		   $imprimir.= "<tr>
					  <td width='30%'>".$rowsub['SubElemento']."</td>
					  <td width='25%'><input name='txtindicacion3' type='text' id='txtindicacion3'></td>
					  <td width='25%'>".$rowsub['Unidad']."</td>
					 </tr>";
		}
	}
}
$imprimir.="</table>";
echo $imprimir;

