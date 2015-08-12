<?php include '../../../../Conexion/ConexionBD.php';
/*
 * CLASES PRINCIPALES PARA EL INGRESO DE SOLICITUDES
 */

class solicitudes{
    
    function DatosPaciente($IdNumeroExp){
        $query="select concat_ws(' ',PrimerNombre,SegundoNombre,TercerNombre,PrimerApellido,SegundoApellido) as Nombre
                from mnt_datospaciente
                inner join mnt_expediente
                on mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente
                where IdNumeroExp='$IdNumeroExp'";
        $resp=mysql_query($query);
        return $resp;        
    }
    
    function ObtenerEspecialidades($IdEmpleado){
        $query="select mnt_subservicio.IdSubServicio,NombreSubServicio
                from mnt_subservicio
                inner join mnt_usuarios
                on mnt_usuarios.IdSubServicio=mnt_subservicio.IdSubServicio
                where IdEmpleado='$IdEmpleado'";
        $resp=mysql_query($query);
        return $resp;
    }
    
    function ObtenerSolicitudEstudio($IdHistorialClinico){
        $query="select IdSolicitudEstudio from sec_solicitudestudios where IdHistorialClinico=".$IdHistorialClinico;
        $resp=mysql_fetch_array(mysql_query($query));
        return $resp[0];
    }
    
    function IngresoHistorialClinico($IdNumeroExp,$IdEmpleado,$IdSubServicio,$IdEstablecimiento,$IdUsuario,$IpAddress){
        $query="insert into sec_historial_clinico (IdNumeroExp,FechaConsulta,IdEmpleado,IdSubServicio,IdUsuarioReg,FechaHoraReg,IpAddress,IdEstablecimiento,Piloto) 
                                           values ('$IdNumeroExp',curdate(),'$IdEmpleado','$IdSubServicio','$IdUsuario',now(),'$IpAddress','$IdEstablecimiento','C')";
        mysql_query($query);
        $IdHistorialClinico=mysql_insert_id();
        return $IdHistorialClinico;
    }
    
    function ObtenerHistorialClinico($IdNumeroExp,$IdEmpleado,$IdSubServicio,$IpAddress){
        $query="select IdHistorialClinico
                from sec_historial_clinico
                where IdNumeroExp='$IdNumeroExp' 
                and IdEmpleado='$IdEmpleado'
                and IdSubServicio='$IdSubServicio'
                and FechaConsulta=curdate()
                and Piloto='C'";
        $resp=mysql_fetch_array(mysql_query($query));
        return $resp[0];
    }
    
    
    function IngresoSolicitudEstudio($IdNumeroExp,$IdHistorialClinico,$IdUsuario,$IdServicio,$IdEstablecimiento){
        $query="insert into sec_solicitudestudios (IdNumeroExp,IdHistorialClinico,Estado,FechaSolicitud,IdUsuarioReg,FechaHoraReg,IdServicio,IdEstablecimiento) 
                                           values ('$IdNumeroExp','$IdHistorialClinico','D',curdate(),'$IdUsuario',now(),'$IdServicio','$IdEstablecimiento')";
        mysql_query($query);
        $resp=mysql_insert_id();
        return $resp;
    }
    
    function IngresoDetalleSolicitudEstudio($IdSolicitudEstudio,$IdExamen){
        $query="insert into sec_detallesolicitudestudios (IdSolicitudEstudio,IdExamen,EstadoDetalle)
                                                   values('$IdSolicitudEstudio','$IdExamen','D')";
        mysql_query($query);
    }
    
    
    function ObtenerDetalleSolicitudEstudio($IdSolicitudEstudio,$IdExamen = ""){
        if($IdExamen==""){ $comp=""; }else{ $comp=" and lab_examenes.IdExamen='$IdExamen'";}
        
        $query="select IdDetalleSolicitud,lab_examenes.IdExamen,NombreExamen
            from sec_detallesolicitudestudios
            inner join lab_examenes
            on lab_examenes.IdExamen=sec_detallesolicitudestudios.IdExamen
            where IdSolicitudEstudio=".$IdSolicitudEstudio."
            ".$comp;
        $resp=mysql_query($query);
        return $resp;
    }
    
    
    function EliminarExamenes($IdDetalleSolicitud){
        $query="delete from sec_detallesolicitudestudios where IdDetalleSolicitud=".$IdDetalleSolicitud;
        mysql_query($query);        
    }
        
    function EliminarSolicitud($IdSolicitudEstudio){
        $query="select IdHistorialClinico from sec_solicitudestudios where IdSolicitudEstudio=".$IdSolicitudEstudio;
        $resp=mysql_fetch_array(mysql_query($query));
        $IdHistorialClinico=$resp[0];
        
        $query1="delete from sec_detallesolicitudestudios where IdSolicitudEstudio=".$IdSolicitudEstudio;
        mysql_query($query1);
        
        $query2="delete from sec_historial_clinico where IdHistorialClinico=".$IdHistorialClinico;
        mysql_query($query2);
        
        $query3="delete from sec_solicitudestudios where IdSolicitudEstudio=".$IdSolicitudEstudio;
        mysql_query($query3);
        
    }
}

?>
