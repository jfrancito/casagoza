<div class="col-sm-6">
  <div class="panel-heading panel-heading-divider formtext">Cliente :
      <span class="panel-subtitle formsub" style="display: inline-block;">{{$precotizacion->cliente_nombre}}</span>
  </div>
  <div class="panel-heading panel-heading-divider formtext">Documento :
      <span class="panel-subtitle formsub" style="display: inline-block;">{{$cliente->numerodocumento}}</span>
  </div>
  <div class="panel-heading panel-heading-divider formtext">Dirección :
      <span class="panel-subtitle formsub" style="display: inline-block;">{{$cliente->direccion}}</span>
  </div>
  <div class="panel-heading panel-heading-divider formtext">Correo :
      <span class="panel-subtitle formsub" style="display: inline-block;">{{$cliente->correo}}</span>
  </div>
  <div class="panel-heading panel-heading-divider formtext">Celular :
      <span class="panel-subtitle formsub" style="display: inline-block;">{{$cliente->celular}}</span>
  </div>
</div>
<div class="col-sm-6">
  <div class="panel-heading panel-heading-divider formtext text-right">Lote :
      <span class="panel-subtitle formsub" style="display: inline-block;">{{$cotizacion->lote}}</span>
  </div>
  <div class="panel-heading panel-heading-divider formtext text-right">Fecha :
      <span class="panel-subtitle formsub" style="display: inline-block;">{{date_format(date_create($cotizacion->fecha), 'd-m-Y')}}</span>
  </div>
</div>

<div class='col-sm-12 listajax'>
  @include('cotizacion.ajax.alistadetallecotizacion')
</div>



