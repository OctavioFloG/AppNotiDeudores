<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $table = 'institutions';
    protected $primaryKey = 'id_institucion';

    public function users()
    {
        return $this->hasMany(User::class, 'id_institucion');
    }
    public function clients()
    {
        return $this->hasMany(Client::class, 'id_institucion');
    }
    public function cuentasPorCobrar()
    {
        return $this->hasMany(CuentaPorCobrar::class, 'id_institucion');
    }
}
