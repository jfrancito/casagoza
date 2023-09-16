<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;

use App\Modelos\Cliente;
use App\Modelos\Cotizacion;

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
}