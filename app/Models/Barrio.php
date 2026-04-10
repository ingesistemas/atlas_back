<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Barrio extends Model
{
    use LogsActivity;
    protected $table = 'barrios';
    protected $connection = 'empresa_dinamica';
    
    protected $fillable = [
        'barrio',
        'id_ficha_tecnica',
        'id_localidad',
        'alerta',
        'activo'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['barrio', 'id_localidad', 'id_ficha_tecnica', 'alerta', 'activo' ])
        ->logOnlyDirty();
    }

    public function localidad(): BelongsTo
    {
        return $this->belongsTo(Localidade::class, 'id_localidad', 'id');
    }

    public function ficha(): BelongsTo
    {
        return $this->belongsTo(FichaTecnica::class, 'id_ficha_tecnica', 'id');
    }
}
