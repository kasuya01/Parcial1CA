<?php 
class clsCombinaciones
{
 //constructor    
 function clsCombinaciones(){
 }

function ComboProcedimientos(){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$consulta  = "SELECT mnt_procedimientosxestablecimiento.IdCiq,UPPER(mnt_ciq.Procedimiento)
			FROM mnt_procedimientosxestablecimiento
			INNER JOIN mnt_ciq ON mnt_procedimientosxestablecimiento.IdCiq=mnt_ciq.IdCiq
			GROUP BY mnt_procedimientosxestablecimiento.IdCiq";
                $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
                              
	}
	return $resultado;
}

function LlenadoLista(){

}
	
	function AgregarUsuario($IdEmpleado,$login,$password){
		$query="insert into mnt_usuarios (login,password,nivel,modulo,Grupo,IdEmpleado) values('$login','$password','1','SEL','0','$IdEmpleado')";
		mysql_query($query);		
		
	}//Agregar Usuarios al sistema
	
	
	function ObtenerUsuarios($EstadoCuenta){
		$query="select * from usuarios where Nivel=".$EstadoCuenta;
		$resp=mysql_query($query);
		return($resp);		
	}//Obtencion de usuarios
	
	function Bloqueo($iduser){
		$query="update usuarios set Nivel=0 where IdUser=".$iduser;
		mysql_query($query);
	}
	
	function Habilitar($iduser){
		$query="update usuarios set Nivel=1 where IdUser=".$iduser;
		mysql_query($query);
	}

}//Clase