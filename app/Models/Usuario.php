<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Usuario extends Model
{
    use LogsActivity;
    protected $table = 'usuarios';
    protected $connection = 'empresa_dinamica';
    protected $fillable = [
        'email',
        'activo'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['email', 'activo'])
        ->logOnlyDirty();
    }
}
