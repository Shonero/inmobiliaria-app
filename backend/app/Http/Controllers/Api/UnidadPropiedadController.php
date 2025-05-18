<?php

namespace App\Http\Controllers\Api;

use App\Models\UnidadPropiedad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class UnidadPropiedadController extends Controller
{

    protected $fillable = [
  'numero_unidad',
  'tipo_unidad',
  'metraje_cuadrado',
  'precio_venta',
  'estado',
  'cliente_id',
  'proyecto_inmobiliario_id',  // <- debe estar aquí
];
    // Listar unidades de un proyecto inmobiliario
    public function index($proyectoId)
    {
        $unidades = UnidadPropiedad::with('cliente')
            ->where('proyecto_inmobiliario_id', $proyectoId)
            ->get();

        return response()->json($unidades);
    }

    // Mostrar una unidad específica
    public function show($proyectoId, $unidadId)
    {
        $unidad = UnidadPropiedad::with('cliente')
            ->where('proyecto_inmobiliario_id', $proyectoId)
            ->findOrFail($unidadId);

        return response()->json($unidad);
    }

    // Crear una nueva unidad para un proyecto inmobiliario
    public function store(Request $request, $proyectoId)
    {
        $request->validate([
            'numero_unidad' => 'required|string',
            'tipo_unidad' => 'required|string',
            'metraje_cuadrado' => 'required|numeric',
            'precio_venta' => 'required|numeric',
            'estado' => 'required|string',
            'cliente_id' => 'nullable|exists:clientes,id',
        ]);
        // Agregar proyecto_inmobiliario_id
    $validated['proyecto_inmobiliario_id'] = $proyectoId;

        $unidad = UnidadPropiedad::create([
            'numero_unidad' => $request->numero_unidad,
            'tipo_unidad' => $request->tipo_unidad,
            'metraje_cuadrado' => $request->metraje_cuadrado,
            'precio_venta' => $request->precio_venta,
            'estado' => $request->estado,
            'proyecto_inmobiliario_id' => $proyectoId,
            'cliente_id' => $request->cliente_id,
        ]);

        return response()->json($unidad, 201);
    }

    // Actualizar una unidad existente
    public function update(Request $request, $proyectoId, $unidadId)
    {
        $request->validate([
            'numero_unidad' => 'required|string',
            'tipo_unidad' => 'required|string',
            'metraje_cuadrado' => 'required|numeric',
            'precio_venta' => 'required|numeric',
            'estado' => 'required|string',
            'cliente_id' => 'nullable|exists:clientes,id',
        ]);

        $unidad = UnidadPropiedad::where('proyecto_inmobiliario_id', $proyectoId)
            ->findOrFail($unidadId);

        $unidad->update($request->only([
            'numero_unidad',
            'tipo_unidad',
            'metraje_cuadrado',
            'precio_venta',
            'estado',
            'cliente_id',
        ]));

        return response()->json($unidad);
    }

    // Eliminar una unidad
    public function destroy($proyectoId, $unidadId)
    {
        $unidad = UnidadPropiedad::where('proyecto_inmobiliario_id', $proyectoId)
            ->findOrFail($unidadId);

        $unidad->delete();

        return response()->json(null, 204);
    }

     public function getByProyecto($id)
    {
        try {
            $unidades = UnidadPropiedad::with('cliente')
                ->where('proyecto_inmobiliario_id', $id)
                ->get();

            return response()->json($unidades);
        } catch (\Exception $e) {
            
            return response()->json([
                'message' => 'Error al obtener unidades',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function validarNumeroUnidad($numero_unidad, $proyecto_id)
{
    try {
        $exists = UnidadPropiedad::where('numero_unidad', $numero_unidad)
                    ->where('proyecto_inmobiliario_id', $proyecto_id)
                    ->exists();

        return response()->json(['exists' => $exists]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}


