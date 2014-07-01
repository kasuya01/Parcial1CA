<?php session_start();		
include_once("clsUsuarios.php");
$objdatos = new clsUsuarios;
$login=htmlentities($_POST['txtlogin']);
$password=htmlentities($_POST['txtpassword']);
  
   $numreg = $objdatos->validarexistencia($login,$password);
   if ($numreg == "1")
   {
	$datos=$objdatos->datosusuario($login,$password);
	$row_datos = mysql_fetch_array($datos);
	$lugar=$row_datos['IdEstablecimiento'];
	$nivel=$row_datos['NIVEL'];
	$area=$row_datos['IdArea'];
        $corr=$row_datos['Correlativo'];
        $cod=$row_datos['IdEmpleado'];
        $_SESSION['iduser']=$row_datos['iduser']; 
        $_SESSION['Modalidad']=$row_datos['area']; //Llevaran 1:Minsal 2:FOSALUD del user logged
	switch ($nivel) 
	{
	    case 1://Administrador y jefe del laboratorio
		//$_SESSION['user']=$login;
			$_SESSION['NIVEL']=$nivel;
	  		$_SESSION['Lugar']=$lugar;
			$_SESSION['Idarea']=$area;
			$_SESSION['Correlativo']=$corr;
                        $_SESSION['IdEmpleado']=$cod;
			header("Location: ../Laboratorio/PaginaPrincipal/index_laboratorio.php");
	    break;
	    case 2://SemiAdministrador o jefe se areas o secciones
			$_SESSION['NIVEL']=$nivel;
	  		$_SESSION['Lugar']=$lugar;
			$_SESSION['Idarea']=$area;
			$_SESSION['Correlativo']=$corr;
                        $_SESSION['IdEmpleado']=$cod;
                        header("Location: ../Laboratorio/PaginaPrincipal/index_laboratorio21.php");
	    break;
	    case 31://Recepcion
			$_SESSION['NIVEL']=$nivel;
	  		$_SESSION['Lugar']=$lugar;
			$_SESSION['Idarea']=$area;
			$_SESSION['Correlativo']=$corr;
                        $_SESSION['IdEmpleado']=$cod;
			header("Location: ../Laboratorio/PaginaPrincipal/index_laboratorio231.php");
	    break;
	    case 32://Toma de Muestras
		header("Location: ../Laboratorio/PaginaPrincipal/index_laboratorio232.html");
	    break;
	    case 33://Recepcion en el Area de Laboratorio
                        $_SESSION['NIVEL']=$nivel;
                        $_SESSION['Lugar']=$lugar;
                        $_SESSION['Idarea']=$area;
                        $_SESSION['Correlativo']=$corr;
                        $_SESSION['IdEmpleado']=$cod;
		header("Location: ../Laboratorio/PaginaPrincipal/index_laboratorio233.php");
	    break;
           
            case 4://Usuario Externo
                $_SESSION['NIVEL']=$nivel;
  		$_SESSION['Lugar']=$lugar;
		$_SESSION['Idarea']=$area;
		$_SESSION['Correlativo']=$corr;
                $_SESSION['IdEmpleado']=$cod;
                header("Location: ../Laboratorio/PaginaPrincipal/index_laboratorio4.php");
            break;    
	}  //switch de nivel
   }
   else
   {
	header("Location: ../Laboratorio/login.php");
   }
 

?>
