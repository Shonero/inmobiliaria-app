<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProyectoInmobiliario;
use Illuminate\Http\Request;

class ProyectoInmobiliarioController extends Controller
{
    // Listar todos los proyectos con paginación, orden y búsqueda
    public function index(Request $request)
    {
        $query = ProyectoInmobiliario::with('unidades');

        // Búsqueda por nombre, ubicación, estado
        if ($request->has('nombre')) {
            $query->where('nombre', 'ilike', "%{$request->nombre}%");
        }

        if ($request->has('ubicacion')) {
            $query->where('ubicacion', 'ilike', "%{$request->ubicacion}%");
        }

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        // Orden por columna y dirección
        $ordenarPor = $request->get('ordenar_por', 'created_at');
        $direccion = $request->get('direccion', 'desc');
        $query->orderBy($ordenarPor, $direccion);

        return response()->json(
            $query->paginate(10)
        );
    }

    // Crear proyecto
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'ubicacion' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:En construcción,Terminado,En pausa,Cancelado',
        ]);

        $proyecto = ProyectoInmobiliario::create($validated);

        return response()->json($proyecto, 201);
    }

    // Mostrar un proyecto por ID
    public function show($id)
    {
        $proyecto = ProyectoInmobiliario::with('unidades')->findOrFail($id);
        return response()->json($proyecto);
    }

    // Modificar un proyecto
    public function update(Request $request, $id)
    {
        $proyecto = ProyectoInmobiliario::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'ubicacion' => 'sometimes|required|string|max:255',
            'fecha_inicio' => 'sometimes|required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'sometimes|required|in:En construcción,Terminado,En pausa,Cancelado',
        ]);

        $proyecto->update($validated);

        return response()->json($proyecto);
    }

    // Eliminar un proyecto
    public function destroy($id)
    {
        $proyecto = ProyectoInmobiliario::findOrFail($id);
        $proyecto->delete();

        return response()->json(['mensaje' => 'Proyecto eliminado correctamente']);
    }
}
