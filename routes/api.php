<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VesselController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    // Rutas para todos los autenticados
    Route::get('/vessels', [VesselController::class, 'index']);
    Route::get('/vessels/{vessel}', [VesselController::class, 'show']);

    // Rutas protegidas para Admin/Supervisor (puedes usar un middleware de rol aquí)
    Route::middleware(['role:admin,supervisor'])->group(function () {
        Route::post('/vessels', [VesselController::class, 'store']);
        Route::patch('/vessels/{vessel}/activate', [VesselController::class, 'activate']);
        Route::patch('/vessels/{vessel}/finish', [VesselController::class, 'finish']);
    });

    Route::middleware(['role:admin,superadmin'])->group(function () {
        Route::apiResource('users', UserController::class);
    });

    // Endpoint para que el frontend (Vue) sepa quién es el usuario actual y sus permisos
    Route::get('/me', function (Request $request) {
        return response()->json([
            'user' => $request->user(),
            'permissions' => $request->user()->getAllPermissions() // Método que definiremos en el modelo
        ]);
    });
});
Route::get('/debug-session', function (Request $request) {
    return response()->json([
        'is_logged_in' => Auth::check(),
        'user' => $request->user(),
        'session_id' => session()->getId(),
    ]);
}); // Quítale el middleware auth:sanctum solo para esta prueba


// Route::prefix('users')->group(function () {
//     // Route::middleware(['auth:sanctum'])->group(function () {
//         Route::get('/', [UserController::class, 'index'])->name('users.get');
//     // });
// });