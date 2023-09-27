<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;

use App\Modelos\Cliente;
use App\Modelos\Cotizacion;
use App\Modelos\DetalleCotizacionAnalisis;
use App\Modelos\DetalleCotizacion;


use View;
use Session;
use Hashids;
Use Nexmo;
use Keygen;

trait CotizacionTraits
{
	private function cot_lista_cotizaciones() {
		$cotizacion 	= 	Cotizacion::get();
	 	return  $cotizacion;
	}

	private function cot_generar_totales_detalle_cotizacion($cotizacion,$detallecotizacion) {

		$listadetalle 								= 	DetalleCotizacionAnalisis::where('activo','=',1)
														->where('detallecotizacion_id','=',$detallecotizacion->id)
														->orderby('categoriaanalisis_id','asc')
														->get();
		$total 		=	0;
		foreach($listadetalle as $index=>$item){
			$total 		=	$total+$item->total;
		}

		$impuesto01 								=	$total*0.3;
		$impuesto02 								=	$total*0.1;
		$totalpreciounitario 						=	($total+$impuesto01+$impuesto02)*1.18;
		$detallecotizacion->total_analisis 			= 	$total;
		$detallecotizacion->impuestoanalisis_01 	= 	$impuesto01;
		$detallecotizacion->impuestoanalisis_02 	= 	$impuesto02;
		$detallecotizacion->totalpreciounitario 	= 	$totalpreciounitario;
		$detallecotizacion->precio_unitario 		= 	$totalpreciounitario;
		$detallecotizacion->total 					= 	$totalpreciounitario * $detallecotizacion->cantidad;
		$detallecotizacion->fecha_mod 	 			=   date('Ymd h:i:s');
		$detallecotizacion->usuario_mod 			=   Session::get('usuario')->id;
		$detallecotizacion->save();

		$listadetallecotizacion 					= 	DetalleCotizacion::where('activo','=',1)
														->where('cotizacion_id','=',$cotizacion->id)->get();

		$total 										=	0;
		foreach($listadetallecotizacion as $index=>$item){
			$total 									=	$total+$item->total;
		}
		$cotizacion->total 							= 	$total;
		$cotizacion->fecha_mod 	 					=   date('Ymd h:i:s');
		$cotizacion->usuario_mod 					=   Session::get('usuario')->id;
		$cotizacion->save();


	}


}