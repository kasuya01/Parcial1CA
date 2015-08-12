<?php

$local=$_POST['cmbEstablecimiento'];
echo $local;
switch ($local) 
	{
	    case 1:
               
                header("Location:http://192.168.10.22/siap/Laboratorio/login.php");
            break;
	    case 2:
                header("Location:http://192.168.10.9/siap/Laboratorio/login.php");
            break;   
        } 
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
