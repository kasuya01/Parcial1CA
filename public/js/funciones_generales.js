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


function validafecha(obj, nombre, fechacompara) {
//    alert(!$("#"+nombre).datepicker( "widget" ).is(":visible"))
//   if (!$("#"+nombre).datepicker( "widget" ).is(":visible"))
//   {
//      alert ('paso')
      //fecha0=document.getElementById('d_fechatoma').value;
      fecha1 = obj;

      fechact=fechacompara.replace(/[-: ]/g, "");
   //var f0 = fecha0.split('-');
   //var fechaPri = parseInt(f0[0]+f0[1]+f0[2]);
      fecha2 = fecha1.replace(/[-: ]/g, "");
      fecha2 =fecha2+'00';

   //var f2 = fecha1.split('-');
   //var fecha2 = parseInt(f2[0]+f2[1]+f2[2]+f2[3]+f2[4]);

      if (fecha1 != "" && fecha2 < fechact)
      {
         alert('La fecha ingresada es menor que la fecha de toma de muestra')
         fechafin = getCurrentDateTime('yyyy-mm-dd');
         document.getElementById(nombre).value = fechafin;
         return false;
      }
   //}
}


jQuery(document).ready(function ($) {

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
});
