$(document).ready(function(){

    var carpeta = $("#carpeta").val();
    $(".ventacotizar").on('click','.agregalinea', function() {
        var _token                  =   $('#token').val();
        var cotizacion_id           =   $(this).attr('data_cotizacion');
        var idopcion                =   $('#idopcion').val();
        data                        =   {
                                            _token                  : _token,
                                            cotizacion_id           : cotizacion_id,
                                            idopcion                : idopcion
                                        };
        ajax_modal(data,"/ajax-modal-configuracion-cotizacion-detalle",
                  "modal-configuracion-cotizacion-modelo-detalle","modal-configuracion-cotizacion-modelo-detalle-container");
    });




    $(".ventacotizar").on('click','.btn-guardar-configuracion', function() {

        var gruposervicio_id                =   $('#gruposervicio_id').val();
        var unidadmedida_id                 =   $('#unidadmedida_id').val();
        var servicio                        =   $('#servicio').val();
        //validacioones
        if(gruposervicio_id ==''){ alerterrorajax("Seleccione una grupo de servicio."); return false;}
        if(unidadmedida_id ==''){ alerterrorajax("Seleccione una unidad de medida."); return false;}
        if(servicio ==''){ alerterrorajax("Ingrese un servicio."); return false;}
        return true;

    });


    $(".ventacotizar").on('click','.modificarcotizacion', function() {

        var _token                      =   $('#token').val();
        var cotizacion_id               =   $(this).attr('data_cotizacion_id');
        var detalle_cotizacion_id       =   $(this).attr('data_detalle_cotizacion_id');
        var idopcion                    =   $('#idopcion').val();

        data                            =   {
                                                _token                      : _token,
                                                cotizacion_id               : cotizacion_id,
                                                detalle_cotizacion_id       : detalle_cotizacion_id,
                                                idopcion                    : idopcion
                                            };

        ajax_modal(data,"/ajax-modal-modificar-configuracion-cotizacion-detalle",
                  "modal-configuracion-cotizacion-modelo-detalle","modal-configuracion-cotizacion-modelo-detalle-container");

    });


    $(".ventacotizar").on('click','.eliminarcotizacion', function() {

        var _token                      =   $('#token').val();
        var cotizacion_id               =   $(this).attr('data_cotizacion_id');
        var detalle_cotizacion_id       =   $(this).attr('data_detalle_cotizacion_id');
        var idopcion                    =   $('#idopcion').val();

        data                            =   {
                                                _token                      : _token,
                                                cotizacion_id               : cotizacion_id,
                                                detalle_cotizacion_id       : detalle_cotizacion_id,
                                                idopcion                    : idopcion
                                            };
        $.confirm({
            title: '¿Confirma la eliminacion?',
            content: 'Eliminar Linea',
            buttons: {
                confirmar: function () {
                    elimnarlinea(data);
                },
                cancelar: function () {
                    $.alert('Se cancelo la eliminacion');
                }
            }
        });

    });



    function elimnarlinea(data){
        ajax_normal_cargar(data,"/ajax-elimnar-linea-cotizacion");
    }



});
