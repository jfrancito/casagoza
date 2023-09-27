<div class="row">
      <div class="col-xs-12">
            <div class="panel-heading panel-heading-divider formtext">{{$detallecotizacion->descripcion}}</div>
      </div>
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                  <label class="col-sm-12 control-label labelleft negrita" >Grupo Analisis <span class="obligatorio">(*)</span> :</label>
                  <div class="col-sm-12 abajocaja">
                                      {!! Form::select( 'grupoanalisis_id', $combo_categoria_analisis, $select_categoria_analisis,
                                                        [
                                                          'class'       => 'select2 form-control control input-xs' ,
                                                          'id'          => 'grupoanalisis_id',
                                                          'required'    => '',
                                                          'data-aw'     => '1'
                                                        ]) !!}
                                        @include('error.erroresvalidate', [ 'id' => $errors->has('grupoanalisis_id')  , 
                                                                            'error' => $errors->first('grupoanalisis_id', ':message') , 
                                                                            'data' => '1'])
                  </div>
            </div>
      </div>


      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <label class="col-sm-12 control-label labelleft negrita" >Unidad Medida <span class="obligatorio">(*)</span> :</label>
                <div class="col-sm-12 abajocaja" >
                    {!! Form::select( 'unidadmedidaa_id', $combo_unidad_medida_a, $select_unidad_medida_a,
                                      [
                                        'class'       => 'select2 form-control control input-xs' ,
                                        'id'          => 'unidadmedidaa_id',
                                        'required'    => '',
                                        'data-aw'     => '1'
                                      ]) !!}
                      @include('error.erroresvalidate', [ 'id' => $errors->has('unidadmedidaa_id')  , 
                                                          'error' => $errors->first('unidadmedidaa_id', ':message') , 
                                                          'data' => '2'])
                </div>
            </div>
      </div>

      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
          <div class="form-group">
              <label class="col-sm-12 control-label text-left">Descripcion <span class="obligatorio">(*)</span> :</label>
              <div class="col-sm-12">
                  <input type="text" class="form-control control input-sm" 
                  name="descripcion" id='descripcion'  placeholder = 'Ingrese Descripcion' value="@if(isset($analizar)){{$analizar->descripcion}}@endif">
              </div>
          </div>
      </div>  

      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-3">
            <div class="form-group">
              <label class="col-sm-12 control-label labelleft negrita" >Cantidad <span class="obligatorio">(*)</span> :</label>
              <div class="col-sm-12">
                  <input  type="text"
                          id="cantidada" name='cantidada' 
                          value="@if(isset($detalle)){{old('cantidad' ,$detalle->cantidad)}}@endif" 
                          placeholder="Cantidad"
                          autocomplete="off" class="form-control input-sm importe" data-aw="1"/>

              </div>
            </div>
      </div>

      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-3">
            <div class="form-group">
              <label class="col-sm-12 control-label labelleft negrita" >Precio <span class="obligatorio">(*)</span> :</label>
              <div class="col-sm-12">
                  <input  type="text"
                          id="precio" name='precio' 
                          value="@if(isset($detalle)){{old('precio' ,$detalle->precio)}}@endif" 
                          placeholder="Precio"
                          autocomplete="off" class="form-control input-sm importe" data-aw="1"/>

              </div>
            </div>
      </div>


</div>

<div class="row xs-pt-15">
  <div class="col-xs-12">
    <p class="text-right">
      <button type="submit" class="btn btn-space btn-primary btnagregaranalisis"                  
                  data_cotizacion_id = "{{$cotizacion->id}}"
                  data_detalle_cotizacion_id = "{{$detallecotizacion->id}}" >Agregar</button>
    </p>
  </div>
</div>

<div class='col-sm-12 listajaxanalisis'>
  @include('cotizacion.ajax.alistadetalleanalizar')
</div>


@if(isset($ajax))
  <script type="text/javascript">
    $(document).ready(function(){
      App.formElements();
      $('.importe').inputmask({ 'alias': 'numeric', 
      'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 
      'digitsOptional': false, 
      'prefix': '', 
      'placeholder': '0'});
    });
  </script>
@endif
