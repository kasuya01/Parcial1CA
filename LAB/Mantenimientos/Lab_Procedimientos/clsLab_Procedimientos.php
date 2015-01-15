<?php
include_once "../../../Conexion/ConexionBD.php";
include_once "../../../Conexion/ConexionBDLab.php";

class clsLab_Procedimientos {
	//CONSTRUCTOR
	function clsLab_Procedimientos() {
	}

	//INSERTA UN REGISTRO
	function insertar( $proce, $idarea, $idexamen, $unidades, $rangoini, $rangofin, $usuario, $lugar, $Fechaini, $Fechafin, $sexo, $redad ) {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			  $query ="INSERT INTO lab_procedimientosporexamen (nombreprocedimiento, id_conf_examen_estab, unidades, rangoinicio, rangofin, idusuarioreg, fechahorareg, idestablecimiento, fechaini, fechafin, idsexo, idrangoedad)
                     VALUES('$proce',$idexamen,'$unidades',$rangoini,$rangofin,$usuario,NOW(),$lugar,'$Fechaini','$Fechafin',$sexo,$redad)";
			$result = @pg_query( $query );

			if ( !$result )
				return false;
			else
				return true;
		}
	}

	//ACTUALIZA UN REGISTRO
	function actualizar( $idproce, $proce, $idarea, $idexamen, $unidades, $rangoini, $rangofin, $usuario, $lugar, $Fechaini, $Fechafin, $sexo, $redad ) {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			 $query ="UPDATE lab_procedimientosporexamen SET nombreprocedimiento='$proce', id_conf_examen_estab ='$idexamen', unidades = '$unidades', rangoinicio=$rangoini,
													rangofin=$rangofin, idusuariomod=$usuario, fechahoramod = NOW(), fechaini = $Fechaini, fechafin = $Fechafin,
													idsexo = $sexo, idrangoedad = $redad
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


	function RangosEdades() {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			$query = "SELECT id, nombre FROM ctl_rango_edad ORDER BY nombre";
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

	//CONSULTA EXAMEN POR EL CODIGO
	function consultarid( $idproce, $lugar ) {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			$query ="SELECT lppe.id AS idprocedimientoporexamen,
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
				 			cre.nombre AS nombregrupoedad
				 	 FROM lab_procedimientosporexamen			lppe
				 	 INNER JOIN lab_conf_examen_estab 			lcee ON (lcee.id = lppe.id_conf_examen_estab)
				 	 INNER JOIN mnt_area_examen_establecimiento mnt4 ON (mnt4.id = lcee.idexamen)
				 	 INNER JOIN ctl_area_servicio_diagnostico	casd ON (casd.id = mnt4.id_area_servicio_diagnostico)
				 	 LEFT OUTER JOIN ctl_sexo 					cex  ON (cex.id  = lppe.idsexo AND cex.abreviatura != 'I')
				 	 LEFT OUTER JOIN ctl_rango_edad 			cre  ON (cre.id  = lppe.idrangoedad)
				 	 WHERE lppe.id = $idproce AND lppe.idestablecimiento = $lugar
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

			 $query ="SELECT lcee.id AS idexamen, 
                                            lcee.nombre_examen AS nombreexamen
                                    FROM mnt_area_examen_establecimiento maees
                                    INNER JOIN lab_conf_examen_estab    lcee ON (maees.id = lcee.idexamen)
                                    INNER JOIN lab_plantilla 		lpla ON (lpla.id  = lcee.idplantilla)
                                    WHERE maees.id_area_servicio_diagnostico = $idarea 
                                    --AND lpla.idplantilla = 'E' 
                                    AND lcee.condicion='H' 
                                    AND maees.id_establecimiento = $lugar
                                    ORDER BY lcee.nombre_examen";

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
        
        
	//*************************************************FUNCIONES PARA MANEJO DE PAGINACION******************************************************/
	//consultando el numero de registros de la tabla
	function NumeroDeRegistros( $lugar ) {
		//creamos el objeto $con a partir de la clase ConexionBD
		$con = new ConexionBD;
		//usamos el metodo conectar para realizar la conexion
		if ( $con->conectar()==true ) {
			$query =    "SELECT lcee.id,lcee.nombre_examen,
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
                                    AND lppe.idestablecimiento=$lugar";



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
             
            //habilitado='t' = 'Habilitado'
            if($cond=='t'){
                 $query="UPDATE lab_procedimientosporexamen SET habilitado='f',
					fechafin=NULL,
					idusuarioreg=$usuario,
					fechahoramod=NOW()
					 WHERE id=$idlppe ";
                $result = pg_query($query);
              //  $query1= "UPDATE lab_examenes SET Habilitado='N' WHERE IdExamen='$idexamen'" ;
               // $result1 = pg_query($query1);
             }
            // habilitado='f' = 'Inhabilitado'
             if($cond=='f'){
                 //$query = "UPDATE lab_conf_examen_estab SET condicion='H' WHERE id=$idexamen";
                 $query="UPDATE lab_procedimientosporexamen SET habilitado='t',
					fechafin=current_date,
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
			 $query =

				"SELECT lppe.id AS idprocedimientoporexamen,
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
						(CASE WHEN lcee.condicion='H' THEN 'Habilitado'
						WHEN lcee.condicion='I' THEN 'Inhabilitado' END) AS cond1,
                                                (CASE WHEN lppe.habilitado='t' THEN 'Habilitado'
						WHEN lppe.habilitado='f' THEN 'Inhabilitado' END) AS cond,
						lppe.habilitado,
                                                lppe.id as idlppe,
                                                mnt4.id as idmnt4,
                                                lcee.condicion
						FROM lab_procedimientosporexamen 		   lppe
						INNER JOIN lab_conf_examen_estab		   lcee ON (lcee.id = lppe.id_conf_examen_estab)
						INNER JOIN mnt_area_examen_establecimiento  mnt4 ON (mnt4.id = lcee.idexamen)
						INNER JOIN ctl_area_servicio_diagnostico    casd ON (casd.id = mnt4.id_area_servicio_diagnostico)
						INNER JOIN lab_areasxestablecimiento        laxe ON (casd.id = laxe.idarea)
						INNER JOIN lab_plantilla                    lpla ON (lpla.id = lcee.idplantilla)
						LEFT OUTER JOIN ctl_sexo                    cex  ON (cex.id  = lppe.idsexo)
						LEFT OUTER JOIN ctl_rango_edad              cre  ON (cre.id  = lppe.idrangoedad)
						WHERE 
                                                lcee.condicion = 'H' 
                                                AND laxe.condicion = 'H' 
                                                AND lpla.idplantilla = 'E' AND 
                                                lppe.idestablecimiento = $lugar
						ORDER BY lcee.codigo_examen, lppe.id LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";

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
