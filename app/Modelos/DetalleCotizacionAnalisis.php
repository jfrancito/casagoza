<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DetalleCotizacionAnalisis extends Model
{
    protected $table = 'detallecotizacionanalisis';
    public $timestamps=false;
    protected $primaryKey = 'id';
	public $incrementing = false;
	public $keyType = 'string';

}
