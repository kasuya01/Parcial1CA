<?php session_start();
include("clsSubElementosExamen.php");
$objdatos=new clsSubElementosExamen;
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
$ROOT_PATH = $_SESSION['ROOT_PATH'];
?>
<html>
<head> 
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">  

<title>Mantenimiento de Examenes de Laboratorio</title>
<?php include_once $ROOT_PATH."/public/css.php";?>
        <?php include_once $ROOT_PATH."/public/js.php";?>
<script language="JavaScript" type="text/javascript" src="ajax_ElementosExamen.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<script language="JavaScript" >
function Guardar(){
   	IngresarRegistroSubElemento();
}
function Buscar(){
	BuscarDatos();
}
function Actualizar(){
	enviarDatos();
}
function Nuevo(){
	MostrarFormularioNuevoSub();
}
function Cancelar()
{
    show_subelemento(1,idelemento);
}
function RecogeValor()
{
    var vtmp=location.search;
    var vtmp2 = vtmp.substring(1,vtmp.length);
    //alert(vtmp2);
    var query = unescape(top.location.search.substring(1));
    var getVars = query.split(/&/);
    for ( i = 0; i < getVars.length; i++)
    {
                if ( getVars[i].substr(0,5) == 'var1=' )
                       idelemento = getVars[i].substr(5);
                if ( getVars[i].substr(0,5) == 'var2=' )
                       elemento = getVars[i].substr(5);
                if ( getVars[i].substr(0,5) == 'var3=' )
                       examen = getVars[i].substr(5); 
                if ( getVars[i].substr(0,5) == 'var4=' )
                       idexamen = getVars[i].substr(5);
                if ( getVars[i].substr(0,5) == 'var5=' )
                       cod = getVars[i].substr(5);      

    }
document.frmnuevo.idelemento.value=idelemento;
//alert(idelemento);
document.frmnuevo.txtelemento.value=elemento;

document.frmnuevo.txtexamen.value=examen;
document.frmnuevo.idexamen.value=idexamen;
//document.frmnuevo.txtcod.value=cod;

show_subelemento(1,idelemento);
//llenarcomboRango(cod);
}

function Cancelar()
{
    show_subelemento(1,idelemento);
    LimpiarSubElementos()
}

    function popup(URL) {
        myWindow=window.open(URL, '" + "', 'scrollbars=yes, width=700, height=500, left=100, top = 100');
    }

/*function llenarcomboRango(idelemento){
    //alert(idexa);
    LlenarRango(idelemento);
    
}*/
</script>


</head>

<body onLoad="RecogeValor();">
    <?php
        $idelemento=$_GET['var1'];
        //echo $idele;
    ?>
<br>

<table align="center" width="80%">
<tr>
    <td>
        <div  id="divFrmNuevo" >
            <form name="frmnuevo">
                <table width="90%" border="0" align="center" class="StormyWeatherFormTABLE">
                    <tr>
                        <td colspan="4" align="center" class="CobaltFieldCaptionTD"><h3><strong>SubElementos de  Examen</h3></strong>
			</td>
                    </tr>
                    <tr>
                    	<td class="StormyWeatherFieldCaptionTD">Examen</td>
			<td colspan="3" class="StormyWeatherDataTD" width="63%">
                        	<input name="idexamen" type="hidden" id="idexamen" >
                                <input name="txtexamen" type="text" id="txtexamen" value="" size="60"  class="form-control height" disabled="disabled">
			</td>
                    </tr>
                    <tr>
                    	<td class="StormyWeatherFieldCaptionTD" >Elemento</td>
			<td colspan="3" class="StormyWeatherDataTD" width="63%">
                        	<input name="idelemento" type="hidden" id="idelemento">
				<input name="txtelemento" type="text" id="txtelemento" value="" size="60" disabled="disabled" class="form-control height">
			</td>
                    </tr>
                    <tr>
                        <td  class="StormyWeatherFieldCaptionTD" width="17%">SubElemento</td>
                        <td colspan="3" class="StormyWeatherDataTD" width="63%"><input name="txtsubelemento" type="text" id="txtsubelemento" placeholder=" Nombre SubElemento" size="60" class="form-control height">
                        </td>
                    </tr>
                    <tr>
                        <td  class="StormyWeatherFieldCaptionTD">Sexo</td>
                        <td colspan="3" class="StormyWeatherDataTD">
                            <select id="cmbSexo" name="cmbSexo" size="1" class="form-control height">
                                <option value="3">Seleccione Sexo </option>
                                <option value="0">Ambos</option>
                                    <?php
                                        $consultaS= $objdatos->consultarsexo();
                                        while($row =pg_fetch_array($consultaS)){
                                            echo "<option value='" . $row[0]. "'>". $row[1] . "</option>";
                                        }
                                    ?>        
                            </select>		  
			</td>        
                    </tr>
                    <tr>
                        <td class="StormyWeatherFieldCaptionTD">Rango Edad</td>
                        <td colspan="3" class="StormyWeatherDataTD">
                            <select id="cmbEdad" name="cmbEdad" size="1"  class="form-control height">
                                <option value="0" >Seleccione un Rango de Edad</option>
                                    <?php
                                        $conEdad = $objdatos->RangosEdades();
                                        while($row = pg_fetch_array($conEdad)){
                                            echo "<option value='" . $row[0]. "'>". $row[1] . "</option>";
                                        }
                                    ?>    
                            </select>		  
                        </td>        
                    </tr>
                    <tr>
                        <td  class="StormyWeatherFieldCaptionTD" width="17%">Unidad</td>
                        <td  colspan="3" class="StormyWeatherDataTD" width="63%"><input name="txtunidad" type="text" id="txtunidad" size="20" class="form-control height" placeholder=" Ingrese Unidad">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="StormyWeatherDataTD" >
                            <fieldset ><legend><span >Rangos</span></legend>
                             <table width="200" border="0" align="center" class="StormyWeatherFormTABLE">
                                    <tr>
                                        <td class="StormyWeatherFieldCaptionTD" width="15%">Inicio</td>
                                        <td class="StormyWeatherDataTD"><input name="txtrangoini" type="text" id="txtrangoini" size="8"  placeholder="Rango Inicial" style="width:150px" class="form-control height placeholder"></td>
                                        <td class="StormyWeatherFieldCaptionTD" style="width:200px">Fin </td>
                                        <td class="StormyWeatherDataTD"><input name="txtrangofin" type="text" id="txtrangofin" size="8"  placeholder="Rango Final" style="width:150px" class="form-control height placeholder"></td>
                                    </tr>
                                </table>
                            </fieldset>               
                        </td>
                    </tr>
                    
                   
                    <tr>
                        <td  class="StormyWeatherFieldCaptionTD">Fecha Inicio</TD>
                        <td  class="StormyWeatherDataTD">
                            <input name="txtFechainicio" type="text" id="txtFechainicio" size="10" style="width:250px" placeholder="aaaa-mm-dd" class="date form-control  height placeholder">
                      
                        </td>
			<td  class="StormyWeatherFieldCaptionTD">Fecha Final</TD>
                        <td  class="StormyWeatherDataTD">
                            <input name="txtFechaFin" type="text" id="txtFechaFin" size="10" width="17%" placeholder="aaaa-mm-dd" class="date form-control  height placeholder">
                        </td>
                    </tr>
                    <tr>
                        <td  class="StormyWeatherFieldCaptionTD">Orden </td>
                        <td  class="StormyWeatherDataTD" colspan="3">
                            <select   name="cmborden"  id="cmborden" style="width:50%"  class="form-control height"  > 
                                <option value="0">Seleccione un Orden</option>
                                    <?php
                                    
                                        $datosDB=0;
                                        $conOrden = $objdatos->llenarrangosubele($idelemento);
                                        while($row = pg_fetch_array($conOrden)){
                                            if($row['orden'] === $orden){
                                                echo "<option value='" . $orden . "' selected='selected'>" .$orden. "</option>";}
                                            else{
                                                $datosDB=$objdatos->existeOrden($idelemento);
                                            }
                                        }
                                        
                                        for ($index = $datosDB ; $index <=25 ; $index++) 
                                            {
                                                echo '<OPTION VALUE="'.$index.'">'.$index.'</OPTION>';  
                                            }
                                                                                         
                                    ?>  
                            </select>
				</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="StormyWeatherDataTD" align="right">
                            <button type="button" name="Submit" class="btn btn-primary" value="Guardar" onClick="Guardar() ;"><span class='glyphicon glyphicon-floppy-disk'></span> Guardar </button>
                            <button type="button" name="Submit" class="btn btn-primary" value="Cerrar"  onClick="window.close() ;">Cerrar </button>
                        </td>
                    </tr>
		</table>
		</form>
		</div>
	</td>
</tr>
<tr>
	<td>
		<div  id="divFrmModificar" >

		</div>
		<div  id="divinicial" >

		</div>

		<div  id="divresultado" >
		</div>
	</td>
</tr>
</table>
</body>
</html>
