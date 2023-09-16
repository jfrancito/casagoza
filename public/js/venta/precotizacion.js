
$(document).ready(function(){
    var carpeta = $("#carpeta").val();

    $('#emitirprecotizacion').on('click', function(event){
        event.preventDefault();
        data = dataenviar();
        if(data.length<=0){alerterrorajax("Seleccione por lo menos un pedido");return false;}
        var datastring = JSON.stringify(data);
        $('#pedido').val(datastring);

        $.confirm({
            title: '¿Confirma la Emision?',
            content: 'Emitir Pre Cotizacion',
            buttons: {
                confirmar: function () {
                    abrircargando();
                    $( "#formpedido" ).submit();
                },
                cancelar: function () {
                    $.alert('Se cancelo la emision');
                }
            }
        });

    });

function dataenviar(){
        var data = [];
        $(".listatabla tr").each(function(){
            check   = $(this).find('input');
            nombre  = $(this).find('input').attr('id');
            if(nombre != 'todo'){
                if($(check).is(':checked')){
                    data.push({id: $(check).attr("id")});
                }               
            }
        });
        return data;
}



});


