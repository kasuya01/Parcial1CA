<?php session_start();
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
extract($_GET);

include ("clsReporteRegistroDiario.php");
//include ("clsSolicitudesProcesadas.php");
//creando los objetos de las clases

$objdatos = new clsReporteRegistroDiario();
$Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
$row_estab = pg_fetch_array($Consulta_Estab);
$Consulta_Area=$objdatos->Nombre_Area($idarea);
$row_area = pg_fetch_array($Consulta_Area);


//creando los objetos de las clases
$obj = new clsReporteRegistroDiario();
$resultgetExamnResult =$obj->getRegistroDiario($idarea, $d_fechadesde, $lugar);

?>
<script>
    
    function imprimir() {
        var obj = window.document.getElementById('imprimir')
        obj.hidden = true
        window.print() 
        obj.hidden = false
    }
</script>
<style>
    .tabla{
        border-collapse: collapse;
        font-size: 8px;
    }
</style>

<?php

$rslt="";
$rslt .= "<table border='1' class='tabla'>
    
        <tr>
            <td colspan='2' align='left' width='20%'><img id='Image1' style='WIDTH: 80px; HEIGHT: 55px' height='86' src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
            <td align='center' colspan='7' width='60%' class='Estilo5'>
                <p><strong>".$row_estab['nombre']."</strong></p>
                <p><strong>REGISTRO DIARIO DE ACTIVIDADES DE LABORATORIO</strong></p>
                <p><strong>&Aacute;REA DE ".htmlentities($row_area['nombre'])." </strong></p>
                <p><strong>F E C H A:  ".$d_fechadesde[8].$d_fechadesde[9]."/".$d_fechadesde[5].$d_fechadesde[6]."/".$d_fechadesde[0].$d_fechadesde[1].$d_fechadesde[2].$d_fechadesde[3]."</strong></p>
            </td>
            <td colspan='1' align='right' width='10%'><img id='Image3' style='WIDTH: 110px; HEIGHT: 55px' height='86' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
        </tr>
        
          <tr>
          <td>&nbsp;</td>
          <td colspan='4' align='center'>IDENTIFICACION DE LA PERSONA</td>
          <td>&nbsp;</td>
          <td colspan='2' align='center'>PROCEDIMIENTO</td>
          <td colspan='2' align='center'>RESULTADO</td>
          <tr>

          <tr>
          <td width='50px'>No. ORDEN</td>
          <td width='300px' align='center'>NOMBRE</td>
          <td>EDAD</td>                
          <td>SEXO</td>
          <td>EXPEDIENTE</td>
          <td>PROCED</td>
          <td align='center'>NOMBRE</td>
          <td>CODIGO</td>
          <td width='300px' align='center' colspan=2>COMENTARIO</td>
          <tr>";

    while ($row = pg_fetch_array($resultgetExamnResult)){
        $edad = str_replace('years','a<br>',$row['edad']);
        $edad = str_replace('year','a<br>',$edad);
        $edad = str_replace('mons','m<br>',$edad);
        $edad = str_replace('mon','m<br>',$edad);
        $edad = str_replace('days','d<br>',$edad);
        $edad = str_replace('day','d<br>',$edad);
        $rslt .= "<tr>
                  <td align='center'>".$row['numeromuestra']."</td>
                  <td>".strtoupper($row['paciente'])."</td>
                  <td width=30px>".$edad."</td>
                  <td align='center'>".substr($row['sexo'],0,1)."</td>
                  <td>".$row['idnumeroexp']."</td>
                  <td align='center'>".$row['idservicio']."</td>
                  <td>".$row['nombreexamen']."</td>
                  <td align='center'>".$row['idestandar']."</td>
                  <td colspan=2>&nbsp;</td>
                  </tr>";
    }
    $rslt .= "</table>";
echo $rslt;
echo "<input type='button' onclick='imprimir()' value='Imprimir' id='imprimir'>";