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
				$cabecera->cantidad 						=   0;
				$cabecera->precio_unitario 					=   0;
				$cabecera->total 							=   0;
				$cabecera->fecha_crea 	 					=   $this->fechaactual;
				$cabecera->usuario_crea 					=   Session::get('usuario')->id;
				$cabecera->save();

			}else{
				//modificar cuenta contable
				$detallecotizacion							= 	DetalleCotizacion::where('id', $cotizaciondetalle_id)->first();
				$detallecotizacion->descripcion 	   		=   $servicio;
				$detallecotizacion->categoriaservicio_id 	=   $gruposervicio->id;
				$detallecotizacion->categoriaservicio_nombre =   $gruposervicio->descripcion;
				$detallecotizacion->unidadmedida_id 		=   $unidadmedida->id;
				$detallecotizacion->unidadmedida_nombre 	=   $unidadmedida->descripcion;
				$detallecotizacion->cantidad 				=   0;
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
