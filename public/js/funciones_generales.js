//Función para pedir el formato de fecha deseado
function getCurrentDateTime(format) {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    var hora = today.getHours()
    var minu = today.getMinutes()

    if(dd<10) {
        dd='0'+dd;
    }

    if(mm<10) {
        mm='0'+mm;
    }
    if (hora<10)
         hora = '0'+hora;
      
    if (minu<10)
         minu = '0'+minu;

    switch (format) {
        case 'dd/mm/yyyy':
            today = dd+'/'+mm+'/'+yyyy+' '+hora+':'+minu;
            break;
        case 'dd-mm-yyyy':
            today = dd+'-'+mm+'-'+yyyy+' '+hora+':'+minu;
            break;
        case 'yyyy/mm/dd':
            today = yyyy+'/'+mm+'/'+dd+' '+hora+':'+minu;
            break;
        case 'yyyy-mm-dd':
            today = yyyy+'-'+mm+'-'+dd+' '+hora+':'+minu;
            break;
        default:
            today = dd+'/'+mm+'/'+yyyy+' '+hora+':'+minu;
            break;
    }

    return today;
}