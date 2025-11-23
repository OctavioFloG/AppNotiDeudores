<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';
    protected $primaryKey = 'id_notificacion';
    
    protected $fillable = [
        'id_institucion',
        'id_cliente',
        'id_cuenta',
        'tipo',
        'mensaje',
        'asunto',
        'estado',
        'destinatario',
        'respuesta_error',
        'fecha_envio'
    ];

    protected $dates = ['fecha_envio', 'created_at', 'updated_at'];

    // Relaciones
    public function cuentaPorCobrar()
    {
        return $this->belongsTo(CuentaPorCobrar::class, 'id_cuenta', 'id_cuenta');
    }

    public function institucion()
    {
        return $this->belongsTo(Institution::class, 'id_institucion', 'id_institucion');
    }

    public function cliente()
    {
        return $this->belongsTo(Client::class, 'id_cliente', 'id_cliente');
    }
}
