<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;

use App\Modelos\Cliente;


use View;
use Session;
use Hashids;
Use Nexmo;
use Keygen;

trait ConfiguracionTraits
{
	
	private function con_lista_clientes() {
		$cliente 	= 	Cliente::get();
	 	return  $cliente;
	}

	private function con_combo_clientes() {
		$array 						= 	Cliente::where('activo','=',1)
										->select(DB::raw("
										  id,
										  numerodocumento +' - '+ nombre_razonsocial as descripcion")
										)
		        						->pluck('descripcion','id')
										->toArray();
		$combo  					= 	array('' => 'Seleccione Cliente') + $array;
	 	return  $combo;
	}


	private function con_generacion_combo($tipo_categoria,$titulo,$todo) {
		
		$array 						= 	DB::table('categorias')
        								->where('activo','=',1)
		        						->where('tipo_categoria','=',$tipo_categoria)
		        						->pluck('descripcion','id')
										->toArray();

		if($todo=='TODO'){
			$combo  				= 	array('' => $titulo , $todo => $todo) + $array;
		}else{
			$combo  				= 	array('' => $titulo) + $array;
		}

	 	return  $combo;					 			
	}


}