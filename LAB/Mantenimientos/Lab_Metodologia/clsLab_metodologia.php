<?php 
include_once("../../../Conexion/ConexionBD.php");
//include_once("../../../Conexion/ConexionBDLab.php");

class clsLab_metodologia
{
     //CONSTRUCTOR
	 function clsLab_metodologia(){
	 }
      //funciones de metodologias
          //RECUPERAR EXAMENES POR AREA
	 function ExamenesPorArea($idarea,$lugar)
	 {
		$con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	      $query = " SELECT distinct(lab_conf_examen_estab.id),lab_conf_examen_estab.nombre_examen
 FROM lab_conf_examen_estab
 INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
 join lab_examen_metodologia t03 on (lab_conf_examen_estab.id=t03.id_conf_exa_estab)
 WHERE mnt_area_examen_establecimiento.id_area_servicio_diagnostico=$idarea
 AND lab_conf_examen_estab.idplantilla=1 AND lab_conf_examen_estab.condicion='H'
 AND mnt_area_examen_establecimiento.id_establecimiento=$lugar
 and id_metodologia is not null
 and t03.activo=true 
 and (t03.fecha_fin >= (current_date) or t03.fecha_fin is null)
 and condicion ='H'
 ORDER BY nombre_examen";
              
              //echo $query;
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
         
          //RECUPERAR metodologias POR examen
	 function buscarmetodologiasxexa($idexamen, $lugar)
	 {
		$con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	      $query = "select * 
from  lab_examen_metodologia t02 
where t02.activo=true 
and (t02.fecha_fin >= (current_date) or t02.fecha_fin is null)
and id_conf_exa_estab=$idexamen
order by nombre_reporta;";
              
              //echo $query;
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
         
         //RECUPERAR metodologias POR examen
        function buscardatosmetodologia($idexamen, $idmetodologia, $lugar)
	 {
		$con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	      $query = "select * 
from lab_examen_metodologia  
where id=$idmetodologia;";
              
              //echo $query;
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
   //fn pg
         
         //RECUPERAR metodologias POR examen
        function buscarposresultmet($idmetodologia)
	 {
		$con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	      $query = "select t01.id as idexametposres, nombre_reporta, id_codigoresultado,  id_posible_resultado, posible_resultado
from lab_examen_metodo_pos_resultado t01
join lab_examen_metodologia t02 on (t02.id=t01.id_examen_metodologia)
join lab_posible_resultado t03 on (t03.id=t01.id_posible_resultado)
               where id_examen_metodologia =$idmetodologia
               and t01.habilitado= true
               and (t01.fechafin between t01.fechainicio and current_date 
               or t01.fechafin is null)";
              
             // echo $query;
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
   //fn pg
   function posresultados(){
      /*
      * Julio Castillo
      */
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
        $query = "select * 
from lab_posible_resultado lpr
where habilitado=true
and fechafin is null or date(fechafin)>=current_date
order by posible_resultado";

           $result = pg_query($query);
           if (!$result)
             return false;
           else
             return $result;
     }
   }
   //fn pg
   function buscarcodigores(){
      /*
      * Julio Castillo
      */
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
        $query = "select * from lab_codigosresultados order by id;";

           $result = pg_query($query);
           if (!$result)
             return false;
           else
             return $result;
     }
   }
      //fn pg
         
         //RECUPERAR metodologias POR examen
        function buscarposresultodo($idmetodologia)
	 {
		$con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	      $query = "select *
from lab_examen_metodo_pos_resultado t01
               where id_examen_metodologia =$idmetodologia";
              
             // echo $query;
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
   
   //INSERTA UN REGISTRO          
	 function insertar($idexamen,$idmetodologia,$cmbreporta,$usuario,$lugar,$Fechaini,$Fechafin,$posresultados_sel,$text_posresultados_sel,$id_posresultados_sel)
	 { //echo $idarea;
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
            $query_up="update lab_examen_metodologia set b_reporta=$cmbreporta,fecha_inicio=$Fechaini, fecha_fin=$Fechafin where id=$idmetodologia;" ;
            //echo $query_up;
           $result=pg_query($query_up);
           $d_fecha=date('Y-m-d');
           if ($Fechafin <= $d_fecha)
           {
              $query="update lab_examen_metodologia set activo=false where id=$idmetodologia";
              $result_q=pg_query($query);
           }
           $aPosResult = explode(',',$posresultados_sel); 
           $aPosResult_text = explode(',',$text_posresultados_sel); 
           $aPosResult_id = explode(',',$id_posresultados_sel);  
           $i=0;
             
            
            //$ant=pg_fetch_array($anterioresactivos);
            if ($aPosResult[0]!=""){
               $query_upd = "update lab_examen_metodo_pos_resultado
                  set id_user_mod=$usuario, 
                     fecha_mod=date_trunc('seconds',NOW()),
                        habilitado='false',
                        fechafin=current_date
                        where id_examen_metodologia= $idmetodologia";
               // echo $query_upd;
                 $result = pg_query($query_upd);
            for ($i=0;$i<(count($aPosResult)-1);$i++){
                $update=0;
                $anterioresactivos=$this-> buscarposresultodo($idmetodologia);
            while ($ant= pg_fetch_array($anterioresactivos)){
               if ($ant['id_posible_resultado']==$aPosResult[$i]){
                  $update=1;
               }
            }
            if ($update==1){
               $query = "update lab_examen_metodo_pos_resultado
                  set id_user_mod=$usuario, 
                     fecha_mod=date_trunc('seconds',NOW()),
                     id_codigoresultado=$aPosResult_id[$i],
                     fechafin=$Fechafin,
                        habilitado='true'
                        where id_examen_metodologia= $idmetodologia
                        and id_posible_resultado=$aPosResult[$i]";
            }
            else{
               $query = "INSERT INTO lab_examen_metodo_pos_resultado
		      (id_examen_metodologia, id_posible_resultado, fechainicio, fechafin, id_user, fecha_registro, id_codigoresultado) 
                      VALUES($idmetodologia,$aPosResult[$i],$Fechaini,$Fechafin,$usuario,date_trunc('seconds',NOW()),$aPosResult_id[$i])";
            }
            //echo $query;
	     $result = pg_query($query);
           
            }        
           }
	     if (!$result)
	       return false;
	     else
	       return true;	   
	   }
	 }
   
   
           function consultarhabilitado($idmetodologia)
       {
         $con = new ConexionBD;
         if($con->conectar()==true)
         {
           $query = "select * from lab_examen_metodologia 
                     where activo=true 
                     and (fecha_fin between fecha_inicio and current_date 
                     or fecha_fin is null)
                     and id=$idmetodologia";
           $result = pg_query($query);
           if (!$result)
             return false;
           else
             return $result;
          }
        }

      //fn pg
	function consultarpag($lugar,$RegistrosAEmpezar, $RegistrosAMostrar)
	{
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
            if($con->conectar()==true){
              $query = "select idestandar, nombre_examen, nombre_reporta, b_reporta, t01.id as id, t05.nombrearea 
                            from lab_examen_metodologia t01 
                            join lab_conf_examen_estab t02 on (t02.id=t01.id_conf_exa_estab) 
                            join mnt_area_examen_establecimiento t03 on (t03.id=t02.idexamen) 
                            join ctl_examen_servicio_diagnostico t04 on (t04.id=t03.id_examen_servicio_diagnostico) 
                            join ctl_area_servicio_diagnostico t05 ON (t05.id = id_area_servicio_diagnostico)
                            where id_metodologia is not null
                            and t01.activo=true
                            and condicion='H'
                            and id_establecimiento=$lugar
                            order by nombrearea,idestandar,nombre_examen, nombre_reporta
                            LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
                //echo $query;
                    $result = pg_query($query);
                    if (!$result)
			return false;
                    else
			return $result;
            }
	}  
      
         
         
      /////////////////Fin funciones Metodologias
         
         
         

	
	 //ACTUALIZA UN REGISTRO
	 function actualizar($iddatosfijosresultado,$idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
              
	   {
	      
		$query = "UPDATE lab_datosfijosresultado SET id_conf_examen_estab=$idexamen, 
	    		  unidades=$unidades, rangoinicio=$rangoinicio , rangofin=$rangofin , nota=$nota,idsexo=$sexo,idedad=$redad,
                          idusuariomod= $usuario, fechahoramod=NOW() ,FechaIni=$Fechaini,fechafin=$Fechafin 
                          WHERE id=$iddatosfijosresultado AND idestablecimiento=$lugar";
                          //    echo $query;
                                
		$result = pg_query($query);
		if (!$result)
                    return false;
				//  return $query; 
                else
                    return true;
			// return $query; 
	   }
	 }

	//ELIMINA UN REGISTRO
	 function eliminar($iddatosfijosresultado,$lugar)
	{
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	     $query = "DELETE FROM lab_datosfijosresultado WHERE id=$iddatosfijosresultado AND IdEstablecimiento=$lugar";
	     $result = pg_query($query);
		 
	     if (!$result)
	       return false;
	     else
	       return true;		   
	   }
	 }

                
        //OBTENER catalogo de sexo
       function consultarsexo()
       {
         $con = new ConexionBD;
         if($con->conectar()==true)
         {
           $query = "SELECT id,nombre FROM ctl_sexo where id<>3";
           $result = pg_query($query);
           if (!$result)
             return false;
           else
             return $result;
          }
        }

       function RangosEdades(){
         $con = new ConexionBD;
         if($con->conectar()==true)
         {
           $query = "SELECT id, nombre FROM ctl_rango_edad WHERE cod_modulo='LAB'";
           $result = pg_query($query);
           if (!$result)
             return false;
           else
             return $result;
          }

        }
        //   }
	 //CONSULTA LOS REGISTROS
	 function consultar($lugar)
	 {
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = "SELECT lab_conf_examen_estab.idexamen,lab_conf_examen_estab.nombre_examen,lab_datosfijosresultado.unidades,
		       lab_datosfijosresultado.rangoinicio,lab_datosfijosresultado.rangofin,nota
	     	       FROM lab_datosfijosresultado 
                       INNER JOIN lab_conf_examen_estab ON lab_datosfijosresultado.id_conf_examen_estab=lab_conf_examen_estab.id 
                       INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                       INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
                       INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
   	               wHERE lab_areasxestablecimiento.Condicion='H' AND lab_conf_examen_estab.condicion='H' AND 
                       lab_datosfijosresultado.IdEstablecimiento=$lugar ORDER BY lab_conf_examen_estab.nombre_examen ";
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	  } 

	//CONSULTA EXAMEN POR EL CODIGO
	function consultarid($idmetodologia,$lugar)
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true)
	   {
	     $query = "select idestandar, nombre_examen, nombre_reporta, b_reporta, t01.id as id, t05.id as id_area, nombrearea, t02.id as id_examen, t01.fecha_inicio, t01.fecha_fin
                  from lab_examen_metodologia t01
                  join lab_conf_examen_estab  t02 on (t02.id=t01.id_conf_exa_estab)
                  join mnt_area_examen_establecimiento  t03 on (t03.id=t02.idexamen)
                  join ctl_examen_servicio_diagnostico t04 on (t04.id=t03.id_examen_servicio_diagnostico)
                  join ctl_area_servicio_diagnostico t05 on (t05.id=t03.id_area_servicio_diagnostico)
                  where id_metodologia is not null
                  and t01.activo=true
                  and condicion='H'
                  and id_establecimiento=$lugar
                  and t01.id =$idmetodologia
                  order by idestandar,nombre_examen, nombre_reporta;";
             //echo $query;
	     $result = pg_query($query);
             
	     if (!$result)
	       return false;
	     else
	       return $result;
	    }
	  }
	
	
//*************************************************FUNCIONES PARA MANEJO DE PAGINACION******************************************************/
	 //consultando el numero de registros de la tabla
	function NumeroDeRegistros($lugar){
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = "select (idestandar||'-'||nombre_examen) as nombre_examen, nombre_reporta, b_reporta
                  from lab_examen_metodologia t01
                  join lab_conf_examen_estab  t02 on (t02.id=t01.id_conf_exa_estab)
                  join mnt_area_examen_establecimiento  t03 on (t03.id=t02.idexamen)
                  join ctl_examen_servicio_diagnostico t04 on (t04.id=t03.id_examen_servicio_diagnostico)
                  where id_metodologia is not null
                  and t01.activo=true
                  and condicion='H'
                  and id_establecimiento=$lugar
                  order by nombre_examen, nombre_reporta;";
               //echo $query;
		 $numreg = pg_num_rows(pg_query($query));
		 if (!$numreg )
		   return false;
		 else
		   return $numreg ;
	   }
	  }

	function NumeroDeRegistrosbus($query_search){
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	      $query = $query_search;
		 $numreg = @pg_num_rows(pg_query($query));
		 if (!$numreg )
		   return false;
		 else
		   return $numreg ;
	   }
	  }	
          
          
          
         //fn_pg          
        function Estadohabilitado($idatofijo,$usuario) {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			 echo $query = "UPDATE lab_datosfijosresultado SET 
					fechafin=current_date,
					idusuarioreg=$usuario,
					fechahoramod=NOW()
					 WHERE id=$idatofijo ";
			$result = pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
          
         //fn_pg          
        function buscardatosfijo($idsexo,$idedad, $idconf) {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
                  $query = "select * 
                     from lab_datosfijosresultado 
                     where id_conf_examen_estab=$idconf
                     and (fechafin is null or date(fechafin)>(current_date))
                     and (idsexo is null or idsexo=$idsexo)
                     and (idedad=4 or idedad=$idedad)";
			$result = pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
          
          
	function consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar)
	{
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = $query_search." LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
		 $result = @pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	} 	  
//************************************************FIN FUNCIONES PARA MANEJO DE PAGINACION***************************************************/
 
}//CLASE

class clsLabor_DatosFijosExamen
{

//INSERTA UN REGISTRO         
	/*function insertar_labo($idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)
	 { $con2 = new ConexionBDLab;
		if($con2->conectarT()==true) 
		{
			$query = "INSERT INTO laboratorio.lab_datosfijosresultado
			(IdArea,IdExamen,Unidades,RangoInicio,RangoFin,Nota,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdEstablecimiento,FechaIni,FechaFin,idsexo,idedad) 
			VALUES('$idarea','$idexamen','$unidades',$rangoinicio,$rangofin,'$nota',$usuario,NOW(),$usuario,NOW(),$lugar,'$Fechaini','$Fechafin',$sexo,$redad)";
		
	     $result = mysql_query($query);
	
	     if (!$result)
	       return false;
	     else
	       return true;	   
	   }
	}
	
	 
	function actualizar_labo($iddatosfijosresultado,$idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)
	{
	    $con2 = new ConexionBDLab;
	    if($con2->conectarT()==true) 
	    {
	        if(empty($Fechafin)){
			$query = "UPDATE laboratorio.lab_datosfijosresultado SET IdExamen='$idexamen', IdArea='$idarea',
                                  Unidades='$unidades', RangoInicio=$rangoinicio , RangoFin=$rangofin , Nota='$nota', 
                                  idsexo=$sexo,idedad=$redad,IdUsuarioMod= $usuario, 
                                  FechaHoraMod=NOW(),FechaIni='$Fechaini' 
                                  WHERE IdDatosFijosResultado=$iddatosfijosresultado AND IdEstablecimiento=$lugar";}
		    else{	
			$query = "UPDATE laboratorio.lab_datosfijosresultado SET IdExamen='$idexamen', IdArea='$idarea',
                                  Unidades='$unidades', RangoInicio=$rangoinicio , RangoFin=$rangofin , Nota='$nota', 
                                  idsexo=$sexo,idedad=$redad,IdUsuarioMod= $usuario, 
                                  FechaHoraMod=NOW() ,FechaIni='$Fechaini', FechaFin='$Fechafin' 
                                  WHERE IdDatosFijosResultado=$iddatosfijosresultado AND IdEstablecimiento=$lugar";}
		 
	     $result = @mysql_query($query);
		 if (!$result)
	       return false;
				//  return $query; 
	     else
			return true;
			// return $query; 
	   }
	}


	//ELIMINA UN REGISTRO
	 function eliminar_labo($iddatosfijosresultado,$lugar)
	{
	   $con2 = new ConexionBDLab;
	   if($con2->conectarT()==true) 
	   {
	    $query = "DELETE FROM laboratorio.lab_datosfijosresultado WHERE IdDatosFijosResultado=$iddatosfijosresultado AND IdEstablecimiento=$lugar";
	     $result = @mysql_query($query);
		 
	     if (!$result)
	       return false;
	     else
	       return true;		   
	   }
	 }*/
	 
	
	

}
?>
