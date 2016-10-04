<?php 
$resultado = $_GET['valorCaja1'] + $GET['valorCaja2']; 
  $jsonresponse['data']=$resultado;
  $jsonresponse['status']=true;
//echo $resultado;
  echo $json_encode($jsonresponse);
  ?>
