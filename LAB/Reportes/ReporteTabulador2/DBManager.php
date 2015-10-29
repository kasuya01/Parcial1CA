<?php 
class ConexionBD {

    var $conect;

    //Metodo constructor
    function ConexionBD() {
        
    }

    function conectar() {
        if (!isset($con)) {
            //$con = pg_connect("host=192.168.10.125 port=5432 dbname=siap_stgomaria user=siap password=b4s3-s14p")
          // $con = pg_connect("host=192.168.10.125 port=5432 dbname=siap_sanrafael user=siap password=b4s3-s14p")
           $con = pg_connect("host=192.168.10.125 port=5432 dbname=siap_diazdelpinal user=siap password=b4s3-s14p")          
                    or die("Error al conectar a la base de datos --> " . pg_last_error($con));
           /* $con = pg_connect("host=localhost port=5432 dbname=siap_stgomaria user=siap password=b4s3-s14p")
                    or die("Error al conectar a la base de datos --> " . pg_last_error($con));*/
        }

        $this->conect = $con;
        return true;
    }

    function desconectar() {
        pg_close();
    }
}
?>