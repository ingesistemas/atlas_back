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
    protected $connection = 'mysql';
    protected $fillable = [
        'localidad',
        'id_ciudad',
        'p_cardinal'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['localidad', 'p_cardinal', 'id_ciudad'])
        ->logOnlyDirty();
    }

    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id');
    }
}
