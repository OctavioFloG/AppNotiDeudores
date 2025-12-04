<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaPorCobrar extends Model
{
    use HasFactory;
    protected $table = 'cuentas_por_cobrar';
    protected $primaryKey = 'id_cuenta';
    public $timestamps = true;

    protected $fillable = [
        'id_cliente',
        'id_institucion',
        'monto',
        'fecha_emision',
        'fecha_vencimiento',
        'descripcion',
        'estado',
        'fecha_pago',
    ];

    protected $dates = [
        'fecha_emision',
        'fecha_vencimiento',
        'fecha_pago',
    ];

    /**
     * Relación: Una cuenta pertenece a un cliente
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_cliente');
    }

    /**
     * Relación: Una cuenta pertenece a una institución
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institucion');
    }

    /**
     * Relación: Una cuenta tiene muchas notificaciones
     */
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'id_cuenta');
    }
}
