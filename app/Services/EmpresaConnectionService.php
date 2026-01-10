<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class EmpresaConnectionService
{
    public static function conectarPorNit(string $nit): void
    {
        $database = 'atlas' . $nit;

        // Definir la BD dinámicamente
        Config::set('database.connections.empresa_dinamica.database', $database);

        // Limpiar conexión previa
        DB::purge('empresa_dinamica');

        // Reconectar
        DB::reconnect('empresa_dinamica');
    }
}
