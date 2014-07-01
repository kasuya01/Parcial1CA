<?php
session_start();
$esdestruida=session_destroy();
if($esdestruida){
	header("Location: login.php");
	exit();
} 
?>
