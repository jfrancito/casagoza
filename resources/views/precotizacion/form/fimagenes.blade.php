<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="form-group">
		<label class="col-sm-12 control-label" style="text-align: left;"><b>IMAGENES : </b></label>
		<div class="col-md-12">
			<input type="file" class="form-control input-sm" name="files[]" id = "files" accept="image/*" multiple>
		</div>
	</div>
</div>

<div class="col-xs-12">
<p class="text-right">
  <button type="submit" class="btn btn-space btn-primary btnguardarcliente">Guardar</button>
</p>
</div>

<div class="row" style="padding-top:160px;">
<div class="be">
	<div class="main-content container-fluid">
	  <div class="gallery-container" >

	    @foreach($listaimagenes as $index => $item)
		    <div class="item" style="top:50px !important;">
		      <div class="photo">
		        <div class="img"><img src="{{ asset('/storage/app/'.$item->nombre_archivo)}}" alt="Gallery Image">
		          <div class="over">
		            <div class="info-wrapper">
		              <div class="info">
		                <div class="func" style="position: absolute;right: -117px;">

		                	<a href="{{ asset('/storage/app/'.$item->nombre_archivo)}}" target="_blank" class="image-zoom">
		                	<i class="icon mdi mdi-search"></i></a>
		                </div>
		              </div>
		            </div>
		          </div>
		        </div>
		      </div>
		    </div>                 
	    @endforeach




	  </div>
	</div>
</div>	
</div>


