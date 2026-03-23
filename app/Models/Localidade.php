<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Localidade extends Model
{
    use LogsActivity;
    protected $table = 'localidades';
    protected $connection = 'empresa_dinamica';
    
    protected $fillable = [
        'localidad',
        'id_ciudad',
        'p_cardinal',
        'id_ficha_tecnica',
        'activo'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['localidad', 'p_cardinal', 'id_ficha_tecnica', 'activo', 'id_ciudad'])
        ->logOnlyDirty();
    }

    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id');
    }

    public function ficha(): BelongsTo
    {
        return $this->belongsTo(FichaTecnica::class, 'id_ficha_tecnica', 'id');
    }
}
