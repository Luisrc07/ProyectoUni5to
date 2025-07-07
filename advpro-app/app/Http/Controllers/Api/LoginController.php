<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
 
    public function register(Request $request)
    {
        //  $validatedData = $request->validate([
        //     'nombre' => 'required|string|max:255',
        //     'tipo_documento' => 'required|string|max:50',
        //     'documento' => 'required|string|unique:clientes,documento|max:20',
        //     'email' => 'nullable|email|max:255',
        //     'telefono' => 'nullable|string|max:20',
        //     'direccion' => 'nullable|string|max:500',
        // ]);

        // $cliente = Cliente::create($validatedData);

        // return $request->wantsJson()
        //     ? response()->json(['message' => 'Cliente creado', 'data' => $cliente], 201)
        //     : redirect('/clientes/panel')->with('success', 'Cliente creado');
        
    }

    public function login(Request $request)
    {
       
    }

    public function logout(Request $request)
    {
       
    }
}
