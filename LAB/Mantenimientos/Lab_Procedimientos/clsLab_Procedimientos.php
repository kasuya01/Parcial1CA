<?php
include_once "../../../Conexion/ConexionBD.php";
include_once "../../../Conexion/ConexionBDLab.php";

class clsLab_Procedimientos {
	//CONSTRUCTOR
	function clsLab_Procedimientos() {
	}

	//INSERTA UN REGISTRO
	function insertar( $proce, $idarea, $idexamen, $unidades, $rangoini, $rangofin, $usuario, $lugar, $Fechaini, $Fechafin, $sexo, $redad,$cmborden,$resultado ) {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			  $query ="INSERT INTO lab_procedimientosporexamen (nombreprocedimiento, id_conf_examen_estab, unidades, rangoinicio, rangofin, idusuarioreg, fechahorareg, idestablecimiento, fechaini, fechafin, idsexo, idrangoedad,orden)
                     VALUES('$proce',$idexamen,$unidades,$rangoini,$rangofin,$usuario,date_trunc('seconds',NOW()),$lugar,'$Fechaini',$Fechafin,$sexo,$redad,$cmborden)";
			$result = @pg_query( $query );
                         $query;
                         
                         
        $query2 ="select COALESCE(max(id),1) from lab_procedimientosporexamen";
        $result2 = pg_query($query2);
        $row2=pg_fetch_array($result2);
        $ultimo=$row2[0];
        
        $aresultados = explode(',',$resultado); 
         for ($i=0;$i<(count($aresultados)-1);$i++){
             
             
              $query = "UPDATE lab_procedimiento_posible_resultado 
                        SET habilitado = true,
                            fechafin = null,
                            id_user_mod = 8,
                            fecha_mod = now()
                        WHERE id_posible_resultado = '$aresultados[$i]' AND id_procedimientoporexamen='$ultimo'";
            $result=pg_query($query);
            if (pg_affected_rows($result)==0){
                $query = "INSERT INTO lab_procedimiento_posible_resultado(
                            id_procedimientoporexamen, id_posible_resultado, fechainicio, fechafin, 
                            habilitado, id_user, fecha_registro, id_user_mod, fecha_mod)
                    VALUES ('$ultimo', '$aresultados[$i]', date_trunc('seconds',NOW()), null, 
                            true, 8, date_trunc('seconds',NOW()), null, null)";
                $result=pg_query($query);
            }
             
             
         }
                         
                         
                         
                         

			if (!$result)
				return false;
			else
				return true;
		}
	}

	//ACTUALIZA UN REGISTRO
	function actualizar( $idproce, $proce, $idarea, $idexamen, $unidades, $rangoini, $rangofin, $usuario, $lugar, $Fechaini, $Fechafin, $sexo, $redad,$cmborden ) {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			echo $query ="UPDATE lab_procedimientosporexamen SET nombreprocedimiento='$proce', id_conf_examen_estab ='$idexamen', unidades = '$unidades', rangoinicio=$rangoini,
				  rangofin=$rangofin, idusuariomod=$usuario, fechahoramod = NOW(), fechaini = $Fechaini, fechafin = $Fechafin,
				  idsexo = $sexo, idrangoedad = $redad, orden=$cmborden
		 		  WHERE lab_procedimientosporexamen.id = $idproce AND idestablecimiento = $lugar";
			$result = pg_query( $query );

			//  echo "SIAP   ".$query;
			if ( !$result )
				return 0;
			else
				return 1;
		}
	}

	//ELIMINA UN REGISTRO
	function eliminar( $idproce, $lugar ) {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			$query = "DELETE FROM lab_procedimientosporexamen WHERE id=$idproce
               AND idestablecimiento=$lugar";
			$result = pg_query( $query );

			if ( !$result )
				return false;
			else
				return true;

		}
	}

	//OBTENER catalogo de sexo
	function consultarsexo() {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			$query = "SELECT id, nombre, abreviatura FROM ctl_sexo WHERE abreviatura != 'I'";
			$result = pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}


	function Rangos($idproce) {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			$query = "SELECT orden FROM lab_procedimientosporexamen where id=$idproce";
			$result = pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}

	}
     
        function RangosEdades() {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			$query = "SELECT id, nombre FROM ctl_rango_edad where cod_modulo='LAB' ORDER BY nombre";
			$result = pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}

	}
        
	//CONSULTA LOS REGISTROS
	function consultar( $lugar ) {
		//creamos el objeto $con a partir de la clase ConexionBD
		$con = new ConexionBD;
		//usamos el metodo conectar para realizar la conexion
		if ( $con->conectar()==true ) {
			$query = "select lppe.id,mnt4.id, lcee.nombre_examen,
                            lppe.nombreprocedimiento, unidades,rangoinicio,rangofin,
                            (lppe.fechaini) AS fechaini, (lppe.fechafin)AS fechafin,
                            cex.id,cex.abreviatura,cre.id,cre.nombre,lppe.idsexo
                            from mnt_area_examen_establecimiento mnt4
                            join lab_conf_examen_estab lcee on (mnt4.id=lcee.idexamen)
                            join lab_procedimientosporexamen lppe on (lppe.id_conf_examen_estab=lcee.id)
                            left JOIN ctl_sexo cex ON lppe.idsexo = cex.id
                            left JOIN ctl_rango_edad  cre ON lppe.idrangoedad = cre.id
                            where id_establecimiento=$lugar
                            ORDER BY mnt4.id";


			// echo $query;
			$result = pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
          function cambiar_estado($id_subelemento){
       
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
          $query = "UPDATE lab_subelemento_posible_resultado SET habilitado = false WHERE id_subelemento = '$id_subelemento'";
            $result = pg_query($query);
            if (!$result)
              return false;
            else
              return $result;
        }
    }
    function cambiar_estadolabprocede($idproce){
       
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
          $query = "UPDATE lab_procedimiento_posible_resultado SET habilitado = false WHERE id_procedimientoporexamen = $idproce";
            $result = pg_query($query);
            if (!$result)
              return false;
            else
              return $result;
        }
    }
    
      function ultimoidprocede(){
       
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
          $query = "SELECT * FROM lab_procedimientosporexamen ORDER BY id DESC LIMIT 1;";
            $result = pg_query($query);
            if (!$result)
              return false;
            else
              return $result;
        }
    }
    
    
     function cambiar_estado_id($id_posible_resultado,$id_subelemento){
       
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
            $query = "UPDATE lab_subelemento_posible_resultado 
                        SET habilitado = true,
                            fechafin = null,
                            id_user_mod = 8,
                            fecha_mod = now()
                        WHERE id_posible_resultado = '$id_posible_resultado' AND id_subelemento='$id_subelemento'";
            $result=pg_query($query);
            if (pg_affected_rows($result)==0){
                $query = "
                    INSERT INTO lab_subelemento_posible_resultado(
                            id_subelemento, id_posible_resultado, fechainicio, fechafin, 
                            habilitado, id_user, fecha_registro, id_user_mod, fecha_mod)
                    VALUES ('$id_subelemento', '$id_posible_resultado', now(), null, 
                            true, 8, now(), null, null)";
                $result=pg_query($query);
            }
        }
    }
    
    function cambiar_estado_idprocedimiento($id_posible_resultado,$idproce){
       
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
            $query = "UPDATE lab_procedimiento_posible_resultado 
                        SET habilitado = true,
                            fechafin = null,
                            id_user_mod = 8,
                            fecha_mod = now()
                        WHERE id_posible_resultado = '$id_posible_resultado' AND id_procedimientoporexamen='$idproce'";
            $result=pg_query($query);
            if (pg_affected_rows($result)==0){
               $query = "INSERT INTO lab_procedimiento_posible_resultado(
                            id_procedimientoporexamen, id_posible_resultado, fechainicio, fechafin, 
                            habilitado, id_user, fecha_registro, id_user_mod, fecha_mod)
                    VALUES ('$idproce', '$id_posible_resultado', now(), null, 
                            true, 8, now(), null, null)";
                $result=pg_query($query);
            }
        }
    }
    
    function metodologias1(){
          
            $con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	      $query = /*"SELECT m.id as id_metodologia,
                                m.nombre_metodologia metodologias
                        FROM lab_metodologia m
                        WHERE m.activa=true
                        ORDER BY m.nombre_metodologia";*/
                    " select 	t01.id,
				t01.posible_resultado resultado
				from lab_posible_resultado t01
                                left join lab_subelemento_posible_resultado t02 on (t02.id_posible_resultado=t01.id)
                                where t02.id is null
                                ORDER BY t01.posible_resultado";
             
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
         
          function prueba_lab1($nombre){
           
            $con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	      $query = "SELECT nombre_examen as nombre_prueba FROM lab_conf_examen_estab WHERE id=$nombre";
             
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
    
    function prueba_lab($nombre){
         
            $con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	      $query = "SELECT nombre_examen as nombre_prueba FROM lab_conf_examen_estab WHERE id=$nombre";
             
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
     function resultados($idproce){
        
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
          $query = 
                  
                  "select 	t01.id as id,
					t01.posible_resultado resultado
				from lab_posible_resultado t01
				left join  (select  id,
						id_posible_resultado,
						id_procedimientoporexamen,
						habilitado from lab_procedimiento_posible_resultado t02
						where t02.id_procedimientoporexamen=$idproce and t02.habilitado= true ) t02 on t02.id_posible_resultado=t01.id
						WHERE t02.id is null 
						ORDER BY t01.posible_resultado";
        

             $result = pg_query($query);
             if (!$result)
               return false;
             else
               return $result;
        }
    }
    
      function resultados1(){
        
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
                           $query = "select 	t01.id id,
                  t01.posible_resultado resultado
                  from lab_posible_resultado t01 ORDER BY t01.posible_resultado";

             $result = pg_query($query);
             if (!$result)
               return false;
             else
               return $result;
        }
    }
    
     function get_subelemento($idproce){
     
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
         $query = "SELECT s.id as id,
                        s.subelemento as subelemento_text
                     FROM lab_subelementos s
                     WHERE s.id = '$idproce'";
             $result = pg_query($query);
             if (!$result)
               return false;
             else
               return $result;
        }
    }
    
      function resultados_seleccionados($idproce){
       
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
          $query = /*"SELECT pr.id as id,
                        pr.posible_resultado as resultado
                    FROM
                        lab_subelemento_posible_resultado spr
                    LEFT JOIN lab_posible_resultado pr ON pr.id = spr.id_posible_resultado
                    WHERE spr.id_subelemento = '$id_subelemento' AND spr.habilitado is true
                    ORDER BY posible_resultado";*/
                
              "select t02.id, t02.posible_resultado resultado, t03.nombreprocedimiento  
                    from lab_procedimiento_posible_resultado t01
                inner join lab_posible_resultado 	t02 on (t02.id=t01.id_posible_resultado)
                inner join lab_procedimientosporexamen t03 on (t03.id=t01.id_procedimientoporexamen)
                 WHERE t01.id_procedimientoporexamen = '$idproce' AND t01.habilitado is true 
                  ORDER BY posible_resultado";

             $result = pg_query($query);
             if (!$result)
               return false;
             else
               return $result;
        }
    }

	//CONSULTA EXAMEN POR EL CODIGO
	function consultarid( $idproce, $lugar ) {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
		echo	 $query ="SELECT lppe.id AS idprocedimientoporexamen,
							lcee.id AS idexamen,
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
                                                        lppe.orden
				 	 FROM lab_procedimientosporexamen			lppe
				 	 INNER JOIN lab_conf_examen_estab 			lcee ON (lcee.id = lppe.id_conf_examen_estab)
				 	 INNER JOIN mnt_area_examen_establecimiento mnt4 ON (mnt4.id = lcee.idexamen)
				 	 INNER JOIN ctl_area_servicio_diagnostico	casd ON (casd.id = mnt4.id_area_servicio_diagnostico)
				 	 LEFT OUTER JOIN ctl_sexo 					cex  ON (cex.id  = lppe.idsexo AND cex.abreviatura != 'I')
				 	 LEFT OUTER JOIN ctl_rango_edad 			cre  ON (cre.id  = lppe.idrangoedad)
				 	 WHERE lppe.id = $idproce  
                                            
                                        AND lppe.idestablecimiento = $lugar
                                             
				 	 ORDER BY lcee.codigo_examen";

			$result = @pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}

	//RECUPERAR EXAMENES POR AREA
	function ExamenesPorArea( $idarea, $lugar ) {
		$con = new ConexionBD;
		//usamos el metodo conectar para realizar la conexion
		if ( $con->conectar()==true ) {

		
                  $query ="SELECT lab_conf_examen_estab.id AS idexamen, lab_conf_examen_estab.nombre_examen AS nombreexamen 
                    FROM mnt_area_examen_establecimiento  
                    INNER JOIN lab_conf_examen_estab ON (mnt_area_examen_establecimiento.id = lab_conf_examen_estab.idexamen) 
                    INNER JOIN lab_plantilla  ON (lab_plantilla.id = lab_conf_examen_estab.idplantilla) 
                    INNER JOIN ctl_examen_servicio_diagnostico ON ctl_examen_servicio_diagnostico.id=mnt_area_examen_establecimiento.id_examen_servicio_diagnostico
                    WHERE mnt_area_examen_establecimiento.id_area_servicio_diagnostico = $idarea AND lab_plantilla.id = 5 
                    AND mnt_area_examen_establecimiento.activo=TRUE
                    AND ctl_examen_servicio_diagnostico.activo=TRUE
                    AND lab_conf_examen_estab.condicion='H' 
                    AND mnt_area_examen_establecimiento.id_establecimiento = $lugar
                    ORDER BY lab_conf_examen_estab.nombre_examen";
                         /*"SELECT lcee.id AS idexamen, 
                                            lcee.nombre_examen AS nombreexamen
                                    FROM mnt_area_examen_establecimiento maees
                                    INNER JOIN lab_conf_examen_estab    lcee ON (maees.id = lcee.idexamen)
                                    INNER JOIN lab_plantilla 		lpla ON (lpla.id  = lcee.idplantilla)
                                    WHERE maees.id_area_servicio_diagnostico = $idarea 
                                    AND lpla.id = 5 
                                    AND lcee.condicion='H' 
                                    AND maees.id_establecimiento = $lugar
                                    ORDER BY lcee.nombre_examen";*/
                        
			$result = @pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
        
        function llenarrangoproc( $idexa ) {
		$con = new ConexionBD;
		//usamos el metodo conectar para realizar la conexion
		if ( $con->conectar()==true ) {

			  $query ="select  orden  from lab_procedimientosporexamen 
                                  where id_conf_examen_estab=$idexa order by orden asc
";

			$result = @pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
           function llenarrangopro1( $idexa ) {
		$con = new ConexionBD;
		//usamos el metodo conectar para realizar la conexion
		if ( $con->conectar()==true ) {

			  $query ="select  orden  from lab_procedimientosporexamen 
                                  where id_conf_examen_estab=$idexa order by orden asc
";

			$result = @pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
        
        
         function contarorden( $idexa ) {
		$con = new ConexionBD;
		//usamos el metodo conectar para realizar la conexion
		if ( $con->conectar()==true ) {

			  $query ="select 
                                        count (*) cantidad
                                        from lab_procedimientosporexamen 
                                        where id_conf_examen_estab=$idexa";

			$result = @pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
        
      function  llenarrangoproc1( $idexa ) {
		$con = new ConexionBD;
		//usamos el metodo conectar para realizar la conexion
		if ( $con->conectar()==true ) {

	            $query ="select id, orden  from lab_procedimientosporexamen where id_conf_examen_estab=$idexa";

		    $result = @pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
        
        function consulhabilitado( $idproce ) {
		$con = new ConexionBD;
		//usamos el metodo conectar para realizar la conexion
		$con = new ConexionBD;
		//usamos el metodo conectar para realizar la conexion
		if ( $con->conectar()==true ) {
			 $query =

				"SELECT 
				lppe.habilitado,
                                lppe.id as idlppe
                                FROM lab_procedimientosporexamen  lppe
				WHERE  lppe.id = $idproce ";

			$result = @pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
        
        
        
        function updatehabilitadot() {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			 $query = "UPDATE lab_procedimientosporexamen SET habilitado='t' where   fechafin is null";
			$result = pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
        
                
        function updatehabilitadof() {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			 $query = "UPDATE lab_procedimientosporexamen SET habilitado='f' where fechafin = fechafin ";
			$result = pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
        
        
	//*************************************************FUNCIONES PARA MANEJO DE PAGINACION******************************************************/
	//consultando el numero de registros de la tabla
	function NumeroDeRegistros( $lugar ) {
		//creamos el objeto $con a partir de la clase ConexionBD
		$con = new ConexionBD;
		//usamos el metodo conectar para realizar la conexion
		if ( $con->conectar()==true ) {
			$query =  "SELECT lppe.id AS idprocedimientoporexamen,
						lcee.codigo_examen AS idexamen,
						lcee.nombre_examen AS nombreexamen,
						lppe.nombreprocedimiento,
						lppe.unidades,
						lppe.rangoinicio,
						lppe.rangofin,
						TO_CHAR(lppe.fechaini::timestamp, 'DD/MM/YYYY')AS fechaini,
						TO_CHAR(lppe.fechafin::timestamp, 'DD/MM/YYYY')AS fechafin,
						CASE cex.id WHEN 1 THEN 'Masculino'
						            WHEN 2 THEN 'Femenino'
						            ELSE 'Ambos'
						END AS sexovn,
						cre.nombre AS nombregrupoedad,
						(CASE WHEN lcee.condicion='H' THEN 'Inhabilitado'
						WHEN lcee.condicion='I' THEN 'Habilitado' END) AS cond1,
                                                

                                                (CASE WHEN lppe.habilitado='t' THEN 'Habilitado'
						WHEN lppe.habilitado='f' THEN 'Inhabilitado' END) AS cond,
						lppe.habilitado,
                                                lppe.id as idlppe,
                                                mnt4.id as idmnt4,
                                                lcee.condicion,
                                                lppe.orden
						FROM lab_procedimientosporexamen 		   lppe
						INNER JOIN lab_conf_examen_estab		   lcee ON (lcee.id = lppe.id_conf_examen_estab)
                                                INNER JOIN mnt_area_examen_establecimiento  mnt4 ON (mnt4.id = lcee.idexamen)
						INNER JOIN ctl_area_servicio_diagnostico    casd ON (casd.id = mnt4.id_area_servicio_diagnostico)
                                                INNER JOIN ctl_examen_servicio_diagnostico  cesd ON  cesd.id= mnt4.id_examen_servicio_diagnostico
						INNER JOIN lab_areasxestablecimiento        laxe ON (casd.id = laxe.idarea)
						INNER JOIN lab_plantilla                    lpla ON (lpla.id = lcee.idplantilla)
						LEFT OUTER JOIN ctl_sexo                    cex  ON (cex.id  = lppe.idsexo)
						LEFT OUTER JOIN ctl_rango_edad              cre  ON (cre.id  = lppe.idrangoedad)
                                                
						WHERE 
                                               lcee.condicion = 'H' AND laxe.condicion = 'H' AND cesd.activo=TRUE AND 
                                                lppe.idestablecimiento = $lugar";
                                /*"SELECT lcee.id,lcee.nombre_examen,
                            lppe.nombreprocedimiento,
                            lppe.unidades,lppe.rangoinicio,
                            lppe.rangofin,(lppe.fechaini)AS fechaini,
                            (lppe.fechafin)AS fechafin,cex.abreviatura,cre.nombre, lppe.id
                            from ctl_area_servicio_diagnostico casd
                            join mnt_area_examen_establecimiento mnt4 on mnt4.id_area_servicio_diagnostico=casd.id
                            join lab_conf_examen_estab lcee on (mnt4.id=lcee.idexamen)
                            join lab_procedimientosporexamen lppe on (lppe.id_conf_examen_estab=lcee.id)
                            left JOIN ctl_sexo cex ON lppe.idsexo = cex.id
                            left JOIN ctl_rango_edad cre ON lppe.idrangoedad = cre.id
                            WHERE lcee.condicion='H'
                                    AND lcee.condicion='H' AND lcee.idplantilla=5
                                    AND lppe.idestablecimiento=$lugar";*/



			$numreg = pg_num_rows( pg_query( $query ) );
			if ( !$numreg )
				return false;
			else
				return $numreg ;
		}
	}

	function NumeroDeRegistrosbus( $query_search ) {
		//creamos el objeto $con a partir de la clase ConexionBD
		$con = new ConexionBD;
		//usamos el metodo conectar para realizar la conexion
		if ( $con->conectar()==true ) {
			 $numreg = pg_num_rows( pg_query( $query_search ) );
			if ( !$numreg )
				return false;
			else
				return $numreg ;
		}
	}
         function EstadoCuenta($idlppe,$cond,$usuario){ 
    $con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
             
            //habilitado='t' = 'Habilitado' --> pasara a f
            if($cond=='t'){
                
                $query="UPDATE lab_procedimientosporexamen SET habilitado='f',
					fechafin=current_date,
					idusuarioreg=$usuario,
					fechahoramod=NOW()
					 WHERE id=$idlppe ";
                
                 
                $result = pg_query($query);
              //  $query1= "UPDATE lab_examenes SET Habilitado='N' WHERE IdExamen='$idexamen'" ;
               // $result1 = pg_query($query1);
             }
            // habilitado='f' = 'Inhabilitado' -->pasara a t
             if($cond=='f'){
                 //$query = "UPDATE lab_conf_examen_estab SET condicion='H' WHERE id=$idexamen";
                 /*$query="UPDATE lab_procedimientosporexamen SET habilitado='t',
					fechafin=current_date,
					idusuarioreg=$usuario,
					fechahoramod=NOW()
					 WHERE id=$idlppe ";*/
                 
                 $query="UPDATE lab_procedimientosporexamen SET habilitado='t',
					fechafin=NULL,
					idusuarioreg=$usuario,
					fechahoramod=NOW()
					 WHERE id=$idlppe ";
                $result = pg_query($query);
               // $query1= "UPDATE lab_examenes SET Habilitado='S' WHERE IdExamen='$idexamen'";
                //$result1 = pg_query($query1);
             }
        }
        $con->desconectar();
    }
    
  

	function consultarpag( $lugar, $RegistrosAEmpezar, $RegistrosAMostrar ) {

		//creamos el objeto $con a partir de la clase ConexionBD
		$con = new ConexionBD;
		//usamos el metodo conectar para realizar la conexion
		if ( $con->conectar()==true ) {
			 $query = "SELECT lppe.id AS idprocedimientoporexamen,
						lcee.codigo_examen AS idexamen,
						lcee.nombre_examen AS nombreexamen,
						lppe.nombreprocedimiento,
						lppe.unidades,
						lppe.rangoinicio,
						lppe.rangofin,
						TO_CHAR(lppe.fechaini::timestamp, 'DD/MM/YYYY')AS fechaini,
						TO_CHAR(lppe.fechafin::timestamp, 'DD/MM/YYYY')AS fechafin,
						CASE cex.id WHEN 1 THEN 'Masculino'
						            WHEN 2 THEN 'Femenino'
						            ELSE 'Ambos'
						END AS sexovn,
						cre.nombre AS nombregrupoedad,
						(CASE WHEN lcee.condicion='H' THEN 'Inhabilitado'
						WHEN lcee.condicion='I' THEN 'Habilitado' END) AS cond1,
                                                

                                                (CASE WHEN lppe.habilitado='t' THEN 'Habilitado'
						WHEN lppe.habilitado='f' THEN 'Inhabilitado' END) AS cond,
						lppe.habilitado,
                                                lppe.id as idlppe,
                                                mnt4.id as idmnt4,
                                                lcee.condicion,
                                                lppe.orden
						FROM lab_procedimientosporexamen 		   lppe
						INNER JOIN lab_conf_examen_estab		   lcee ON (lcee.id = lppe.id_conf_examen_estab)
                                                INNER JOIN mnt_area_examen_establecimiento  mnt4 ON (mnt4.id = lcee.idexamen)
						INNER JOIN ctl_area_servicio_diagnostico    casd ON (casd.id = mnt4.id_area_servicio_diagnostico)
                                                INNER JOIN ctl_examen_servicio_diagnostico  cesd ON  cesd.id= mnt4.id_examen_servicio_diagnostico
						INNER JOIN lab_areasxestablecimiento        laxe ON (casd.id = laxe.idarea)
						INNER JOIN lab_plantilla                    lpla ON (lpla.id = lcee.idplantilla)
						LEFT OUTER JOIN ctl_sexo                    cex  ON (cex.id  = lppe.idsexo)
						LEFT OUTER JOIN ctl_rango_edad              cre  ON (cre.id  = lppe.idrangoedad)
                                                
						WHERE 
                                               lcee.condicion = 'H' AND laxe.condicion = 'H' AND cesd.activo=TRUE AND 
                                                lppe.idestablecimiento = $lugar
						ORDER BY lcee.codigo_examen, lppe.orden LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";

			$result = @pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}

	function consultarpagbus( $query_search, $RegistrosAEmpezar, $RegistrosAMostrar ) {
		//creamos el objeto $con a partir de la clase ConexionBD
		$con = new ConexionBD;
		//usamos el metodo conectar para realizar la conexion
		if ( $con->conectar()==true ) {
			$query = $query_search." LIMIT $RegistrosAMostrar  OFFSET $RegistrosAEmpezar ";
			$result = @pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
}
//************************************************  FIN FUNCIONES PARA MANEJO DE PAGINACION  ***************************************************/
class clsLabor_Procedimientos {
	//INSERTA UN REGISTRO
	/* function insertar_labo($proce,$idarea,$idexamen,$unidades,$rangoini,$rangofin,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)
	 {
	   $con2 = new ConexionBDLab;
	   if($con2->conectarT()==true)
	   {
	    $query ="INSERT INTO laboratorio.lab_procedimientosporexamen
                (nombreprocedimiento,IdArea,IdExamen,unidades,rangoinicio,rangofin,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdEstablecimiento,FechaIni,FechaFin,idsexo,idedad)
                VALUES('$proce','$idarea','$idexamen','$unidades',$rangoini,$rangofin,$usuario,NOW(),$usuario,NOW(),$lugar,'$Fechaini','$Fechafin',$sexo,$redad)";
		$result = @pg_query($query);
	     if (!$result)
	       return false;
	     else
	       return true;
	   }

	  }

	  //ACTUALIZA UN REGISTRO
	 function actualizar_labo($idproce,$proce,$idarea,$idexamen,$unidades,$rangoini,$rangofin,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)
	 {
	    $con2 = new ConexionBDLab;
	   if($con2->conectarT()==true)
	   {
	     $query = "UPDATE laboratorio.lab_procedimientosporexamen SET nombreprocedimiento='$proce',IdExamen='$idexamen', IdArea='$idarea',
                       unidades='$unidades',rangoinicio=$rangoini,rangofin=$rangofin,IdUsuarioMod=$usuario ,FechaHoraMod=NOW(),
                       Fechaini='$Fechaini', FechaFin='$Fechafin',idsexo=$sexo,idedad=$redad
                       WHERE idprocedimientoporexamen=$idproce AND IdEstablecimiento=$lugar";
	       echo $query;
	     $result = @pg_query($query);
		 if (!$result)
	       return false;
	     else
	       return true;
	   }
	 }

	//ELIMINA UN REGISTRO
        function eliminar_labo($idproce,$lugar)
        {
            $con2 = new ConexionBDLab;
                if($con2->conectarT()==true){

             $query = "DELETE FROM laboratorio.lab_procedimientosporexamen
                 WHERE idprocedimientoporexamen=$idproce AND Idestablecimiento=$lugar";
             $result = @pg_query($query);

             if (!$result)
               return 0;
             else
               return 1;

           }
         }*/


}//CLASE la



?>
