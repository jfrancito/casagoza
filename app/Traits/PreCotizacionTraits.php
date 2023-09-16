<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;

use App\Modelos\Cliente;
use App\Modelos\PreCotizacion;

use View;
use Session;
use Hashids;
Use Nexmo;
use Keygen;

trait PreCotizacionTraits
{
	
	private function pre_lista_cotizaciones() {
		$precotizacion 	= 	PreCotizacion::get();
	 	return  $precotizacion;
	}

}