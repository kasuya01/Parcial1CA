<html>
    <head>
        <title>Seleccionar metodolog&iacute;as</title>
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
                if (acn===1){
                    var forma_reporte = prompt("Por favor ingrese nombre que reporta", document.getElementById('nombre_prueba').innerHTML);
                }   
                if(forma_reporte === null || forma_reporte === ""){
                    return;
                }
                var obj_lista_sel=document.getElementById('lista_sel'); //ponemos la referencia al select en una variable para no escribir tanto
                if (acn===1){ // si la accion en agregar una metodologia seleccionada
                    var option=window.opener.document.createElement("option");//creamos el elemento
                    option.value=obj_metodologia.value;//asignamos valores a sus parametros
                    option.text=forma_reporte;
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
        <div id="">Use doble clic para seleccionar la metodolog&iacute;a</div>
        <?php
        /* 
         * Julio Castillo
         */
        
        extract($_GET);
        include_once("clsLab_Examenes.php");
        $obj = new clsLab_Examenes;
        
        /*
         * creando arreglo de elementos seleccionados
         */
        $aMetodologias = explode(',',$metodologias_sel);
        $aMetodologias_text = explode(',',$text_metodologias_sel); 
        
         /*
         * conocer el nombre de la prueba si es modificacion
         */
        if (isset($id_examen)){
            $consulta = $obj->prueba_lab($id_examen);
            $r = pg_fetch_array($consulta);
            $nombre_prueba = $r['nombre_prueba'];
        } else {
            $nombre_prueba = $nombre;
        }
        
        /*
         * crear listado de metodologias existentes
         */
        $consulta = $obj->metodologias();
        $r = pg_fetch_array($consulta);
        
       
        
        /*
         * mostrar tabla con listas de metodologias
         */
        pg_result_seek($consulta, 0);
        $table = "<table align='center'>";
        $table .= "<tr><head><center><label id='nombre_prueba'>".$nombre_prueba."</label></center></head></tr>";
        $table .= "<tr><td>Metodologías</td><td>Selección</td></tr>";
        $table .= "<td><select name='lista' id='lista' size=22 style='width: 300px' ondblclick='list_reload(this,1)'>";
        while ($r = pg_fetch_array($consulta)){
            if (!in_array($r['id_metodologia'], $aMetodologias)) $table .= "<option value='$r[id_metodologia]' >$r[metodologias]</option>";  
        }
        $table .= "</select></td>";
        
        /*
         * mostrar segundo select con metodologias seleccionadas
         */
        $table .= "<td><select name='lista_sel' id='lista_sel' size=22 style='width: 300px' ondblclick='list_reload(this,2)'>";
        for ($i=0;$i<count($aMetodologias);$i++){
            if ($aMetodologias[$i]!=""){
                $table .= "<option value='$aMetodologias[$i]' >".utf8_encode($aMetodologias_text[$i])."</option>"; 
            }
        }
        
        
        
//        pg_result_seek($consulta, 0);
//        while ($r = pg_fetch_array($consulta)){
//            if (in_array($r['id_metodologia'], $aMetodologias)) $table .= "<option value='$r[id_metodologia]' >$r[metodologias]</option>";  
//        }
        $table .= "</select></td></tr></table>";
        
        print utf8_decode($table);
        ?> 
        <div align="center"><br><input type="button" value="Aceptar" onclick="cerrar()"></div>
        <input type="hidden" name="metodologias_sel" id="metodologias_sel" value="<?php print $metodologias_sel; ?>">
        <input type="hidden" name="text_metodologias_sel" id="text_metodologias_sel" value="<?php print $text_metodologias_sel; ?>">
    </body>
    
    
    <script language="JavaScript">
        function cerrar(){
            opener.document.<?php print $form; ?>.metodologias_sel.value=document.getElementById('metodologias_sel').value;
            opener.document.<?php print $form; ?>.text_metodologias_sel.value=document.getElementById('text_metodologias_sel').value;
            window.close();
        }
    </script>    
    
</html>
