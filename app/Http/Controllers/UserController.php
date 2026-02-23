<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) return response()->json(['message' => 'No autorizado.'], 401);

        $query = User::query()->latest();
         // opcional: filtro por status ?status=active
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // opcional: búsqueda por nombre o email ?q=name
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
            ->orWhere('email', 'like', '%' . $request->q . '%');
        }
        
        $perPage = (int) request('per_page', 10);
        $perPage = max(1, min($perPage, 100)); // límite razonable

        return response()->json([
        'data' => $query->paginate($perPage),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
