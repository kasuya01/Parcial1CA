<?php
include_once("../../../Conexion/ConexionBD.php");
include_once("ClaseSolicitud.php"); //Agregamos el Archivo con las clases y funciones a utilizar
@session_start();
// Creamos un objeto Conexion, Paciente, Laboratorio
$Conexion = new ConexionBD;
//Abrimos la Conexion
$Conectar = $Conexion->conectar();
$IdNumeroExp = $_GET["IdNumeroExp"];
$IdEstablecimiento = $_GET["IdEstablecimiento"]; //IdEstablecimiento solicitante
$lugar = $_GET["lugar"]; //IdEstablecimiento local
//echo 'Idestab:'.$IdEstablecimiento.'<br/>Lugar:'.$lugar.'<br\>';
$IdSubServicio = $_GET["IdSubServicio"];
$IdEmpleado = $_GET["IdEmpleado"];
$IdUsuarioReg = $_SESSION['Correlativo'];
$FechaConsulta = $_GET["FechaConsulta"];
$IdCitaServApoyo = $_GET["IdCitaServApoyo"];
$sexo = $_GET["Sexo"];
$idexpediente = $_GET["idexpediente"];
//echo '<br\>.Idexpediente: '.$idexpediente.' IdNumeroExp:'.$IdNumeroExp;
//$IdHistorialClinico = $_GET["IdHistorialClinico"];
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
            <script language="javascript" src="./ajax.js"></script>
    </head>


    <body>
        <table width="100%">
            <tr>
                <td><img src="../../../Imagenes/paisanito.png"  width="124" height="80" /></td>
                <td width="80%" align="center" class="TdTitulo1"><strong>SOLICITUD A LABORATORIO CLINICO</strong></td>
            </tr>
        </table>
        <table  cellspacing="1" cellpadding="2" border="0" align="justify" width="100%" class="General">
            <tr>
                <td colspan='7' class='TdTitulo' color='white' nowrap><strong><font color="white">P R U E B A S &nbsp;&nbsp;&nbsp; I N D I V I D U A L E S  </font></strong>
                </td>
            </tr>

            <?php
//Llamar a funcion para buscar las areas que tiene configurado el establecimiento
            $areas = $Historial->buscarareas($lugar);
           // echo 'num areas: '.pg_num_rows($areas).'<br/>';
            echo "<form  method='post' name='Solicitud'>";
            $i=0;
            while ($rowar = pg_fetch_array($areas)) {
               // echo 'i got here ';

                $examen = $Historial->busca_mnt_area_exa_est($lugar, $rowar['id'], $sexo, $IdHistorialClinico, $IdEstablecimiento);
                if (pg_num_rows($examen) > 0) {
                    echo "<tr><td colspan='7' class='TdAreas'><b>" . ($rowar['nombrearea']) . "</b></td></tr>";
                    while ($ResultadoExamenes = pg_fetch_array($examen)) {
                        //Primera Columna
                        echo "<tr>
                                <td class='TdCheck'>
                                    <input type='checkbox' name='Examenes'  Id='Examenes" . $i . "' value='" . $ResultadoExamenes['idconf'] . "' onclick=\"MostrarLista(".$ResultadoExamenes['idconf'].",$i)\" />
                                </td>";
                        echo "<td  class='TdExamenNombre'><b>" . $ResultadoExamenes['nombre_examen'] . "</b>
                                    <input type='hidden' id='Nombre" . $ResultadoExamenes['idconf'] . "' value='" . $ResultadoExamenes['nombre_examen'] . "'>
                                </td>";
                        echo "<td class='TdExamenList'><div id='" . $ResultadoExamenes['idconf'] . "'></div>";
                        echo "<div id='O" . $ResultadoExamenes['idconf'] . "'></div></td>";
                        $i++;
                        // SEGUNDA COLUMNA
			if($ResultadoExamenes=pg_fetch_array($examen)){
                            echo "<td class='TdCheck'><input type='checkbox' name='Examenes'  Id='Examenes" . $i . "' value='".$ResultadoExamenes['idconf']."' onclick=\"MostrarLista(".$ResultadoExamenes['idconf'].",$i)\" /></td>";
                            echo "<td  class='TdExamenNombre'><b>".$ResultadoExamenes['nombre_examen']."</b>
                            <input type='hidden' id='Nombre".$ResultadoExamenes['idconf']."' value='".$ResultadoExamenes['nombre_examen']."'></td>";
                            echo "<td  class='TdExamenList'><div  id='".$ResultadoExamenes['idconf']."'></div>";
                            echo "<div  id='O".$ResultadoExamenes['idconf']."'></div>";
                            echo "</td></tr>";
                            $i++;
                        }else
                            echo "<td colspan='3' class='TdExamenNombre'></td></tr>";


                    }
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
              <table class='General'>
              <tr><td colspan='6'><div id='Resultados'></div></td></tr>
              <tr><td colspan='6' align='right' >
              <img src='../../../Imagenes/flecha.jpg' align='top'  Alt='Click  en el Botón Para Enviar Solicitud' />
              <img src='../../../Imagenes/flecha.jpg' align='top'  Alt='Click  en el Botón Para Enviar Solicitud' />
              <img src='../../../Imagenes/flecha.jpg' align='top'  Alt='Click  en el Botón Para Enviar Solicitud' />
              <input value='ENVIAR SOLICITUD' type='button' class='boton' id='Enviar' onclick='GuardarSolicitud(); '/>
              <input value='CANCELAR SOLICITUD' type='button' class='boton' onclick='window.close();'/></td></tr>";    
                  
          
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

    </body>
</html>
