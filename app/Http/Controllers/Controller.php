<?php

namespace App\Http\Controllers;
use App\Biblioteca\Funcion;
use DateTime;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public $funciones;

	public $inicio;
	public $fin;
	public $hoy;
	public $prefijomaestro;
	public $fechaactual;
	public $fecha_sin_hora;
	
	public function __construct() {
		$this->funciones = new Funcion();

		$fecha = new DateTime();
		$fecha->modify('first day of this month');
		$this->inicio = date_format(date_create($fecha->format('Y-m-d')), 'd-m-Y');
		$this->fin = date_format(date_create(date('Y-m-d')), 'd-m-Y');

		$this->prefijomaestro = $this->funciones->prefijomaestra();
		$this->fechaactual = date('Ymd h:i:s');
		$this->hoy = date_format(date_create(date('Ymd h:i:s')), 'Ymd h:i:s');
		$this->fecha_sin_hora 			= date('d-m-Y');
	}

}
