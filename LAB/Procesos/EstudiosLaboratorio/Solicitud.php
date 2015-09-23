<?php
include_once("../../../Conexion/ConexionBD.php");
include_once("ClaseSolicitud.php"); //Agregamos el Archivo con las clases y funciones a utilizar
@session_start();
$ROOT_PATH = $_SESSION['ROOT_PATH'];
// Creamos un objeto Conexion, Paciente, Laboratorio
$Conexion = new ConexionBD;
//Abrimos la Conexion
$Conectar = $Conexion->conectar();
$IdNumeroExp = $_GET["IdNumeroExp"];
$IdEstablecimiento = $_GET["IdEstablecimiento"]; //IdEstablecimiento solicitante
$lugar = $_GET["lugar"]; //IdEstablecimiento local
//echo 'Idestab:'.$IdEstablecimiento.'<br/>Lugar:'.$lugar.'<br\>';
//$IdSubServicio = $_GET["IdSubServicio"];
$IdSubServicio =  isset($_GET['IdSubServicio']) ? $_GET['IdSubServicio'] : null ;
//$IdEmpleado = $_GET["IdEmpleado"];
$IdEmpleado = isset($_GET['IdEmpleado']) ? $_GET['IdEmpleado'] : null ;
$IdUsuarioReg = $_SESSION['Correlativo'];
$FechaConsulta = $_GET["FechaConsulta"];
$IdCitaServApoyo = $_GET["IdCitaServApoyo"];
$sexo = $_GET["Sexo"];
$idexpediente = $_GET["idexpediente"];
//echo '<br\>.Idexpediente: '.$idexpediente.' IdNumeroExp:'.$IdNumeroExp. '  numhistorial: '.$_GET["IdHistorialClinico"].'  issethist: '.isset( $_GET["IdHistorialClinico"]);
//IdHistorialClinico = $_GET["IdHistorialClinico"];
$IdHistorialClinico = isset($_GET['IdHistorialClinico']) ? $_GET['IdHistorialClinico'] : null ;
//echo 'idexp: '.$idexpediente;
$FechaSolicitud = $FechaConsulta;
/* PARA OBTENER LA IP REAL DE LA PC QUE SE CONECTA */
if (!empty($_SERVER['HTTP_CLIENT_IP']))
    $ippc = $_SERVER['HTTP_CLIENT_IP'];
else
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    $ippc = $_SERVER['HTTP_X_FORWARDED_FOR'];
else
    $ippc = $_SERVER['REMOTE_ADDR'];
/* * *************************************************************** */
$Historial = new CrearHistorialClinico;
   if (!isset( $_GET["IdHistorialClinico"])|| $IdHistorialClinico==''){
    $IdHistorialClinico = $Historial->HistorialClinico($IdNumeroExp, $IdEstablecimiento, $IdSubServicio, $IdEmpleado, $FechaConsulta, $_SESSION['Correlativo'], $ippc, $idexpediente, $lugar);
    } 
$_SESSION["IdNumeroExp"] = $IdNumeroExp;
$_SESSION["idexpediente"] = $idexpediente;
$_SESSION["IdHistorialClinico"] = $IdHistorialClinico;
$_SESSION["Fecha"] = $FechaSolicitud;
$_SESSION["FechaConsulta"]=$FechaConsulta;
$_SESSION["IdUsuarioReg"] = $IdUsuarioReg;
$_SESSION["IdEstablecimiento"] = $IdEstablecimiento;
$_SESSION["lugar"] = $lugar;
//echo 'Ĺugar'.$lugar.'  idhistclin'.$IdHistorialClinico.'<br/>';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Solicitud de Estudios Para Laboratorio Clínico</title>
        <link rel="stylesheet" type="text/css" href="./Estilo.css">
        <?php include_once $ROOT_PATH."/public/css.php";?>
        <?php include_once $ROOT_PATH."/public/js.php";?>
            <script languaje="javascript">
               var band=0;
             //  var id_solicitud=null;
            </script>
            <script language="javascript" src="./ajax.js"></script>
    </head>


    <body>
        
        <div class="panel panel-primary">
        <table  cellspacing="1" cellpadding="2" border="1" align="justify" width="100%"  class="table table-bordered table-condensed table-white no-v-border">
            <thead><tr>
                   
                <!--<td colspan='7'color='white' nowrap><strong><font color="white">P R U E B A S &nbsp;&nbsp;&nbsp; I N D I V I D U A L E S  </font></strong>-->
                    <td colspan='6'color='white' style="background-color: #428bca" nowrap>
                        <img src="../../../Imagenes/paisanito_white.png" class="pull-left" style="padding: 5px; height: 80px;" />
                        <strong><font color="white"> <div class="panel-heading">
                                    <center>
                                        <h2><strong>SOLICITUD A LABORATORIO CLINICO</strong></h2>
                                        P R U E B A S &nbsp;&nbsp;&nbsp; I N D I V I D U A L E S
                                    </center></div>     </font></strong>
                </td>
                </tr></thead>

            <?php
//Llamar a funcion para buscar las areas que tiene configurado el establecimiento
            $areas = $Historial->buscarareas($lugar);
           // echo 'num areas: '.pg_num_rows($areas).' areas:'.$areas.'<br/>';
            echo "<form  method='post' name='Solicitud'>";
            $i=0;
            //$row22=pg_fetch_array($areas);
            //echo 'row1: '.$row22['nombrearea'];
            while ($rowar = @pg_fetch_array($areas)) {
                //echo '<br>i got here lugar:'.$lugar.'  -rowar[id]: '.$rowar['id'];

                $examen = $Historial->busca_mnt_area_exa_est($lugar, $rowar['id'], $sexo, $IdHistorialClinico, $IdEstablecimiento);
                //echo 'cant: '.pg_num_rows($examen);
                if (pg_num_rows($examen) > 0) {
                    echo "<thead><tr><td colspan='6'><b>" . ($rowar['nombrearea']) . "</b></td></tr></thead><tbody>";
                    while ($ResultadoExamenes = pg_fetch_array($examen)) {
                        //Primera Columna
                        echo "<tr>
                                <td style='width:3%'>
                                    <input type='checkbox' name='Examenes'  Id='Examenes" . $i . "' value='" . $ResultadoExamenes['idconf'] . "' onclick=\"MostrarLista(".$ResultadoExamenes['idconf'].",$i)\" />
                                </td>";
                        echo "<td style='width:23%'><b>" . $ResultadoExamenes['nombre_examen'] . "</b>
                                    <input type='hidden' id='Nombre" . $ResultadoExamenes['idconf'] . "' value='" . $ResultadoExamenes['nombre_examen'] . "'>
                                </td>";
                        echo "<td style='width:23%'><div id='" . $ResultadoExamenes['idconf'] . "'></div>";
                        echo "<div id='O" . $ResultadoExamenes['idconf'] . "'></div></td>";
                        $i++;
                        // SEGUNDA COLUMNA
			if($ResultadoExamenes=pg_fetch_array($examen)){
                            echo "<td style='width:3%'><input type='checkbox' name='Examenes'  Id='Examenes" . $i . "' value='".$ResultadoExamenes['idconf']."' onclick=\"MostrarLista(".$ResultadoExamenes['idconf'].",$i)\" /></td>";
                            echo "<td style='width:23%'><b>".$ResultadoExamenes['nombre_examen']."</b>
                            <input type='hidden' id='Nombre".$ResultadoExamenes['idconf']."' value='".$ResultadoExamenes['nombre_examen']."'></td>";
                            echo "<td style='width:23%'><div  id='".$ResultadoExamenes['idconf']."'></div>";
                            echo "<div  id='O".$ResultadoExamenes['idconf']."'></div>";
                            echo "</td></tr>";
                            $i++;
                        }else
                            echo "<td colspan='3'></td></tr>";


                    }
                    echo '</tbody>';
                }//fin while examenes
            }//Fin while areas
            echo "</table>
              <input type='hidden' name='IdNumeroExp' Id='IdNumeroExp' value='".$IdNumeroExp."'>
              <input type='hidden' name='idexpediente' Id='idexpediente' value='".$idexpediente ."'>
              <input type='hidden' name='IdHistorialClinico' Id='IdHistorialClinico' value='".$IdHistorialClinico."'>
              <input type='hidden' name='FechaSolicitud' Id='FechaSolicitud' value='".$FechaSolicitud."'>
              <input type='hidden' name='IdUsuarioReg' Id='IdUsuarioReg' value='".$IdUsuarioReg."'>
              <input type='hidden' name='IdCitaServApoyo' Id='IdCitaServApoyo' value='".$IdCitaServApoyo."'>
              <input type='hidden' name='IdEstablecimiento' Id='IdEstablecimiento' value='".$IdEstablecimiento."'>
              <input type='hidden' name='lugar' Id='lugar' value='".$lugar."'>
              <input type='hidden' name='TipoSexo' Id='TipoSexo' value='".$sexo."'>";
                
            echo "
              <br>
              <table  class='table table-bordered table-condensed table-white'>
              <tr><td colspan='6'><div id='Resultados'></div></td></tr>
              <tr><td colspan='6' align='right' >
              
              <span class='glyphicon glyphicon-chevron-right'></span>
              <span class='glyphicon glyphicon-chevron-right'></span>
              <span class='glyphicon glyphicon-chevron-right'></span>
              <span class='glyphicon glyphicon-chevron-right'></span>
              <button type='button' class='btn btn-primary' id='Enviar' onclick='GuardarSolicitud(); '><span class='glyphicon glyphicon-share-alt'></span> Enviar Solicitud</button>
              <button type='button' class='btn btn-primary'  onclick='window.close();'><span class='glyphicon glyphicon-remove-circle'></span> Cancelar Solicitud</button></td></tr>";    
                  
          
            /* $SQL="Select lab_areas.IdArea, NombreArea from lab_areas
              inner join lab_areasxestablecimiento on lab_areas.IdArea=lab_areasxestablecimiento.IdArea
              where condicion= 'H'
              and IdEstablecimiento=".$_SESSION['Lugar']."
              and Administrativa='N'
              order by NombreArea Asc";  //SQL para Extraer todas las areas de Laboratorio
              // echo $SQL;

              if($Conectar==true){
              $Ejecutar = mysql_query($SQL) or die('La consulta 2 fall&oacute;: ' . mysql_error());
              echo "<form  method='post' name='Solicitud'>";
              $i=0;
              while($Resultado=mysql_fetch_array($Ejecutar))	{

              // Primera Columna
              echo "<tr><td colspan='7' class='TdAreas'><b>".htmlentities($Resultado['NombreArea'])."</b></td></tr>";
              //SQL para Extraer todas las areas de Laboratorio */
            /* 			$SQL2="Select lab_examenes.IdExamen,NombreExamen from lab_examenes
              inner join lab_examenesxestablecimiento on lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
              Where Idarea='".$Resultado['IdArea']."' and Condicion='H' and IdEstablecimiento=".$_SESSION['Lugar']." AND (IdSexo=$sexo or IdSexo=3) AND ubicacion=0 order by NombreExamen asc";

              //Extraer los examenes para cada area
              $EjecutarExamenes = mysql_query($SQL2) or die('La consulta 3 fall&oacute;: ' . mysql_error());

              while($ResultadoExamenes=mysql_fetch_array($EjecutarExamenes))	{ */

            // PRIMERA  COLUMNA

            /*
              // SEGUNDA COLUMNA
              if($ResultadoExamenes=mysql_fetch_array($EjecutarExamenes)){
              echo "<td class='TdCheck'><input type='checkbox' name='Examenes'  Id='Examenes".$i."'
              value='".$ResultadoExamenes['IdExamen']."'
              onclick=\"MostrarLista('$ResultadoExamenes[IdExamen]',$i)\" /></td>";
              echo "<td  class='TdExamenNombre'><b>".htmlentities($ResultadoExamenes['NombreExamen'])."</b>
              <input type='hidden' id='Nombre".$ResultadoExamenes['IdExamen']."' value='".$ResultadoExamenes['NombreExamen']."'></td>";
              echo "<td  class='TdExamenList'><div  id='".$ResultadoExamenes['IdExamen']."'></div>";
              echo "<div  id='O".$ResultadoExamenes['IdExamen']."'></div>";
              echo "</td></tr>";
              $i++;
              }else
              echo "<td colspan='3' class='TdExamenNombre'></td></tr>";
              } // FIn While para sacar Examenes

              }
              echo "
              </table><input type='hidden' name='IdNumeroExp' Id='IdNumeroExp' value='".$IdNumeroExp."'>
              <input type='hidden' name='IdHistorialClinico' Id='IdHistorialClinico' value='".$IdHistorialClinico."'>
              <input type='hidden' name='FechaSolicitud' Id='FechaSolicitud' value='".$FechaSolicitud."'>
              <input type='hidden' name='IdUsuarioReg' Id='IdUsuarioReg' value='".$IdUsuarioReg."'>
              <input type='hidden' name='IdCitaServApoyo' Id='IdCitaServApoyo' value='".$IdCitaServApoyo."'>
              <input type='hidden' name='IdEstablecimiento' Id='IdEstablecimiento' value='".$IdEstablecimiento."'>
              <input type='hidden' name='TipoSexo' Id='TipoSexo' value='".$sexo."'>";


              echo "<br><table class='General'>
              <tr><td colspan='6'><div id='Resultados'></div></td></tr>
              <tr><td colspan='6' align='right' >
              <img src='../imagenes/flecha.jpg' align='top'  Alt='Click  en el Botón Para Enviar Solicitud' />
              <img src='../imagenes/flecha.jpg' align='top'  Alt='Click  en el Botón Para Enviar Solicitud' />
              <img src='../imagenes/flecha.jpg' align='top'  Alt='Click  en el Botón Para Enviar Solicitud' />
              <input value='ENVIAR SOLICITUD' type='button' class='boton' id='Enviar' onclick='GuardarSolicitud(); '/>
              <input value='CANCELAR SOLICITUD' type='button' class='boton' onclick='window.close();'/></td></tr>";


              }// FIn If Conectar */
            echo "</form>";
            ?>

        </table>
        </div>
    </body>
</html>
