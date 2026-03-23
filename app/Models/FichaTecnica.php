<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class FichaTecnica extends Model
{
    protected $table = 'fichas_tecnicas';
    protected $connection = 'empresa_dinamica';

    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'observaciones',
        'firmado_digital',
        'id_usuario_firma'
    ];

    /**
     * Usuario que firmó la ficha técnica
     */
    public function usuarioFirma(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_firma');
    }

    /**
     * Usuarios asociados a la ficha técnica (comité o responsables)
     */
    public function fichaUsuarios(): HasMany
    {
        return $this->hasMany(FichaUsuario::class, 'id_ficha_tecnica');
    }

}
