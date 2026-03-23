<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Dpto extends Model
{
    protected $table = 'dptos';
    protected $connection = 'mysql';
    protected $fillable = [
        'cod_dep',
        'departamento'
    ];
}
