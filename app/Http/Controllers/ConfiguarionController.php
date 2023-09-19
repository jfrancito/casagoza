<?php

namespace App\Http\Controllers;

use App\Modelos\Grupoopcion;
use App\Modelos\Opcion;
use App\Modelos\Rol;
use App\Modelos\RolOpcion;
use App\Modelos\Cliente;
use App\Modelos\Categoria;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;
use App\Traits\GeneralesTraits;
use App\Traits\ConfiguracionTraits;


class ConfiguarionController extends Controller {

	use GeneralesTraits;
	use ConfiguracionTraits;

	public function actionListarUnidadMedida($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    View::share('titulo','Listar Unidad Medida');
	    $listacategoria 	= 	$this->con_lista_categoria('UNIDAD_MEDIDA');
		$funcion 			= 	$this;

		return View::make('configuracion/listaunidadmedida',
						 [
						 	'listacategoria' 		=> $listacategoria,
						 	'funcion' 				=> $funcion,
						 	'idopcion' 				=> $idopcion,						 	
						 ]);
	}


	public function actionAgregarUnidadMedida($idopcion,Request $request)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Anadir');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
		View::share('titulo','Agregar Unidad Medida');
		if($_POST)
		{
			$this->validate($request, [
	            'descripcion' => 'unique:categorias',
			], [
            	'descripcion.unique' => 'Unidad de medida ya Registrado',
        	]);

			$descripcion 	 					= 	$request['descripcion'];
			$aux01 	 							= 	$request['aux01'];

			$idcategoria 						=   $this->funciones->getCreateIdMaestra('categorias');
			$cabecera            	 			=	new Categoria;
			$cabecera->id 	     	 			=   $idcategoria;
			$cabecera->descripcion				=   $descripcion;
			$cabecera->aux01					=   $aux01;
			$cabecera->tipo_categoria			=   'UNIDAD_MEDIDA';
			$cabecera->fecha_crea 	 			=   $this->fechaactual;
			$cabecera->usuario_crea 			=   Session::get('usuario')->id;
			$cabecera->save();
 
 		 	return Redirect::to('/gestion-de-unidad-medida/'.$idopcion)->with('bienhecho', 'Unidad de medida '.$descripcion.' registrado con exito');

		}else{

		    $disabledescripcion  	=	false;

			return View::make('configuracion/agregarunidadmedida',
						[
							'disabledescripcion'   	=> $disabledescripcion,
						  	'idopcion'  			 => $idopcion
						]);
		}
	}


	public function actionModificarUnidadMedida($idopcion,$idcategoria,Request $request)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Modificar');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    $idcategoria = $this->funciones->decodificarmaestra($idcategoria);
	    View::share('titulo','Modificar Unidad de medida');

		if($_POST)
		{
			$activo 	 		 				= 	$request['activo'];
			$aux01 	 		 					= 	$request['aux01'];
			$cabecera            	 			=	Categoria::find($idcategoria);
			$cabecera->activo 	 	 			=   $activo;
			$cabecera->aux01					=   $aux01;
			$cabecera->fecha_mod 	 			=   $this->fechaactual;
			$cabecera->usuario_mod 				=   Session::get('usuario')->id;
			$cabecera->save();

 			return Redirect::to('/gestion-de-unidad-medida/'.$idopcion)->with('bienhecho', 'Unidad de medida '.$cabecera->descripcion.' modificado con éxito');

		}else{

			$categoria 					= 	Categoria::where('id', $idcategoria)->first();
		    $disabledescripcion  		=	true;

	        return View::make('configuracion/modificarunidadmedida', 
	        				[
	        					'categoria'  					=> $categoria,
								'disabledescripcion' 			=> $disabledescripcion,
					  			'idopcion' 						=> $idopcion
	        				]);
		}
	}

	public function actionListarGrupoServicio($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    View::share('titulo','Listar Grupo de Servicio');
	    $listacategoria 	= 	$this->con_lista_categoria('CATEGORIA_SERVICIO');
		$funcion 			= 	$this;

		return View::make('configuracion/listagruposervicio',
						 [
						 	'listacategoria' 		=> $listacategoria,
						 	'funcion' 				=> $funcion,
						 	'idopcion' 				=> $idopcion,						 	
						 ]);
	}
	public function actionAgregarGrupoServicio($idopcion,Request $request)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Anadir');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
		View::share('titulo','Agregar Grupo de Servicio');
		if($_POST)
		{
			$this->validate($request, [
	            'descripcion' => 'unique:categorias',
			], [
            	'descripcion.unique' => 'Grupo de servicio ya Registrado',
        	]);

			$descripcion 	 					= 	$request['descripcion'];
			$idcategoria 						=   $this->funciones->getCreateIdMaestra('categorias');
			$cabecera            	 			=	new Categoria;
			$cabecera->id 	     	 			=   $idcategoria;
			$cabecera->descripcion				=   $descripcion;
			$cabecera->tipo_categoria			=   'CATEGORIA_SERVICIO';
			$cabecera->fecha_crea 	 			=   $this->fechaactual;
			$cabecera->usuario_crea 			=   Session::get('usuario')->id;
			$cabecera->save();
 
 		 	return Redirect::to('/gestion-de-grupo-servicio/'.$idopcion)->with('bienhecho', 'Grupo de servicio '.$descripcion.' registrado con exito');

		}else{

		    $disabledescripcion  	=	false;
			return View::make('configuracion/agregargruposervicio',
						[
							'disabledescripcion'   	=> $disabledescripcion,
						  	'idopcion'  			 => $idopcion
						]);
		}
	}
	public function actionModificarGrupoServicio($idopcion,$idcategoria,Request $request)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Modificar');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    $idcategoria = $this->funciones->decodificarmaestra($idcategoria);
	    View::share('titulo','Modificar Grupo Servicio');

		if($_POST)
		{
			$activo 	 		 				= 	$request['activo'];
			$cabecera            	 			=	Categoria::find($idcategoria);
			$cabecera->activo 	 	 			=   $activo;
			$cabecera->fecha_mod 	 			=   $this->fechaactual;
			$cabecera->usuario_mod 				=   Session::get('usuario')->id;
			$cabecera->save();

 			return Redirect::to('/gestion-de-grupo-servicio/'.$idopcion)->with('bienhecho', 'Grupo de servicio '.$cabecera->descripcion.' modificado con éxito');

		}else{

			$categoria 					= 	Categoria::where('id', $idcategoria)->first();
		    $disabledescripcion  		=	true;

	        return View::make('configuracion/modificargruposervicio', 
	        				[
	        					'categoria'  					=> $categoria,
								'disabledescripcion' 			=> $disabledescripcion,
					  			'idopcion' 						=> $idopcion
	        				]);
		}
	}
	public function actionListarClientes($idopcion)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Ver');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    View::share('titulo','Listar Clientes');

	    $listacliente 	= 	$this->con_lista_clientes();
		$funcion 		= 	$this;


		return View::make('configuracion/listaclientes',
						 [
						 	'listacliente' 			=> $listacliente,
						 	'funcion' 				=> $funcion,
						 	'idopcion' 				=> $idopcion,						 	
						 ]);
	}
	public function actionAgregarClientes($idopcion,Request $request)
	{
		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Anadir');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
		View::share('titulo','Agregar Clientes');
		if($_POST)
		{
			$this->validate($request, [
	            'numerodocumento' => 'unique:clientes',
			], [
            	'numerodocumento.unique' => 'Cliente ya Registrado',
        	]);

			$tipo_documento_id 	 		= 	$request['tipo_documento_id'];
			$numerodocumento 	 		= 	$request['numerodocumento'];
			$nombre_razonsocial 	 	= 	$request['nombre_razonsocial'];
			$direccion 	 		 		= 	$request['direccion'];
			$correo 	 		 		= 	$request['correo'];
			$celular 	 		 		= 	$request['celular'];
		
			$tipo_documento 			= 	Categoria::where('id','=',$tipo_documento_id)->first();

			$idcliente 					=   $this->funciones->getCreateIdMaestra('clientes');
			
			$cabecera            	 			=	new Cliente;
			$cabecera->id 	     	 			=   $idcliente;
			$cabecera->tipo_documento_id		=   $tipo_documento->id;
			$cabecera->tipo_documento_nombre 	=   $tipo_documento->descripcion;
			$cabecera->numerodocumento 			=   $numerodocumento;
			$cabecera->nombre_razonsocial 	   	=   $nombre_razonsocial;
			$cabecera->direccion 	   			=   $direccion;
			$cabecera->correo 					=   $correo;
			$cabecera->celular 					=   $celular;
			$cabecera->fecha_crea 	 			=   $this->fechaactual;
			$cabecera->usuario_crea 			=   Session::get('usuario')->id;
			$cabecera->save();
 
 		 	return Redirect::to('/gestion-de-clientes/'.$idopcion)->with('bienhecho', 'Cliente '.$nombre_razonsocial.' registrado con exito');

		}else{

		    $select_tipo_documento  =	'';
		    $combo_tipo_documento 	=	$this->gn_combo_categoria('TIPO_DOCUMENTO','Seleccione tipo documento','');

		    $disabletipodocumento  	=	false;
		    $disablenumerodocumento =	false;

			return View::make('configuracion/agregarclientes',
						[
							'select_tipo_documento'  => $select_tipo_documento,
							'combo_tipo_documento'   => $combo_tipo_documento,
							'disabletipodocumento'   => $disabletipodocumento,
							'disablenumerodocumento' => $disablenumerodocumento,
						  	'idopcion'  			 => $idopcion
						]);
		}
	}
	public function actionModificarCliente($idopcion,$idcliente,Request $request)
	{

		/******************* validar url **********************/
		$validarurl = $this->funciones->getUrl($idopcion,'Modificar');
	    if($validarurl <> 'true'){return $validarurl;}
	    /******************************************************/
	    $idcliente = $this->funciones->decodificarmaestra($idcliente);
	    View::share('titulo','Modificar Cliente');

		if($_POST)
		{


			$nombre_razonsocial 	 			= 	$request['nombre_razonsocial'];
			$direccion 	 		 				= 	$request['direccion'];
			$correo 	 		 				= 	$request['correo'];
			$celular 	 		 				= 	$request['celular'];
			$activo 	 		 				= 	$request['activo'];


			$cabecera            	 			=	Cliente::find($idcliente);
			$cabecera->nombre_razonsocial 	   	=   $nombre_razonsocial;
			$cabecera->direccion 	   			=   $direccion;
			$cabecera->correo 					=   $correo;
			$cabecera->celular 					=   $celular;
			$cabecera->activo 	 	 			=   $activo;
			$cabecera->fecha_mod 	 			=   $this->fechaactual;
			$cabecera->usuario_mod 				=   Session::get('usuario')->id;
			$cabecera->save();


 			return Redirect::to('/gestion-de-clientes/'.$idopcion)->with('bienhecho', 'Cliente '.$nombre_razonsocial.' modificado con éxito');

		}else{

		    $combo_tipo_documento 		=	$this->gn_combo_categoria('TIPO_DOCUMENTO','Seleccione tipo documento','');
			$cliente 					= 	Cliente::where('id', $idcliente)->first();
			$select_tipo_documento 		= 	$cliente->tipo_documento_id;
		    $disabletipodocumento  		=	true;
		    $disablenumerodocumento 	=	true;



	        return View::make('configuracion/modificarcliente', 
	        				[
	        					'combo_tipo_documento'  	=> $combo_tipo_documento,
	        					'cliente'  					=> $cliente,
		        				'select_tipo_documento' 	=> $select_tipo_documento,	
								'disabletipodocumento'   => $disabletipodocumento,
								'disablenumerodocumento' => $disablenumerodocumento,

					  			'idopcion' 					=> $idopcion
	        				]);
		}
	}

}
