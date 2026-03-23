<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class FichaUsuario extends Model
{
    protected $table = 'ficha_usuarios';
    protected $connection = 'empresa_dinamica';

    protected $fillable = [
        'id_ficha_tecnica',
        'id_usuario'
    ];

    public $timestamps = false;

    public function fichaTecnica(): BelongsTo
    {
        return $this->belongsTo(FichaTecnica::class, 'id_ficha_tecnica');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

}
