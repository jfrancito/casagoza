
<form method="POST" action="{{ url('/cotizar-cotizacion/'.$idopcion.'/'.Hashids::encode(substr($cotizacion->id, -8))) }}">
      {{ csrf_field() }}
	<div class="modal-header">
		<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
		<h3 class="modal-title">
			 {{$cotizacion->cliente_nombre}} <span>({{$cotizacion->lote}})</span>
		</h3>
		<input type="hidden" name="cotizaciondetalle_id" id="cotizaciondetalle_id" value='{{$cotizaciondetalle_id}}'>
	</div>
	<div class="modal-body">
		<div  class="row regla-modal">
		    <div class="col-md-12">
		        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
		              <div class="form-group">
		                <label class="col-sm-12 control-label labelleft negrita" >Grupo Servicio <span class="obligatorio">(*)</span> :</label>
		                <div class="col-sm-12 abajocaja">
							        {!! Form::select( 'gruposervicio_id', $combo_categoria_servicio, $select_categoria_servicio,
							                          [
							                            'class'       => 'select2 form-control control input-xs' ,
							                            'id'          => 'gruposervicio_id',
							                            'required'    => '',
							                            'data-aw'     => '1'
							                          ]) !!}

							          @include('error.erroresvalidate', [ 'id' => $errors->has('gruposervicio_id')  , 
							                                              'error' => $errors->first('gruposervicio_id', ':message') , 
							                                              'data' => '1'])
		                </div>
		              </div>
		        </div>

		        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
		              <div class="form-group">
		                <label class="col-sm-12 control-label labelleft negrita" >Unidad Medida <span class="obligatorio">(*)</span> :</label>
		                <div class="col-sm-12 abajocaja" >
								        {!! Form::select( 'unidadmedida_id', $combo_unidad_medida, $select_unidad_medida,
								                          [
								                            'class'       => 'select2 form-control control input-xs' ,
								                            'id'          => 'unidadmedida_id',
								                            'required'    => '',
								                            'data-aw'     => '1'
								                          ]) !!}
								          @include('error.erroresvalidate', [ 'id' => $errors->has('unidadmedida_id')  , 
								                                              'error' => $errors->first('unidadmedida_id', ':message') , 
								                                              'data' => '2'])
		                </div>
		              </div>
		        </div>
		        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					    <div class="form-group">
					        <label class="col-sm-12 control-label text-left">Servicio <span class="obligatorio">(*)</span> :</label>
					        <div class="col-sm-12">
			                <input type="text" class="form-control control input-sm" 
			                name="servicio" id='servicio'  placeholder = 'Ingrese Servicio' value="@if(isset($detalle)){{$detalle->descripcion}}@endif">
					        </div>
					    </div>
		        </div>



		    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
					<div class="form-group">
					  <label class="col-sm-12 control-label labelleft negrita" >Cantidad <span class="obligatorio">(*)</span> :</label>
					  <div class="col-sm-12">

					      <input  type="text"
					              id="cantidad" name='cantidad' 
					              value="@if(isset($detalle)){{old('cantidad' ,$detalle->cantidad)}}@endif" 
					              placeholder="Cantidad"
					              autocomplete="off" class="form-control input-sm importe" data-aw="1"/>

					  </div>
					</div>
				</div>



		    </div>
		    <div class="col-md-6">
		    </div>
		</div>
	</div>
	<div class="modal-footer">
	  <button type="submit" data-dismiss="modal" class="btn btn-success btn-guardar-configuracion">Guardar</button>
	</div>
</form>
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
