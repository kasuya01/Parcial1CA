<?php
include_once("clsSubElementosExamen.php");
$obj = new clsSubElementosExamen;
@session_start();
$ROOT_PATH = $_SESSION['ROOT_PATH'];
?>


<html>
    <head>
       <meta charset="UTF-8">
       <meta http-equiv="Content-Type" content="text/html" />
        <title>Seleccionar Posibles Resultados</title>
        <?php include_once $ROOT_PATH."/public/css.php";?>
        <?php include_once $ROOT_PATH."/public/js.php";?>
        <script language="JavaScript" >
            /*
             * Juio castillo
             * 
             * recargar las listar de seleccion
             * para ver el efecto de agregar y quitar
             * 
             */
            function list_reload(obj_metodologia,acn){
                obj = obj_metodologia;
//                if (acn===1){
//                    var forma_reporte = prompt("Por favor ingrese nombre que reporta", document.getElementById('nombre_prueba').innerHTML);
//                }   
//                if(forma_reporte === null || forma_reporte === ""){
//                    return;
//                }
                var obj_lista_sel=document.getElementById('lista_sel'); //ponemos la referencia al select en una variable para no escribir tanto
                if (acn===1){ // si la accion en agregar una metodologia seleccionada
                    var option=window.opener.document.createElement("option");//creamos el elemento
                    option.value=obj_metodologia.value;//asignamos valores a sus parametros
                    option.text=obj[obj.selectedIndex].text;
                    obj_lista_sel.appendChild(option);//insertamos el elemento
                } else { // quitar una metodologia de la lista seleccionada y agregarla a la lista original
                    var obj_lista=document.getElementById('lista'); //ponemos la referencia al select en una variable para no escribir tanto
                    var option=window.opener.document.createElement("option");//creamos el elemento
                    option.value=obj_metodologia.value;//asignamos valores a sus parametros
                    option.text=obj[obj.selectedIndex].text;
                    obj_lista.appendChild(option);//insertamos el elemento                                     
                }
                /*
                 * borrar el item trasladado
                 */
                aBorrar = obj.options[obj.selectedIndex];
                aBorrar.parentNode.removeChild(aBorrar);
                /*
                 * leer lista seleccionada
                 */
                txt_id = "";
                txt_text = "";
                for (i=0; i<obj_lista_sel.length;i++){
                    txt_id = txt_id+obj_lista_sel.options[i].value+',';
                    txt_text = txt_text+obj_lista_sel.options[i].text+',';
                }
                document.getElementById('metodologias_sel').value = txt_id;
                document.getElementById('text_metodologias_sel').value = txt_text;
            }
            
            
        </script>
    </head>
    <body>
        <div id="divmetodo" class="panel panel-primary">
        <form method="post">
            <?php
             echo '<strong><font color="white"> 
            <div class="panel-heading" style="background-color: #428bca">
            <center>
            <h3>Use doble clic para seleccionar el resultado </h3>
                <h4><strong>Dar un clic sobre la opción para seleccionar los posibles resultados.</strong></h4>
                
            </center></div>     </font></strong>'; 
            /* 
             * Julio Castillo
             */

            extract($_GET);
            extract($_POST);
            
            
            /*
             * verificar si se estan guardando los resultados
             */
            if(isset($btnGuardar)){
                /*
                 * cambiar estado de habilitado
                 */
                $obj->cambiar_estado($id_subelemento);
                /*
                 * activar id de resultados seleccionados
                 */
                $elementos = split(',',$metodologias_sel);
                for ($i=0;$i<count($elementos)-1;$i++){
                    $obj->cambiar_estado_id($elementos[$i],$id_subelemento);
                }
                echo "<script>window.close();</script>";
            }
            
            
            
            /*
             * crear listado de resultados existentes
             */
            $consulta = $obj->resultados($id_subelemento);
            $r = pg_fetch_array($consulta);



            /*
             * mostrar tabla con listas de resultados
             */
            $result = $obj->get_subelemento($id_subelemento);
            $r = pg_fetch_array($result);
             $table = "<center><span id='nombre_prueba'>".$r['subelemento_text']."</span></center>";
            $table .= "<table align='center' class='table table-bordered table-condensed table-white no-v-border' style='width:100%'><thead>";
           
            $table .= "<tr><th>Resultados</th><th>Seleccionados</th></tr></thead>";
            $table .= "<tbody><td width='50%'><select name='lista' id='lista' size=22 style='width: 100%; height:400px' ondblclick='list_reload(this,1)'>";
            while ($r = pg_fetch_array($consulta)){
               $resultado= utf8_encode($r[resultado]);
                $table .= "<option value='$r[id]' >".$resultado."</option>";  
            }
            $table .= "</select></td>";

            /*
             * mostrar segundo select con resultados seleccionadas
             */
                    /*
             * crear listado de resultados existentes
             */
            $consulta = $obj->resultados_seleccionados($id_subelemento);
            $metodologias_sel="";
            $table .= "<td  width='50%'><select name='lista_sel' id='lista_sel' size=22 style='width: 100%; height:400px;' ondblclick='list_reload(this,2)'>";
            while ($r = pg_fetch_array($consulta)){
                    $table .= "<option value='$r[id]' >".utf8_encode($r[resultado])."</option>";
                    $metodologias_sel .= $r['id'].',';
            }



    //        pg_result_seek($consulta, 0);
    //        while ($r = pg_fetch_array($consulta)){
    //            if (in_array($r['id_metodologia'], $aMetodologias)) $table .= "<option value='$r[id_metodologia]' >$r[metodologias]</option>";  
    //        }
            $table .= "</select></td></tr></tbody></table>";

            print ($table);
            ?> 
            <div align="center"><br><input type="submit" name="btnGuardar" value="Guardar"> <input type="button" value="Cancelar" onclick="window.close()"></div>
            <input type="hidden" name="metodologias_sel" id="metodologias_sel" value="<?php print $metodologias_sel; ?>">
            <input type="hidden" name="id_subelemento" id="id_subelemento" value="<?php print $id_subelemento; ?>">
        </form>
        </div>
        </body>
</html>
