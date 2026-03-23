<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Model implements JWTSubject
{
    use LogsActivity;
    protected $table = 'usuarios';
    protected $appends = ['rol'];
    protected $connection = 'empresa_dinamica';
    protected $fillable = [
        'email',
        'clave',
        'atlas',
        'id_rol',
        'activo',
        'created_at'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['email', 'activo'])
        ->logOnlyDirty();
    }

    /**
     * Devuelve el identificador del token
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Claims personalizados (por ahora vacío)
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role(): BelongsTo{
        return $this->belongsTo(Role::class, 'id_rol');
    }

    public function getRolAttribute()
    {
        return Role::on('mysql')
            ->where('id', $this->id_rol)
            ->value('rol');
    }

    public function fichasTecnicas()
{
    return $this->belongsToMany(
        FichaTecnica::class,
        'ficha_usuarios',
        'id_usuario',
        'id_ficha_tecnica'
    );
}

}
