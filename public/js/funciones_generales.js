/*
 *  Funcion que permite obtener los atributos de un elemento como un objeto json
 *
 *  Ejemplo:
 *      var $div = $("<div data-a='1' id='b'>");
 *      $div.attr();  // { "data-a": "1", "id": "b" }
 */
(function (old) {
   $.fn.attr = function () {
      if (arguments.length === 0) {
         if (this.length === 0) {
            return null;
         }

         var obj = {};

         $.each(this[0].attributes, function () {
            if (this.specified) {
               obj[this.name] = this.value;
            }
         });

         return obj;
      }

      return old.apply(this, arguments);
   };
})($.fn.attr);
//fin Funcion que permite obtener los atributos de un elemento como un objeto json

/*
 *  slugToCamelCase:
 *      Funcion que permite convertir un string separado por guion '-'
 *      en un string en formato camelCase
 *
 *  ParÃ¡metros:
 *      string: Cadena de texto dividido por guiones.
 *
 *  Retorna:
 *      String en formato camelCase
 *
 *  Ejemplo:
 *      Entrada: 'esto-es-un-string'
 *      Salida:  'estoEsUnString'
 */
function slugToCamelCase(string) {
   return string.replace(/-([a-z])/ig, function (all, letter) {
      return letter.toUpperCase();
   });
}

//Declaracion de variables
var modal_elements = [];

//Función para pedir el formato de fecha deseado
function getCurrentDateTime(format) {
   var today = new Date();
   var dd = today.getDate();
   var mm = today.getMonth() + 1; //January is 0!
   var yyyy = today.getFullYear();
   var hora = today.getHours()
   var minu = today.getMinutes()

   if (dd < 10) {
      dd = '0' + dd;
   }

   if (mm < 10) {
      mm = '0' + mm;
   }
   if (hora < 10)
      hora = '0' + hora;

   if (minu < 10)
      minu = '0' + minu;

   switch (format) {
      case 'dd/mm/yyyy':
         today = dd + '/' + mm + '/' + yyyy + ' ' + hora + ':' + minu;
         break;
      case 'dd-mm-yyyy':
         today = dd + '-' + mm + '-' + yyyy + ' ' + hora + ':' + minu;
         break;
      case 'yyyy/mm/dd':
         today = yyyy + '/' + mm + '/' + dd + ' ' + hora + ':' + minu;
         break;
      case 'yyyy-mm-dd':
         today = yyyy + '-' + mm + '-' + dd + ' ' + hora + ':' + minu;
         break;
      default:
         today = dd + '/' + mm + '/' + yyyy + ' ' + hora + ':' + minu;
         break;
   }

   return today;
}


function valfechasolicita(obj, nombre) {

   //fecha0=document.getElementById('d_fechatoma').value;
   fecha1 = obj;
   var fecha_actual = new Date();
   var dia = fecha_actual.getDate()
   var mes = fecha_actual.getMonth() + 1
   var anio = fecha_actual.getFullYear()
   var hora = fecha_actual.getHours()
   var mins = fecha_actual.getMinutes()

   if (mes < 10)
      mes = '0' + mes;
   if (dia < 10)
      dia = '0' + dia;
   if (hora < 10)
      hora = '0' + hora;
   if (mins < 10)
      mins = '0' + mins;
   fechact = parseInt(anio + "" + mes + "" + dia + "" + hora + "" + mins);

//var f0 = fecha0.split('-');
//var fechaPri = parseInt(f0[0]+f0[1]+f0[2]);
   fecha2 = fecha1.replace(/[-: ]/g, "");

//var f2 = fecha1.split('-');
//var fecha2 = parseInt(f2[0]+f2[1]+f2[2]+f2[3]+f2[4]);


   if (fecha1 != "" && fecha2 > fechact)
   {
      alert('La fecha ingresada es mayor que la fecha actual')
      fechafin = getCurrentDateTime('yyyy-mm-dd');
      document.getElementById(nombre).value = fechafin;
      return false;
   }
}


function valdatesolicita(obj, nombre) {

   //fecha0=document.getElementById('d_fechatoma').value;
   fecha1 = obj;
   var fecha_actual = new Date();
   var dia = fecha_actual.getDate()
   var mes = fecha_actual.getMonth() + 1
   var anio = fecha_actual.getFullYear();

   if (mes < 10)
      mes = '0' + mes;
   if (dia < 10)
      dia = '0' + dia;
   fechact = parseInt(anio + "" + mes + "" + dia);

//var f0 = fecha0.split('-');
//var fechaPri = parseInt(f0[0]+f0[1]+f0[2]);
   fecha2 = fecha1.replace(/[-: ]/g, "");

//var f2 = fecha1.split('-');
//var fecha2 = parseInt(f2[0]+f2[1]+f2[2]+f2[3]+f2[4]);
   if (fecha1 != "" && fecha2 > fechact)
   {
      alert('La fecha ingresada es mayor que la fecha actual')
      fechafin = (anio + "-" + mes + "-" + dia)
      document.getElementById(nombre).value = fechafin;
      return false;
   }
}


function validafecha(obj, nombre, fechacompara) {
//    alert(!$("#"+nombre).datepicker( "widget" ).is(":visible"))
//   if (!$("#"+nombre).datepicker( "widget" ).is(":visible"))
//   {
//      alert ('paso')
   //fecha0=document.getElementById('d_fechatoma').value;
   fecha1 = obj;
   // alert (fechacompara)
   fechact = fechacompara.replace(/[-: ]/g, "");
   //var f0 = fecha0.split('-');
   //var fechaPri = parseInt(f0[0]+f0[1]+f0[2]);
   fecha2 = fecha1.replace(/[-: ]/g, "");
   if (fechact.length > 8)
      fecha2 = fecha2 + '00';
   var fecha_actual = new Date();
   var dia = fecha_actual.getDate()
   var mes = fecha_actual.getMonth() + 1
   var anio = fecha_actual.getFullYear();
   if (mes < 10)
      mes = '0' + mes;
   if (dia < 10)
      dia = '0' + dia;
   //var f2 = fecha1.split('-');
   //var fecha2 = parseInt(f2[0]+f2[1]+f2[2]+f2[3]+f2[4]);
   //alert (fechact.length+' fecha1: '+fecha1+ ' fecha2 : '+fecha2+'   fechact: '+fechact)
   if (fecha1 != "" && fecha2 < fechact)
   {
      alert('La fecha ingresada es menor que la fecha de toma de muestra')
      if (fechact.length == 8) {
         fechafin = (anio + "-" + mes + "-" + dia);
      }
      else {
         fechafin = getCurrentDateTime('yyyy-mm-dd');
      }
      document.getElementById(nombre).value = fechafin;
      return false;
   }
   //}
}

function validafecharesrep(nombre, fechacompara) {
//    alert(!$("#"+nombre).datepicker( "widget" ).is(":visible"))
//   if (!$("#"+nombre).datepicker( "widget" ).is(":visible"))
//   {
//      alert ('paso')
   //fecha0=document.getElementById('d_fechatoma').value;
   fecha1 = obj;
   // alert (fechacompara)
   fechact = fechacompara.replace(/[-: ]/g, "");
   //var f0 = fecha0.split('-');
   //var fechaPri = parseInt(f0[0]+f0[1]+f0[2]);
   fecha2 = fecha1.replace(/[-: ]/g, "");
   if (fechact.length > 8)
      fecha2 = fecha2 + '00';
   var fecha_actual = new Date();
   var dia = fecha_actual.getDate()
   var mes = fecha_actual.getMonth() + 1
   var anio = fecha_actual.getFullYear();
   if (mes < 10)
      mes = '0' + mes;
   if (dia < 10)
      dia = '0' + dia;
   //var f2 = fecha1.split('-');
   //var fecha2 = parseInt(f2[0]+f2[1]+f2[2]+f2[3]+f2[4]);
   alert(fechact.length + ' fecha1: ' + fecha1 + ' fecha2 : ' + fecha2
           + '   fechact: ' + fechact)
   if (fecha1 != "" && fecha2 < fechact)
   {
      alert('La fecha ingresada es menor que la fecha de toma de muestra')
      if (fechact.length == 8) {
         fechafin = (anio + "-" + mes + "-" + dia);
      }
      else {
         fechafin = getCurrentDateTime('yyyy-mm-dd');
      }
      document.getElementById(nombre).value = fechafin;
      return false;
   }
   //}
}


jQuery(document).ready(function ($) {
   checkToSwitch();

   $('.searchable').multiSelect({
      selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Buscar...'>",
      selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Buscar...'>",
      afterInit: function (ms) {
         var that = this,
                 $selectableSearch = that.$selectableUl.prev(),
                 $selectionSearch = that.$selectionUl.prev(),
                 selectableSearchString = '#' + that.$container.attr('id')
                 + ' .ms-elem-selectable:not(.ms-selected)',
                 selectionSearchString = '#' + that.$container.attr('id')
                 + ' .ms-elem-selection.ms-selected';

         that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                 .on('keydown', function (e) {
                    if (e.which === 40) {
                       that.$selectableUl.focus();
                       return false;
                    }
                 });

         that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                 .on('keydown', function (e) {
                    if (e.which == 40) {
                       that.$selectionUl.focus();
                       return false;
                    }
                 });
      },
      afterSelect: function () {
         this.qs1.cache();
         this.qs2.cache();
      },
      afterDeselect: function () {
         this.qs1.cache();
         this.qs2.cache();
      }
   });

   //Modal
   $('body').append(
           '\
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\
            <div class="modal-dialog">\
                <div class="modal-content">\
                    <div class="modal-header">\
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\
                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>\
                    </div>\
                    <div class="modal-body">\
                    </div>\
                    <div class="modal-footer">\
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="color: #636363;font-weight: bold;">Cerrar</button>\
                    </div>\
                </div>\
            </div>\
        </div>');

   $("body").on('click', 'a[data-modal-enabled="true"]', function (e) {
      var currentIDM = $(this).attr("id");
      if (!(typeof modal_elements === 'undefined') && modal_elements.length
              != 0) {
         for (var i = 0; i < modal_elements.length; i++) {
            if (currentIDM == modal_elements[i].id) {
               if (modal_elements[i].empty != true) {
                  /*Limpiando los elementos del modal*/
                  $('#myModal div.modal-header h4#myModalLabel').empty();
                  $('#myModal div.modal-body').empty();
                  $('#myModal div.modal-footer').empty();

                  /*Verificando el contendio a mostrar*/
                  if (typeof modal_elements[i].header === 'undefined'
                          || modal_elements[i].header == '') {
                     modal_elements[i].header = 'Detalle';
                  }

                  if (typeof modal_elements[i].func === 'undefined'
                          || modal_elements[i].func == '') {
                     modal_elements[i].func = 'defalutlModalBodyMessage';
                  }

                  if (typeof modal_elements[i].parameters === 'undefined'
                          || modal_elements[i].func == '') {
                     var modalBody = window[modal_elements[i].func]();
                  } else {
                     var modalBody = window[modal_elements[i].func](
                             modal_elements[i].parameters);
                  }

                  /*Estableciendo los nuevos valores del modal*/
                  $('#myModal div.modal-header h4#myModalLabel').append(
                          modal_elements[i].header);
                  if (modalBody != '') {
                     $('#myModal div.modal-body').append(modalBody);
                     if (typeof modal_elements[i].closeBtnName === 'undefined'
                             || modal_elements[i].closeBtnName == '') {
                        $('#myModal div.modal-footer').append(
                                modal_elements[i].footer
                                + '<button type="button" class="btn btn-default" data-dismiss="modal" style="color: #636363;font-weight: bold;">Cerrar</button>');
                     } else {
                        $('#myModal div.modal-footer').append(
                                modal_elements[i].footer
                                + '<button type="button" class="btn btn-default" data-dismiss="modal" style="color: #636363;font-weight: bold;">'
                                + modal_elements[i].closeBtnName
                                + '</button>');
                     }
                  } else {
                     $('#myModal div.modal-body').append(
                             window['defalutlModalBodyMessage']());
                     $('#myModal div.modal-footer').append(
                             '<button type="button" class="btn btn-default" data-dismiss="modal" style="color: #636363;font-weight: bold;">Cerrar</button>');
                  }

                  if (typeof modal_elements[i].afterLoadCallFunction
                          !== 'undefined'
                          && modal_elements[i].afterLoadCallFunction != '') {
                     window[modal_elements[i].afterLoadCallFunction]();
                  }

                  if (typeof modal_elements[i].widthModal !== 'undefined'
                          && modal_elements[i].widthModal != '') {
                     /*$('div#myModal').css({ 'width': modal_elements[i].widthModal+'px', 'margin-left': '-'+(modal_elements[i].widthModal/2)+'px' });*/
                     $('div#myModal div.modal-dialog').css({
                        'width': modal_elements[i].widthModal + 'px'});
                  }

               } else {
                  if (typeof modal_elements[i].emptyMessage === 'undefined') {
                     var mBody =
                             '<i class="icon-exclamation-sign" style="margin-right:7px;"></i>\
                                         No se ha seleccionado ningun elemento del cual se puedan mostrar los detalles,\
                                         por favor seleccione uno e intente nuevamente.';

                     modal_elements[i].emptyMessage = [{
                           emptyMTitle: 'Elemento no seleccionado',
                           emptyMBody: mBody}];
                  } else {

                     if (typeof modal_elements[i].emptyMessage[0].emptyMTitle
                             === 'undefined'
                             || modal_elements[i].emptyMessage[0].emptyMTitle
                             == '') {
                        modal_elements[i].emptyMessage[0].emptyMTitle =
                                'Elemento no seleccionado';
                     }

                     if (typeof modal_elements[i].emptyMessage[0].emptyMBody
                             === 'undefined'
                             || modal_elements[i].emptyMessage[0].emptyMBody
                             == '') {
                        modal_elements[i].emptyMessage[0].emptyMBody =
                                '<i class="icon-exclamation-sign" style="margin-right:7px;"></i>\
                                         No se ha seleccionado ningun elemento del cual se puedan mostrar los detalles,\
                                         por favor seleccione uno e intente nuevamente.';
                     }
                  }

                  $('#myModal div.modal-header h4#myModalLabel').empty();
                  $('#myModal div.modal-body').empty();
                  $('#myModal div.modal-footer').empty();

                  $('#myModal div.modal-header h4#myModalLabel').append(
                          modal_elements[i].emptyMessage[0].emptyMTitle);
                  $('#myModal div.modal-body').append(
                          modal_elements[i].emptyMessage[0].emptyMBody);
                  $('#myModal div.modal-footer').append(
                          '<button class="action" data-dismiss="modal" aria-hidden="true"><span class="label">Cerrar</span></button>');
               }
            }
         }
      } else {
         $('#myModal div.modal-header h4#myModalLabel').empty();
         $('#myModal div.modal-body').empty();
         $('#myModal div.modal-footer').empty();

         $('#myModal div.modal-header h4#myModalLabel').append('Error!!!');
         $('#myModal div.modal-body').append(
                 '<div class="alert alert-error">\
                                                     <h4>Oops! ha ocurrido un error</h4>\
                                                     Lo sentimos pero ha ocurrido un error, por favor intente nuevamente, si el problema persiste por favor contacte con el administrador\
                                                 </div>');
         $('#myModal div.modal-footer').append(
                 '<button class="action" data-dismiss="modal" aria-hidden="true"><span class="label">Cerrar</span></button>');

      }
   });



   //Fin Modal


});

/*
 *  checkToSwitch
 *      Función que permite cambiar un checkbox a la forma siwtch on - off
 *
 *  Opciones:
 *      Las opciones se envian a tráves de los attr que inicien con data-switch,
 *      los cuales se describen a continuacion:
 *          .- data-switch-enabled   = true : Permite habilitar el cambio de check a switch
 *          .- data-switch-on-label  = 'label' : Permite establecer el label cuando el switch este On
 *          .- data-switch-off-label = 'label' : Permite establecer el label cuando el switch este Off
 *          .- data-switch-float     = false | 'right' | 'left' : Permite colocar el switch flotante izquierda, derecha,
 *                                     valor por defecto false.
 *
 *  Documentación:
 *      https://proto.io/freebies/onoff/
 */
function checkToSwitch() {
   jQuery('body input[data-switch-enabled="true"]').each(function () {
      var element = jQuery(this);
      var attr = element.attr();
      var hasOnLabel = false;
      var hasOffLabel = false;
      var onLabel = 'SI';
      var offLabel = 'NO';
      var float = false;

      jQuery.each(attr, function (key, value) {
         if (key.match('^data-switch-')) {
            var option = key.replace('data-switch-', '');

            switch (option) {
               case 'on-label':
                  onLabel = value;
                  break;
               case 'off-label':
                  offLabel = value;
                  break;
               case 'float':
                  float = value;
                  break;
               default:
                  break;
            }
         }
      });

      initializeSwitchOnOff(element, onLabel, offLabel, float)
   });
}

function initializeSwitchOnOff(element, onLabel, offLabel, float) {
   element.parent().css('width', 'auto');
   element.parent().prepend(
           '<div class="onoffswitch" id="onoff_' + element.attr('id') + '">' +
           '<label class="onoffswitch-label" for="' + element.attr('id') + '">'
           +
           '<span class="onoffswitch-inner" data-switch-on-label="' + onLabel
           + '" data-switch-off-label="' + offLabel + '"></span>' +
           '<span class="onoffswitch-switch"></span>' +
           '</label>' +
           '</div>'
           );

   element.prependTo($('#onoff_' + element.attr('id'))).addClass(
           'onoffswitch-checkbox');

   if (typeof float === "undefined" || float === null || float === '') {
      float = false;
   }

   if (float) {
      var outDiv = jQuery('#onoff_' + element.attr('id')).parent();
      outDiv.attr('style', outDiv.attr('style') + ' float:' + float + ';');
   }
}

//funcion Modal

function defalutlModalBodyMessage(e) {

   e = typeof e !== 'undefined' ? e : '';

   var html =
           '<div class="alert alert-block alert-error">\
                <h4>Error al cargar el elemento</h4>\
                Lo sentimos, hubo un problema al cargar la vista, \
                por favor intente nuevamente.<br /> \
                Si el problema persiste por favor contacte al administrador...</div>';

   if (e != '') {
      html = html + '<p><b>Detalle del Error</b><br />' + e + '</p>';
   }
   return html;
}

/*
 *  setFullCalendar
 *      FunciÃ³n que inicializa los calendarios que tengan la classe fullcalendar
 *      con el plugin Full Calendar
 *
 *  DocumentaciÃ³n:
 *      http://fullcalendar.io/
 */
function setFullCalendarOptions(element) {
   var newOptions = {
      header: {
         left: 'prev, next,today',
         center: 'title',
         right: 'prevYear, nextYear'
      },
      lazyFetching: true,
      buttonText: {
         prev: '<< Mes Anterior',
         next: 'Mes Siguiente >>',
         prevYear: '<< Año Anterior',
         nextYear: 'Año Siguiente >>',
         today: 'Hoy'
      },
      buttonIcons: {
         prev: 'left-single-arrow',
         next: 'right-single-arrow',
         prevYear: 'left-double-arrow',
         nextYear: 'right-double-arrow'
      },
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
         'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago',
         'Sep', 'Oct', 'Nov', 'Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves',
         'Viernes', 'Sabado'],
      hiddenDays: [0, 6],
      dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sab'],
      eventSources: [
         {
            url: '',
            type: 'POST',
            // A way to add custom filters to your event listeners
            data: {
            },
            error: function () {
               //alert('There was an error while fetching Google Calendar!');
            }
         }
      ]
   };

   return newOptions;
}

/* Funcion que permite inicializar un Select2 especificando:
 *   element:        Selector del objeto
 *   blankOption:    Si es true, agrega una opcion en blanco al select2
 *   removeChildren: Si es true, remueve las opciones iniciales del select2
 *   options:        Opciones propias utilizadas por el select2
 */
function initializeSelect2(element, blankOption, removeChildren, options) {
    var attr = element.attr();
    var hasDataStyle = false;

    jQuery.each(attr, function(key, value) {
        if(key.match('^data-style')) {
            hasDataStyle = true;
        }
    });

    if( removeChildren ) {
        element.children().remove();
    }

    if( blankOption ) {
        appendEmptyOption(jQuery(element).attr('id'));
    }

    if (typeof options === 'undefined' || options == '' || options === null) {
        options = {
            placeholder: 'Seleccione...',
            allowClear: true,
            containerCss: {
                'width': '100%'
            }
        }
    }

    if(hasDataStyle) {
        element.removeAttr('style');
        element.attr('style',element.attr('data-style'));
    } else {
        element.attr('data-style',element.attr('style'));
    }

    element.parent().css('position','relative');
    element.select2(options).attr('style','display:block; position:absolute; bottom: 0; left: 0; clip:rect(0,0,0,0);width:100%;');
};


/*
 *  appendEmptyOption:
 *      Funcion que permite agregar un option vacio, normalmente utilizado en la
 *      inicializaicón del select2
 *
 *  Parámetros:
 *      id: id del elemento al cual se le quiere agregar un option vacio.
 */
function appendEmptyOption(id) {
    if($('#'+id).find('option[value=""]').length === 0 && $('#'+id).find('option[value=null]').length === 0 && $('#'+id).find('option:not([value])').length === 0) {
        $('#'+id).prepend('<option/>').val(function(){
            return $('[selected]',this).val();
        });
    }
}

/*
 *  setDataTables
 *      Función que inicializa las tablas que tengan la classe data-tables
 *      con el plugin data-tables
 *
 *  Documentación:
 *      http://eternicode.github.io/bootstrap-datepicker/
 */
function setDataTables() {
    jQuery('body table[data-table-enabled="true"]').each(function() {
        initializeDataTable(jQuery(this));
    });
}

function initializeDataTable(element) {
    var options = setDataTableOptions(element);

    element.DataTable(options);
}

function setDataTableOptions(element) {
    var newOptions = {
            "language": {
                decimal:        "",
                emptyTable:     "Sin resultados para mostrar...",
                info:           "Mostrando _START_ a _END_ de _TOTAL_ resultados",
                infoEmpty:      "Mostrando 0 a 0 de 0 resultados",
                infoFiltered:   "(filtrado de _MAX_ resultados en total)",
                infoPostFix:    "",
                thousands:      ",",
                lengthMenu:     "Mostrar _MENU_ resultados",
                loadingRecords: "Cargando...",
                processing:     "Procesando...",
                search:         "Buscar en la Tabla:",
                zeroRecords:    "No se encontraron coincidencias",
                paginate: {
                    first:      "Primero",
                    last:       "Último",
                    next:       "Siguiente",
                    previous:   "Anterior"
                },
                aria: {
                    sortAscending:  ": activar para ordenar la columna ascendentemente",
                    sortDescending: ": activar para ordenar la columna descendentemente"
                }
            },
            "autoWidth": true
        };

    var attr = element.attr();

    jQuery.each(attr, function(key, value) {
        if(key.match('^data-table-')) {
            var option = slugToCamelCase(key.replace('data-date-',''));

            newOptions[option] = isNaN(value) ? value : parseInt(value);
        }
    });

    return newOptions;
}

jQuery(document).ready(function($) {
    setDataTables();
});
