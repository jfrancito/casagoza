$(document).ready(function(){

    var carpeta = $("#carpeta").val();

    $(".ventacotizar").on('click','.eliminaranalisis', function() {

        var _token                      =   $('#token').val();
        var cotizacion_id               =   $(this).attr('data_cotizacion_id');
        var detalle_cotizacion_id       =   $(this).attr('data_detalle_cotizacion_id');
        var detalle_cotizacion_analisis_id  =   $(this).attr('data_detalle_cotizacion_analisis_id');
        var idopcion                    =   $('#idopcion').val();
        data                            =   {
                                                _token                      : _token,
                                                cotizacion_id               : cotizacion_id,
                                                detalle_cotizacion_id       : detalle_cotizacion_id,
                                                detalle_cotizacion_analisis_id  : detalle_cotizacion_analisis_id,
                                                idopcion                    : idopcion
                                            };
        $.confirm({
            title: '¿Confirma la eliminacion?',
            content: 'Eliminar Linea',
            buttons: {
                confirmar: function () {
                    elimnarlineaanalisis(data,cotizacion_id,detalle_cotizacion_id,detalle_cotizacion_analisis_id,_token,idopcion);
                },
                cancelar: function () {
                    $.alert('Se cancelo la eliminacion');
                }
            }
        });

    });


    $(".ventacotizar").on('click','.btnagregaranalisis', function() {

        var _token                          =   $('#token').val();
        var idopcion                        =   $('#idopcion').val();
        var grupoanalisis_id                =   $('#grupoanalisis_id').val();
        var unidadmedidaa_id                =   $('#unidadmedidaa_id').val();
        var descripcion                     =   $('#descripcion').val();
        var cantidada                       =   $('#cantidada').val();
        var precio                          =   $('#precio').val();
        var data_cotizacion_id              =   $(this).attr('data_cotizacion_id');
        var data_detalle_cotizacion_id      =   $(this).attr('data_detalle_cotizacion_id');

        //validacioones
        if(grupoanalisis_id ==''){ alerterrorajax("Seleccione una grupo de analisis."); return false;}
        if(unidadmedidaa_id ==''){ alerterrorajax("Seleccione una unidad de medida."); return false;}
        if(descripcion ==''){ alerterrorajax("Ingrese una descripcion."); return false;}
        if(cantidada ==''){ alerterrorajax("Ingrese un cantidad."); return false;}
        if(precio ==''){ alerterrorajax("Ingrese un precio."); return false;}

        data                        =   {
                                            _token                  : _token,
                                            grupoanalisis_id        : grupoanalisis_id,
                                            unidadmedidaa_id        : unidadmedidaa_id,
                                            descripcion             : descripcion,
                                            cantidad                : cantidada,
                                            precio                  : precio,
                                            data_cotizacion_id      : data_cotizacion_id,
                                            data_detalle_cotizacion_id : data_detalle_cotizacion_id,
                                            idopcion                : idopcion
                                        };
        abrircargando();
        $.ajax({
            type    :   "POST",
            url     :   carpeta+'/ajax-agregar-producto-analisis',
            data    :   data,
            success: function (data) {
                cerrarcargando();
                $('.listajaxanalisis').html(data);
                actualizar_tabla_cotizacion(data_cotizacion_id,data_detalle_cotizacion_id,_token,idopcion);
            },
            error: function (data) {
                cerrarcargando();
                error500(data);
            }
        });

    });

});

function elimnarlineaanalisis(data,cotizacion_id,detalle_cotizacion_id,detalle_cotizacion_analisis_id,_token,idopcion){
    abrircargando();
    $.ajax({
        type    :   "POST",
        url     :   carpeta+'/ajax-eliminar-tabla-cotizacion-analisis',
        data    :   data,
        success: function (data) {
            $('.listajaxanalisis').html(data);
            actualizar_tabla_cotizacion(cotizacion_id,detalle_cotizacion_id,_token,idopcion);
            cerrarcargando();
        },
        error: function (data) {
            cerrarcargando();
            error500(data);
        }
    });
}


function actualizar_tabla_cotizacion(data_cotizacion_id,data_detalle_cotizacion_id,_token,idopcion){

    data                        =   {
                                        _token                  : _token,
                                        data_cotizacion_id      : data_cotizacion_id,
                                        data_detalle_cotizacion_id : data_detalle_cotizacion_id,
                                        idopcion                : idopcion
                                    };
    $.ajax({
        type    :   "POST",
        url     :   carpeta+'/ajax-actualizar-tabla-cotizacion',
        data    :   data,
        success: function (data) {
            $('.listaajaxdetallecotizar').html(data);
            debugger;
        },
        error: function (data) {
            cerrarcargando();
            error500(data);
        }
    });
}

