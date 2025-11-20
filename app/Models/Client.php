<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id_cliente';
    public $timestamps = true;

    protected $fillable = [
        'id_institucion',
        'nombre',
        'telefono',
        'correo',
        'direccion',
    ];

    /**
     * Relación: Un cliente pertenece a una institución
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institucion');
    }

    /**
     * Relación: Un cliente tiene muchas cuentas por cobrar
     */
    public function cuentasPorCobrar()
    {
        return $this->hasMany(CuentaPorCobrar::class, 'id_cliente');
    }
}
