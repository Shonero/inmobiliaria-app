<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente; // Asegúrate de que este modelo exista

class ClienteController extends Controller
{
    // Listar todos los clientes
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json($clientes);
    }

    // Crear un nuevo cliente
   public function store(Request $request)
{
    $validated = $request->validate([
        'rut' => 'required|string|max:255',
        'nombre' => 'required|string|max:255',
        'apellido' => 'nullable|string|max:255',
        'email' => 'nullable|email',
        'telefono_contacto' => 'nullable|string|max:255',
    ]);

    $cliente = Cliente::create($validated);

    return response()->json($cliente, 201);
}
    // Mostrar un cliente específico
    public function show($id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }
        return response()->json($cliente);
    }

    // Actualizar un cliente existente
    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|unique:clientes,email,'.$id,
            'telefono_contacto' => 'nullable|string|max:50',
        ]);

        $cliente->update($validated);

        return response()->json($cliente);
    }

    // Eliminar un cliente
    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        $cliente->delete();

        return response()->json(null, 204);
    }



    public function validarRut($rut)
{
    $existe = \App\Models\Cliente::where('rut', $rut)->exists();
    return response()->json(['unico' => !$existe]);
}

}
