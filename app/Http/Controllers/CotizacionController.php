<?php

namespace App\Http\Controllers;

use App\Modelos\Grupoopcion;
use App\Modelos\Opcion;
use App\Modelos\Rol;
use App\Modelos\RolOpcion;
use App\Modelos\Cliente;
use App\Modelos\Categoria;
use App\Modelos\Precotizacion;
use App\Modelos\Archivo;
use App\Modelos\Cotizacion;
use App\Modelos\DetalleCotizacion;
use App\Modelos\DetalleCotizacionAnalisis;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;
use App\Traits\GeneralesTraits;
use App\Traits\CotizacionTraits;
use App\Traits\ConfiguracionTraits;

class CotizacionController extends Controller {

	use GeneralesTraits;
	use CotizacionTraits;
	use ConfiguracionTraits;


	public function actionActulizarTablaCotizacion(Request $request)
	{

		$cotizacion_id 	 			= 	$request['data_cotizacion_id'];
		$detalle_cotizacion_id 	 	= 	$request['data_detalle_cotizacion_id'];
		$idopcion 	 				= 	$request['idopcion'];
		$cotizacion 				= 	Cotizacion::where('id', $cotizacion_id)->first();
		$listadetalle 				= 	DetalleCotizacion::where('activo','=',1)->where('cotizacion_id','=',$cotizacion_id)
										->orderby('categoriaservicio_id','asc')->get();
		$funcion 					= 	$this;
		return View::make('cotizacion/ajax/alistadetallecotizacion',
						 [
						 	'cotizacion' 				=> $cotizacion,
						 	'listadetalle' 				=> $listadetalle,
						 	'idopcion' 					=> $idopcion,
						 	'ajax' 						=> true,						 	
						 ]);
	}


	public function actionEliminarTablaCotizacionAnalisis(Request $request)
	{

		$cotizacion_id 	 					= 	$request['cotizacion_id'];
		$detalle_cotizacion_id 	 			= 	$request['detalle_cotizacion_id'];
		$detalle_cotizacion_analisis_id 	= 	$request['detalle_cotizacion_analisis_id'];
		$idopcion 	 						= 	$request['idopcion'];
		$cotizacion 						= 	Cotizacion::where('id', $cotizacion_id)->first();
		$detallecotizacion					= 	DetalleCotizacion::where('id', $detalle_cotizacion_id)->first();

		$detalle 							= 	DetalleCotizacionAnalisis::where('id','=',$detalle_cotizacion_analisis_id)->first();
		$detalle->activo 					=	0;
		$detalle->fecha_mod 	 			=   $this->fechaactual;
		$detalle->usuario_mod 				=   Session::get('usuario')->id;
		$detalle->save();
		$funcion 							= 	$this;

		//generar el precio y totales	
	    $this->cot_generar_totales_detalle_cotizacion($cotizacion,$detallecotizacion);
		$listadetalle 						= 	DetalleCotizacionAnalisis::where('activo','=',1)
												->where('detallecotizacion_id','=',$detallecotizacion->id)
												->orderby('categoriaanalisis_id','asc')->get();

		$funcion 					= 	$this;
		return View::make('cotizacion/ajax/alistadetalleanalizar',
						 [
						 	'detallecotizacion' 		=> $detallecotizacion,
						 	'listadetalle' 				=> $listadetalle,
						 	'idopcion' 					=> $idopcion,
						 	'ajax' 						=> true,						 	
						 ]);
	}




	public function actionAgregarProductoAnalisis(Request $request)
	{

		$grupoanalisis_id 	 						= 	$request['grupoanalisis_id'];
		$unidadmedidaa_id 	 						= 	$request['unidadmedidaa_id'];
		$descripcion 	 							= 	$request['descripcion'];
		$cantidad 	 								= 	$request['cantidad'];
		$precio 	 								= 	$request['precio'];
		$data_cotizacion_id 	 					= 	$request['data_cotizacion_id'];
		$data_detalle_cotizacion_id 	 			= 	$request['data_detalle_cotizacion_id'];

		$idopcion 	 								= 	$request['idopcion'];
		$detallecotizacion							= 	DetalleCotizacion::where('id', $data_detalle_cotizacion_id)->first();
		$cotizacion 								= 	Cotizacion::where('id', $data_cotizacion_id)->first();

		$grupoanalisis 								= 	Categoria::where('id', $grupoanalisis_id)->first();
		$unidadmedida 								= 	Categoria::where('id', $unidadmedidaa_id)->first();

		$iddetallecotizacionanalisis				=   $this->funciones->getCreateIdMaestra('detallecotizacionanalisis');
		$cabecera            	 					=	new DetalleCotizacionAnalisis;
		$cabecera->id 	     	 					=   $iddetallecotizacionanalisis;
		$cabecera->cotizacion_id 	     	 		=   $data_cotizacion_id;
		$cabecera->detallecotizacion_id 	     	=   $data_detalle_cotizacion_id;
		$cabecera->descripcion 	   					=   $descripcion;
		$cabecera->categoriaanalisis_id 			=   $grupoanalisis->id;
		$cabecera->categoriaanalisis_nombre 		=   $grupoanalisis->descripcion;
		$cabecera->unidadmedida_id 					=   $unidadmedida->id;
		$cabecera->unidadmedida_nombre 				=   $unidadmedida->descripcion;
		$cabecera->cantidad 						=   floatval($cantidad);
		$cabecera->precio_unitario 					=   floatval($precio);
		$cabecera->total 							=   floatval($cantidad)*floatval($precio);
		$cabecera->fecha_crea 	 					=   $this->fechaactual;
		$cabecera->usuario_crea 					=   Session::get('usuario')->id;
		$cabecera->save();



		//generar el precio y totales	
	    $this->cot_generar_totales_detalle_cotizacion($cotizacion,$detallecotizacion);


		$funcion 									= 	$this;

		$listadetalle 								= 	DetalleCotizacionAnalisis::where('activo','=',1)
														->where('detallecotizacion_id','=',$detallecotizacion->id)
														->orderby('categoriaanalisis_id','asc')->get();

		return View::make('cotizacion/ajax/alistadetalleanalizar',
						 [
						 	'detallecotizacion' 		=> $detallecotizacion,
						 	'cotizacion' 				=> $cotizacion,
						 	'funcion' 					=> $funcion,
						 	'idopcion' 					=> $idopcion,
						 	'listadetalle' 				=> $listadetalle,
						 	'ajax' 						=> true,						 	
						 ]);
	}




	public function actionAnalizarDetalleCotizacion(Request $request)
	{

		$cotizacion_id 	 			= 	$request['cotizacion_id'];
		$detalle_cotizacion_id 	 	= 	$request['detalle_cotizacion_id'];
		$idopcion 	 				= 	$request['idopcion'];

		$detallecotizacion			= 	DetalleCotizacion::where('id', $detalle_cotizacion_id)->first();
		$cotizacion 				= 	Cotizacion::where('id', $cotizacion_id)->first();
	    $combo_categoria_analisis	=	$this->con_generacion_combo('CATEGORIA_ANALISIS','Seleccione Categoria Analisis','');
	    $select_categoria_analisis  =	'';
	    $combo_unidad_medida_a 		=	$this->con_generacion_combo('UNIDAD_MEDIDA','Seleccione Unidad Medida','');
	    $select_unidad_medida_a  	=	'';

		$listadetalle 				= 	DetalleCotizacionAnalisis::where('activo','=',1)
										->where('detallecotizacion_id','=',$detallecotizacion->id)
										->orderby('categoriaanalisis_id','asc')->get();

		$funcion 					= 	$this;
		return View::make('cotizacion/form/fanalizar',
						 [
						 	'detallecotizacion' 		=> $detallecotizacion,
						 	'cotizacion' 				=> $cotizacion,
						 	'combo_categoria_analisis' 	=> $combo_categoria_analisis,
						 	'select_categoria_analisis' => $select_categoria_analisis,
						 	'combo_unidad_medida_a' 	=> $combo_unidad_medida_a,
						 	'select_unidad_medida_a' 	=> $select_unidad_medida_a,
						 	'funcion' 					=> $funcion,
						 	'idopcion' 					=> $idopcion,
						 	'listadetalle' 				=> $listadetalle,
						 	'ajax' 						=> true,						 	
						 ]);
	}

	public function actionListarcotizaciones($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    View::share('titulo','Listar Cotizacion');

	    $listacotizaciones 	= 	$this->cot_lista_cotizaciones();
		$funcion 				= 	$this;

		return View::make('cotizacion/listacotizaciones',
						 [
						 	'listacotizaciones' 	=> $listacotizaciones,
						 	'funcion' 				=> $funcion,
						 	'idopcion' 				=> $idopcion,						 	
						 ]);
	}


	public function actionCotizarcotizacion($idopcion,$idcotizacion,Request $request)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Modificar');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    $sidcotizacion = $idcotizacion;
	    $idcotizacion = $this->funciones->decodificarmaestra($idcotizacion);
	    View::share('titulo','Venta Cotizar');

		if($_POST)
		{

			$gruposervicio_id 	 		 					= 	$request['gruposervicio_id'];
			$unidadmedida_id 	 	 						= 	$request['unidadmedida_id'];
			$servicio 	 									= 	$request['servicio'];
			$cotizaciondetalle_id 	 						= 	$request['cotizaciondetalle_id'];
			$cantidad 	 									= 	$request['cantidad'];

			$cotizacion 									= 	Cotizacion::where('id', $idcotizacion)->first();
			$gruposervicio 									= 	Categoria::where('id', $gruposervicio_id)->first();
			$unidadmedida 									= 	Categoria::where('id', $unidadmedida_id)->first();

			//agregar cuenta contable
			if(trim($cotizaciondetalle_id)==''){
				
				$iddetallecotizacion						=   $this->funciones->getCreateIdMaestra('detallecotizaciones');
				$cabecera            	 					=	new DetalleCotizacion;
				$cabecera->id 	     	 					=   $iddetallecotizacion;
				$cabecera->cotizacion_id 	     	 		=   $idcotizacion;
				$cabecera->descripcion 	   					=   $servicio;
				$cabecera->categoriaservicio_id 			=   $gruposervicio->id;
				$cabecera->categoriaservicio_nombre 		=   $gruposervicio->descripcion;
				$cabecera->unidadmedida_id 					=   $unidadmedida->id;
				$cabecera->unidadmedida_nombre 				=   $unidadmedida->descripcion;
				$cabecera->cantidad 						=   $cantidad;
				$cabecera->precio_unitario 					=   0;
				$cabecera->total 							=   0;

				$cabecera->total_analisis 					=   0;
				$cabecera->impuestoanalisis_01 				=   0;
				$cabecera->impuestoanalisis_02 				=   0;
				$cabecera->totalpreciounitario 				=   0;

				$cabecera->fecha_crea 	 					=   $this->fechaactual;
				$cabecera->usuario_crea 					=   Session::get('usuario')->id;
				$cabecera->save();


			}else{
				//modificar cuenta contable
				$detallecotizacion							= 	DetalleCotizacion::where('id', $cotizaciondetalle_id)->first();
				$detallecotizacion->descripcion 	   		=   $servicio;
				$detallecotizacion->categoriaservicio_id 	=   $gruposervicio->id;
				$detallecotizacion->categoriaservicio_nombre=   $gruposervicio->descripcion;
				$detallecotizacion->unidadmedida_id 		=   $unidadmedida->id;
				$detallecotizacion->unidadmedida_nombre 	=   $unidadmedida->descripcion;
				$detallecotizacion->cantidad 				=   $cantidad;
				$detallecotizacion->precio_unitario 		=   0;
				$detallecotizacion->total 					=   0;
				$detallecotizacion->fecha_mod 	 			=   $this->fechaactual;
				$detallecotizacion->usuario_mod 			=   Session::get('usuario')->id;
				$detallecotizacion->save();
			}

 			return Redirect::to('/cotizar-cotizacion/'.$idopcion.'/'.$sidcotizacion)->with('bienhecho', 'Servicio '.$servicio.' agregada con éxito');

		}else{

			$cotizacion 						= 	Cotizacion::where('id', $idcotizacion)->first();
			$cliente 							= 	Cliente::where('id', $cotizacion->cliente_id)->first();
			$precotizacion 						= 	Precotizacion::where('lote', $cotizacion->lote)->first();
			$listaimagenes 						= 	Archivo::where('referencia_id','=',$precotizacion->id)
													->where('tipo_archivo','=','precotizacion')->where('activo','=','1')->get();
			$listadetalle 						= 	DetalleCotizacion::where('activo','=',1)->orderby('categoriaservicio_id','asc')->get();


	        return View::make('cotizacion/ventacotizar', 
	        				[
	        					'precotizacion'  			=> $precotizacion,
	        					'cotizacion'  				=> $cotizacion,
	        					'listaimagenes'  			=> $listaimagenes,
	        					'cliente'  					=> $cliente,
	        					'listadetalle'  			=> $listadetalle,
					  			'idopcion' 					=> $idopcion
	        				]);
		}
	}



	public function actionConfigurarDetalle(Request $request)
	{

		$cotizacion_id 						=   $request['cotizacion_id'];
		$idopcion 							=   $request['idopcion'];
		$cotizacion 						= 	Cotizacion::where('id', $cotizacion_id)->first();
	    $combo_unidad_medida 				=	$this->con_generacion_combo('UNIDAD_MEDIDA','Seleccione Unidad Medida','');
	    $select_unidad_medida  				=	'';
	    $combo_categoria_servicio 			=	$this->con_generacion_combo('CATEGORIA_SERVICIO','Seleccione Categoria Servicio','');
	    $select_categoria_servicio  		=	'';
		$cotizaciondetalle_id 				=	'';

		return View::make('cotizacion/modal/ajax/mconfiguracioncotizacion',
						 [		 	
						 	'idopcion' 					=> $idopcion,
						 	'cotizacion' 				=> $cotizacion,
						 	'combo_unidad_medida' 		=> $combo_unidad_medida,
						 	'select_unidad_medida' 		=> $select_unidad_medida,
						 	'combo_categoria_servicio' 	=> $combo_categoria_servicio,
						 	'select_categoria_servicio' => $select_categoria_servicio,
						 	'cotizaciondetalle_id' 		=> $cotizaciondetalle_id,
						 	'ajax' 						=> true,						 	
						 ]);
	}


	public function actionAjaxEliminarLineaCotizacion(Request $request)
	{
		$cotizacion_id 				=   $request['cotizacion_id'];
		$detalle_cotizacion_id 		=   $request['detalle_cotizacion_id'];
		$idopcion 					=   $request['idopcion'];
		$detalle 					= 	DetalleCotizacion::where('id','=',$detalle_cotizacion_id)->first();
		$detalle->activo 			=	0;
		$detalle->fecha_mod 	 	=   $this->fechaactual;
		$detalle->usuario_mod 		=   Session::get('usuario')->id;
		$detalle->save();
	}





	public function actionAjaxModalModificarConfiguracionCotizacion(Request $request)
	{
		$cotizacion_id 				=   $request['cotizacion_id'];
		$detalle_cotizacion_id 		=   $request['detalle_cotizacion_id'];
		$idopcion 					=   $request['idopcion'];

		$cotizacion 				= 	Cotizacion::where('id', $cotizacion_id)->first();
		$detalle 					= 	DetalleCotizacion::where('id','=',$detalle_cotizacion_id)->first();

	    $combo_unidad_medida 		=	$this->con_generacion_combo('UNIDAD_MEDIDA','Seleccione Unidad Medida','');
	    $select_unidad_medida  		=	$detalle->unidadmedida_id;
	    $combo_categoria_servicio 	=	$this->con_generacion_combo('CATEGORIA_SERVICIO','Seleccione Categoria Servicio','');
	    $select_categoria_servicio  =	$detalle->categoriaservicio_id;
		$cotizaciondetalle_id 		=	$detalle_cotizacion_id;

		return View::make('cotizacion/modal/ajax/mconfiguracioncotizacion',
						 [		 	
						 	'idopcion' 					=> $idopcion,
						 	'cotizacion' 				=> $cotizacion,
						 	'detalle' 					=> $detalle,
						 	'combo_unidad_medida' 		=> $combo_unidad_medida,
						 	'select_unidad_medida' 		=> $select_unidad_medida,
						 	'combo_categoria_servicio' 	=> $combo_categoria_servicio,
						 	'select_categoria_servicio' => $select_categoria_servicio,
						 	'cotizaciondetalle_id' 		=> $cotizaciondetalle_id,
						 	'ajax' 						=> true,						 	
						 ]);
	}





}
