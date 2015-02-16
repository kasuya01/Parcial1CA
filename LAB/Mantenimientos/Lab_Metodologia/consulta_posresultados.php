<?php
include_once("clsLab_metodologia.php");
@session_start();
$ROOT_PATH = $_SESSION['ROOT_PATH'];
?>
<html>
    <head>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
            function list_reload(obj,acn){
               var eli=0;
               if (obj=='list'){
                  obj=$('#lista option:selected');
                  objval=$('#lista option:selected').val();
                  objtext=$('#lista option:selected').text();
                  eli=1;
               }
               else{
                  obj_posresultado = obj;
                  objtext=obj_posresultado.options[obj_posresultado.selectedIndex].text;
               }
               
               if (acn==1){
                  codreporte=$('#cmbcodigoresultado option:selected').val();
               }
               if (codreporte==0){
                  alert ('Seleccione una opción del resultado de tabulador por favor');
                  return false
               }
               
                  var obj_lista_sel=document.getElementById('lista_sel');
                 
                  if (acn===1){ // si la accion en agregar una metodologia seleccionada
                    var option=window.opener.document.createElement("option");//creamos el elemento
                    option.value=objval;//asignamos valores a sus parametros
                    option.id=codreporte;
                   // option.text=cod_reporte;
                    option.text=objtext;
                    obj_lista_sel.appendChild(option);//insertamos el elemento
                }
                else { // quitar una metodologia de la lista seleccionada y agregarla a la lista original
                    var obj_lista=document.getElementById('lista'); //ponemos la referencia al select en una variable para no escribir tanto
                    var option=window.opener.document.createElement("option");//creamos el elemento
                    //option.value=objval;//asignamos valores a sus parametros
                    option.value=obj_posresultado.value;//asignamos valores a sus parametros
                    //option.text=obj[obj.selectedIndex].text;
                    option.text=obj_posresultado.options[obj_posresultado.selectedIndex].text;
                    obj_lista.appendChild(option);//insertamos el elemento
//                    option.text=objtext;
//                    obj_lista.appendChild(option);//insertamos el elemento                                     
                }
                 if (eli==1){
                     aBorrar = obj;
                     aBorrar.remove();
                 }
                 else{
                    aBorrar = obj.options[obj.selectedIndex];
                    aBorrar.parentNode.removeChild(aBorrar);
                 }
                
                /*
                 * leer lista seleccionada
                 */
                txt_id = "";
                txt_text = "";
                id_text = "";
                for (i=0; i<obj_lista_sel.length;i++){
                    txt_id = txt_id+obj_lista_sel.options[i].value+',';
                    txt_text = txt_text+obj_lista_sel.options[i].text+',';
                    id_text = id_text+obj_lista_sel.options[i].id+',';
                }
                document.getElementById('posresultados_sel').value = txt_id;
                document.getElementById('text_posresultados_sel').value = txt_text;
                document.getElementById('id_posresultados_sel').value = id_text;
                $("#myModal").modal("hide");
                $("#cmbcodigoresultado option[value='0']").attr('selected', 'selected');
              // return false;
               
               //////////////////////////////////////////////////////////////////////////////
               
              //alert(obj_posresultado+'/'+obj_posresultado.value+'/'+obj_posresultado.options[obj_posresultado.selectedIndex].text)
//                obj = obj_posresultado;
//                objtext=obj_posresultado.options[obj_posresultado.selectedIndex].text;
//                if (acn===1){
//                    //var forma_reporte = prompt("Por favor ingrese nombre que reporta", document.getElementById('nombre_prueba').innerHTML);
//                    var forma_reporte = prompt("Por favor ingrese nombre que reporta", obj_posresultado.options[obj_posresultado.selectedIndex].text);
//                }   
//                if(forma_reporte === null || forma_reporte === ""){
//                    return;
//                }
//                var obj_lista_sel=document.getElementById('lista_sel'); //ponemos la referencia al select en una variable para no escribir tanto
//                if (acn===1){ // si la accion en agregar una metodologia seleccionada
//                    var option=window.opener.document.createElement("option");//creamos el elemento
//                    option.value=obj_posresultado.value;//asignamos valores a sus parametros
//                    option.id=forma_reporte;
//                   // option.text=forma_reporte;
//                    option.text=obj_posresultado.options[obj_posresultado.selectedIndex].text;
//                    obj_lista_sel.appendChild(option);//insertamos el elemento
//                } else { // quitar una metodologia de la lista seleccionada y agregarla a la lista original
//                    var obj_lista=document.getElementById('lista'); //ponemos la referencia al select en una variable para no escribir tanto
//                    var option=window.opener.document.createElement("option");//creamos el elemento
//                    option.value=obj_posresultado.value;//asignamos valores a sus parametros
//                    //option.text=obj[obj.selectedIndex].text;
//                    option.text=obj_posresultado.options[obj_posresultado.selectedIndex].text;
//                    obj_lista.appendChild(option);//insertamos el elemento                                     
//                }
//                /*
//                 * borrar el item trasladado
//                 */
//                aBorrar = obj.options[obj.selectedIndex];
//                aBorrar.parentNode.removeChild(aBorrar);
//                /*
//                 * leer lista seleccionada
//                 */
//                txt_id = "";
//                txt_text = "";
//                id_text = "";
//                for (i=0; i<obj_lista_sel.length;i++){
//                    txt_id = txt_id+obj_lista_sel.options[i].value+',';
//                    txt_text = txt_text+obj_lista_sel.options[i].text+',';
//                    id_text = id_text+obj_lista_sel.options[i].id+',';
//                }
//                document.getElementById('posresultados_sel').value = txt_id;
//                document.getElementById('text_posresultados_sel').value = txt_text;
//                document.getElementById('id_posresultados_sel').value = id_text;
//    $('#myModal').modal('hide');         
   }
   

function abrirmodal(){
    $('#myModal').modal('toggle');
}
   
           
            
        </script>
        
    </head>
    <body>
        <div id="divmetodo" class="panel panel-primary">
         
           
        <?php
        /* 
         * Julio Castillo
         */
        
        extract($_GET);
        
        $obj = new clsLab_metodologia();
        
        /*
         * creando arreglo de elementos seleccionados
         */
        $aposresultados = explode(',',$posresultados_sel);
        $aposresultados_text = explode(',',$text_posresultados_sel); 
        $aposresultados_id = explode(',',$id_posresultados_sel); 
        
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
        $consulta = $obj->posresultados();
        $r = pg_fetch_array($consulta);
       
      echo '<strong><font color="white"> 
            <div class="panel-heading" style="background-color: #428bca">
            <center>
            <h3>Metodología: <strong><label id="nombre_prueba">'.$nombre_prueba.'</label> </strong></h3>
                <h4><strong>Dar un clic sobre la opción para seleccionar los posibles resultados.</strong></h4>
                
            </center></div>     </font></strong>';       
           
  
       
        
        /*
         * mostrar tabla con listas de metodologias
         */
        pg_result_seek($consulta, 0);
        $table = "<br/><table align='center'  class='table table-bordered table-condensed table-white no-v-border' style='width:100%' ond><thead>";
       // $table .= "<tr><head><center><label id='nombre_prueba'>".$nombre_prueba."</label></center></head></tr>";
        
        $table .= "<tr><th style='text-align:center; width:300px;'>Posible Resultado</th>"
                . "<th style='text-align:center;width:300px;'>Selecci&oacute;n</th></tr></thead><tbody>";
        $table .= "<td><select name='lista' id='lista' size=22 style='width: 100%;height:400px;' ondblclick='abrirmodal()'>";
        while ($r = pg_fetch_array($consulta)){
           $metodologia=utf8_encode($r['posible_resultado']);
            if (!in_array($r['id'], $aposresultados)) 
              $table .= "<option value='$r[id]'  data-target='#myModal'>$metodologia </option>";  
        }
        $table .= "</select></td>";
        
        /*
         * mostrar segundo select con metodologias seleccionadas
         */
        $table .= "<td><select name='lista_sel' id='lista_sel' size=22 style='width: 100%; height:400px;' ondblclick='list_reload(this,2)'>";
        for ($i=0;$i<count($aposresultados);$i++){
            if ($aposresultados[$i]!=""){
                $table .= "<option value='$aposresultados[$i]' id='$aposresultados_id[$i]'>".utf8_encode($aposresultados_text[$i])."</option>"; 
            }
        }
        
        
        
//        pg_result_seek($consulta, 0);
//        while ($r = pg_fetch_array($consulta)){
//            if (in_array($r['id_metodologia'], $aMetodologias)) $table .= "<option value='$r[id_metodologia]' >$r[metodologias]</option>";  
//        }
        $table .= "</select></td></tr></tbody></table>";
        
        print utf8_decode($table);
        ?> 
        <div align="center"><br>
          <button type='button' class='btn btn-primary' id='Aceptar' onclick='cerrar(); '>
               <span class='glyphicon glyphicon-ok-circle'></span> 
               Aceptar
          </button><br/>
        <input type="hidden" name="posresultados_sel" id="posresultados_sel" value="<?php print $posresultados_sel; ?>">
        <input type="hidden" name="text_posresultados_sel" id="text_posresultados_sel" value="<?php print $text_posresultados_sel; ?>" >
        <input type="hidden" name="id_posresultados_sel" id="id_posresultados_sel" value="<?php print $id_posresultados_sel; ?>">
        <br></div>
           
<!--           <div id="dialog-form" class="modal"><center>
              <label>Seleccione el tipo de resultado en tabulador</label>
              <select id="cmbcodigoresultado" name="codigoresultado" class="form-control height" style="width: 40%">
                 <option value="0">Seleccione una opción</option>
                 <?php
//                 $d=$obj->buscarcodigores();
//                 
//                 while ($res=pg_fetch_array($d)){
//                    echo ' <option value="'.$res['id'].'">'.$res['id'].'-'.$res['resultado'].'</option>';
//                 }
//                 
                 ?>
                 
                 
              </select>
              </center>
           </div>
           
           
            Button trigger modal 
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button>-->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Seleccione el tipo de resultado en tabulador</h4>
      </div>
      <div class="modal-body">
         <center>
              <label></label>
              <select id="cmbcodigoresultado" name="codigoresultado" class="form-control height" style="width: 40%">
                 <option value="0">Seleccione una opción</option>
                 <?php
                 $d=$obj->buscarcodigores();
                 
                 while ($res=pg_fetch_array($d)){
                    echo ' <option value="'.$res['id'].'">'.$res['id'].'-'.$res['resultado'].'</option>';
                 }
                 
                 ?>
                 
                 
              </select>
         </center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="list_reload('list',1)">Aceptar</button>
      </div>
    </div>
  </div>
</div>

        </body>
    
    
    <script language="JavaScript">
        function cerrar(){
            opener.document.<?php print $form; ?>.posresultados_sel.value=document.getElementById('posresultados_sel').value;
            opener.document.<?php print $form; ?>.text_posresultados_sel.value=document.getElementById('text_posresultados_sel').value;
            opener.document.<?php print $form; ?>.id_posresultados_sel.value=document.getElementById('id_posresultados_sel').value;
            window.close();
        }
    </script>    
    
</html>
