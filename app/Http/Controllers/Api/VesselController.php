<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VesselController extends Controller
{
    /**
     * Listar buques con filtros (activos, terminados, etc.)
     */
    public function index(Request $request)
    {
        $vessels = Vessel::withCount('operations')
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Añadimos el progreso calculado en el modelo
        $vessels->each->append('progress_percentage');

        return response()->json($vessels);
    }

    /**
     * Crear un nuevo buque (Estado inicial: incoming)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'dock_number' => 'required|string',
            'product' => 'required|string',
            'target_tonnage' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $vessel = Vessel::create($request->all());

        return response()->json([
            'message' => 'Buque registrado exitosamente',
            'vessel' => $vessel
        ], 201);
    }

    /**
     * Activar un buque para comenzar la descarga
     */
    public function activate(Vessel $vessel)
    {
        // Regla de Negocio: Opcionalmente cerrar otros buques en el mismo muelle
        // o simplemente marcar este como activo.
        $vessel->update(['status' => 'active']);

        return response()->json([
            'message' => 'El buque ahora está recibiendo descargas',
            'vessel' => $vessel
        ]);
    }

    /**
     * Mostrar detalles de un buque específico y su rendimiento
     */
    public function show(Vessel $vessel)
    {
        $vessel->load(['operations.truck', 'operations.driver']);
        
        return response()->json([
            'vessel' => $vessel->append('progress_percentage'),
            'stats' => [
                'total_net_weight' => $vessel->operations()->sum('weight_net'),
                'trucks_in_port' => $vessel->operations()->where('current_status', '!=', 'salida')->count(),
                'completed_trips' => $vessel->operations()->where('current_status', 'salida')->count(),
            ]
        ]);
    }

    /**
     * Finalizar descarga del buque
     */
    public function finish(Vessel $vessel)
    {
        $vessel->update(['status' => 'finished']);

        return response()->json([
            'message' => 'Operación de buque finalizada y archivada',
            'vessel' => $vessel
        ]);
    }
}