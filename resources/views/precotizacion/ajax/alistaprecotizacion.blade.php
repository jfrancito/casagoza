<table id="nso" class="table table-striped table-borderless table-hover td-color-borde td-padding-7 listatabla">
  <thead>
    <tr>
      <th></th>
      <th>Lote</th>
      <th>Fecha</th>
      <th>Cliente</th>
      <th>Descripcion</th>
      <th>Estado</th>
      <th>Opciones</th>
    </tr>
  </thead>
  <tbody>
    @foreach($listaprecotizaciones as $index => $item)
      <tr data_precotizacion_id = "{{$item->id}}">
        <td >  
          <div class="text-center be-checkbox be-checkbox-sm" >
            <input  type="checkbox"
                    class="{{Hashids::encode(substr($item->id, -8))}} input_check_pe_ln check{{Hashids::encode(substr($item->id, -8))}}" 
                    id="{{Hashids::encode(substr($item->id, -8))}}" 
                    @if($item->estado_id != '1CIX00000003') disabled @endif>
            <label  for="{{Hashids::encode(substr($item->id, -8))}}"
                  data-atr = "ver"
                  class = "checkbox"                    
                  name="{{Hashids::encode(substr($item->id, -8))}}"
            ></label>
          </div>
        </td>
        <td>{{$item->lote}}</td>

        <td>{{date_format(date_create($item->fecha_crea), 'd-m-Y H:i')}}</td>
        <td>{{$item->cliente_nombre}}</td>
        <td>{{$item->descripcion}}</td>
        <td>
          @if($item->estado_id=='1CIX00000003')
            <span class="badge badge-light">{{$item->estado_descripcion}}</span>
          @else
            @if($item->estado_id=='1CIX00000004')
              <span class="badge badge-success">{{$item->estado_descripcion}}</span><br>
            @endiF
          @endiF
        </td>
        <td class="rigth">
          <div class="btn-group btn-hspace">
            <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle">Acción <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
            <ul role="menu" class="dropdown-menu pull-right">
              <li>
                <a href="{{ url('/modificar-precotizacion/'.$idopcion.'/'.Hashids::encode(substr($item->id, -8))) }}">
                  Modificar
                </a>  
              </li>
              <li>
                <a href="{{ url('/subir-imagenes-precotizacion/'.$idopcion.'/'.Hashids::encode(substr($item->id, -8))) }}">
                  Imagenes
                </a>  
              </li>
            </ul>
          </div>
        </td>
      </tr>                    
    @endforeach
  </tbody>
</table>

@if(isset($ajax))
  <script type="text/javascript">
    $(document).ready(function(){
       App.dataTables();
    });
  </script> 
@endif