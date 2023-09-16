<table id="nso" class="table table-striped table-borderless table-hover td-color-borde td-padding-7">
  <thead>
    <tr>
      <th>Orden</th>
      <th>Categoria</th>
      <th>Descripciones</th>
      <th>Medida</th>
      <th>Cantidad</th>
      <th>Precio Unitario</th>
      <th>Total</th>
      <th>Opciones</th>
    </tr>
  </thead>
  <tbody>
    @foreach($listadetalle as $index => $item)
      <tr >
        <td>{{$index + 1}}</td>
        <td><b>{{$item->categoriaservicio_nombre}}</b></td>
        <td>{{$item->descripcion}}</td>
        <td>{{$item->unidadmedida_nombre}}</td>
        <td>{{$item->cantidad}}</td>
        <td>{{$item->precio_unitario}}</td>
        <td>{{$item->total}}</td>
        <td class="rigth">
          <div class="btn-group btn-hspace">
            <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle">Acción <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
            <ul role="menu" class="dropdown-menu pull-right">
              <li>
                <a href="#" 
                  class= 'modificarcotizacion' 
                  data_cotizacion_id = "{{$item->cotizacion_id}}"
                  data_detalle_cotizacion_id = "{{$item->id}}" >
                  Modificar
                </a>  
              </li>

              <li>
                <a href="#" 
                  class= 'eliminarcotizacion' 
                  data_cotizacion_id = "{{$item->cotizacion_id}}"
                  data_detalle_cotizacion_id = "{{$item->id}}" >
                  Eliminar
                </a>  
              </li>


            </ul>
          </div>
        </td>
      </tr>                    
    @endforeach
  </tbody>
  <tfooter>
      <tr >
        <td colspan="6" class="text-right"><b>Total : </b></td>
        <td>{{$cotizacion->total}}</td>
        <td></td>
      </tr>     
  </tfooter> 
</table>
