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

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;
use App\Traits\GeneralesTraits;
use App\Traits\PreCotizacionTraits;
use App\Traits\ConfiguracionTraits;

class PreCotizacionController extends Controller {

	use GeneralesTraits;
	use PreCotizacionTraits;
	use ConfiguracionTraits;

	public function actionListarPrecotizaciones($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    View::share('titulo','Listar Pre-Cotizacion');

	    $listaprecotizaciones 	= 	$this->pre_lista_cotizaciones();
		$funcion 				= 	$this;

		return View::make('precotizacion/listaprecotizaciones',
						 [
						 	'listaprecotizaciones' 	=> $listaprecotizaciones,
						 	'funcion' 				=> $funcion,
						 	'idopcion' 				=> $idopcion,						 	
						 ]);
	}


	public function actionAgregarPrecotizacion($idopcion,Request $request)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Anadir');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
		View::share('titulo','Agregar Pre-Cotizacion');
		if($_POST)
		{

			$cliente_id 	 					= 	$request['cliente_id'];
			$descripcion 	 					= 	$request['descripcion'];
			$cliente 							= 	Cliente::where('id','=',$cliente_id)->first();

			$codigo 							= 	$this->funciones->generar_codigo('precotizaciones',10);
			$idprecotizacion 					=   $this->funciones->getCreateIdMaestra('precotizaciones');
			
			$cabecera            	 			=	new Precotizacion;
			$cabecera->id 	     	 			=   $idprecotizacion;
			$cabecera->lote						=   $codigo;
			$cabecera->cliente_id 				=   $cliente->id;
			$cabecera->fecha 					=   $this->fecha_sin_hora;
			$cabecera->cliente_nombre 			=   $cliente->nombre_razonsocial;
			$cabecera->descripcion 				=   $descripcion;
			$cabecera->estado_id 	   			=   '1CIX00000003';
			$cabecera->estado_descripcion 	   	=   'GENERADO';
			$cabecera->fecha_crea 	 			=   $this->fechaactual;
			$cabecera->usuario_crea 			=   Session::get('usuario')->id;
			$cabecera->save();

 		 	return Redirect::to('/gestion-de-pre-cotizacion/'.$idopcion)->with('bienhecho', 'Pre-Cotizacion '.$cliente->nombre_razonsocial.' registrado con exito');

		}else{

		    $select_cliente  		=	'';
		    $combo_cliente 			=	$this->con_combo_clientes();

			return View::make('precotizacion/agregarprecotizacion',
						[
							'select_cliente'  => $select_cliente,
							'combo_cliente'   => $combo_cliente,
						  	'idopcion'  	  => $idopcion
						]);
		}
	}

	public function actionSubirImagenesPrecotizacion($idopcion,$idprecotizacion,Request $request)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Modificar');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    $idprecotizacion = $this->funciones->decodificarmaestra($idprecotizacion);
	    View::share('titulo','Subir Imagenes Pre-Cotizacion');

		if($_POST)
		{

			$precotizacion 		= 	PreCotizacion::where('id', $idprecotizacion)->first();
			$files 				= 	$request['files'];
			$listadetalledoc 	= 	Archivo::where('referencia_id','=',$precotizacion->id)
									->where('tipo_archivo','=','precotizacion')
									->get();

			$index 				= 	0;
			if(!is_null($files)){
				foreach($files as $file){

					//dd($file);

					$numero = count($listadetalledoc)+$index+1;
					$nombre = $precotizacion->lote.'-'.$numero.'-'.$file->getClientOriginalName();
					\Storage::disk('local')->put($nombre,  \File::get($file));
					$idarchivo = $this->funciones->getCreateIdMaestra('archivos');
					$dcontrol = new Archivo;
					$dcontrol->id = $idarchivo;
					$dcontrol->lote = $precotizacion->lote;
					$dcontrol->referencia_id = $precotizacion->id;
					$dcontrol->nombre_archivo = $nombre;
					$dcontrol->tipo_archivo = 'precotizacion';
					$dcontrol->fecha_crea = $this->fechaactual;
					$dcontrol->usuario_crea = Session::get('usuario')->id;
					$dcontrol->save();

					$index 				= 	$index + 1;
				}	
			}

 			return Redirect::to('/gestion-de-pre-cotizacion/'.$idopcion)->with('bienhecho', 'Imagenes Pre-Cotizacion '.$precotizacion->nombre_razonsocial.' registrado con éxito');

		}else{

			$precotizacion 			= 	PreCotizacion::where('id', $idprecotizacion)->first();
			$listaimagenes 			= 	Archivo::where('referencia_id','=',$precotizacion->id)
										->where('tipo_archivo','=','precotizacion')->where('activo','=','1')->get();
										
	        return View::make('precotizacion/imagenesprecotizacion', 
	        				[
	        					'precotizacion'  			=> $precotizacion,
	        					'listaimagenes'  			=> $listaimagenes,
					  			'idopcion' 					=> $idopcion
	        				]);
		}
	}

	public function actionModificarPrecotizacion($idopcion,$idprecotizacion,Request $request)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Modificar');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    $idprecotizacion = $this->funciones->decodificarmaestra($idprecotizacion);
	    View::share('titulo','Modificar Pre-Cotizacion');

		if($_POST)
		{
			$precotizacion 						= 	PreCotizacion::where('id', $idprecotizacion)->first();

            if($precotizacion->estado_id=='1CIX00000004'){
                    return Redirect::back()->withInput()->with('errorbd', 'No se puede modificar porque ya se encuentra en estado emitido');
            }    

			$cliente_id 	 					= 	$request['cliente_id'];
			$descripcion 	 					= 	$request['descripcion'];
			$cliente 							= 	Cliente::where('id','=',$cliente_id)->first();
			$cabecera            	 			=	Precotizacion::find($idprecotizacion);
			$cabecera->cliente_id 				=   $cliente->id;
			$cabecera->cliente_nombre 			=   $cliente->nombre_razonsocial;
			$cabecera->descripcion 				=   $descripcion;
			$cabecera->fecha_mod 	 			=   $this->fechaactual;
			$cabecera->usuario_mod 				=   Session::get('usuario')->id;
			$cabecera->save();

 			return Redirect::to('/gestion-de-pre-cotizacion/'.$idopcion)->with('bienhecho', 'Pre-Cotizacion '.$cliente->nombre_razonsocial.' modificado con éxito');

		}else{

			$precotizacion 				= 	PreCotizacion::where('id', $idprecotizacion)->first();
		    $combo_cliente 				=	$this->con_combo_clientes();
		    $select_cliente  			=	$precotizacion->cliente_id;

	        return View::make('precotizacion/modificarprecotizacion', 
	        				[
	        					'precotizacion'  			=> $precotizacion,
	        					'combo_cliente'  			=> $combo_cliente,
		        				'select_cliente' 			=> $select_cliente,
					  			'idopcion' 					=> $idopcion
	        				]);
		}
	}


	public function actionEmitirPrecotizacion($idopcion,Request $request)
	{

		if($_POST)
		{
			$msjarray  			= array();
			$respuesta 			= json_decode($request['pedido'], true);
	        $conts   			= 0;
	        $contw				= 0;
			$contd				= 0;
		
			foreach($respuesta as $obj){
	    		$pedido_id 					= $this->funciones->decodificarmaestra($obj['id']);
				$pedido 					=   PreCotizacion::where('id','=',$pedido_id)->first();

			    if($pedido->estado_id == '1CIX00000003'){ 

				    $pedido->estado_id 				 		= 	'1CIX00000004';
				    $pedido->estado_descripcion 			= 	'EMITIDO';
					$pedido->fecha_emision 	 				=   $this->fechaactual;
					$pedido->usuario_emision 				=   Session::get('usuario')->id;
   					$pedido->save();


					$idcotizacion 						=   $this->funciones->getCreateIdMaestra('cotizaciones');

					$cabecera            	 			=	new Cotizacion;
					$cabecera->id 	     	 			=   $idcotizacion;
					$cabecera->lote						=   $pedido->lote;
					$cabecera->cliente_id 				=   $pedido->cliente_id;
					$cabecera->fecha 					=   $this->fecha_sin_hora;
					$cabecera->cliente_nombre 			=   $pedido->cliente_nombre;
					$cabecera->total 					=   0;
					$cabecera->estado_id 	   			=   '1CIX00000003';
					$cabecera->estado_descripcion 	   	=   'GENERADO';
					$cabecera->fecha_crea 	 			=   $this->fechaactual;
					$cabecera->usuario_crea 			=   Session::get('usuario')->id;
					$cabecera->save();


			    	$msjarray[] 							= 	array(	"data_0" => $pedido->lote, 
			    														"data_1" => 'PreCotizacion Emitido', 
			    														"tipo" => 'S');
					$conts 									= 	$conts + 1;
					$codigo 								= 	$pedido->lote;

			    }else{
					/**** ERROR DE PROGRMACION O SINTAXIS ****/
					$msjarray[] = array("data_0" => $pedido->lote, 
										"data_1" => 'este pedido esta autorizado', 
										"tipo" => 'D');
					$contd 		= 	$contd + 1;

			    }

			}


			/************** MENSAJES DEL DETALLE PEDIDO  ******************/
	    	$msjarray[] = array("data_0" => $conts, 
	    						"data_1" => 'PreCotizacion Emitido', 
	    						"tipo" => 'TS');

	    	$msjarray[] = array("data_0" => $contw, 
	    						"data_1" => 'PreCotizacion', 
	    						"tipo" => 'TW');	 

	    	$msjarray[] = array("data_0" => $contd, 
	    						"data_1" => 'PreCotizacion errados', 
	    						"tipo" => 'TD');

			$msjjson = json_encode($msjarray);


			return Redirect::to('/gestion-de-pre-cotizacion/'.$idopcion)->with('xmlmsj', $msjjson);

		
		}
	}


}
