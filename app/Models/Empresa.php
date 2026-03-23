<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Empresa extends Model
{
    use LogsActivity;
    protected $table = 'empresas';
    protected $connection = 'mysql';
    protected $fillable = [
        'nit',
        'dig_ver',
        'id_ciudad',
        'nombre',
        'email',
        'web',
        'lema',
        'activo'
    ];

    // ✅ Empresa pertenece a una ciudad
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['nit', 'dig_ver', 'id_ciudad', 'nombre', 'email', 'web', 'lema', 'activo'])
        ->logOnlyDirty();
    }
}
