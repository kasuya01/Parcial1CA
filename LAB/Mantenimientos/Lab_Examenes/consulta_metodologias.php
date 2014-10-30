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
                var obj_lista_sel=document.getElementById('lista_sel'); //ponemos la referencia al select en una variable para no escribir tanto
                if (acn===1){ // si la accion en agregar una metodologia seleccionada
                    //alert(obj[obj.selectedIndex].text);
                    var option=window.opener.document.createElement("option");//creamos el elemento
                    option.value=obj_metodologia.value;//asignamos valores a sus parametros
                    option.text=obj[obj.selectedIndex].text;
                    obj_lista_sel.appendChild(option);//insertamos el elemento
                    /*
                     * quitamos el elemento seleccionado
                     */

                } else { // quitar una metodologia de la lista seleccionada y agregarla a la lista original
                    var obj_lista=document.getElementById('lista'); //ponemos la referencia al select en una variable para no escribir tanto
                    var option=window.opener.document.createElement("option");//creamos el elemento
                    option.value=obj_metodologia.value;//asignamos valores a sus parametros
                    option.text=obj[obj.selectedIndex].text;
                    obj_lista.appendChild(option);//insertamos el elemento
                    /*
                     * quitamos el elemento seleccionado
                     */
                   
                }
                /*
                 * borrar el item trasladado
                 */
                aBorrar = obj.options[obj.selectedIndex];
                aBorrar.parentNode.removeChild(aBorrar);
                /*
                 * leer lista seleccionada
                 */
                txt='';
                for (i=0; i<obj_lista_sel.length;i++){
                    txt = txt+obj_lista_sel.options[i].value+',';
                }
                document.getElementById('metodologias_sel').value = txt;
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
        $table .= "<tr><head><center>".$r['nombre_prueba']."</center></head></tr>";
        $table .= "<tr><td>Metodologías</td><td>Selección</td></tr>";
        $table .= "<td><select name='lista' id='lista' size=22 style='width: 300px' ondblclick='list_reload(this,1)'>";
        while ($r = pg_fetch_array($consulta)){
            if (!in_array($r['id_metodologia'], $aMetodologias)) $table .= "<option value='$r[id_metodologia]' >$r[metodologias]</option>";  
        }
        $table .= "</select></td>";
        
        /*
         * mostrar segundo select con metodologias seleccionadas
         */
        pg_result_seek($consulta, 0);
        $table .= "<td><select name='lista_sel' id='lista_sel' size=22 style='width: 300px' ondblclick='list_reload(this,2)'>";
        while ($r = pg_fetch_array($consulta)){
            if (in_array($r['id_metodologia'], $aMetodologias)) $table .= "<option value='$r[id_metodologia]' >$r[metodologias]</option>";  
        }
        $table .= "</select></td></tr></table>";
        
        print utf8_decode($table);
        ?> 
        <div align="center"><br><input type="button" value="Aceptar" onclick="cerrar()"></div>
        <input type="hidden" name="metodologias_sel" id="metodologias_sel" value="<?php print $metodologias_sel; ?>">
    </body>
    
    
    <script language="JavaScript">
        function cerrar(){
            opener.document.<?php print $form; ?>.metodologias_sel.value=document.getElementById('metodologias_sel').value;
            window.close();
        }
    </script>    
    
</html>
