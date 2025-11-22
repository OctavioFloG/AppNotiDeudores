<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientToken extends Model
{
    protected $table = 'client_tokens';
    protected $primaryKey = 'id_token';
    public $timestamps = true;

    protected $fillable = [
        'id_cliente',
        'token',
        'qr_code',
        'expires_at',
        'used',
        'used_at',
    ];

    protected $dates = [
        'expires_at',
        'used_at',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_cliente');
    }
}
