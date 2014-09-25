<?php session_start();
include_once("clsUsuarios.php");
$objdatos = new clsUsuarios;
$login    = htmlentities($_POST['txtlogin']);
$password = htmlentities($_POST['txtpassword']);
//echo 'log: '.$login.'  pass:'.$password.'<br/>';


   $numreg = $objdatos->validarexistencia($login,$password);
   if ($numreg == "1")
   {
	$datos = $objdatos->datosusuario($login,$password);
	$row_datos = pg_fetch_array($datos);

	$lugar = $row_datos['id_establecimiento'];
	$nivel = $row_datos['nivel'];
	$area  = $row_datos['idarea'];
        $corr  = $row_datos['correlativo'];
        $cod   = $row_datos['id_empleado'];

        $_SESSION['ROOT_PATH'] = realpath(__DIR__);

        if(isset($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] == '443') {
            $REQUEST_SCHEME = 'https';
        } else {
            $REQUEST_SCHEME = 'http';
        }

        $base_url = $REQUEST_SCHEME.'://'.$_SERVER['HTTP_HOST'];
        $_SESSION['base_url'] = $base_url; 

	switch ($nivel) {
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
		//header("Location: ../Laboratorio/PaginaPrincipal/index_laboratorio232.html");
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
	//header("Location: ../Laboratorio/login.php");
   }


?>
