<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_Rol extends Model
{
    use HasFactory, SoftDeletes;

    const DELETED_AT = 'DELETED_AT';
    protected $table = 'ROLES';  
    protected $fillable = ['DESCRIPCION'];
    protected $primaryKey = 'ID';
    protected $dates = ['DELETED_AT'];
    public $timestamps = false;
    public $incrementing = false;

    public function PERSONAS()
    {
        return $this->belongsToMany(mod_Persona::class, 'ROLXPERSONA', 'ID_ROLES', 'ID_PERSONAS');
    }
    

    public function PERMISOS()
    {
        return $this->belongsToMany(mod_Permiso::class, 'ROLXPERMISO', 'ID_ROLES', 'ID_PERMISOS');
    }

    
}

// los roles serán:
// 1. Administrador
// 2. Usuario
