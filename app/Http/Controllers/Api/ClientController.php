<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\CuentaPorCobrar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Registrar un nuevo cliente
     * Solo usuarios autenticados (institución) pueden crear clientes
     */
    public function store(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado'
            ], 401);
        }

        $institutionId = $user->id_institucion;
        // Validación
        $validator = Validator::make($request->all(), [
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:255'
            ],
            'telefono' => [
                'required',
                'string',
                'regex:/^(\+\d{1,3}\s?)?(\d{1,4}\s?)*\d{1,4}$/',
                'min:7',
                'max:20',
                // Verificar que no esté duplicado para esta institución
                function ($attribute, $value, $fail) use ($institutionId) {
                    $exists = Client::where('id_institucion', $institutionId)
                        ->where('telefono', $value)
                        ->exists();

                    if ($exists) {
                        $fail('Este teléfono ya está registrado en la institución.');
                    }
                }
            ],
            'correo' => [
                'required',
                'email',
                'max:255',
                'unique:clients,correo'
            ]
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'Formato de teléfono inválido. Use: +57 300 1234567',
            'telefono.min' => 'El teléfono debe tener al menos 7 dígitos',
            'telefono.max' => 'El teléfono no puede exceder 20 caracteres',
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo no es válido',
            'correo.unique' => 'Este correo ya está registrado'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validar correo duplicado en la institución
        $correoExiste = Client::where('correo', $request->correo)
            ->where('id_institucion', $request->user()->id_institucion)
            ->exists();

        if ($correoExiste) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => [
                    'correo' => ['Este correo ya está registrado en su institución.']
                ]
            ], 422);
        }

        try {
            // Crear cliente asociado a la institución del usuario autenticado
            $client = Client::create([
                'id_institucion' => $request->user()->id_institucion,
                'nombre'         => $request->nombre,
                'telefono'       => $request->telefono,
                'correo'         => $request->correo,
                'direccion'      => $request->direccion ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cliente registrado exitosamente',
                'data' => [
                    'id'              => $client->id_cliente,
                    'nombre'          => $client->nombre,
                    'telefono'        => $client->telefono,
                    'correo'          => $client->correo,
                    'direccion'       => $client->direccion,
                    'id_institucion'  => $client->id_institucion,
                    'created_at'      => $client->created_at,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar cliente',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todos los clientes de la institución autenticada con búsqueda y paginación
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->per_page ?? 10; // 10 clientes más recientes por página por defecto
            $search = $request->search ?? '';

            $query = Client::where('id_institucion', $request->user()->id_institucion)->orderBy('created_at', 'asc');

            // Búsqueda por nombre, teléfono o correo
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'LIKE', "%{$search}%")
                        ->orWhere('telefono', 'LIKE', "%{$search}%")
                        ->orWhere('correo', 'LIKE', "%{$search}%");
                });
            }

            // Paginar resultados
            $clients = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $clients->items(),
                'pagination' => [
                    'total' => $clients->total(),
                    'per_page' => $clients->perPage(),
                    'current_page' => $clients->currentPage(),
                    'last_page' => $clients->lastPage(),
                    'from' => $clients->firstItem(),
                    'to' => $clients->lastItem(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener clientes',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener un cliente específico
     */
    public function show(Request $request, $id)
    {
        try {
            $client = Client::where('id_cliente', $id)
                ->where('id_institucion', $request->user()->id_institucion)
                ->firstOrFail();

            // Obtener deudas del cliente
            $deudas = CuentaPorCobrar::where('id_cliente', $id)
                ->with('institution')
                ->orderBy('fecha_vencimiento', 'asc')
                ->get();

            // Calcular estadísticas
            $totalDeudas = $deudas->count();
            $montoTotal = $deudas->sum('monto');
            $deudasVencidas = $deudas->where('estado', 'Vencida')->count();
            $montoVencido = $deudas->where('estado', 'Vencida')->sum('monto');
            $deudasPagadas = $deudas->where('estado', 'Pagada')->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'cliente' => $client,
                    'deudas' => $deudas,
                    'resumen' => [
                        'total_deudas' => $totalDeudas,
                        'monto_total' => $montoTotal,
                        'deudas_vencidas' => $deudasVencidas,
                        'monto_vencido' => $montoVencido,
                        'deudas_pagadas' => $deudasPagadas,
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado',
                'error'   => $e->getMessage()
            ], 404);
        }
    }


    /**
     * Actualizar cliente
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre'   => 'sometimes|required|string|max:255',
            'telefono' => 'sometimes|required|string|regex:/^\+?[1-9]\d{1,14}$/',
            'correo'   => 'sometimes|required|email|max:255',
        ], [
            'telefono.regex' => 'El formato del teléfono no es válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $client = Client::where('id_cliente', $id)
                ->where('id_institucion', $request->user()->id_institucion)
                ->firstOrFail();

            $client->update($request->only('nombre', 'telefono', 'correo', 'direccion'));

            return response()->json([
                'success' => true,
                'message' => 'Cliente actualizado exitosamente',
                'data' => $client
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar cliente',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar cliente
     */
    public function destroy(Request $request, $id)
    {
        try {
            $client = Client::where('id_cliente', $id)
                ->where('id_institucion', $request->user()->id_institucion)
                ->firstOrFail();

            $client->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cliente eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar cliente',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
