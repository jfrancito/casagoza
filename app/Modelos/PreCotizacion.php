<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class PreCotizacion extends Model
{
    protected $table = 'precotizaciones';
    public $timestamps=false;
    protected $primaryKey = 'id';
	public $incrementing = false;
	public $keyType = 'string';

}
