<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $table = 'institutions';
    protected $primaryKey = 'id_institucion';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'correo',
    ];

    /**
     * Relación: Una institución tiene muchos usuarios
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_institucion');
    }

    /**
     * Relación: Una institución tiene muchos clientes
     */
    public function clients()
    {
        return $this->hasMany(Client::class, 'id_institucion');
    }

    /**
     * Relación: Una institución tiene muchas cuentas por cobrar
     */
    public function cuentasPorCobrar()
    {
        return $this->hasMany(CuentaPorCobrar::class, 'id_institucion');
    }
}
