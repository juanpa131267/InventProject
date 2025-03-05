<?php

namespace App\Models;

use App\Models\mod_MovimientosInventario;
use App\Models\mod_Articulo;
use App\Models\mod_Inventario;
use App\Models\mod_RolxPersona;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;


class mod_Usuario extends Model implements Authenticatable
{
    use HasFactory, SoftDeletes, AuthenticatableTrait;

    const DELETED_AT = 'DELETED_AT';
    protected $table = 'USUARIOS';  
    protected $fillable = ['ID_PERSONAS', 'LOGIN', 'PASSWORD'];  
    protected $primaryKey = 'ID';
    protected $dates = ['DELETED_AT'];
    public $timestamps = false;
    public $incrementing = false;

    public function PERSONAS()
    {
        return $this->belongsTo(mod_Persona::class, 'ID_PERSONAS', 'ID');
    }
    
    public function ROLES()
    {
        return $this->hasMany(mod_RolxPersona::class, 'ID_USUARIOS', 'ID');
    }

    public function ROLXPERSONA()
    {
        return $this->hasMany(mod_RolxPersona::class, 'ID_USUARIOS', 'ID');
    }

        

}

