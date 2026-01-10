<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Role;
//use Illuminate\Http\Request;
use App\Http\Requests\EmpresaRequest;
use App\Models\Ciudad;
use Exception;

class CiudadController extends Controller
{
    public function consultar()
    {
 
        return response()->json([
            'error' => false,
            'data' => Ciudad::all()
        ]);
    }

    public function show(string $id)
    {
        //
    }
}
