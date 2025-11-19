<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id_cliente';

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institucion');
    }

    public function cuentasPorCobrar()
    {
        return $this->hasMany(CuentaPorCobrar::class, 'id_cliente');
    }
}