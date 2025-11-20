<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasApiTokens;

    protected $table = 'users';
    protected $primaryKey = 'id_usuario';
    public $timestamps = true;

    protected $fillable = [
        'id_institucion',
        'rol',
        'usuario',
        'contrasena_hash',
    ];

    protected $hidden = [
        'contrasena_hash',
    ];

    /**
     * Relación: Un usuario pertenece a una institución
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institucion');
    }
}
