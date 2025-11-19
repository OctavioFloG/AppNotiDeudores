<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaPorCobrar extends Model
{
    protected $table = 'cuentas_por_cobrar';
    protected $primaryKey = 'id_cuenta';

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_cliente');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institucion');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'id_cuenta');
    }
}

