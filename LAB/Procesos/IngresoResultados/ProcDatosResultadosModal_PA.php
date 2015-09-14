  <div id="agregarexamenpag">
            
         </div>
         <div id="agregarexamen">
              
            <table class="table table-bordered table-condensed table-hover table-white">
               <thead>
                  <tr><th colspan="2"><center>Listado de examenes solicitados en la orden de laboratorio</center></th></tr>
                  <tr><th>Exámen</th>
                  <th>Estado</th>
                  </tr>
               
               </thead>
               <tbody>
                  <?php
                  $todosexamen=$objdatos->buscartodosexamens($solicitud);
                  while ($row5=  pg_fetch_array($todosexamen)){
                     echo '<tr>'
                     . '<td>'.$row5["nombre_examen"].'</td>'
                     . '<td>'.$row5["descripcion"].'</td>'
                     . '</tr>';
                  }
                  ?>
               </tbody>
            </table>
         </div>