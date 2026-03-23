<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Ciudad extends Model
{
    protected $table = 'ciudades';
    protected $connection = 'mysql';
    protected $fillable = [
        'id_dep',
        'ciudad',
        'cod_ciu'
    ];

    public function dpto()
    {
        return $this->belongsTo(Dpto::class, 'id_dep', 'cod_dep');
    }
}
