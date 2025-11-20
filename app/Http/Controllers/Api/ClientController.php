<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Registrar un nuevo cliente
     * US3: Registrar clientes
     * Solo usuarios autenticados (institución) pueden crear clientes
     */
    public function store(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'nombre'   => 'required|string|max:255',
            'telefono' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/',
            'correo'   => 'required|email|max:255',
        ], [
            'nombre.required'    => 'El nombre del cliente es requerido.',
            'telefono.required'  => 'El teléfono es requerido.',
            'telefono.regex'     => 'El formato del teléfono no es válido.',
            'correo.required'    => 'El correo es requerido.',
            'correo.email'       => 'El correo no es válido.',
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
     * Obtener todos los clientes de la institución autenticada
     */
    public function index(Request $request)
    {
        try {
            $clients = Client::where('id_institucion', $request->user()->id_institucion)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $clients,
                'total' => $clients->count()
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

            return response()->json([
                'success' => true,
                'data' => $client
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
