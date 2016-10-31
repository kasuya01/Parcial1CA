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
        jQuery(document).ready(function($) {
            $('[id^=valor_default_]').select2({
            allowClear: true,
            dropdownAutoWidth: true
         });
        });
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


                modal_elements.push({
                   id: 'addexam_modal', func:'crearmodal', header:'Seleccionar un valor por defecto, si aplica, por favor', footer:'', widthModal: '500'
                 /*
                },
                {
                  id: 'reportvih_modal', func:'reportfvihmodal', header:'FVIH-01', footer:'', widthModal: '900' */
                });

            //}



            //fn pg
            function guardarDefault() {

              var idsolicitud=$("#id_subelemento").val();
              var iddefault=$('[id^=valor_default_]').val();
              console.log(idsolicitud+' - '+iddefault)
              jQuery.ajax({
                 url: "ctrSubElementosExamen.php",
                 method: "POST",
                 async: false,
                 data: {solicitud: idsolicitud, opcion: 7, iddefault: iddefault},
                 dataType: "json",
                 success: function (object) {
                     console.log(object.status)
                    if (object.status) {
                        window.close();
                    }
                 }
              });
            }

            //fn pg
            function crearmodal() {
              var idsolicitud=$("#id_subelemento").val();
              var content='';
             // content+= crearmodaladdexam(idsolicitud);
              content+='<div id="agregarexamen">';
              content+= detallemodaladdexam(idsolicitud);
              content+='</div>';
              $('[id^=valor_default_]').select2({
                  allowClear: true,
                  dropdownAutoWidth: true
               });
            //   content+='<button type="button" class="btn btn-primary">Save changes</button>';
               $('#myModal div.modal-footer').append(
                         '<button type="button" class="btn btn-primary" onclick="guardarDefault()">Confirmar</button>');
               return content;
            }

            function crearmodaladdexam(idsolicitud){
               var content='';
               var idexpediente=$("#idexpediente_").val();
               var fecharecepcion=$("#fecharecepcion").val();
               var idestablecimiento=$("#idestabext_").val();
               var banderacerrar=1;
              jQuery.ajax({
                url: "../AgregarExamen/SolicitudEstudiosPaciente.php",
                type: "GET",
                async: false,
                data: {var1:idexpediente, var2:idsolicitud, var3:idestablecimiento, var4:fecharecepcion, var5:banderacerrar},
                dataType: "html",
                success: function(html) {
                 content+=html;
                }


              });
              return content;
            }
            function detallemodaladdexam(idsolicitud){
               var content= '';
               jQuery.ajax({
                  url: "ctrSubElementosExamen.php",
                  method: "POST",
                  async: false,
                  data: {solicitud: idsolicitud, opcion: 6 },
                  dataType: "json",
                  success: function (object) {
                     if (object.status) {
                         content +='<select id="valor_default_'+idsolicitud+'" name="valor_default_" class="height js-example-basic-single">'+
                                '<option value="xyz">..Ningún valor por defecto...</option>';
                                var b=0;
                        jQuery.each(object.data,function(index, value){
                            if (value.b_default=='t'){
                                content +='<option value="'+value.id+'" selected>'+value.resultado+'</option>';
                            }
                            else{
                                 content +='<option value="'+value.id+'">'+value.resultado+'</option>';
                            }

                        });
                        content +='</select>';
                     }
                  }
               });

               return content;
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

            //    echo "<script>window.close();</script>";
            }



            /*
             * crear listado de resultados existentes
             */
            $consulta = $obj->resultados($id_subelemento);
          //  $r = pg_fetch_array($consulta);



            /*
             * mostrar tabla con listas de resultados
             */
            $result = $obj->get_subelemento($id_subelemento);
            //$r = pg_fetch_array($result);
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

            print utf8_decode($table);
            ?>
            <div align="center"><br>
                 <a  href='#myModal' id='addexam_modal' role='button' data-toggle='modal' data-modal-enabled='true' style='height:20px'><button type='submit'  name='btnGuardar'  id='modaladdexam' align='center' class='btn btn-primary' title='Guardar' ><span class='glyphicon glyphicon-floppy-disk'></span>&nbsp;Guardar</button></a>
            <!--    <a  href='#myModal' id='addexam_modal' role='button' data-toggle='modal' data-modal-enabled='true' style='height:20px'>
                <button type="submit" name="btnGuardar" value="Guardar">Guardar</button>
                </a>  -->
                <button type="button" class='btn btn-default' value="Cancelar" onclick="window.close()">
                    <span class='glyphicon glyphicon-remove'></span>Cancelar</button></div>

            <input type="hidden" name="metodologias_sel" id="metodologias_sel" value="<?php print $metodologias_sel; ?>">
            <input type="hidden" name="id_subelemento" id="id_subelemento" value="<?php print $id_subelemento; ?>">
        </form>
        </div>
        </body>
</html>
