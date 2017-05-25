<?php

include_once("../../../Conexion/ConexionBD.php");

////////////////////////////////////////////////////////////////////////
///     CLASE DE PACIENTES								            ////
////////////////////////////////////////////////////////////////////////
class Comunicacion {

   //Método constructor
   function Comunicacion() {

   }

   function ConsultaEnvioHL7($idestab) {
   $Conexion = new ConexionBD();
   $conectar = $Conexion->conectar();
   if ($conectar == true) {
      $SQL = "select * from lab_proceso_establecimiento where id_proceso_laboratorio=11 and activo=true and id_establecimiento=$idestab";
      $Resultado = pg_query($SQL);
      if (!$Resultado)
         return false;
      else {
         $num = pg_num_rows($Resultado);
         return $num;
      }
   }// fin if conectar
}//fin funcion ConsultaEnvioHL7
   function ConsultaTipoConexion($idsuministrante) {
   $Conexion = new ConexionBD();
   $conectar = $Conexion->conectar();
   if ($conectar == true) {
      $SQL = "SELECT id_tipo_conexion from lab_suministrante where id= $idsuministrante";
      $Resultado = pg_query($SQL);
      if (!$Resultado)
         return false;
      else {
         return $Resultado;
      }
   }// fin if conectar
}//fin funcion ConsultaEnvioHL7

}
?>
