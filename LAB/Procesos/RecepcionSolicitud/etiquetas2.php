<html>
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
        <link href="/PrimerSitio/paginalab.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
        <link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
        <style type="text/css">
            <!--
            @media print{
                #boton{display:none;}
            }
            .Estilo5 {font-size: 10pt}
            .Estilo12 {font-size: 6pt}
            -->
        </style>
        <title>Datos de las Vi&ntilde;etas</title>
        <script language="JavaScript" type="text/javascript" src="ajax_RecepcionSolicitud.js"></script>
        <script language="JavaScript" >
            function RecogeValor()
            {
                var vtmp = location.search;
                var vtmp2 = vtmp.substring(1, vtmp.length);
                var query = unescape(top.location.search.substring(1));
                var getVars = query.split(/&/);
                for (i = 0; i < getVars.length; i++)
                {
                    if (getVars[i].substr(0, 5) == 'var1=')//loops through this array and extract each name and value
                        idexpediente = getVars[i].substr(5);
                    if (getVars[i].substr(0, 5) == 'var2=')
                        fechacita = getVars[i].substr(5);
                }
            }

            imprimir(){
                document.getElementById('btnImprimir').style.visibility = "hidden";
                window.print();
                document.getElementById('btnImprimir').style.visibility = "visible";
            }
        </script>
    </head>
    <body onLoad="RecogeValor();">
        <table  border="0"  align="rigth" class='estilotabla' >
            <tr>
                <td>
                    <div id="boton">	<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" /></div>
                </td>
            </tr>
        </table>
        <?php
        include_once("clsRecepcionSolicitud.php");
        //variables POST
        $idexpediente = $_GET['var1'];
        $fechacita    = $_GET['var2'];
        
        $objdatos = new clsRecepcionSolicitud;

        //recuperando los valores del detalle de la solicitud
        $consultadetalle = $objdatos->VinetasRecepcion($idexpediente, $fechacita);
        while ($fila = pg_fetch_array($consultadetalle)) {
            IF ($fila[3] <> "QUI") {   
            }
            ?>    
            <table width="275" border = 0  class="estilotabla">
                <tr>
                    <td width="50"><span class="Estilo12"><strong>Expediente:</strong></span></td>
                    <td width="65"><span class="Estilo12"><?php echo $fila[1]; ?></span></td>  
                    <td width="21"><span class="Estilo12"><strong>�rea:</strong></span></td> 
                    <td width="45"><span class="Estilo12"><?php echo $fila[3]; ?></span></td>
                    <td width="41"><span class="Estilo12"><strong>Muestra:</strong></span></td> 
                    <td width="60"><span class="Estilo12"><?php echo $fila[0]; ?></span></td>
                </tr>
                <tr>  
                    <td style='font:bold'><span class="Estilo12"><strong>Paciente:</strong></span></td>
                    <td colspan="5" class="Estilo5" ><div align="justify" class="Estilo12"><?php echo htmlentities($fila[2]); ?></div></td>
                </tr>
                <tr>	 
                    <td style="font:bold" ><span class="Estilo12"><strong>Examen:</strong></span></td> 
                    <td colspan="5" ><span class="Estilo12"><?php echo $fila[5]; ?></span></td>
                </tr>
            </table>
            <br>
    <?php
    }
    ?>
    </form>
</body>
</html>
<div id="divFormulario">
</div>
</body>
</html>
