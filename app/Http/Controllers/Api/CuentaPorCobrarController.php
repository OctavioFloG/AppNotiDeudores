<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CuentaPorCobrar;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CuentaPorCobrarController extends Controller
{
    /**
     * Registrar una nueva cuenta por cobrar (deuda/factura)
     */
    public function store(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'id_cliente'           => 'required|integer|exists:clients,id_cliente',
            'monto'                => 'required|numeric|min:0.01',
            'fecha_emision'        => 'required|date',
            'fecha_vencimiento'    => 'required|date|after_or_equal:fecha_emision',
            'descripcion'          => 'nullable|string|max:500',
        ], [
            'id_cliente.required'           => 'El cliente es requerido.',
            'id_cliente.exists'             => 'El cliente no existe.',
            'monto.required'                => 'El monto es requerido.',
            'monto.numeric'                 => 'El monto debe ser un número.',
            'monto.min'                     => 'El monto debe ser mayor a 0.',
            'fecha_emision.required'        => 'La fecha de emisión es requerida.',
            'fecha_emision.date'            => 'La fecha de emisión debe ser una fecha válida.',
            'fecha_vencimiento.required'    => 'La fecha de vencimiento es requerida.',
            'fecha_vencimiento.after_or_equal' => 'La fecha de vencimiento no puede ser anterior a la emisión.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verificar que el cliente pertenece a la institución autenticada
            $client = Client::where('id_cliente', $request->id_cliente)
                ->where('id_institucion', $request->user()->id_institucion)
                ->firstOrFail();

            // Crear la cuenta por cobrar con estado "Pendiente"
            $cuenta = CuentaPorCobrar::create([
                'id_cliente'           => $request->id_cliente,
                'id_institucion'       => $request->user()->id_institucion,
                'monto'                => $request->monto,
                'fecha_emision'        => $request->fecha_emision,
                'fecha_vencimiento'    => $request->fecha_vencimiento,
                'descripcion'          => $request->descripcion ?? null,
                'estado'               => 'Pendiente',  // Estado inicial siempre es Pendiente
                'fecha_pago'           => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cuenta por cobrar registrada exitosamente',
                'data' => [
                    'id'                  => $cuenta->id_cuenta,
                    'id_cliente'          => $cuenta->id_cliente,
                    'id_institucion'      => $cuenta->id_institucion,
                    'monto'               => $cuenta->monto,
                    'fecha_emision'       => $cuenta->fecha_emision,
                    'fecha_vencimiento'   => $cuenta->fecha_vencimiento,
                    'descripcion'         => $cuenta->descripcion,
                    'estado'              => $cuenta->estado,
                    'fecha_pago'          => $cuenta->fecha_pago,
                    'created_at'          => $cuenta->created_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar cuenta por cobrar',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todas las cuentas por cobrar de la institución
     */
    public function index(Request $request)
    {
        try {
            $query = CuentaPorCobrar::where('id_institucion', $request->user()->id_institucion);

            // Filtrar por cliente si se proporciona
            if ($request->id_cliente) {
                $query->where('id_cliente', $request->id_cliente);
            }

            // Filtrar por estado si se proporciona
            if ($request->estado) {
                $query->where('estado', $request->estado);
            }

            // Ordenar por fecha de vencimiento
            $cuentas = $query->orderBy('fecha_vencimiento', 'asc')->get();

            return response()->json([
                'success' => true,
                'data' => $cuentas,
                'total' => $cuentas->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener cuentas por cobrar',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener una cuenta por cobrar específica
     */
    public function show(Request $request, $id)
    {
        try {
            $cuenta = CuentaPorCobrar::where('id_cuenta', $id)
                ->where('id_institucion', $request->user()->id_institucion)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $cuenta
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cuenta por cobrar no encontrada',
                'error'   => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Actualizar una cuenta por cobrar
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'monto'             => 'sometimes|required|numeric|min:0.01',
            'fecha_emision'     => 'sometimes|required|date',
            'fecha_vencimiento' => 'sometimes|required|date',
            'descripcion'       => 'sometimes|nullable|string|max:500',
            'estado'            => 'sometimes|required|in:Pendiente,Pagada,Vencida',
            'fecha_pago'        => 'sometimes|nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cuenta = CuentaPorCobrar::where('id_cuenta', $id)
                ->where('id_institucion', $request->user()->id_institucion)
                ->firstOrFail();

            $cuenta->update($request->only('monto', 'fecha_emision', 'fecha_vencimiento', 'descripcion', 'estado', 'fecha_pago'));

            return response()->json([
                'success' => true,
                'message' => 'Cuenta por cobrar actualizada exitosamente',
                'data' => $cuenta
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar cuenta por cobrar',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Registrar pago de una cuenta
     */
    public function registrarPago(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fecha_pago' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cuenta = CuentaPorCobrar::where('id_cuenta', $id)
                ->where('id_institucion', $request->user()->id_institucion)
                ->firstOrFail();

            $cuenta->update([
                'estado'      => 'Pagada',
                'fecha_pago'  => $request->fecha_pago
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pago registrado exitosamente',
                'data' => $cuenta
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar pago',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una cuenta por cobrar
     */
    public function destroy(Request $request, $id)
    {
        try {
            $cuenta = CuentaPorCobrar::where('id_cuenta', $id)
                ->where('id_institucion', $request->user()->id_institucion)
                ->firstOrFail();

            $cuenta->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cuenta por cobrar eliminada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar cuenta por cobrar',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
