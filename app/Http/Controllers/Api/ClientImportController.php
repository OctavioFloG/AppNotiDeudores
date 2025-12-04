<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\ClientsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Throwable;

class ClientImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado',
            ], 401);
        }

        $institucionId = $user->id_institucion;

        Log::info('IMPORT CLIENTES INSTITUCION', [
            'institucion_id' => $institucionId,
        ]);

        try {
            Excel::import(
                new ClientsImport($institucionId),
                $request->file('file')
            );

            Log::info('IMPORT CLIENTES DESPUES DE EXCEL', [
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Clientes importados correctamente',
            ], 200);

        } catch (Throwable $e) {
            Log::error('IMPORT CLIENTES ERROR', [
                'msg'   => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al importar clientes',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
