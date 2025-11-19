<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';
    protected $primaryKey = 'id_notificacion';

    public function cuentaPorCobrar()
    {
        return $this->belongsTo(CuentaPorCobrar::class, 'id_cuenta');
    }
}

