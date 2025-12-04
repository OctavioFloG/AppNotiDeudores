<?php

namespace App\Imports;

use App\Models\Client;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ClientsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    protected int $institucionId;

    public function __construct(int $institucionId)
    {
        $this->institucionId = $institucionId;
    }

    public function model(array $row)
    {
        if (
            empty($row['nombre']) &&
            empty($row['telefono']) &&
            empty($row['correo']) &&
            empty($row['direccion'])
        ) {
            return null;
        }

        return new Client([
            'id_institucion' => $this->institucionId,
            'nombre'         => $row['nombre']   ?? '',
            'telefono'       => $row['telefono'] ?? '',
            'correo'         => $row['correo']   ?? '',
            'direccion'      => $row['direccion'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nombre'   => ['required','string','max:255'],
            '*.telefono' => [
                'required',
                'string',
                'max:30',
                Rule::unique('clients', 'telefono')->where(fn($q) =>
                    $q->where('id_institucion', $this->institucionId)
                ),
            ],
            '*.correo'   => [
                'nullable',
                'email',
                Rule::unique('clients', 'correo')->where(fn($q) =>
                    $q->where('id_institucion', $this->institucionId)
                ),
            ],
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '*.telefono' => 'teléfono',
            '*.correo'   => 'correo',
        ];
    }
}
